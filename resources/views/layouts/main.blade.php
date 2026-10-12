<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Informasi Inventaris') - {{ config('app.name', 'Inventory Hub') }}</title>
    
    <!-- Google Fonts Preconnect (Outfit, Inter, JetBrains Mono) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Modern SaaS UI -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div class="app-layout">
    <!-- Sidebar Navigasi -->
    <aside class="sidebar" aria-label="Menu Navigasi Utama">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="brand-logo">
                <div class="brand-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                </div>
                <span>{{ config('app.name', 'Inventory Hub') }}</span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span>Dashboard</span>
            </a>

            <div class="nav-section-title">Master Data</div>
            <a href="{{ route('products.index') }}" class="nav-item {{ request()->is('products*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                <span>Katalog Produk</span>
            </a>
            <a href="{{ route('categories.index') }}" class="nav-item {{ request()->is('categories*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span>Kategori</span>
            </a>
            <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->is('suppliers*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                <span>Pemasok / Supplier</span>
            </a>

            <div class="nav-section-title">Operasional Transaksi</div>
            <a href="{{ route('transactions.in') }}" class="nav-item {{ request()->routeIs('transactions.in') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                <span>Barang Masuk (IN)</span>
            </a>
            <a href="{{ route('transactions.out') }}" class="nav-item {{ request()->routeIs('transactions.out') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
                <span>Barang Keluar (OUT)</span>
            </a>
            <a href="{{ route('transactions.history') }}" class="nav-item {{ request()->routeIs('transactions.history') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Riwayat Mutasi Stok</span>
            </a>

            <div class="nav-section-title">Analisis & Laporan</div>
            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->is('reports*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                <span>Laporan Inventaris</span>
            </a>

            @if(Auth::check() && Auth::user()->isAdmin())
            <div class="nav-section-title">Administrasi</div>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Manajemen User</span>
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            Laravel {{ app()->version() }} &bull; Semester 3
        </div>
    </aside>

    <!-- Area Konten Utama -->
    <div class="main-area">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Buka Menu Navigasi">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                </button>
                <h1 class="topbar-title">Sistem Manajemen Inventaris</h1>
            </div>

            <div class="topbar-right">
                <!-- Theme Switcher -->
                <button type="button" class="theme-toggle-btn" id="themeToggleBtn" aria-label="Ganti Tema">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                    <span>Mode Gelap</span>
                </button>

                @if(Auth::check())
                <!-- Info User -->
                <div class="user-badge-group">
                    <div class="user-avatar" aria-hidden="true">
                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        @php
                            $userProfile = Auth::user()->profile;
                            $nameDisplay = ($userProfile && $userProfile->full_name) ? $userProfile->full_name : Auth::user()->username;
                            $roleDisplay = Auth::user()->role;
                        @endphp
                        <span class="user-name">{{ $nameDisplay }}</span>
                        <span class="user-role badge {{ $roleDisplay === 'admin' ? 'badge-primary' : 'badge-neutral' }}">
                            {{ $roleDisplay }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-secondary btn-sm" title="Keluar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        <span>Keluar</span>
                    </button>
                </form>
                @endif
            </div>
        </header>

        <!-- Body Konten -->
        <main class="content-wrapper" id="mainContent">
            <!-- Flash Pesan Sukses / Error -->
            @if(session('flash_success'))
                <div class="alert alert-success" role="alert">
                    <span>{{ session('flash_success') }}</span>
                </div>
            @endif

            @if(session('flash_error'))
                <div class="alert alert-error" role="alert">
                    <span>{{ session('flash_error') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-error" role="alert">
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="{{ asset('assets/js/app.js') }}"></script>
@yield('scripts')
</body>
</html>
