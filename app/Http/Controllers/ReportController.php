<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Category;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getReportData($categoryId = null, $startDate = null, $endDate = null)
    {
        $query = DB::table('products as p')
            ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
            ->leftJoin('stock_movements as sm', function ($join) use ($startDate, $endDate) {
                $join->on('sm.product_id', '=', 'p.id');
                if (!empty($startDate)) {
                    $join->whereDate('sm.created_at', '>=', $startDate);
                }
                if (!empty($endDate)) {
                    $join->whereDate('sm.created_at', '<=', $endDate);
                }
            })
            ->selectRaw('
                p.id,
                p.sku,
                p.name AS product_name,
                p.price,
                p.stock AS current_stock,
                p.minimum_stock,
                c.name AS category_name,
                COALESCE(SUM(CASE WHEN sm.type = "IN" THEN sm.quantity ELSE 0 END), 0) AS total_in,
                COALESCE(SUM(CASE WHEN sm.type = "OUT" THEN sm.quantity ELSE 0 END), 0) AS total_out,
                COUNT(sm.id) AS movement_count,
                (p.stock * p.price) AS total_asset_value
            ')
            ->groupBy('p.id', 'p.sku', 'p.name', 'p.price', 'p.stock', 'p.minimum_stock', 'c.name')
            ->orderBy('c.name', 'ASC')
            ->orderBy('p.name', 'ASC');

        if (!empty($categoryId)) {
            $query->where('p.category_id', (int) $categoryId);
        }

        return $query->get();
    }

    public function index(Request $request)
    {
        $categoryId = $request->input('category_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $reportData = $this->getReportData($categoryId, $startDate, $endDate);
        $categories = Category::orderBy('name', 'ASC')->get();

        return view('reports.index', compact('reportData', 'categories', 'categoryId', 'startDate', 'endDate'));
    }

    public function printView(Request $request)
    {
        $categoryId = $request->input('category_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $reportData = $this->getReportData($categoryId, $startDate, $endDate);
        $categories = Category::orderBy('name', 'ASC')->get();

        return view('reports.print', compact('reportData', 'categories', 'categoryId', 'startDate', 'endDate'));
    }

    public function exportCsv(Request $request)
    {
        $categoryId = $request->input('category_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $reportData = $this->getReportData($categoryId, $startDate, $endDate);

        $filename = 'Laporan_Inventaris_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($reportData) {
            $file = fopen('php://output', 'w');
            // BOM UTF-8 agar Excel membaca aksen / format dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'No', 'SKU', 'Nama Produk', 'Kategori', 'Harga Jual Satuan',
                'Total Barang Masuk (IN)', 'Total Barang Keluar (OUT)',
                'Stok Akhir Saat Ini', 'Batas Minimum', 'Total Nilai Aset Produk'
            ]);

            $no = 1;
            foreach ($reportData as $row) {
                fputcsv($file, [
                    $no++,
                    $row->sku,
                    $row->product_name,
                    $row->category_name,
                    $row->price,
                    $row->total_in,
                    $row->total_out,
                    $row->current_stock,
                    $row->minimum_stock,
                    $row->total_asset_value,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
