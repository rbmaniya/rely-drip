@extends('storefront.layouts.app')

@section('page-title', 'Contact')

@section('content')
@php
    $settings = \App\Models\WebsiteSetting::allSettings();
@endphp

<div style="padding:3rem 2.5rem 2rem;" class="border-bottom">
    <h1 class="sec-ttl mb-0">Contact <span>Us</span></h1>
    <p class="text-muted small mt-2">We respond to every message within 24 hours.</p>
</div>

<div class="ticker"><div class="ti"></div></div>

<div class="contact-split">
    <div class="contact-info-panel p-4 p-lg-5">
        <div class="sec-lbl">Reach Us</div>
        <div class="sec-ttl mb-4">Let's <span>Talk</span></div>

        <div class="d-flex flex-column gap-3 mb-4">
            @if ($settings->get('contact_phone'))
                <div>
                    <div class="opt-lbl mb-1">WhatsApp / Phone</div>
                    <div class="font-display fw-semibold">{{ $settings->get('contact_phone') }}</div>
                </div>
            @endif
            @if ($settings->get('contact_email'))
                <div>
                    <div class="opt-lbl mb-1">Email</div>
                    <div class="font-display fw-semibold" style="color:var(--blue);">{{ $settings->get('contact_email') }}</div>
                </div>
            @endif
            @if ($settings->get('social_instagram'))
                <div>
                    <div class="opt-lbl mb-1">Instagram</div>
                    <a href="{{ $settings->get('social_instagram') }}" target="_blank" rel="noopener" class="font-display fw-semibold" style="color:var(--blue);">Follow us</a>
                </div>
            @endif
        </div>

        @if ($settings->get('office_address'))
            <div class="sec-lbl">Our Office</div>
            <div class="store-card mb-4">
                <div class="sc-city">RELYDRIP</div>
                <div class="sc-addr">{{ $settings->get('office_address') }}</div>
                <span class="sc-status sc-open">Open</span>
            </div>
        @endif

        <div class="contact-trust-row">
            <div class="ctr-item"><i class="bi bi-shield-check"></i><span>Secure &amp; Verified Payments</span></div>
            <div class="ctr-item"><i class="bi bi-gem"></i><span>Handcrafted, Quality-Checked Pieces</span></div>
            <div class="ctr-item"><i class="bi bi-clock-history"></i><span>We Reply Within 24 Hours</span></div>
        </div>
    </div>

    <div class="contact-form-panel p-4 p-lg-5">
        <div class="sec-lbl">Send a Message</div>
        <div class="sec-ttl mb-4">Write to <span>Us</span></div>

        <form method="POST" action="{{ route('storefront.contact.store') }}">
            @csrf
            <input type="hidden" name="source" value="general">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subject</label>
                    <select name="subject" class="form-select">
                        <option>General Inquiry</option>
                        <option>Custom Order</option>
                        <option>Artist / Collab</option>
                        <option>Wholesale</option>
                        <option>Press / Media</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">WhatsApp</label>
                    <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" value="{{ old('country') }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Message</label>
                    <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror" placeholder="Tell us everything. We read every word." required>{{ old('message') }}</textarea>
                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Send Message ◆</button>
        </form>
    </div>
</div>
@endsection
