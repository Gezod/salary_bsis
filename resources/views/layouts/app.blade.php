<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- ① CSS untuk menyembunyikan elemen ber-x-cloak sampai Alpine aktif --}}
    <style>[x-cloak]{display:none!important}</style>

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Tempat ekstra <head> dari halaman jika diperlukan --}}
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800 dark:bg-gray-900">

    {{-- Navigasi --}}
    @include('layouts.navigation')

    {{-- Page Header --}}
    @yield('header')

    {{-- Konten --}}
    <main class="py-6">
        @yield('content')
    </main>

    {{-- ② Alpine.js (via CDN) – tambahkan **SEBELUM** @stack('scripts') --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ③ Slot untuk script tambahan tiap-halaman (@push('scripts')) --}}
    @stack('scripts')
</body>
</html>
