<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Home') — RELYDRIP</title>
    <meta name="description" content="@yield('meta_description', 'Shop premium jewelry crafted with elegance at RELYDRIP.')">
    @hasSection('meta_keywords')
        <meta name="keywords" content="@yield('meta_keywords')">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/storefront.scss', 'resources/js/storefront.js'])
    @stack('styles')
</head>
<body>
    <div id="g-vbg" aria-hidden="true"></div>
    <canvas id="g-canvas"></canvas>

    <div class="site-wrap">
        @include('storefront.partials.header')
        <div class="beat-bar" id="bbar"></div>

        @include('storefront.partials.flash-messages')

        <main>
            @yield('content')
        </main>

        @include('storefront.partials.footer')
    </div>

    @stack('modals')

    @stack('scripts')
</body>
</html>
