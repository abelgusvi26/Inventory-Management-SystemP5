@extends('layouts.main')

@section('title', 'Tambah Pemasok')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div style="display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">&larr; Batal</a>
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0;">Tambah Pemasok Baru</h2>
    </div>
</div>

<div class="card" style="max-width: 720px;">
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.store') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="name" class="form-label">Nama Perusahaan / Supplier *</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Contoh: PT Sumber Logistik Sejahtera" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="contact_person" class="form-label">Kontak Person (PIC)</label>
                    <input type="text" id="contact_person" name="contact_person" class="form-input" placeholder="Nama penanggung jawab" value="{{ old('contact_person') }}">
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" class="form-input" placeholder="021-xxxxxxx" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="sales@perusahaan.co.id" value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Alamat Gudang / Kantor</label>
                <textarea id="address" name="address" class="form-textarea" rows="3" placeholder="Alamat lengkap...">{{ old('address') }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Pemasok</button>
            </div>
        </form>
    </div>
</div>
@endsection
