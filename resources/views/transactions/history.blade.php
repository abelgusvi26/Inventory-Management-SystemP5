@extends('layouts.main')

@section('title', 'Riwayat Mutasi Stok')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700;">Riwayat Mutasi Transaksi Stok</h2>
        <p class="form-hint" style="margin: 0;">Log audit lengkap keluar-masuk barang, referensi surat jalan, dan petugas penanggung jawab.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('transactions.in') }}" class="btn btn-primary btn-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
            <span>+ Barang Masuk</span>
        </a>
        <a href="{{ route('transactions.out') }}" class="btn btn-secondary btn-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
            <span>- Barang Keluar</span>
        </a>
    </div>
</div>

<!-- Filter Bar Transaksi -->
<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('transactions.history') }}" class="filter-bar" style="margin-bottom: 0;">
            <div style="min-width: 200px;">
                <select name="product_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Produk</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ $productId == $p->id ? 'selected' : '' }}>
                            [{{ $p->sku }}] {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="min-width: 140px;">
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    <option value="IN" {{ $type === 'IN' ? 'selected' : '' }}>Masuk (IN)</option>
                    <option value="OUT" {{ $type === 'OUT' ? 'selected' : '' }}>Keluar (OUT)</option>
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 0.8125rem; color: var(--text-subtle);">Dari:</label>
                <input type="date" name="start_date" class="form-input" value="{{ $startDate }}" onchange="this.form.submit()">
            </div>

            <div style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 0.8125rem; color: var(--text-subtle);">Sampai:</label>
                <input type="date" name="end_date" class="form-input" value="{{ $endDate }}" onchange="this.form.submit()">
            </div>

            <button type="submit" class="btn btn-secondary">Filter</button>

            @if(!empty($productId) || !empty($type) || !empty($startDate) || !empty($endDate))
                <a href="{{ route('transactions.history') }}" class="btn btn-secondary btn-sm" style="color: var(--danger);">Reset Filter</a>
            @endif
        </form>
    </div>
</div>

<!-- Tabel Log Mutasi -->
<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu Kejadian</th>
                    <th>Tipe Transaksi</th>
                    <th>SKU & Produk</th>
                    <th>Jumlah Mutasi</th>
                    <th>No. Referensi / PO</th>
                    <th>Petugas (User)</th>
                    <th>Catatan Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @if($movements->isEmpty())
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-title">Belum Ada Catatan Transaksi</div>
                                <div class="empty-desc">Tidak ada histori mutasi stok yang sesuai dengan filter pencarian saat ini.</div>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach($movements as $m)
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
                                <strong class="mono">{{ $m->product ? $m->product->sku : '-' }}</strong>
                                <div style="font-size: 0.8125rem;">
                                    <a href="{{ route('products.detail', $m->product_id) }}">
                                        {{ $m->product ? $m->product->name : '-' }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $m->quantity }}</strong> unit
                            </td>
                            <td class="mono">{{ $m->reference_number ?: '-' }}</td>
                            <td>
                                {{ ($m->user && $m->user->profile && $m->user->profile->full_name) ? $m->user->profile->full_name : ($m->user ? $m->user->username : '-') }}
                            </td>
                            <td style="color: var(--text-muted); font-size: 0.8125rem;">
                                {{ $m->notes ?: '-' }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Paginasi -->
    @if($movements->total() > 0)
        <div style="padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-subtle); flex-wrap: wrap; gap: 1rem;">
            <div style="font-size: 0.875rem; color: var(--text-muted);">
                Menampilkan <strong>{{ $movements->firstItem() }}</strong> - <strong>{{ $movements->lastItem() }}</strong> dari <strong>{{ $movements->total() }}</strong> catatan mutasi
            </div>
            <div>
                {!! $movements->links() !!}
            </div>
        </div>
    @endif
</div>
@endsection
