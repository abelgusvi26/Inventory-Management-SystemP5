@extends('layouts.main')

@section('title', 'Catat Barang Keluar (OUT)')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--danger-text);">Pencatatan Barang Keluar (Stock OUT)</h2>
        <p class="form-hint" style="margin: 0;">Pengeluaran stok barang untuk distribusi / penjualan. Stok tidak boleh menjadi negatif.</p>
    </div>
</div>

<div class="card" style="max-width: 720px;">
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.store_out') }}" id="stockOutForm">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="product_id" class="form-label">Pilih Produk Inventaris *</label>
                <select id="product_id" name="product_id" class="form-select" required autofocus>
                    <option value="" data-stock="0">-- Pilih Produk --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" data-stock="{{ $p->stock }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                            [{{ $p->sku }}] {{ $p->name }} &mdash; Tersedia: {{ $p->stock }} unit
                        </option>
                    @endforeach
                </select>
                <!-- Indikator Stok Dinamis (R-03 / app.js) -->
                <div id="stockAvailableIndicator" style="margin-top: 6px; font-size: 0.8125rem; font-weight: 600; color: var(--text-subtle);"></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="quantity" class="form-label">Jumlah Barang Keluar (Unit) *</label>
                    <input type="number" id="quantity" name="quantity" class="form-input" min="1" placeholder="Masukkan jumlah unit" value="{{ old('quantity') }}" required>
                    <span class="form-hint" id="stockLimitHint">Jumlah tidak boleh melebihi stok yang tersedia.</span>
                </div>

                <div class="form-group">
                    <label for="reference_number" class="form-label">No. DO / Invoice / Referensi</label>
                    <input type="text" id="reference_number" name="reference_number" class="form-input mono" placeholder="Contoh: DO-2026-089" value="{{ old('reference_number') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Tujuan Pengeluaran / Alasan</label>
                <textarea id="notes" name="notes" class="form-textarea" rows="2" placeholder="Keterangan pengiriman, nama penerima, atau divisi pemohon...">{{ old('notes') }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <a href="{{ route('transactions.history') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" style="background-color: var(--danger); border-color: var(--danger);" id="submitOutBtn">
                    <span>Konfirmasi Pengeluaran Barang</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
