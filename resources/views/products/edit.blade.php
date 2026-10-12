@extends('layouts.main')

@section('title', 'Ubah Produk: ' . $product->name)

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">&larr; Batal</a>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0;">Ubah Data Produk</h2>
        </div>
        <p class="form-hint" style="margin: 4px 0 0 0;">Perbarui informasi master produk dan pemetaan supplier.</p>
    </div>
</div>

<form method="POST" action="{{ route('products.update', $product->id) }}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem;">
        <!-- Card Data Utama Produk -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Dasar Produk</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="sku" class="form-label">Nomor SKU (Stock Keeping Unit) *</label>
                    <input type="text" id="sku" name="sku" class="form-input mono" value="{{ old('sku', $product->sku) }}" required>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap Produk *</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="category_id" class="form-label">Kategori Produk *</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price" class="form-label">Harga Jual Satuan (Rp) *</label>
                        <input type="number" id="price" name="price" class="form-input" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stok Fisik Saat Ini</label>
                        <input type="text" class="form-input" value="{{ $product->stock }} unit (Kelola lewat Menu Transaksi)" disabled style="background-color: var(--bg-subtle);">
                    </div>

                    <div class="form-group">
                        <label for="minimum_stock" class="form-label">Ambang Batas Min. Stok *</label>
                        <input type="number" id="minimum_stock" name="minimum_stock" class="form-input" min="0" value="{{ old('minimum_stock', $product->minimum_stock) }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Pemasok / Supplier (Many-to-Many) -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pemasok / Supplier Terhubung (Many-to-Many)</h3>
            </div>
            <div class="card-body">
                <p class="form-hint" style="margin-top: 0;">Centang supplier yang memasok produk ini dan tentukan harga pasoknya:</p>

                @php
                    $connectedSuppliers = $product->suppliers->keyBy('id');
                @endphp

                <div style="display: flex; flex-direction: column; gap: 1rem; max-height: 380px; overflow-y: auto; padding-right: 0.5rem;">
                    @foreach($suppliers as $s)
                        @php
                            $isChecked = $connectedSuppliers->has($s->id);
                            $existingPrice = $isChecked ? $connectedSuppliers[$s->id]->pivot->supplier_price : 0;
                        @endphp
                        <div style="padding: 0.75rem; border: 1px solid var(--border-subtle); border-radius: 6px; background-color: var(--bg-card);">
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer;">
                                <input type="checkbox" name="supplier_ids[]" value="{{ $s->id }}" {{ $isChecked ? 'checked' : '' }}>
                                <span>{{ $s->name }}</span>
                            </label>
                            <div style="margin-top: 6px; display: flex; align-items: center; gap: 8px;">
                                <label style="font-size: 0.8125rem; color: var(--text-subtle); white-space: nowrap;">Harga Pasok (Rp):</label>
                                <input type="number" name="supplier_prices[{{ $s->id }}]" class="form-input" step="0.01" min="0" value="{{ old('supplier_prices.' . $s->id, $existingPrice) }}" style="padding: 0.375rem 0.625rem; font-size: 0.875rem;">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: flex-end;">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batalkan</a>
        <button type="submit" class="btn btn-primary">Simpan Perubahan Produk</button>
    </div>
</form>
@endsection
