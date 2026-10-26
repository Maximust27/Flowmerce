<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Flowmerce POS — Terminal kasir."><title>{{ $title ?? 'Kasir · Flowmerce' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <script>(function(){try{var v=localStorage.getItem('flowmerce-theme');document.documentElement.dataset.fmTheme=(v==='dark'||v==='light')?v:(matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light')}catch(e){document.documentElement.dataset.fmTheme='light'}})();</script>
  @vite(['resources/css/app.css', 'resources/css/flowmerce-studio.css', 'resources/js/app.js'])
</head>
<body class="fm-pos">{{ $slot }}</body>
</html>