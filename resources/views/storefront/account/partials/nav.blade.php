<div class="list-group">
    <a href="{{ route('storefront.account.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('storefront.account.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a href="{{ route('storefront.account.orders.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('storefront.account.orders.*') ? 'active' : '' }}">
        <i class="bi bi-bag-check me-2"></i>My Orders
    </a>
    <a href="{{ route('storefront.wishlist.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('storefront.wishlist.*') ? 'active' : '' }}">
        <i class="bi bi-heart me-2"></i>Wishlist
    </a>
    <a href="{{ route('storefront.account.addresses.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('storefront.account.addresses.*') ? 'active' : '' }}">
        <i class="bi bi-geo-alt me-2"></i>Addresses
    </a>
    <a href="{{ route('storefront.account.profile.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('storefront.account.profile.*') ? 'active' : '' }}">
        <i class="bi bi-person me-2"></i>Profile Settings
    </a>
    <form method="POST" action="{{ route('storefront.logout') }}">
        @csrf
        <button type="submit" class="list-group-item list-group-item-action text-danger w-100 text-start">
            <i class="bi bi-box-arrow-right me-2"></i>Logout
        </button>
    </form>
</div>
