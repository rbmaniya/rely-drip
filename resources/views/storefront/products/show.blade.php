@extends('storefront.layouts.app')

@section('page-title', $product->seo_title ?: $product->title)

@section('meta_description', $product->seo_description ?: $product->short_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 155))

@if ($product->meta_keywords)
    @section('meta_keywords', $product->meta_keywords)
@endif

@section('content')
@php
    $metals = $product->variations->pluck('metal')->unique('value');
    $colors = $product->variations->pluck('color')->unique('value');
    $purities = $product->variations->pluck('gold_purity')->filter()->unique('value');
    $isWishlisted = $wishlistedIds->contains($product->id);
    $averageRating = $product->reviews->avg('rating');

    $galleryImages = collect([$product->thumbnail])
        ->merge($product->images->pluck('path'))
        ->filter()
        ->unique()
        ->map(fn ($path) => asset('storage/'.$path))
        ->values();

    if ($galleryImages->isEmpty()) {
        $galleryImages = collect(['https://placehold.co/600x600']);
    }

    $videoEmbedUrl = $product->video_embed_url;
    $videoAutoplaySrc = null;

    if ($videoEmbedUrl) {
        $separator = str_contains($videoEmbedUrl, '?') ? '&' : '?';
        $videoAutoplaySrc = $videoEmbedUrl.$separator.(str_contains($videoEmbedUrl, 'vimeo') ? 'autoplay=1&muted=1' : 'autoplay=1&mute=1&playsinline=1');
    } elseif ($product->is_direct_video_file) {
        $videoAutoplaySrc = $product->video_url;
    }
@endphp

<span class="back-link d-block px-4 py-3 border-bottom" style="font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;font-family:'Space Grotesk',sans-serif;">
    <a href="{{ route('storefront.home') }}" class="text-reset text-decoration-none">Home</a> /
    <a href="{{ route('storefront.products.index', ['category' => $product->category?->slug]) }}" class="text-reset text-decoration-none">{{ $product->category?->name }}</a> /
    <span class="text-muted">{{ $product->title }}</span>
</span>

<div class="pdp-shell">
<div class="pdp-wrap">
    <div class="pdp-gallery">
        <div class="product-gallery-main" data-gallery-zoom>
            <button type="button" class="gallery-zoom-btn" data-gallery-fullscreen aria-label="View image fullscreen">
                <i class="bi bi-arrows-fullscreen"></i>
            </button>
            <img data-gallery-main src="{{ $galleryImages->first() }}" alt="{{ $product->title }}">

            @if ($videoAutoplaySrc)
                <div class="product-gallery-video d-none" data-gallery-video-wrap>
                    @if ($videoEmbedUrl)
                        <iframe data-gallery-video data-src="{{ $videoAutoplaySrc }}" title="{{ $product->title }} video"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    @else
                        <video data-gallery-video data-src="{{ $videoAutoplaySrc }}" muted autoplay playsinline controls></video>
                    @endif
                </div>
            @endif
        </div>

        @if ($galleryImages->count() > 1 || $videoAutoplaySrc)
            <div class="product-gallery-thumbs-wrap">
                <button type="button" class="gallery-arrow" data-gallery-scroll="-1" aria-label="Scroll thumbnails left">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <div class="product-gallery-thumbs" data-gallery-thumbs>
                    @foreach ($galleryImages as $index => $src)
                        <div class="product-gallery-thumb {{ $index === 0 ? 'active' : '' }}" data-gallery-thumb="{{ $src }}">
                            <img src="{{ $src }}" alt="{{ $product->title }} view {{ $index + 1 }}">
                        </div>
                    @endforeach

                    @if ($videoAutoplaySrc)
                        <div class="product-gallery-thumb product-gallery-thumb-video" data-gallery-thumb-video>
                            <img src="{{ $galleryImages->first() }}" alt="{{ $product->title }} video">
                            <span class="thumb-play-icon"><i class="bi bi-play-fill"></i></span>
                        </div>
                    @endif
                </div>
                <button type="button" class="gallery-arrow" data-gallery-scroll="1" aria-label="Scroll thumbnails right">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        @endif
    </div>

    <div class="pdp-info">
        <p class="pdp-cat">{{ $product->category?->name }}</p>
        <h1 class="pdp-name">{{ $product->title }}</h1>

        @if ($product->reviews->isNotEmpty())
            <div class="text-warning mb-2">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="bi {{ $i <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                @endfor
                <span class="text-muted small">({{ $product->reviews->count() }} reviews)</span>
            </div>
        @endif

        <p class="pdp-price mb-3">$<span data-variation-price>0.00</span></p>

        @if ($product->short_description)
            <p class="text-muted small mb-3">{{ $product->short_description }}</p>
        @endif

        <form id="pdp-add-to-cart-form" method="POST" action="{{ route('storefront.cart.store') }}" data-variation-selector>
            @csrf
            <input type="hidden" name="variation_id" data-variation-id-field value="">

            <div class="mb-3">
                <span class="opt-lbl">Metal</span>
                <div class="opt-row d-flex gap-2 flex-wrap">
                    @foreach ($metals as $metal)
                        <label class="variation-swatch">
                            <input type="radio" name="metal" data-variation-field="metal" value="{{ $metal->value }}" {{ $loop->first ? 'checked' : '' }}>
                            <span>{{ $metal->label() }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <span class="opt-lbl">Color</span>
                <div class="opt-row d-flex gap-2 flex-wrap">
                    @foreach ($colors as $color)
                        <label class="variation-swatch">
                            <input type="radio" name="color" data-variation-field="color" value="{{ $color->value }}" {{ $loop->first ? 'checked' : '' }}>
                            <span>{{ $color->label() }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            @if ($purities->isNotEmpty())
                <div class="mb-3" data-purity-group>
                    <span class="opt-lbl">Gold Purity</span>
                    <div class="opt-row d-flex gap-2 flex-wrap">
                        @foreach ($purities as $purity)
                            <label class="variation-swatch">
                                <input type="radio" name="gold_purity" data-variation-field="gold_purity" value="{{ $purity->value }}" {{ $loop->first ? 'checked' : '' }}>
                                <span>{{ $purity->label() }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <p class="small text-muted mb-1">SKU: <span data-variation-sku>-</span></p>
            <p class="small mb-3" data-variation-stock>-</p>
        </form>

        <div class="d-flex align-items-center gap-3 mb-3">
            <div data-quantity-stepper class="d-flex align-items-center gap-1">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-quantity-step="-1">−</button>
                <input type="number" name="quantity" form="pdp-add-to-cart-form" data-quantity-input value="1" min="1" class="form-control form-control-sm text-center" style="width:60px">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-quantity-step="1">+</button>
            </div>

            @auth('customer')
                <form method="POST" action="{{ $isWishlisted ? route('storefront.wishlist.destroy', $product) : route('storefront.wishlist.store', $product) }}" data-wishlist-form>
                    @csrf
                    @if ($isWishlisted) @method('DELETE') @endif
                    <button type="submit" data-wishlist-button class="btn btn-outline-secondary {{ $isWishlisted ? 'text-danger' : '' }}">
                        <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                    </button>
                </form>
            @endauth
        </div>

        <div class="d-flex gap-2">
            <button type="submit" form="pdp-add-to-cart-form" data-add-to-cart-button class="pdp-buy flex-fill">Add to Cart</button>
            <button type="submit" form="pdp-add-to-cart-form" name="redirect_to" value="checkout" data-add-to-cart-button class="btn btn-outline-secondary flex-fill">Buy Now</button>
        </div>
    </div>
</div>
</div>

<div class="gallery-lightbox" data-gallery-lightbox>
    <button type="button" class="gallery-lightbox-close" data-gallery-lightbox-close aria-label="Close">&times;</button>
    <img data-gallery-lightbox-img src="" alt="{{ $product->title }}">
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            @if ($product->description)
                <div class="sec-lbl">Description</div>
                <div class="mb-4">{!! nl2br(e($product->description)) !!}</div>
            @endif

            @if ($product->specifications->isNotEmpty())
                <div class="sec-lbl">Specifications</div>
                <table class="table table-sm mb-5">
                    <tbody>
                        @foreach ($product->specifications as $spec)
                            <tr>
                                <th style="width:200px">{{ $spec->title }}</th>
                                <td>{{ $spec->value }}</td>
                            </tr>
                        @endforeach
                        @if ($product->weight)
                            <tr>
                                <th>Weight</th>
                                <td>{{ $product->weight }} {{ $product->weight_unit }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endif

            @if ($product->reviews->isNotEmpty())
                <div class="sec-lbl">Customer Reviews</div>

                <div class="review-grid mb-3">
                    @foreach ($product->reviews as $review)
                        <div class="review-card">
                            <div class="review-card-top">
                                <div class="review-avatar">{{ \Illuminate\Support\Str::substr($review->customer->name, 0, 1) }}</div>
                                <div>
                                    <p class="review-author mb-0">{{ $review->customer->name }}</p>
                                    <span class="text-warning small">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </span>
                                </div>
                                <span class="review-date">{{ $review->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="review-text mb-0">{{ $review->description }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            @auth('customer')
                @if ($canReview)
                    <div class="card border-0 mt-3">
                        <div class="review-form-box">
                            <div class="sec-lbl">Write a Review</div>
                            <form method="POST" action="{{ route('storefront.reviews.store', $product) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small">Your Rating</label>
                                    <div class="star-picker" data-star-picker>
                                        <input type="hidden" name="rating" data-star-picker-input required>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star star-picker-icon" data-value="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Your Review</label>
                                    <textarea name="description" rows="3" class="form-control form-control-sm" required></textarea>
                                </div>
                                <button type="submit" class="review-submit-btn">Submit Review</button>
                            </form>
                        </div>
                    </div>
                @elseif ($alreadyReviewed)
                    <p class="small text-muted">You've already reviewed this product.</p>
                @else
                    <p class="small text-muted">Only customers who purchased and received this product can leave a review.</p>
                @endif
            @else
                <p class="small text-muted"><a href="{{ route('storefront.login') }}">Login</a> to write a review.</p>
            @endauth
        </div>
    </div>
</div>

@if ($relatedProducts->isNotEmpty())
    <div style="background:var(--off);" class="py-5 border-top">
        <div class="container">
            <div class="sec-lbl">You May Also Like</div>
            <div class="prod-grid">
                @foreach ($relatedProducts as $related)
                    @include('storefront.partials.product-card', ['product' => $related, 'wishlistedIds' => $wishlistedIds])
                @endforeach
            </div>
        </div>
    </div>
@endif

<script type="application/json" id="variations-data">{!! $variationsPayload->toJson() !!}</script>
@endsection
