@extends('layouts.main')

@section('title', 'Ubah Pemasok: ' . $supplier->name)

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div style="display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">&larr; Batal</a>
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0;">Ubah Data Pemasok</h2>
    </div>
</div>

<div class="card" style="max-width: 720px;">
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="form-group">
                <label for="name" class="form-label">Nama Perusahaan / Supplier *</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $supplier->name) }}" required autofocus>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="contact_person" class="form-label">Kontak Person (PIC)</label>
                    <input type="text" id="contact_person" name="contact_person" class="form-input" value="{{ old('contact_person', $supplier->contact_person) }}">
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone', $supplier->phone) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $supplier->email) }}">
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Alamat Gudang / Kantor</label>
                <textarea id="address" name="address" class="form-textarea" rows="3">{{ old('address', $supplier->address) }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
