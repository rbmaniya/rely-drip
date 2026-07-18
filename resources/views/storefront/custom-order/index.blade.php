@extends('storefront.layouts.app')

@section('page-title', 'Custom Order')

@section('content')
<div class="hero" style="min-height:44vh;">
    <p class="hero-eye">Your Vision. Our Craft.</p>
    <h1 class="hero-h1" style="font-size:clamp(32px,6vw,70px);">CUSTOM <span>ORDER</span></h1>
    <div class="hero-rule"><span>Built for you. Only for you.</span></div>
    <p class="hero-sub">Design your piece. We build it.</p>
</div>

<div class="ticker"><div class="ti"></div></div>

<div style="background:var(--off);" class="py-5 border-bottom">
    <div class="container">
        <div class="sec-lbl">The Process</div>
        <div class="sec-ttl">How Custom <span>Works</span></div>
        <div class="stat-row">
            <div class="stat-c text-center">
                <div class="font-display fw-bold" style="font-size:2rem;color:var(--blue-dim);">01</div>
                <div class="font-display fw-bold text-uppercase small mb-2">You Tell Us</div>
                <div class="small text-muted">Submit your vision. Budget, design details, any reference images.</div>
            </div>
            <div class="stat-c text-center">
                <div class="font-display fw-bold" style="font-size:2rem;color:var(--blue-dim);">02</div>
                <div class="font-display fw-bold text-uppercase small mb-2">We Design</div>
                <div class="small text-muted">Our jewelers sketch your piece and send a design for approval.</div>
            </div>
            <div class="stat-c text-center">
                <div class="font-display fw-bold" style="font-size:2rem;color:var(--blue-dim);">03</div>
                <div class="font-display fw-bold text-uppercase small mb-2">We Build</div>
                <div class="small text-muted">Hand-crafted using real gold, silver or platinum.</div>
            </div>
            <div class="stat-c text-center">
                <div class="font-display fw-bold" style="font-size:2rem;color:var(--blue-dim);">04</div>
                <div class="font-display fw-bold text-uppercase small mb-2">Delivered</div>
                <div class="small text-muted">Quality-checked, packaged, and shipped to your door.</div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="sec-lbl">Build Your Piece</div>
    <div class="sec-ttl">Configure Your <span>Order</span></div>

    <form method="POST" action="{{ route('storefront.custom-order.store') }}" enctype="multipart/form-data" class="custom-order-form">
        @csrf

        <div class="row g-0">
            <div class="col-lg-7 custom-order-fields">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Full Name" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number *</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="Enter Phone Number" class="form-control @error('whatsapp') is-invalid @enderror" required>
                        @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email Address" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Your Budget</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="estimated_price" value="{{ old('estimated_price') }}" placeholder="Enter Your Budget" min="0" class="form-control @error('estimated_price') is-invalid @enderror">
                        </div>
                        @error('estimated_price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Design Details</label>
                        <textarea name="vision" rows="6" class="form-control @error('vision') is-invalid @enderror" placeholder="Describe the design you have in mind — metal, weight, style, gemstones, occasion..." required>{{ old('vision') }}</textarea>
                        @error('vision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="pdp-buy w-100 mt-4">Submit Custom Order ◆</button>
            </div>

            <div class="col-lg-5 custom-order-upload">
                <label class="form-label">Upload Design Reference</label>
                <div class="small text-muted mb-2">PNG, JPG or GIF — your sketch, photo, or inspiration</div>

                <div class="design-upload-box">
                    <img id="design-reference-preview" class="d-none" alt="Design reference preview">
                    <div id="design-reference-placeholder" class="design-upload-placeholder">No image selected</div>
                </div>

                <label class="design-upload-btn">
                    Upload Image
                    <input type="file" name="design_reference" accept="image/*" data-image-preview-input="design-reference-preview" class="d-none">
                </label>
                @error('design_reference')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>
    </form>
</div>
@endsection
