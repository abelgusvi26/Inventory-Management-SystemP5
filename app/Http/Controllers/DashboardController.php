<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Category;
use App\Supplier;
use App\StockMovement;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // 1. Total Produk & Total Nilai Inventaris
        $productStats = DB::table('products')
            ->selectRaw('
                COUNT(*) AS total_products,
                COALESCE(SUM(stock * price), 0) AS total_inventory_value,
                SUM(CASE WHEN stock <= minimum_stock THEN 1 ELSE 0 END) AS low_stock_count,
                SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) AS out_of_stock_count
            ')->first();

        // 2. Total Barang Masuk & Barang Keluar
        $movementStats = DB::table('stock_movements')
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END), 0) AS total_stock_in,
                COALESCE(SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END), 0) AS total_stock_out,
                COUNT(*) AS total_transactions
            ")->first();

        $categoryCount = DB::table('categories')->count();
        $supplierCount = DB::table('suppliers')->count();

        $summary = [
            'total_products'        => (int) ($productStats->total_products ? $productStats->total_products : 0),
            'total_inventory_value' => (float) ($productStats->total_inventory_value ? $productStats->total_inventory_value : 0),
            'low_stock_count'       => (int) ($productStats->low_stock_count ? $productStats->low_stock_count : 0),
            'out_of_stock_count'    => (int) ($productStats->out_of_stock_count ? $productStats->out_of_stock_count : 0),
            'total_stock_in'        => (int) ($movementStats->total_stock_in ? $movementStats->total_stock_in : 0),
            'total_stock_out'       => (int) ($movementStats->total_stock_out ? $movementStats->total_stock_out : 0),
            'total_transactions'    => (int) ($movementStats->total_transactions ? $movementStats->total_transactions : 0),
            'total_categories'      => $categoryCount,
            'total_suppliers'       => $supplierCount,
        ];

        // Produk stok rendah
        $lowStockProducts = Product::with('category')
            ->whereRaw('stock <= minimum_stock')
            ->orderBy('stock', 'ASC')
            ->limit(8)
            ->get();

        // Mutasi terkini
        $recentMovements = StockMovement::with(['product', 'user.profile'])
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(6)
            ->get();

        return view('dashboard.index', compact('summary', 'lowStockProducts', 'recentMovements'));
    }
}
