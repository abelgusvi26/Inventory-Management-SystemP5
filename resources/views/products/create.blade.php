@extends('layouts.main')

@section('title', 'Tambah Produk Baru')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">&larr; Batal</a>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0;">Tambah Produk Baru</h2>
        </div>
        <p class="form-hint" style="margin: 4px 0 0 0;">Lengkapi formulir master data produk inventaris baru dan tautkan supplier.</p>
    </div>
</div>

<form method="POST" action="{{ route('products.store') }}">
    {{ csrf_field() }}

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem;">
        <!-- Card Data Utama Produk -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Dasar Produk</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="sku" class="form-label">Nomor SKU (Stock Keeping Unit) *</label>
                    <input type="text" id="sku" name="sku" class="form-input mono" placeholder="Contoh: PRD-MN-004" value="{{ old('sku') }}" required>
                    <span class="form-hint">SKU harus unik di seluruh sistem database.</span>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap Produk *</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Contoh: Laptop Asus VivoBook 14" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="category_id" class="form-label">Kategori Produk *</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price" class="form-label">Harga Jual Satuan (Rp) *</label>
                        <input type="number" id="price" name="price" class="form-input" step="0.01" min="0" placeholder="0" value="{{ old('price', 0) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="stock" class="form-label">Stok Awal Fisik *</label>
                        <input type="number" id="stock" name="stock" class="form-input" min="0" placeholder="0" value="{{ old('stock', 0) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="minimum_stock" class="form-label">Ambang Batas Min. Stok *</label>
                        <input type="number" id="minimum_stock" name="minimum_stock" class="form-input" min="0" value="{{ old('minimum_stock', 5) }}" required>
                        <span class="form-hint">Pemicu sinyal peringatan stok menipis.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Pemasok / Supplier (Many-to-Many) -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tautkan Pemasok / Supplier (Many-to-Many)</h3>
            </div>
            <div class="card-body">
                <p class="form-hint" style="margin-top: 0;">Centang supplier yang menyediakan produk ini beserta harga pasok dari mereka:</p>

                @if($suppliers->isEmpty())
                    <div class="empty-state">
                        <div class="empty-desc">Belum ada master supplier. <a href="{{ route('suppliers.create') }}">Tambah supplier</a> terlebih dahulu.</div>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 1rem; max-height: 380px; overflow-y: auto; padding-right: 0.5rem;">
                        @foreach($suppliers as $s)
                            <div style="padding: 0.75rem; border: 1px solid var(--border-subtle); border-radius: 6px; background-color: var(--bg-card);">
                                <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer;">
                                    <input type="checkbox" name="supplier_ids[]" value="{{ $s->id }}" {{ (is_array(old('supplier_ids')) && in_array($s->id, old('supplier_ids'))) ? 'checked' : '' }}>
                                    <span>{{ $s->name }}</span>
                                </label>
                                <div style="margin-top: 6px; display: flex; align-items: center; gap: 8px;">
                                    <label style="font-size: 0.8125rem; color: var(--text-subtle); white-space: nowrap;">Harga Pasok (Rp):</label>
                                    <input type="number" name="supplier_prices[{{ $s->id }}]" class="form-input" step="0.01" min="0" placeholder="Harga pasok supplier" value="{{ old('supplier_prices.' . $s->id, 0) }}" style="padding: 0.375rem 0.625rem; font-size: 0.875rem;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: flex-end;">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batalkan</a>
        <button type="submit" class="btn btn-primary">Simpan Produk Baru</button>
    </div>
</form>
@endsection
