@extends('storefront.layouts.app')

@section('page-title', 'Shopping Cart')

@section('content')
<div class="container py-4">
    <h1 class="sec-ttl">Shopping <span>Cart</span></h1>

    @if ($items->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-bag display-4 text-muted"></i>
            <p class="mt-3 mb-3">Your cart is empty.</p>
            <a href="{{ route('storefront.products.index') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                @foreach ($items as $item)
                    <div class="card mb-3 border">
                        <div class="card-body d-flex gap-3 align-items-center">
                            <img src="{{ $item->variation->product->thumbnail ? asset('storage/'.$item->variation->product->thumbnail) : 'https://placehold.co/100x100' }}"
                                 alt="{{ $item->variation->product->title }}" style="width:90px;height:90px;object-fit:cover" class="rounded">

                            <div class="flex-grow-1">
                                <a href="{{ route('storefront.products.show', $item->variation->product->slug) }}" class="fw-semibold text-reset text-decoration-none d-block">
                                    {{ $item->variation->product->title }}
                                </a>
                                <p class="small text-muted mb-1">{{ $item->variation->label }} &middot; SKU: {{ $item->variation->sku }}</p>
                                <p class="fw-semibold mb-0">${{ number_format($item->variation->price, 2) }}</p>

                                @if ($item->insufficient_stock)
                                    <p class="small text-danger mb-0">
                                        Only {{ $item->variation->stock }} left in stock — quantity adjusted.
                                    </p>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('storefront.cart.update', $item->variation) }}" data-quantity-stepper class="d-flex align-items-center gap-1">
                                @csrf @method('PATCH')
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-quantity-step="-1">−</button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->variation->stock }}"
                                       class="form-control form-control-sm text-center" style="width:60px" onchange="this.form.submit()">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-quantity-step="1">+</button>
                            </form>

                            <div class="text-end" style="min-width:100px">
                                <p class="fw-semibold mb-2">${{ number_format($item->line_total, 2) }}</p>
                                <form method="POST" action="{{ route('storefront.cart.destroy', $item->variation) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <a href="{{ route('storefront.products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Continue Shopping
                </a>
            </div>

            <div class="col-lg-4">
                <div class="card border">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Order Summary</h2>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <p class="small text-muted">Shipping and tax will be calculated at checkout.</p>
                        <hr>
                        <a href="{{ route('storefront.checkout.index') }}" class="btn btn-primary w-100">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
