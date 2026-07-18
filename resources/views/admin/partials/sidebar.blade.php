@php($admin = auth('admin')->user())

<div class="sidebar-backdrop" data-admin-sidebar-backdrop></div>

<aside class="admin-sidebar" data-admin-sidebar>
    <div class="brand">
        <i class="bi bi-gem me-2"></i>
        <span>{{ config('app.name', 'Jewellery Admin') }}</span>
    </div>

    <nav class="nav flex-column py-2">
        @if ($admin->hasAbility('dashboard.view'))
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        @endif

        @if ($admin->hasAbility('categories.view'))
            <div class="nav-section-title">Catalog</div>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Categories
            </a>
        @endif

        @if ($admin->hasAbility('products.view'))
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Products
            </a>
        @endif

        @if ($admin->hasAbility('orders.view') || $admin->hasAbility('customers.view'))
            <div class="nav-section-title">Sales</div>
        @endif

        @if ($admin->hasAbility('orders.view'))
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Orders
            </a>
        @endif

        @if ($admin->hasAbility('customers.view'))
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Customers
            </a>
        @endif

        @if ($admin->hasAbility('reviews.view'))
            <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i> Reviews
            </a>
        @endif

        @if ($admin->hasAbility('contact_messages.view'))
            <a href="{{ route('admin.contact-messages.index') }}" class="nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                <i class="bi bi-envelope"></i> Contact Messages
            </a>
        @endif

        @if ($admin->hasAbility('custom_orders.view'))
            <a href="{{ route('admin.custom-order-requests.index') }}" class="nav-link {{ request()->routeIs('admin.custom-order-requests.*') ? 'active' : '' }}">
                <i class="bi bi-gem"></i> Custom Order Requests
            </a>
        @endif

        @if ($admin->hasAbility('website_settings.view') || $admin->hasAbility('employees'))
            <div class="nav-section-title">Configuration</div>
        @endif

        @if ($admin->hasAbility('website_settings.view'))
            <a href="{{ route('admin.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Website Settings
            </a>
        @endif

        @if ($admin->hasAbility('employees'))
            <a href="{{ route('admin.employees.index') }}" class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Employees
            </a>
        @endif

        <div class="nav-section-title">Account</div>
        <a href="{{ route('admin.profile.edit') }}" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> My Profile
        </a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </nav>
</aside>
