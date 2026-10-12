@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700;">Ringkasan Inventaris</h2>
        <p class="form-hint" style="margin: 0;">Pantau ketersediaan barang, valuasi aset gudang, dan aktivitas mutasi stok secara langsung.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('transactions.in') }}" class="btn btn-primary btn-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
            <span>Catat Barang Masuk</span>
        </a>
        <a href="{{ route('transactions.out') }}" class="btn btn-secondary btn-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
            <span>Catat Barang Keluar</span>
        </a>
    </div>
</div>

<!-- 5 Widget Metrik Wajib PRD (Bagian 9.1) -->
<div class="stats-grid">
    <!-- Total Produk -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Total Item Produk</span>
            <div class="stat-icon primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
            </div>
        </div>
        <div class="stat-value">{{ number_format($summary['total_products']) }}</div>
        <div class="stat-desc">{{ number_format($summary['total_categories']) }} kategori aktif</div>
    </div>

    <!-- Nilai Total Inventaris -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Nilai Total Inventaris</span>
            <div class="stat-icon success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
        </div>
        <div class="stat-value mono" style="font-size: 1.35rem;">Rp {{ number_format($summary['total_inventory_value'], 0, ',', '.') }}</div>
        <div class="stat-desc">Kalkulasi agregat dari stok &times; harga jual</div>
    </div>

    <!-- Produk Stok Rendah -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Stok Kritis / Menipis</span>
            <div class="stat-icon warning">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
        </div>
        <div class="stat-value" style="color: var(--warning);"><?= number_format($summary['low_stock_count']) ?></div>
        <div class="stat-desc"><?= number_format($summary['out_of_stock_count']) ?> produk benar-benar habis</div>
    </div>

    <!-- Akumulasi Barang Masuk -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Akumulasi Masuk (IN)</span>
            <div class="stat-icon primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
            </div>
        </div>
        <div class="stat-value">{{ number_format($summary['total_stock_in']) }}</div>
        <div class="stat-desc">Total unit barang masuk tercatat</div>
    </div>

    <!-- Akumulasi Barang Keluar -->
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Akumulasi Keluar (OUT)</span>
            <div class="stat-icon danger">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
            </div>
        </div>
        <div class="stat-value">{{ number_format($summary['total_stock_out']) }}</div>
        <div class="stat-desc">Total unit barang keluar terdistribusi</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
    <!-- Tabel Peringatan Stok Rendah -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span>Peringatan Stok Rendah / Habis</span>
            </h3>
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Katalog</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Sisa Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($lowStockProducts->isEmpty())
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-title">Semua Stok Aman</div>
                                    <div class="empty-desc">Tidak ada produk yang berada di bawah batas minimum stok saat ini.</div>
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($lowStockProducts as $p)
                            <tr>
                                <td class="mono"><strong>{{ $p->sku }}</strong></td>
                                <td>
                                    <a href="{{ route('products.detail', $p->id) }}">{{ $p->name }}</a>
                                </td>
                                <td>{{ $p->category ? $p->category->name : '-' }}</td>
                                <td><strong>{{ $p->stock }}</strong> unit (min: {{ $p->minimum_stock }})</td>
                                <td>
                                    @if($p->stock === 0)
                                        <span class="badge badge-danger">Habis</span>
                                    @else
                                        <span class="badge badge-warning">Menipis</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Aktivitas Mutasi Terkini -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Aktivitas Mutasi Terkini</span>
            </h3>
            <a href="{{ route('transactions.history') }}" class="btn btn-secondary btn-sm">Semua Riwayat</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Tipe</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @if($recentMovements->isEmpty())
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-title">Belum Ada Transaksi</div>
                                    <div class="empty-desc">Belum ada aktivitas mutasi stok barang masuk atau keluar.</div>
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($recentMovements as $m)
                            <tr>
                                <td style="font-size: 0.8125rem; color: var(--text-muted);">
                                    {{ date('d/m/Y H:i', strtotime($m->created_at)) }}
                                </td>
                                <td>
                                    @if($m->type === 'IN')
                                        <span class="badge badge-success">&darr; IN</span>
                                    @else
                                        <span class="badge badge-danger">&uarr; OUT</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $m->product ? $m->product->sku : '-' }}</strong>
                                    <div style="font-size: 0.8125rem; color: var(--text-muted);">
                                        {{ $m->product ? $m->product->name : '-' }}
                                    </div>
                                </td>
                                <td><strong>{{ $m->quantity }}</strong> unit</td>
                                <td>
                                    {{ ($m->user && $m->user->profile && $m->user->profile->full_name) ? $m->user->profile->full_name : ($m->user ? $m->user->username : '-') }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
