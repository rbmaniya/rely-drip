@extends('storefront.layouts.app')

@section('page-title', 'My Wishlist')

@section('content')
<div class="container py-4">
    <h1 class="sec-ttl">My <span>Wishlist</span></h1>

    @if ($products->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-heart display-4 text-muted"></i>
            <p class="mt-3 mb-3">Your wishlist is empty.</p>
            <a href="{{ route('storefront.products.index') }}" class="btn btn-primary">Browse Products</a>
        </div>
    @else
        <div class="prod-grid">
            @foreach ($products as $product)
                <div>
                    @include('storefront.partials.product-card', ['product' => $product, 'wishlistedIds' => $products->pluck('id')])

                    @if ($product->variations->count() === 1 && $product->variations->first()->stock > 0)
                        <form method="POST" action="{{ route('storefront.cart.store') }}" class="mt-2">
                            @csrf
                            <input type="hidden" name="variation_id" value="{{ $product->variations->first()->id }}">
                            <button type="submit" class="btn btn-sm btn-outline-primary w-100">Move to Cart</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
