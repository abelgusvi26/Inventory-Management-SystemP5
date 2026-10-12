@extends('layouts.main')

@section('title', 'Tambah Kategori')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div style="display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">&larr; Batal</a>
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0;">Tambah Kategori Baru</h2>
    </div>
</div>

<div class="card" style="max-width: 680px;">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="name" class="form-label">Nama Kategori *</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Contoh: Perangkat Jaringan & Server" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Kategori</label>
                <textarea id="description" name="description" class="form-textarea" rows="3" placeholder="Deskripsi ringkas mengenai kategori ini...">{{ old('description') }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection
