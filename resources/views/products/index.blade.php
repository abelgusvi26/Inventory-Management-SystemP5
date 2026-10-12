@extends('layouts.main')

@section('title', 'Katalog Produk')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700;">Katalog Produk Inventaris</h2>
        <p class="form-hint" style="margin: 0;">Kelola master data produk, pantau ketersediaan stok, dan periksa harga pasok supplier.</p>
    </div>
    @if(Auth::user()->isAdmin())
    <div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Tambah Produk Baru</span>
        </a>
    </div>
    @endif
</div>

<!-- Filter Bar: Search & Filter Kategori -->
<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="filter-bar" style="margin-bottom: 0;">
            <div style="flex: 1; min-width: 240px; position: relative;">
                <input type="text" name="q" class="form-input" placeholder="Cari berdasarkan nama produk atau SKU..." value="{{ $search }}">
            </div>

            <div style="min-width: 200px;">
                <select name="category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="sort" value="{{ $sortBy }}">
            <input type="hidden" name="direction" value="{{ $sortDir }}">

            <button type="submit" class="btn btn-secondary">Cari</button>

            @if(!empty($search) || !empty($categoryId))
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm" style="color: var(--danger);">Reset Filter</a>
            @endif
        </form>
    </div>
</div>

<!-- Tabel Listing Produk dengan Sorting Aman -->
<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>
                        <a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'sku', 'direction' => ($sortBy === 'sku' && $sortDir === 'ASC') ? 'DESC' : 'ASC'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                            <span>SKU</span>
                            @if($sortBy === 'sku')
                                <span>{!! $sortDir === 'ASC' ? '&uarr;' : '&darr;' !!}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => ($sortBy === 'name' && $sortDir === 'ASC') ? 'DESC' : 'ASC'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                            <span>Nama Produk</span>
                            @if($sortBy === 'name')
                                <span>{!! $sortDir === 'ASC' ? '&uarr;' : '&darr;' !!}</span>
                            @endif
                        </a>
                    </th>
                    <th>Kategori</th>
                    <th>
                        <a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price', 'direction' => ($sortBy === 'price' && $sortDir === 'ASC') ? 'DESC' : 'ASC'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                            <span>Harga Jual</span>
                            @if($sortBy === 'price')
                                <span>{!! $sortDir === 'ASC' ? '&uarr;' : '&darr;' !!}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'stock', 'direction' => ($sortBy === 'stock' && $sortDir === 'ASC') ? 'DESC' : 'ASC'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                            <span>Sisa Stok</span>
                            @if($sortBy === 'stock')
                                <span>{!! $sortDir === 'ASC' ? '&uarr;' : '&darr;' !!}</span>
                            @endif
                        </a>
                    </th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($products->isEmpty())
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-title">Tidak Ada Data Produk</div>
                                <div class="empty-desc">Tidak ditemukan produk sesuai kriteria pencarian atau filter yang ditentukan.</div>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach($products as $p)
                        <tr>
                            <td class="mono"><strong>{{ $p->sku }}</strong></td>
                            <td>
                                <div><strong>{{ $p->name }}</strong></div>
                                <div style="font-size: 0.8125rem; color: var(--text-muted);">
                                    Ditambahkan: {{ date('d M Y', strtotime($p->created_at)) }}
                                </div>
                            </td>
                            <td>{{ $p->category ? $p->category->name : '-' }}</td>
                            <td class="mono">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td>
                                <strong>{{ $p->stock }}</strong> unit
                                <div style="font-size: 0.75rem; color: var(--text-subtle);">min: {{ $p->minimum_stock }}</div>
                            </td>
                            <td>
                                @if($p->stock === 0)
                                    <span class="badge badge-danger">Habis</span>
                                @elseif($p->stock <= $p->minimum_stock)
                                    <span class="badge badge-warning">Menipis</span>
                                @else
                                    <span class="badge badge-success">Tersedia</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <a href="{{ route('products.detail', $p->id) }}" class="btn btn-secondary btn-sm" title="Lihat Detail & Hubungan Supplier">Detail</a>
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('products.edit', $p->id) }}" class="btn btn-secondary btn-sm" title="Edit">Ubah</a>
                                        @if($p->stock_movements_count == 0)
                                            <form method="POST" action="{{ route('products.destroy', $p->id) }}" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--danger);" title="Hapus">Hapus</button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm" style="opacity: 0.45; cursor: not-allowed;" title="Produk tidak dapat dihapus karena memiliki riwayat transaksi (RESTRICT)" disabled>Hapus</button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Paginasi -->
    @if($products->total() > 0)
        <div style="padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-subtle); flex-wrap: wrap; gap: 1rem;">
            <div style="font-size: 0.875rem; color: var(--text-muted);">
                Menampilkan halaman <strong>{{ $products->currentPage() }}</strong> dari <strong>{{ $products->lastPage() }}</strong> (Total: {{ $products->total() }} produk)
            </div>
            <div>
                {!! $products->links() !!}
            </div>
        </div>
    @endif
</div>
@endsection
