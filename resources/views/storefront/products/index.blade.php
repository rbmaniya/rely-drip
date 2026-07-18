@extends('storefront.layouts.app')

@php
    $activeCategory = request('category');
    $baseParams = request()->except('category');
    $activeCategoryModel = $activeCategory ? \App\Models\Category::where('slug', $activeCategory)->first() : null;
@endphp

@section('page-title', $activeCategoryModel->seo_title ?? $activeCategoryModel->name ?? 'Shop')

@if ($activeCategoryModel?->seo_description)
    @section('meta_description', $activeCategoryModel->seo_description)
@endif

@section('content')
<div class="border-bottom shop-filters">
    <h1 class="sec-ttl mb-3">
        @if ($activeCategory)
            {{ $activeCategoryModel?->name }}
        @elseif (request('filter') === 'new_arrival')
            New <span>Arrivals</span>
        @elseif (request('filter') === 'best_seller')
            Best <span>Sellers</span>
        @elseif (request('filter') === 'featured')
            Featured <span>Products</span>
        @elseif (request('q'))
            Results for "{{ request('q') }}"
        @else
            All <span>Products</span>
        @endif
    </h1>

    <div class="filter-row">
        <a href="{{ route('storefront.products.index', $baseParams) }}" class="fpill {{ !$activeCategory ? 'on' : '' }}">All</a>
        @foreach ($categories as $category)
            <a href="{{ route('storefront.products.index', array_merge($baseParams, ['category' => $category->slug])) }}"
               class="fpill {{ $activeCategory === $category->slug ? 'on' : '' }}">{{ $category->name }}</a>
        @endforeach
    </div>

    <form method="GET" action="{{ route('storefront.products.index') }}" class="row g-2 align-items-end">
        @if (request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
        @if ($activeCategory)<input type="hidden" name="category" value="{{ $activeCategory }}">@endif

        <div class="col-6 col-md-2">
            <label class="form-label small mb-1">Metal</label>
            <select name="metal" class="form-select form-select-sm">
                <option value="">All</option>
                @foreach (\App\Enums\Metal::cases() as $metal)
                    <option value="{{ $metal->value }}" {{ request('metal') === $metal->value ? 'selected' : '' }}>{{ $metal->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-2">
            <label class="form-label small mb-1">Color</label>
            <select name="color" class="form-select form-select-sm">
                <option value="">All</option>
                @foreach (\App\Enums\MetalColor::cases() as $color)
                    <option value="{{ $color->value }}" {{ request('color') === $color->value ? 'selected' : '' }}>{{ $color->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-2">
            <label class="form-label small mb-1">Gold Purity</label>
            <select name="gold_purity" class="form-select form-select-sm">
                <option value="">All</option>
                @foreach (\App\Enums\GoldPurity::cases() as $purity)
                    <option value="{{ $purity->value }}" {{ request('gold_purity') === $purity->value ? 'selected' : '' }}>{{ $purity->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-2">
            <label class="form-label small mb-1">Availability</label>
            <select name="availability" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="in_stock" {{ request('availability') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                <option value="out_of_stock" {{ request('availability') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>
        </div>

        <div class="col-6 col-md-2">
            <label class="form-label small mb-1">Min $</label>
            <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control form-control-sm">
        </div>

        <div class="col-6 col-md-2">
            <label class="form-label small mb-1">Max $</label>
            <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control form-control-sm">
        </div>

        <div class="col-12 d-flex gap-2 mt-2">
            <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button>
            <a href="{{ route('storefront.products.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
        </div>
    </form>
</div>

<div class="ticker"><div class="ti"></div></div>

<div class="d-flex justify-content-between align-items-center py-3 px-3">
    <p class="text-muted small mb-0">{{ $products->total() }} products found</p>

    <form method="GET" class="d-flex align-items-center gap-2">
        @foreach (request()->except('sort') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <label class="small text-muted mb-0">Sort by</label>
        <select name="sort" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
            <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            <option value="best_selling" {{ request('sort') === 'best_selling' ? 'selected' : '' }}>Best Selling</option>
            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
        </select>
    </form>
</div>

<div class="prod-grid">
    @forelse ($products as $product)
        @include('storefront.partials.product-card', ['product' => $product, 'wishlistedIds' => $wishlistedIds])
    @empty
        <p class="text-muted p-4">No products match your filters.</p>
    @endforelse
</div>

<div class="p-3">
    {{ $products->links() }}
</div>
@endsection
