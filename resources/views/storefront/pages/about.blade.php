@extends('storefront.layouts.app')

@section('page-title', 'About')

@section('content')
{{-- @php
    $siteName = \App\Models\WebsiteSetting::get('site_name', config('app.name', 'Jewellery Store'));
@endphp --}}

<div style="padding:5rem 2.5rem;" class="border-bottom">
    <h1 class="hero-h1" style="font-size:clamp(2.2rem,6vw,5rem);">BORN TO BE<br><span>SEEN.</span></h1>
    <p class="text-muted" style="font-size:.88rem;line-height:1.9;max-width:480px;">RELYDRIP is handcrafted jewelry in gold, silver and platinum — built for people who wear their identity, not just their outfit.</p>
</div>

<div class="ticker"><div class="ti"></div></div>

<div class="stat-row">
    <div class="stat-c"><div class="stat-n">3</div><div class="stat-l">Metals — Gold, Silver, Platinum</div></div>
    <div class="stat-c"><div class="stat-n">6</div><div class="stat-l">Gold purities — 9K to 24K</div></div>
    <div class="stat-c"><div class="stat-n">100%</div><div class="stat-l">Made to order</div></div>
    <div class="stat-c"><div class="stat-n">∞</div><div class="stat-l">Custom possibilities</div></div>
</div>

<div style="padding:6rem 2.5rem;text-align:center;" class="border-bottom">
    <div style="max-width:700px;margin:0 auto;">
        <div class="sec-lbl justify-content-center">The Manifesto</div>
        <div class="font-display fw-bold text-uppercase" style="font-size:clamp(1.2rem,3vw,2rem);letter-spacing:-.01em;line-height:1.4;margin-bottom:2rem;">
            Jewelry is a statement.<br><span style="color:var(--blue);">A statement of personality.</span>
        </div>
        <p class="text-muted fst-italic" style="font-size:.88rem;line-height:2.2;">Every piece you wear tells the world something about you before you open your mouth. RELYDRIP was built for the people who wear jewelry not because it is beautiful — but because it is an extension of themselves.</p>
    </div>
</div>

<div style="background:var(--off);" class="py-5">
    <div class="container">
        <div class="sec-lbl">What We Build</div>
        <div class="sec-ttl">Identity. <span>Not Just Jewelry.</span></div>
        <div class="id-grid">
            <div class="id-card"><span class="id-icon">⭐</span><div class="id-word">Your Statement</div><p class="id-desc">Every chain, ring, pendant says something without a single word. You choose what the world hears.</p></div>
            <div class="id-card"><span class="id-icon">🔥</span><div class="id-word">Your Vision</div><p class="id-desc">The greatest icons had a vision so clear it showed up in everything they wore. Your jewelry is part of that.</p></div>
            <div class="id-card"><span class="id-icon">👑</span><div class="id-word">Your Identity</div><p class="id-desc">You are not wearing jewelry. You are wearing yourself. Your culture, your journey, your art, your ambition.</p></div>
            <div class="id-card"><span class="id-icon">❤️</span><div class="id-word">Your Craft</div><p class="id-desc">The love for the craft. Every piece we make carries that care from design to delivery.</p></div>
            <div class="id-card"><span class="id-icon">🎨</span><div class="id-word">Your Art</div><p class="id-desc">Hip hop, music, film, performance — art that moves the world deserves jewelry that moves with it.</p></div>
            <div class="id-card"><span class="id-icon">🌍</span><div class="id-word">Your World</div><p class="id-desc">RELYDRIP is a world — and every piece you own is a part of it.</p></div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="sec-lbl">The Journey</div>
    <div class="sec-ttl">RELYDRIP <span>Timeline</span></div>
    <div class="timeline">
        <div class="tl-item"><div class="tl-year">Y1</div><div><div class="tl-ttl">RELYDRIP Founded</div><div class="tl-desc">The vision: real gold, real silver, real platinum jewelry that says something about the person wearing it.</div></div></div>
        <div class="tl-item"><div class="tl-year">Y1</div><div><div class="tl-ttl">First Collection Launched</div><div class="tl-desc">Chains, rings, pendants and earrings across every metal and purity, made to order.</div></div></div>
        <div class="tl-item"><div class="tl-year">Y2</div><div><div class="tl-ttl">Custom Order Program</div><div class="tl-desc">One-of-one pieces designed around your vision, built by hand.</div></div></div>
        <div class="tl-item" style="border:none;margin:0;padding:0;"><div class="tl-year">∞</div><div><div class="tl-ttl">Built to Last</div><div class="tl-desc">The goal. The only acceptable destination.</div></div></div>
    </div>
</div>
@endsection
