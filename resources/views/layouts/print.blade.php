<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cetak Laporan') - {{ config('app.name', 'Inventory Hub') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        body {
            background-color: #ffffff !important;
            color: #0f172a !important;
            padding: 2rem;
        }
        .no-print {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; }
        }
    </style>
</head>
<body>
<div class="no-print">
    <button onclick="window.print()" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
        <span>Cetak Halaman Ini (Print / PDF)</span>
    </button>
    <button onclick="window.close()" class="btn btn-secondary">Tutup Jendela</button>
</div>

@yield('content')

</body>
</html>
