@extends('layouts.main')

@section('title', 'Manajemen Pengguna')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700;">Manajemen Pengguna Sistem (Users)</h2>
        <p class="form-hint" style="margin: 0;">Pengaturan akun akses internal, pembagian peran (Role), dan profil pengguna.</p>
    </div>
    <div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Tambah User Baru</span>
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna (Username)</th>
                    <th>Nama Lengkap Profil</th>
                    <th>Peran (Role)</th>
                    <th>Telepon</th>
                    <th>Transaksi Dicatat</th>
                    <th>Tanggal Didaftarkan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $u)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong class="mono">{{ $u->username }}</strong>
                            @if($u->id === Auth::id())
                                <span class="badge badge-primary" style="margin-left: 6px; font-size: 0.6875rem;">Akun Anda</span>
                            @endif
                        </td>
                        <td>{{ ($u->profile && $u->profile->full_name) ? $u->profile->full_name : '-' }}</td>
                        <td>
                            <span class="badge {{ $u->role === 'admin' ? 'badge-primary' : 'badge-neutral' }}">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td>{{ ($u->profile && $u->profile->phone) ? $u->profile->phone : '-' }}</td>
                        <td>
                            <span class="badge badge-neutral">{{ $u->stock_movements_count }} transaksi</span>
                        </td>
                        <td style="font-size: 0.8125rem; color: var(--text-muted);">
                            {{ date('d M Y', strtotime($u->created_at)) }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <a href="{{ route('users.edit', $u->id) }}" class="btn btn-secondary btn-sm">Ubah</a>
                                @if($u->id !== Auth::id())
                                    <form method="POST" action="{{ route('users.destroy', $u->id) }}" style="margin: 0;" onsubmit="return confirm('Hapus pengguna {{ $u->username }}?');">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--danger);">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
