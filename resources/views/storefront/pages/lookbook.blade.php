@extends('storefront.layouts.app')

@section('page-title', 'Lookbook')

@section('content')
<div style="padding:3rem 2.5rem 2rem;" class="border-bottom">
    <h1 class="sec-ttl mb-0">Lookbook</h1>
    <p class="text-muted small mt-2">Real jewelry. Real craft. Real identity.</p>
</div>

<div class="ticker"><div class="ti"></div></div>

<div class="look-grid">
    <div class="look-item"><div class="look-bg">DRIP</div><div class="look-info"><div class="look-ttl">The Opening</div><div class="look-sub">Chain + Pendant · Gold</div></div></div>
    <div class="look-item"><div class="look-bg">ICE</div><div class="look-info"><div class="look-ttl">Tennis Ice</div><div class="look-sub">Tennis Bracelet · Silver</div></div></div>
    <div class="look-item"><div class="look-bg">GOLD</div><div class="look-info"><div class="look-ttl">Gold Statement</div><div class="look-sub">Signature Ring · Gold</div></div></div>
    <div class="look-item"><div class="look-bg">STUD</div><div class="look-info"><div class="look-ttl">Ear Candy</div><div class="look-sub">Stud Earrings</div></div></div>
    <div class="look-item"><div class="look-bg">SET</div><div class="look-info"><div class="look-ttl">Full Drip</div><div class="look-sub">Complete Set</div></div></div>
    <div class="look-item"><div class="look-bg">CUSTOM</div><div class="look-info"><div class="look-ttl">One of One</div><div class="look-sub">Custom Order Piece</div></div></div>
</div>

<div class="container py-5 text-center">
    <p class="text-muted fst-italic mb-4" style="font-size:.82rem;">Real photography coming soon.</p>
    <a href="{{ route('storefront.products.index') }}" class="btn btn-primary">Shop the Look</a>
</div>
@endsection
