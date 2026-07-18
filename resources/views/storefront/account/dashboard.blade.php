@extends('storefront.layouts.app')

@section('page-title', 'My Account')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-3">
            @include('storefront.account.partials.nav')
        </div>

        <div class="col-lg-9">
            <h1 class="h4 mb-4">Welcome, {{ $customer->name }}</h1>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border text-center p-3">
                        <div class="fs-3 fw-bold">{{ $recentOrders->count() ? $customer->orders()->count() : 0 }}</div>
                        <div class="text-muted small">Total Orders</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border text-center p-3">
                        <div class="fs-3 fw-bold">{{ $addressCount }}</div>
                        <div class="text-muted small">Saved Addresses</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border text-center p-3">
                        <div class="fs-3 fw-bold">{{ $wishlistCount }}</div>
                        <div class="text-muted small">Wishlist Items</div>
                    </div>
                </div>
            </div>

            <div class="card border">
                <div class="card-body">
                    <h2 class="h6 mb-3">Recent Orders</h2>

                    @forelse ($recentOrders as $order)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <a href="{{ route('storefront.account.orders.show', $order) }}" class="text-reset fw-semibold text-decoration-none">
                                    {{ $order->order_number }}
                                </a>
                                <div class="small text-muted">{{ $order->created_at->format('d M Y') }}</div>
                            </div>
                            <span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                            <span>${{ number_format($order->grand_total, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">You haven't placed any orders yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
