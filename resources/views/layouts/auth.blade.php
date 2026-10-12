<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk') - {{ config('app.name', 'Inventory Hub') }}</title>
    
    <!-- Google Fonts Preconnect & Loaded Fonts (Outfit, Inter, JetBrains Mono) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div class="auth-wrapper">
    @yield('content')
</div>
<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
