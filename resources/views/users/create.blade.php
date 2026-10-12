@extends('layouts.main')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div style="display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">&larr; Batal</a>
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0;">Tambah Pengguna Baru</h2>
    </div>
</div>

<div class="card" style="max-width: 720px;">
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            {{ csrf_field() }}

            <div class="form-row">
                <div class="form-group">
                    <label for="username" class="form-label">Nama Pengguna (Username) *</label>
                    <input type="text" id="username" name="username" class="form-input mono" placeholder="Contoh: staff_gudang" value="{{ old('username') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi (Password) *</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="role" class="form-label">Peran Hak Akses (Role) *</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staf Petugas (Operasional)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="full_name" class="form-label">Nama Lengkap Profil *</label>
                    <input type="text" id="full_name" name="full_name" class="form-input" placeholder="Contoh: Rian Pratama" value="{{ old('full_name') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Nomor Kontak Telepon</label>
                <input type="text" id="phone" name="phone" class="form-input" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label for="bio" class="form-label">Bio / Keterangan Tugas</label>
                <textarea id="bio" name="bio" class="form-textarea" rows="2" placeholder="Catatan tugas staf...">{{ old('bio') }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>
@endsection
