@extends('layouts.main')

@section('title', '404 Halaman Tidak Ditemukan')

@section('content')
<div class="card" style="max-width: 600px; margin: 3rem auto; text-align: center; padding: 2rem;">
    <div style="width: 64px; height: 64px; border-radius: 50%; background-color: var(--primary-subtle); color: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem;">404 - Halaman Tidak Ditemukan</h2>
    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
        Halaman atau rute yang Anda tuju tidak tersedia di sistem inventaris.
    </p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary" style="display: inline-flex;">Kembali ke Dashboard</a>
</div>
@endsection
