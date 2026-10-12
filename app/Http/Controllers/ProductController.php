<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Supplier;
use App\StockMovement;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->input('q', ''));
        $categoryId = $request->input('category_id');
        $sortBy = $request->input('sort', 'created_at');
        $sortDir = strtoupper($request->input('direction', 'DESC')) === 'ASC' ? 'ASC' : 'DESC';

        // PRD Rule 8: Sorting allowlist
        $allowlist = ['name', 'price', 'stock', 'sku', 'created_at'];
        if (!in_array($sortBy, $allowlist, true)) {
            $sortBy = 'created_at';
        }

        $query = Product::with('category')->withCount('stockMovements');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', (int) $categoryId);
        }

        $query->orderBy($sortBy, $sortDir);

        $products = $query->paginate(10)->appends($request->except('page'));
        $categories = Category::orderBy('name', 'ASC')->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoryId', 'sortBy', 'sortDir'));
    }

    public function detail($id)
    {
        $product = Product::with(['category', 'suppliers'])->findOrFail($id);
        $movements = StockMovement::with('user.profile')
            ->where('product_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(20)
            ->get();

        return view('products.detail', compact('product', 'movements'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $suppliers = Supplier::orderBy('name', 'ASC')->get();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id'   => 'required|exists:categories,id',
            'sku'           => 'required|string|max:50|unique:products,sku',
            'name'          => 'required|string|max:150',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'category_id'   => (int) $request->input('category_id'),
                'sku'           => trim($request->input('sku')),
                'name'          => trim($request->input('name')),
                'price'         => (float) $request->input('price'),
                'stock'         => (int) $request->input('stock'),
                'minimum_stock' => (int) $request->input('minimum_stock'),
            ]);

            // Sync suppliers jika ada
            $supplierIds = $request->input('supplier_ids', []);
            $supplierPrices = $request->input('supplier_prices', []);
            $syncData = [];
            if (is_array($supplierIds)) {
                foreach ($supplierIds as $sId) {
                    $sId = (int) $sId;
                    if ($sId > 0) {
                        $sPrice = isset($supplierPrices[$sId]) ? (float) $supplierPrices[$sId] : 0.00;
                        $syncData[$sId] = ['supplier_price' => max(0, $sPrice)];
                    }
                }
            }
            $product->suppliers()->sync($syncData);

            DB::commit();
            return redirect()->route('products.index')->with('flash_success', 'Produk "' . $product->name . '" berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('flash_error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::with('suppliers')->findOrFail($id);
        $categories = Category::orderBy('name', 'ASC')->get();
        $suppliers = Supplier::orderBy('name', 'ASC')->get();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $this->validate($request, [
            'category_id'   => 'required|exists:categories,id',
            'sku'           => 'required|string|max:50|unique:products,sku,' . $id,
            'name'          => 'required|string|max:150',
            'price'         => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $product->category_id = (int) $request->input('category_id');
            $product->sku = trim($request->input('sku'));
            $product->name = trim($request->input('name'));
            $product->price = (float) $request->input('price');
            $product->minimum_stock = (int) $request->input('minimum_stock');
            $product->save();

            // Sync suppliers
            $supplierIds = $request->input('supplier_ids', []);
            $supplierPrices = $request->input('supplier_prices', []);
            $syncData = [];
            if (is_array($supplierIds)) {
                foreach ($supplierIds as $sId) {
                    $sId = (int) $sId;
                    if ($sId > 0) {
                        $sPrice = isset($supplierPrices[$sId]) ? (float) $supplierPrices[$sId] : 0.00;
                        $syncData[$sId] = ['supplier_price' => max(0, $sPrice)];
                    }
                }
            }
            $product->suppliers()->sync($syncData);

            DB::commit();
            return redirect()->route('products.index')->with('flash_success', 'Produk "' . $product->name . '" berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('flash_error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // PRD Rule 5: Produk yang memiliki histori mutasi tidak boleh dihapus
        if ($product->stockMovements()->count() > 0) {
            return redirect()->route('products.index')->with('flash_error', 'Produk "' . $product->name . '" tidak dapat dihapus karena memiliki riwayat mutasi stok (RESTRICT).');
        }

        $product->suppliers()->detach();
        $product->delete();

        return redirect()->route('products.index')->with('flash_success', 'Produk berhasil dihapus.');
    }
}
