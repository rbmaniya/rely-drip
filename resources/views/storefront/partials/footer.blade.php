@php
    $settings = \App\Models\WebsiteSetting::allSettings();
@endphp

<footer class="store-footer pt-5 pb-4">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <a href="{{ route('storefront.home') }}" class="store-brand text-decoration-none d-inline-block mb-2">
                    {{-- {{ $settings->get('site_name', config('app.name', 'Jewellery Store')) }} --}}
                    RELYDRIP
                </a>
                <p class="small mb-3">{{ $settings->get('footer_tagline', 'Premium jewelry, handcrafted with real gold, silver and platinum.') }}</p>
                <div class="d-flex gap-3 fs-5">
                    @if ($settings->get('social_facebook'))
                        <a href="{{ $settings->get('social_facebook') }}" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if ($settings->get('social_instagram'))
                        <a href="{{ $settings->get('social_instagram') }}" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if ($settings->get('social_youtube'))
                        <a href="{{ $settings->get('social_youtube') }}" target="_blank" rel="noopener"><i class="bi bi-youtube"></i></a>
                    @endif
                    @if ($settings->get('social_pinterest'))
                        <a href="{{ $settings->get('social_pinterest') }}" target="_blank" rel="noopener"><i class="bi bi-pinterest"></i></a>
                    @endif
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <h4 class="mb-3">Shop</h4>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('storefront.products.index') }}">All Products</a></li>
                    <li class="mb-2"><a href="{{ route('storefront.categories.index') }}">Categories</a></li>
                    <li class="mb-2"><a href="{{ route('storefront.products.index', ['filter' => 'new_arrival']) }}">New Arrivals</a></li>
                    <li class="mb-2"><a href="{{ route('storefront.products.index', ['filter' => 'best_seller']) }}">Best Sellers</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-6">
                <h4 class="mb-3">Company</h4>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('storefront.about') }}">About Us</a></li>
                    <li class="mb-2"><a href="{{ route('storefront.culture') }}">Culture</a></li>
                    <li class="mb-2"><a href="{{ route('storefront.lookbook') }}">Lookbook</a></li>
                    <li class="mb-2"><a href="{{ route('storefront.custom-order.index') }}">Custom Order</a></li>
                    <li class="mb-2"><a href="{{ route('storefront.contact.index') }}">Contact</a></li>
                </ul>
            </div>

            <div class="col-lg-3">
                <h4 class="mb-3">Contact Us</h4>
                <ul class="list-unstyled small">
                    @if ($settings->get('office_address'))
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>{{ $settings->get('office_address') }}</li>
                    @endif
                    @if ($settings->get('contact_phone'))
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i>{{ $settings->get('contact_phone') }}</li>
                    @endif
                    @if ($settings->get('contact_email'))
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i>{{ $settings->get('contact_email') }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <p class="small text-center mb-0">
            {{ $settings->get('footer_copyright', '© '.date('Y').' RELYDRIP') }}
        </p>
    </div>
</footer>
