@extends('layouts.print')

@section('title', 'Cetak Laporan Mutasi Inventaris')

@section('content')
@php
    $totalValuation = 0;
    $totalInAll = 0;
    $totalOutAll = 0;
    $totalStockAll = 0;

    foreach ($reportData as $r) {
        $totalValuation += (float) $r->total_asset_value;
        $totalInAll += (int) $r->total_in;
        $totalOutAll += (int) $r->total_out;
        $totalStockAll += (int) $r->current_stock;
    }
@endphp

<div style="border-bottom: 2px solid #0f172a; padding-bottom: 1rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: flex-end;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0; color: #0f172a;">{{ config('app.name', 'Inventory Hub') }}</h1>
        <div style="font-size: 0.875rem; color: #64748b;">Laporan Resmi Mutasi & Nilai Aset Inventaris Pergudangan</div>
    </div>
    <div style="text-align: right; font-size: 0.8125rem; color: #64748b;">
        <div>Tanggal Cetak: <strong>{{ date('d F Y, H:i') }} WIB</strong></div>
        <div>Dicetak Oleh: <strong>{{ (Auth::user()->profile && Auth::user()->profile->full_name) ? Auth::user()->profile->full_name : Auth::user()->username }}</strong> ({{ Auth::user()->role }})</div>
    </div>
</div>

@if(!empty($categoryId) || !empty($startDate) || !empty($endDate))
    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.8125rem;">
        <strong>Parameter Filter:</strong>
        @if(!empty($startDate) || !empty($endDate))
            Periode: {{ $startDate ?: 'Awal' }} s/d {{ $endDate ?: 'Sekarang' }} &bull;
        @endif
        @if(!empty($categoryId))
            Kategori ID: {{ $categoryId }}
        @else
            Semua Kategori
        @endif
    </div>
@endif

<table class="data-table" style="width: 100%; border-collapse: collapse; font-size: 0.8125rem;">
    <thead>
        <tr style="background-color: #f1f5f9; text-align: left;">
            <th style="padding: 8px; border: 1px solid #cbd5e1;">No</th>
            <th style="padding: 8px; border: 1px solid #cbd5e1;">SKU</th>
            <th style="padding: 8px; border: 1px solid #cbd5e1;">Nama Produk</th>
            <th style="padding: 8px; border: 1px solid #cbd5e1;">Kategori</th>
            <th style="padding: 8px; border: 1px solid #cbd5e1; text-align: right;">Harga Satuan</th>
            <th style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">Masuk (IN)</th>
            <th style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">Keluar (OUT)</th>
            <th style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">Stok Akhir</th>
            <th style="padding: 8px; border: 1px solid #cbd5e1; text-align: right;">Total Valuasi Aset</th>
        </tr>
    </thead>
    <tbody>
        @if($reportData->isEmpty())
            <tr>
                <td colspan="9" style="text-align: center; padding: 2rem;">Tidak ada data laporan yang sesuai.</td>
            </tr>
        @else
            @foreach($reportData as $index => $row)
                <tr>
                    <td style="padding: 8px; border: 1px solid #cbd5e1;">{{ $index + 1 }}</td>
                    <td style="padding: 8px; border: 1px solid #cbd5e1; font-family: monospace;">{{ $row->sku }}</td>
                    <td style="padding: 8px; border: 1px solid #cbd5e1;">{{ $row->product_name }}</td>
                    <td style="padding: 8px; border: 1px solid #cbd5e1;">{{ $row->category_name ?: '-' }}</td>
                    <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-family: monospace;">Rp {{ number_format($row->price, 0, ',', '.') }}</td>
                    <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">{{ (int) $row->total_in }}</td>
                    <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">{{ (int) $row->total_out }}</td>
                    <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center; font-weight: bold;">{{ (int) $row->current_stock }}</td>
                    <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-family: monospace; font-weight: bold;">Rp {{ number_format($row->total_asset_value, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
    @if(!$reportData->isEmpty())
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="5" style="padding: 8px; border: 1px solid #cbd5e1; text-align: right;">TOTAL:</td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">{{ number_format($totalInAll) }}</td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">{{ number_format($totalOutAll) }}</td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center;">{{ number_format($totalStockAll) }}</td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-family: monospace;">Rp {{ number_format($totalValuation, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    @endif
</table>
@endsection
