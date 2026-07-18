@extends('storefront.layouts.app')

@section('page-title', 'Culture')

@section('content')
<div class="hero" style="min-height:55vh;">
    <p class="hero-eye">Jewelry × Culture</p>
    <h1 class="hero-h1" style="font-size:clamp(32px,6vw,72px);">WE SUPPORT<br>THE <span>CULTURE</span></h1>
    <div class="hero-rule"><span>Not a sponsor. A believer.</span></div>
    <p class="hero-sub">Real jewelry for real artists</p>
    <div class="d-flex gap-2 justify-content-center flex-wrap">
        <a href="#cult-connect" class="btn btn-primary px-4 py-3">Work With Us</a>
    </div>
</div>

<div class="ticker"><div class="ti"></div></div>

<div style="padding:5rem 2.5rem;text-align:center;" class="border-bottom">
    <div style="max-width:680px;margin:0 auto;">
        <div class="font-display fw-bold text-uppercase" style="font-size:clamp(1.2rem,3vw,2rem);letter-spacing:-.01em;margin-bottom:1.5rem;">
            "Jewelry is not decoration.<br><span style="color:var(--blue);">It is a declaration."</span>
        </div>
        <p class="text-muted fst-italic" style="font-size:.85rem;line-height:2;">We provide real handcrafted jewelry for music video shoots, festival stages, and cultural moments. We don't pay for placements. We believe in the culture.</p>
    </div>
</div>

<div style="background:var(--off);" class="py-5">
    <div class="container">
        <div class="sec-lbl">What We Stand For</div>
        <div class="sec-ttl">The <span>Pillars</span></div>
        <div class="pill-grid">
            <div class="pill"><div class="pill-n">01</div><div class="pill-t">Music Video Jewelry</div><div class="pill-d">Real premium pieces for shoots — gold, silver and platinum. Not props — real jewelry on camera.</div></div>
            <div class="pill"><div class="pill-n">02</div><div class="pill-t">Festival Stages</div><div class="pill-d">The biggest stages deserve the realest drip. We want to be on every stage.</div></div>
            <div class="pill"><div class="pill-n">03</div><div class="pill-t">Artist Identity</div><div class="pill-d">Custom jewelry built around your signature — your name, your logo, your vibe.</div></div>
            <div class="pill"><div class="pill-n">04</div><div class="pill-t">Real Metal Only</div><div class="pill-d">925 silver, 9K–24K gold, platinum 950. Every piece we provide is 100% real. No fakes. Never.</div></div>
            <div class="pill"><div class="pill-n">05</div><div class="pill-t">Handcrafted</div><div class="pill-d">Every piece is made to order and quality-checked before it ships.</div></div>
            <div class="pill"><div class="pill-n">06</div><div class="pill-t">Long Term Vision</div><div class="pill-d">One shoot leads to a signature piece. A signature piece leads to a collection. We build legacies — not campaigns.</div></div>
        </div>
    </div>
</div>

<div style="padding:5rem 2.5rem;" class="border-top" id="cult-connect">
    <div style="max-width:600px;margin:0 auto;">
        <div class="sec-lbl justify-content-center">Connect</div>
        <div class="sec-ttl text-center mb-2">Work With <span>Us</span></div>
        <p class="text-muted fst-italic text-center mb-4" style="font-size:.82rem;line-height:1.8;">Tell us about your shoot, video, or performance. We respond within 24 hours.</p>

        <form method="POST" action="{{ route('storefront.contact.store') }}">
            @csrf
            <input type="hidden" name="source" value="culture_collab">
            <div class="row g-2 mb-2">
                <div class="col-md-6">
                    <label class="form-label">Artist / Stage Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">You Are A</label>
                    <select name="subject" class="form-select">
                        <option>Hip Hop Artist</option>
                        <option>DJ / Producer</option>
                        <option>Film / Music Video Director</option>
                        <option>Festival / Event</option>
                        <option>Athlete</option>
                        <option>Content Creator</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" value="{{ old('country') }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Instagram</label>
                    <input type="text" name="instagram_handle" value="{{ old('instagram_handle') }}" placeholder="@yourhandle" class="form-control">
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
                <div class="col-12">
                    <label class="form-label">What Do You Need?</label>
                    <textarea name="message" rows="3" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-2">Connect With Us ◆</button>
        </form>
    </div>
</div>
@endsection
