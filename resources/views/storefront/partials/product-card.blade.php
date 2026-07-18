@php
    $wishlistedIds = $wishlistedIds ?? collect();
    $isWishlisted = $wishlistedIds->contains($product->id);
    $outOfStock = $product->is_out_of_stock;
@endphp

<div class="prod-card">
    <div class="prod-img-wrap position-relative">
        <a href="{{ route('storefront.products.show', $product->slug) }}" class="text-decoration-none">
            <img src="{{ $product->thumbnail ? asset('storage/'.$product->thumbnail) : 'https://placehold.co/400x500?text=No+Image' }}"
                 alt="{{ $product->title }}" class="product-card-thumb">
        </a>

        @if ($outOfStock)
            <span class="prod-badge badge-sold">Sold Out</span>
        @elseif ($product->is_best_seller)
            <span class="prod-badge badge-hot">Hot</span>
        @elseif ($product->is_new_arrival)
            <span class="prod-badge badge-new">New</span>
        @endif

        @auth('customer')
            <form method="POST" action="{{ $isWishlisted ? route('storefront.wishlist.destroy', $product) : route('storefront.wishlist.store', $product) }}"
                  data-wishlist-form class="position-absolute top-0 end-0 m-2">
                @csrf
                @if ($isWishlisted) @method('DELETE') @endif
                <button type="submit" data-wishlist-button
                        class="btn btn-sm wishlist-toggle-btn {{ $isWishlisted ? 'is-active' : '' }}"
                        title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                    <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                </button>
            </form>
        @endauth
    </div>

    <div class="prod-body p-3">
        <div class="prod-name">
            <a href="{{ route('storefront.products.show', $product->slug) }}" class="text-reset text-decoration-none text-truncate-custom">
                {{ $product->title }}
            </a>
        </div>
        <div class="prod-spec">{{ $product->category?->name }}</div>

        @if ($product->review_count > 0)
            <div class="small text-warning mb-1">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="bi {{ $i <= round($product->average_rating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                @endfor
                <span class="text-muted">({{ $product->review_count }})</span>
            </div>
        @endif

        <div class="prod-price">
            @if ($product->min_price !== null)
                From ${{ number_format($product->min_price, 2) }}
            @else
                Price on request
            @endif
        </div>
    </div>
</div>
