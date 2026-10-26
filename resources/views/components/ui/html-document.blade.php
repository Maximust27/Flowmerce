@props([
    'title' => null,
    'description' => 'Flowmerce — Workspace terpadu untuk inventaris, kasir, keuangan, dan operasional UMKM.',
    'bodyClass' => '',
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description }}">
    <meta name="theme-color" content="#f7f8f8">
    <title>{{ $title ? $title.' · Flowmerce' : 'Flowmerce · Workspace UMKM' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        (function () {
            var theme = 'light';
            try {
                var stored = localStorage.getItem('flowmerce-theme');
                theme = (stored === 'light' || stored === 'dark')
                    ? stored
                    : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            } catch (error) {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.dataset.fmTheme = theme;
            document.documentElement.style.colorScheme = theme;
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/css/flowmerce-studio.css', 'resources/css/flowmerce-experience.css', 'resources/js/app.js'])
</head>
<body class="{{ $bodyClass }}">
    {{ $slot }}
</body>
</html>