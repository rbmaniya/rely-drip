<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Login') — {{ config('app.name', 'Jewellery Admin') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-shell">
        <div class="auth-card">
            <div class="text-center mb-4 text-white">
                <div class="fs-3 fw-bold">{{ config('app.name', 'Jewellery Admin') }}</div>
                <div class="text-white-50 small">Administration Panel</div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-sm-5">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
