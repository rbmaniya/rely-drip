<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Account') — RELYDRIP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/storefront.scss', 'resources/js/storefront.js'])
</head>
<body>
    <div id="g-vbg" aria-hidden="true"></div>
    <canvas id="g-canvas"></canvas>

    <div class="site-wrap">
        @include('storefront.partials.header')
        <div class="beat-bar" id="bbar"></div>

        @include('storefront.partials.flash-messages')

        <main class="store-auth-shell">
            <div class="store-auth-card">
                <div class="text-center mb-4">
                    <a href="{{ route('storefront.home') }}" class="store-brand text-decoration-none">
                        RELYDRIP
                    </a>
                </div>

                <div class="card border-0">
                    <div class="card-body p-4 p-sm-5">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </main>

        @include('storefront.partials.footer')
    </div>
</body>
</html>
