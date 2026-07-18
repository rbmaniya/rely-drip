@extends('storefront.layouts.app')

@section('page-title', 'Categories')

@section('content')
<div style="padding:3rem 2.5rem 1.5rem;" class="border-bottom">
    <h1 class="sec-ttl mb-0">All <span>Categories</span></h1>
</div>

<div class="ticker"><div class="ti"></div></div>

<div class="prod-grid">
    @forelse ($categories as $category)
        <a href="{{ route('storefront.products.index', ['category' => $category->slug]) }}" class="prod-card text-decoration-none d-block">
            <img src="{{ $category->image ? asset('storage/'.$category->image) : 'https://placehold.co/400x500?text='.urlencode($category->name) }}"
                 class="product-card-thumb" alt="{{ $category->name }}">
            <div class="prod-body p-3">
                <div class="prod-name">{{ $category->name }}</div>
                <div class="prod-spec">{{ $category->products_count }} products</div>
            </div>
        </a>
    @empty
        <p class="text-muted p-4">No categories available yet.</p>
    @endforelse
</div>
@endsection
