@extends('storefront.layouts.app')

@php
    $settings = \App\Models\WebsiteSetting::allSettings();
@endphp

@section('page-title', $settings->get('seo_homepage_title') ?: 'Home')
@section('meta_description', $settings->get('seo_meta_description') ?: 'Shop premium jewelry crafted with elegance at RELYDRIP.')

@section('content')
{{-- @php
    $siteName = \App\Models\WebsiteSetting::get('site_name', config('app.name', 'Jewellery Store'));
@endphp --}}

<div class="hero hero-banner-mode">
    <div class="hero-bg-carousel carousel slide" id="heroBannerCarousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('banner/banner1.png') }}" class="d-block w-100" alt="RELYDRIP Banner">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('banner/banner2.png') }}" class="d-block w-100" alt="RELYDRIP Collection Banner">
            </div>
            {{-- <div class="carousel-item">
                <img src="{{ asset('banner/UB_Desktop.jpg') }}" class="d-block w-100" alt="RELYDRIP Banner">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('banner/Monsoon_Banner_Web.webp') }}" class="d-block w-100" alt="RELYDRIP Monsoon Banner">
            </div> --}}
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="prev">
            <span class="hero-banner-arrow"><i class="bi bi-chevron-left"></i></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="next">
            <span class="hero-banner-arrow"><i class="bi bi-chevron-right"></i></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="hero-overlay"></div>

    <div class="hero-content">
        <p class="hero-eye">{{ \App\Models\WebsiteSetting::get('hero_eyebrow', 'Born in Surat · Worn by the World') }}</p>
        <div class="speak-wrap" id="speak"></div>
        <h1 class="hero-h1">RELY<span>DRIP</span></h1>
        <div class="hero-rule"><span>Drip You Can Rely On</span></div>
        <p class="hero-sub">Jewelry is a Statement</p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="{{ route('storefront.products.index') }}" class="btn btn-primary px-4 py-3">Shop the Drip</a>
            <a href="{{ route('storefront.custom-order.index') }}" class="btn btn-outline-light px-4 py-3">Custom Order</a>
        </div>
    </div>

    <div class="hero-indicators">
        <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="1"></button>
    </div>
</div>

<div class="ticker"><div class="ti"></div></div>

<div>
    <div class="mf-line"><div><div class="mf-word">Jewelry</div><div class="mf-word dim">is not</div><div class="mf-word">Decoration.</div></div></div>
    <div class="mf-line"><div><div class="mf-word">It is a</div><div class="mf-word blue">Statement.</div><span class="mf-small">Of who you are. Of where you are going.</span></div></div>
    <div class="mf-line"><div><div class="mf-word dim">A statement</div><div class="mf-word">of Personality.</div></div></div>
    <div class="mf-line"><div><div class="mf-word">Your Vision.</div><div class="mf-word dim">Made visible.</div></div></div>
    <div class="mf-line"><div><div class="mf-word">Your Identity.</div><div class="mf-word blue">For the world.</div><div class="mf-word dim">To see.</div></div></div>
</div>

@if ($categories->isNotEmpty())
    <div style="background:var(--off);" class="py-5 border-top">
        <div class="container">
            <div class="sec-lbl">Collections</div>
            <div class="sec-head">
                <div class="sec-ttl">Shop by <span>Category</span></div>
                <a href="{{ route('storefront.categories.index') }}" class="sec-view-all">View All Categories <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="prod-grid mb-4">
                @foreach ($categories as $category)
                    <a href="{{ route('storefront.products.index', ['category' => $category->slug]) }}" class="prod-card text-decoration-none d-block">
                        <img src="{{ $category->image ? asset('storage/'.$category->image) : 'https://placehold.co/400x500?text='.urlencode($category->name) }}"
                             class="product-card-thumb" alt="{{ $category->name }}">
                        <div class="prod-body p-3">
                            <div class="prod-name">{{ $category->name }}</div>
                            {{-- <div class="prod-spec">{{ $category->products_count }} products</div> --}}
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if ($featuredProducts->isNotEmpty())
    <div class="container py-5">
        <div class="sec-lbl">Featured</div>
        <div class="sec-head">
            <div class="sec-ttl">Shop the <span>Drip</span></div>
            <a href="{{ route('storefront.products.index', ['filter' => 'featured']) }}" class="sec-view-all">Shop All <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="prod-grid">
            @foreach ($featuredProducts as $product)
                @include('storefront.partials.product-card', ['product' => $product, 'wishlistedIds' => $wishlistedIds])
            @endforeach
        </div>
    </div>
@endif

<div class="split border-top">
    <div class="split-p accent">
        <div class="split-ttl">Built for the culture.<br>Worn by the world.</div>
        <p class="split-body">Every piece is crafted with one rule — if it doesn't hit, it doesn't ship. Real gold. Real silver. Real platinum. Real drip.</p>
        <a href="{{ route('storefront.about') }}" class="btn btn-primary">Our Story</a>
    </div>
    <div class="split-p">
        <div class="split-ttl">Handcrafted.<br>Delivered to you.</div>
        <p class="split-body">Every order is made to order and quality-checked before it ships. Secure online payment, nationwide delivery.</p>
        <a href="{{ route('storefront.contact.index') }}" class="btn btn-outline-secondary">Get in Touch</a>
    </div>
</div>

@if ($newArrivals->isNotEmpty())
    <div class="container py-5">
        <div class="sec-lbl">Just Dropped</div>
        <div class="sec-head">
            <div class="sec-ttl">New <span>Arrivals</span></div>
            <a href="{{ route('storefront.products.index', ['filter' => 'new_arrival']) }}" class="sec-view-all">View All <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="prod-grid">
            @foreach ($newArrivals as $product)
                @include('storefront.partials.product-card', ['product' => $product, 'wishlistedIds' => $wishlistedIds])
            @endforeach
        </div>
    </div>
@endif

@if ($bestSellers->isNotEmpty())
    <div style="background:var(--off);" class="py-5 border-top">
        <div class="container">
            <div class="sec-lbl">Fan Favorites</div>
            <div class="sec-ttl">Best <span>Sellers</span></div>
            <div class="prod-grid">
                @foreach ($bestSellers as $product)
                    @include('storefront.partials.product-card', ['product' => $product, 'wishlistedIds' => $wishlistedIds])
                @endforeach
            </div>
        </div>
    </div>
@endif

<div class="border-top">
    <div class="container py-5">
        <div class="sec-lbl">Shop by Metal</div>
        <div class="sec-head">
            <div class="sec-ttl">Choose Your <span>Metal</span></div>
            <a href="{{ route('storefront.products.index') }}" class="sec-view-all">Shop All <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="metal-grid">
            <a href="{{ route('storefront.products.index', ['metal' => 'gold']) }}" class="metal-c">
                <span class="metal-gem">✨</span>
                <div class="metal-name">Gold</div>
                <p class="metal-desc">9K to 24K purity · Yellow, White &amp; Rose Gold finishes</p>
            </a>
            <a href="{{ route('storefront.products.index', ['metal' => 'silver']) }}" class="metal-c">
                <span class="metal-gem">💠</span>
                <div class="metal-name">Silver</div>
                <p class="metal-desc">925 Sterling Silver · Everyday drip, built to last</p>
            </a>
            <a href="{{ route('storefront.products.index', ['metal' => 'platinum']) }}" class="metal-c">
                <span class="metal-gem">◆</span>
                <div class="metal-name">Platinum</div>
                <p class="metal-desc">Platinum 950 · The ultimate statement piece</p>
            </a>
        </div>
    </div>
</div>

<div class="sw">
    <div class="sw-pre">The RELYDRIP Promise</div>
    <div class="sw-main">You wear it.<em>The world sees you.</em></div>
    <div class="sw-div"><span>◆</span></div>
    <p class="sw-body">We do not make jewelry for everyone. We make it for the ones who understand that what you wear is what you say — without saying anything. For the artists. The dreamers. The performers.</p>
    <div class="sw-sig">RELYDRIP</div>
</div>
@endsection
