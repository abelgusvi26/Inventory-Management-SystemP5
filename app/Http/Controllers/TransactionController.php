<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\StockMovement;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function history(Request $request)
    {
        $productId = $request->input('product_id');
        $type = strtoupper($request->input('type', ''));
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = StockMovement::with(['product', 'user.profile']);

        if (!empty($productId)) {
            $query->where('product_id', (int) $productId);
        }

        if (in_array($type, ['IN', 'OUT'], true)) {
            $query->where('type', $type);
        }

        if (!empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $movements = $query->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(15)
            ->appends($request->except('page'));

        $products = Product::orderBy('name', 'ASC')->get();

        return view('transactions.history', compact('movements', 'products', 'productId', 'type', 'startDate', 'endDate'));
    }

    public function in()
    {
        $products = Product::orderBy('name', 'ASC')->get();
        return view('transactions.in', compact('products'));
    }

    public function storeIn(Request $request)
    {
        $this->validate($request, [
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|integer|min:1',
            'reference_number' => 'nullable|string|max:50',
            'notes'            => 'nullable|string',
        ]);

        $productId = (int) $request->input('product_id');
        $qty = (int) $request->input('quantity');
        $ref = trim($request->input('reference_number', ''));
        $notes = trim($request->input('notes', ''));

        DB::beginTransaction();
        try {
            // Lock row dengan FOR UPDATE (Anti race condition)
            $product = DB::table('products')->where('id', $productId)->lockForUpdate()->first();
            if (!$product) {
                throw new \Exception('Produk tidak ditemukan.');
            }

            // Tambah stok
            DB::table('products')->where('id', $productId)->update([
                'stock'      => $product->stock + $qty,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Catat ke stock_movements
            StockMovement::create([
                'product_id'       => $productId,
                'user_id'          => Auth::id(),
                'type'             => 'IN',
                'quantity'         => $qty,
                'reference_number' => $ref ?: null,
                'notes'            => $notes ?: null,
                'created_at'       => date('Y-m-d H:i:s'),
            ]);

            DB::commit();
            return redirect()->route('transactions.history')
                ->with('flash_success', 'Barang Masuk berhasil dicatat. Stok bertambah +' . $qty . ' unit.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('flash_error', 'Gagal mencatat barang masuk: ' . $e->getMessage());
        }
    }

    public function out()
    {
        $products = Product::orderBy('name', 'ASC')->get();
        return view('transactions.out', compact('products'));
    }

    public function storeOut(Request $request)
    {
        $this->validate($request, [
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|integer|min:1',
            'reference_number' => 'nullable|string|max:50',
            'notes'            => 'nullable|string',
        ]);

        $productId = (int) $request->input('product_id');
        $qty = (int) $request->input('quantity');
        $ref = trim($request->input('reference_number', ''));
        $notes = trim($request->input('notes', ''));

        DB::beginTransaction();
        try {
            // Lock row dengan FOR UPDATE
            $product = DB::table('products')->where('id', $productId)->lockForUpdate()->first();
            if (!$product) {
                throw new \Exception('Produk tidak ditemukan.');
            }

            // PRD Rule 3 & 4: Barang keluar tidak boleh melebihi stok, stok tidak boleh negatif
            if ($qty > $product->stock) {
                throw new \Exception('Jumlah barang keluar (' . $qty . ') melebihi stok tersedia saat ini (' . $product->stock . '). Transaksi dibatalkan.');
            }

            // Kurangi stok
            DB::table('products')->where('id', $productId)->update([
                'stock'      => $product->stock - $qty,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Catat ke stock_movements
            StockMovement::create([
                'product_id'       => $productId,
                'user_id'          => Auth::id(),
                'type'             => 'OUT',
                'quantity'         => $qty,
                'reference_number' => $ref ?: null,
                'notes'            => $notes ?: null,
                'created_at'       => date('Y-m-d H:i:s'),
            ]);

            DB::commit();
            return redirect()->route('transactions.history')
                ->with('flash_success', 'Barang Keluar berhasil dicatat. Stok berkurang -' . $qty . ' unit.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());
        }
    }
}
