<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Dashboard') — {{ config('app.name', 'Jewellery Admin') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="admin-shell">
        @include('admin.partials.sidebar')

        <div class="admin-content">
            @include('admin.partials.topbar')

            <main class="admin-main">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h2 class="h4 mb-0">@yield('page-title', 'Dashboard')</h2>
                        @hasSection('page-subtitle')
                            <p class="text-muted small mb-0">@yield('page-subtitle')</p>
                        @endif
                    </div>
                    <div>
                        @yield('page-actions')
                    </div>
                </div>

                @include('admin.partials.flash-messages')

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
