@extends('layouts.main')

@section('title', 'Master Data Kategori')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700;">Master Kategori Produk</h2>
        <p class="form-hint" style="margin: 0;">Kelola pengelompokan produk inventaris ke dalam kategori logistik.</p>
    </div>
    @if(Auth::user()->isAdmin())
    <div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Tambah Kategori</span>
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
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Produk Terdaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($categories->isEmpty())
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-title">Belum Ada Kategori</div>
                                <div class="empty-desc">Tambahkan kategori pertama untuk mulai mengelompokkan produk.</div>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach($categories as $index => $cat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $cat->name }}</strong></td>
                            <td style="color: var(--text-muted);">{{ $cat->description ?: '-' }}</td>
                            <td>
                                <span class="badge {{ $cat->products_count > 0 ? 'badge-primary' : 'badge-neutral' }}">
                                    {{ $cat->products_count }} item produk
                                </span>
                            </td>
                            <td>
                                @if(Auth::user()->isAdmin())
                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                        <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-secondary btn-sm">Ubah</a>
                                        @if($cat->products_count == 0)
                                            <form method="POST" action="{{ route('categories.destroy', $cat->id) }}" style="margin: 0;" onsubmit="return confirm('Hapus kategori ini?');">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--danger);">Hapus</button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm" style="opacity: 0.45; cursor: not-allowed;" title="Kategori masih memiliki produk terkait (RESTRICT)" disabled>Hapus</button>
                                        @endif
                                    </div>
                                @else
                                    <span style="font-size: 0.8125rem; color: var(--text-subtle);">Hanya Lihat</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
