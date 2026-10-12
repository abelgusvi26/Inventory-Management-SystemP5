@extends('layouts.main')

@section('title', 'Catat Barang Masuk (IN)')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--success-text);">Pencatatan Barang Masuk (Stock IN)</h2>
        <p class="form-hint" style="margin: 0;">Penerimaan stok fisik ke gudang dari pabrikan / supplier. Stok akan bertambah secara atomik.</p>
    </div>
</div>

<div class="card" style="max-width: 720px;">
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.store_in') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="product_id" class="form-label">Pilih Produk Inventaris *</label>
                <select id="product_id" name="product_id" class="form-select" required autofocus>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" data-stock="{{ $p->stock }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                            [{{ $p->sku }}] {{ $p->name }} (Sisa Stok: {{ $p->stock }} unit)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="quantity" class="form-label">Jumlah Barang Masuk (Unit) *</label>
                    <input type="number" id="quantity" name="quantity" class="form-input" min="1" placeholder="Masukkan jumlah unit" value="{{ old('quantity') }}" required>
                </div>

                <div class="form-group">
                    <label for="reference_number" class="form-label">Nomor Surat Jalan / PO</label>
                    <input type="text" id="reference_number" name="reference_number" class="form-input mono" placeholder="Contoh: SJ-2026-001" value="{{ old('reference_number') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Catatan Tambahan Penerimaan</label>
                <textarea id="notes" name="notes" class="form-textarea" rows="2" placeholder="Keterangan kondisi barang atau pengirim...">{{ old('notes') }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <a href="{{ route('transactions.history') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" style="background-color: var(--success); border-color: var(--success);">
                    <span>Konfirmasi Barang Masuk</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
