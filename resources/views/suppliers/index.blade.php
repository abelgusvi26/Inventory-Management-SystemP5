@extends('layouts.main')

@section('title', 'Master Data Pemasok (Suppliers)')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700;">Master Pemasok (Suppliers)</h2>
        <p class="form-hint" style="margin: 0;">Kelola daftar rekanan vendor logistik pemasok inventaris barang.</p>
    </div>
    @if(Auth::user()->isAdmin())
    <div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Tambah Pemasok</span>
        </a>
    </div>
    @endif
</div>

<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan Supplier</th>
                    <th>Kontak Person</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Produk Disuplai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($suppliers->isEmpty())
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-title">Belum Ada Pemasok</div>
                                <div class="empty-desc">Tambahkan data rekanan vendor/supplier pertama Anda.</div>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach($suppliers as $index => $s)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $s->name }}</strong>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ str_limit($s->address, 40) }}</div>
                            </td>
                            <td>{{ $s->contact_person ?: '-' }}</td>
                            <td>{{ $s->phone ?: '-' }}</td>
                            <td>{{ $s->email ?: '-' }}</td>
                            <td>
                                <span class="badge {{ $s->products_count > 0 ? 'badge-primary' : 'badge-neutral' }}">
                                    {{ $s->products_count }} item produk
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <a href="{{ route('suppliers.detail', $s->id) }}" class="btn btn-secondary btn-sm">Katalog</a>
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('suppliers.edit', $s->id) }}" class="btn btn-secondary btn-sm">Ubah</a>
                                        <form method="POST" action="{{ route('suppliers.destroy', $s->id) }}" style="margin: 0;" onsubmit="return confirm('Hapus pemasok ini? Tautan produk ke pemasok ini juga akan dilepas.');">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--danger);">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
