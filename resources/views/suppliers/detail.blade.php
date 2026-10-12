@extends('layouts.main')

@section('title', 'Katalog Pemasok: ' . $supplier->name)

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali</a>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0;">{{ $supplier->name }}</h2>
        </div>
        <p class="form-hint" style="margin: 4px 0 0 0;">Detail kontak dan daftar seluruh produk yang disuplai oleh rekanan ini.</p>
    </div>
    @if(Auth::user()->isAdmin())
    <div>
        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-primary">Ubah Data Supplier</a>
    </div>
    @endif
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Profil Rekanan Vendor</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; font-size: 0.875rem;">
            <div>
                <span style="color: var(--text-subtle); display: block;">Kontak Person:</span>
                <strong>{{ $supplier->contact_person ?: '-' }}</strong>
            </div>
            <div>
                <span style="color: var(--text-subtle); display: block;">Nomor Telepon:</span>
                <strong>{{ $supplier->phone ?: '-' }}</strong>
            </div>
            <div>
                <span style="color: var(--text-subtle); display: block;">Alamat Email:</span>
                <strong>{{ $supplier->email ?: '-' }}</strong>
            </div>
            <div>
                <span style="color: var(--text-subtle); display: block;">Alamat Kantor / Gudang:</span>
                <div>{{ $supplier->address ?: '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Produk yang Dipasok oleh {{ $supplier->name }}</h3>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Harga Pasok Supplier</th>
                    <th>Harga Jual Katalog</th>
                    <th>Stok Tersedia</th>
                </tr>
            </thead>
            <tbody>
                @if($supplier->products->isEmpty())
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-desc">Supplier ini belum ditautkan ke produk apapun. Anda dapat menautkannya lewat formulir produk.</div>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach($supplier->products as $p)
                        <tr>
                            <td class="mono"><strong>{{ $p->sku }}</strong></td>
                            <td>
                                <a href="{{ route('products.detail', $p->id) }}">{{ $p->name }}</a>
                            </td>
                            <td class="mono"><strong>Rp {{ number_format($p->pivot->supplier_price, 0, ',', '.') }}</strong></td>
                            <td class="mono">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td>{{ $p->stock }} unit</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
