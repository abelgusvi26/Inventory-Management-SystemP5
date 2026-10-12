@extends('layouts.main')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali</a>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0;">{{ $product->name }}</h2>
            <span class="mono" style="font-size: 0.875rem; background: var(--bg-subtle); padding: 2px 8px; border-radius: 4px;">{{ $product->sku }}</span>
        </div>
        <p class="form-hint" style="margin: 4px 0 0 0;">Informasi detail produk, pemasok (many-to-many), dan histori mutasi barang.</p>
    </div>
    @if(Auth::user()->isAdmin())
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Ubah Produk</a>
    </div>
    @endif
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Informasi Ringkas Produk -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Spesifikasi & Nilai Valuasi</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 140px 1fr; gap: 0.75rem; font-size: 0.875rem;">
                <span style="color: var(--text-subtle);">Kategori:</span>
                <strong>{{ $product->category ? $product->category->name : '-' }}</strong>

                <span style="color: var(--text-subtle);">Harga Jual Satuan:</span>
                <strong class="mono" style="color: var(--primary);">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>

                <span style="color: var(--text-subtle);">Stok Saat Ini:</span>
                <div>
                    <strong>{{ $product->stock }}</strong> unit
                    @if($product->stock === 0)
                        <span class="badge badge-danger" style="margin-left: 6px;">Habis</span>
                    @elseif($product->stock <= $product->minimum_stock)
                        <span class="badge badge-warning" style="margin-left: 6px;">Menipis</span>
                    @else
                        <span class="badge badge-success" style="margin-left: 6px;">Aman</span>
                    @endif
                </div>

                <span style="color: var(--text-subtle);">Batas Minimum Stok:</span>
                <span>{{ $product->minimum_stock }} unit</span>

                <span style="color: var(--text-subtle);">Total Nilai Valuasi:</span>
                <strong class="mono" style="color: var(--success-text);">Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <!-- Relasi Many-to-Many: Daftar Pemasok / Supplier Produk Ini -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pemasok / Supplier Terhubung (Many-to-Many)</h3>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Supplier</th>
                        <th>Kontak</th>
                        <th>Harga Pasok</th>
                    </tr>
                </thead>
                <tbody>
                    @if($product->suppliers->isEmpty())
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <div class="empty-desc">Belum ada supplier yang ditautkan ke produk ini.</div>
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($product->suppliers as $sup)
                            <tr>
                                <td>
                                    <strong>{{ $sup->name }}</strong>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $sup->contact_person }}</div>
                                </td>
                                <td>{{ $sup->phone ?: '-' }}</td>
                                <td class="mono">
                                    Rp {{ number_format($sup->pivot->supplier_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Histori Mutasi Barang Khusus Produk Ini -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Riwayat Mutasi Stok Terbaru</h3>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Tipe Mutasi</th>
                    <th>Jumlah</th>
                    <th>No. Referensi / Surat Jalan</th>
                    <th>Petugas</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @if($movements->isEmpty())
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-desc">Belum ada catatan mutasi transaksi untuk produk ini.</div>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach($movements as $m)
                        <tr>
                            <td style="font-size: 0.8125rem;">{{ date('d/m/Y H:i', strtotime($m->created_at)) }}</td>
                            <td>
                                @if($m->type === 'IN')
                                    <span class="badge badge-success">&darr; IN</span>
                                @else
                                    <span class="badge badge-danger">&uarr; OUT</span>
                                @endif
                            </td>
                            <td><strong>{{ $m->quantity }}</strong> unit</td>
                            <td class="mono">{{ $m->reference_number ?: '-' }}</td>
                            <td>{{ ($m->user && $m->user->profile && $m->user->profile->full_name) ? $m->user->profile->full_name : ($m->user ? $m->user->username : '-') }}</td>
                            <td>{{ $m->notes ?: '-' }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
