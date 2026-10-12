@extends('layouts.main')

@section('title', 'Ubah Pengguna: ' . $user->username)

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div style="display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">&larr; Batal</a>
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0;">Ubah Data Pengguna</h2>
    </div>
</div>

<div class="card" style="max-width: 720px;">
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user->id) }}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Pengguna (Username)</label>
                    <input type="text" class="form-input mono" value="{{ $user->username }}" disabled style="background-color: var(--bg-subtle);">
                    <span class="form-hint">Username bersifat permanen dan tidak dapat diubah.</span>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Kosongkan jika tidak diubah">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="role" class="form-label">Peran Hak Akses (Role) *</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staf Petugas (Operasional)</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="full_name" class="form-label">Nama Lengkap Profil *</label>
                    <input type="text" id="full_name" name="full_name" class="form-input" value="{{ old('full_name', $user->profile ? $user->profile->full_name : '') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Nomor Kontak Telepon</label>
                <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->profile ? $user->profile->phone : '') }}">
            </div>

            <div class="form-group">
                <label for="bio" class="form-label">Bio / Keterangan Tugas</label>
                <textarea id="bio" name="bio" class="form-textarea" rows="2">{{ old('bio', $user->profile ? $user->profile->bio : '') }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
