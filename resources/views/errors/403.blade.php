@extends('layouts.main')

@section('title', '403 Akses Ditolak')

@section('content')
<div class="card" style="max-width: 600px; margin: 3rem auto; text-align: center; padding: 2rem;">
    <div style="width: 64px; height: 64px; border-radius: 50%; background-color: var(--danger-subtle); color: var(--danger); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
    </div>
    <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--danger);">403 - Akses Dilarang (Forbidden)</h2>
    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
        {{ isset($message) ? $message : 'Anda tidak memiliki otorisasi untuk mengakses fitur administrasi ini.' }}
    </p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary" style="display: inline-flex;">Kembali ke Dashboard</a>
</div>
@endsection
