@php
    $cartCount = app(\App\Services\CartService::class)->count();
    $customer = auth('customer')->user();
@endphp

<header class="store-header">
    <div class="d-flex align-items-center justify-content-between gap-3 h-100">
        <button class="store-menu-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="Open menu">
            <i class="bi bi-list"></i>
        </button>

        <a href="{{ route('storefront.home') }}" class="store-brand text-decoration-none">
            RELY<span>DRIP</span>
        </a>

        <nav class="store-nav d-none d-lg-flex gap-4">
            <a href="{{ route('storefront.home') }}" class="nav-link {{ request()->routeIs('storefront.home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('storefront.products.index') }}" class="nav-link {{ request()->routeIs('storefront.products.*') || request()->routeIs('storefront.categories.*') ? 'active' : '' }}">Shop</a>
            {{-- <a href="{{ route('storefront.lookbook') }}" class="nav-link {{ request()->routeIs('storefront.lookbook') ? 'active' : '' }}">Lookbook</a> --}}
            <a href="{{ route('storefront.custom-order.index') }}" class="nav-link {{ request()->routeIs('storefront.custom-order.*') ? 'active' : '' }}">Custom</a>
            {{-- <a href="{{ route('storefront.culture') }}" class="nav-link {{ request()->routeIs('storefront.culture') ? 'active' : '' }}">Culture</a> --}}
            <a href="{{ route('storefront.about') }}" class="nav-link {{ request()->routeIs('storefront.about') ? 'active' : '' }}">About</a>
            <a href="{{ route('storefront.contact.index') }}" class="nav-link {{ request()->routeIs('storefront.contact.*') ? 'active' : '' }}">Contact</a>
        </nav>

        <div class="store-actions d-flex align-items-center gap-3">
            <a href="{{ route('storefront.wishlist.index') }}" class="store-icon-btn text-decoration-none" aria-label="Wishlist">
                <i class="bi bi-heart"></i>
            </a>

            <a href="{{ route('storefront.cart.index') }}" class="store-icon-btn text-decoration-none" aria-label="Cart">
                <i class="bi bi-bag"></i>
                @if ($cartCount > 0)
                    <span class="cart-dot">{{ $cartCount }}</span>
                @endif
            </a>

            @if ($customer)
                <div class="dropdown">
                    <button class="store-account-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person"></i> <span class="d-none d-md-inline">{{ Str::limit($customer->name, 14) }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('storefront.account.dashboard') }}">My Account</a></li>
                        <li><a class="dropdown-item" href="{{ route('storefront.account.orders.index') }}">My Orders</a></li>
                        <li><a class="dropdown-item" href="{{ route('storefront.wishlist.index') }}">Wishlist</a></li>
                        <li><a class="dropdown-item" href="{{ route('storefront.account.addresses.index') }}">Addresses</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('storefront.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('storefront.login') }}" class="store-login-link d-none d-md-inline-block">Login</a>
                <a href="{{ route('storefront.register') }}" class="nav-cta">Sign Up</a>
            @endif
        </div>
    </div>
</header>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title store-brand">{{ \App\Models\WebsiteSetting::get('site_name', config('app.name', 'Jewellery Store')) }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('storefront.products.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search...">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="nav flex-column gap-2">
            <a href="{{ route('storefront.home') }}" class="nav-link">Home</a>
            <a href="{{ route('storefront.products.index') }}" class="nav-link">Shop</a>
            <a href="{{ route('storefront.categories.index') }}" class="nav-link">Categories</a>
            <a href="{{ route('storefront.lookbook') }}" class="nav-link">Lookbook</a>
            <a href="{{ route('storefront.custom-order.index') }}" class="nav-link">Custom Order</a>
            <a href="{{ route('storefront.culture') }}" class="nav-link">Culture</a>
            <a href="{{ route('storefront.about') }}" class="nav-link">About</a>
            <a href="{{ route('storefront.contact.index') }}" class="nav-link">Contact</a>
        </div>
    </div>
</div>
