@extends('admin.layouts.app')

@section('page-title', 'Products')
@section('page-subtitle', 'Manage your jewellery product catalog')

@section('page-actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Product
    </a>
@endsection

@section('content')
    <div class="stat-card p-0">
        <div class="p-3 border-bottom">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Title or SKU">
                </div>
                <div class="col-6 col-sm-3 col-md-3">
                    <label class="form-label small mb-1">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category') === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-sm-3 col-md-3">
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-secondary">Filter</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="d-none d-md-table-cell">Category</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($product->thumbnail)
                                        <img src="{{ asset('storage/'.$product->thumbnail) }}" class="thumb-sm" alt="{{ $product->title }}">
                                    @else
                                        <div class="thumb-sm bg-light d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image"></i></div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $product->title }}</div>
                                        <div class="text-muted small">{{ $product->variations->count() }} variation(s)</div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $product->category->name }}</td>
                            <td>
                                @if ($product->is_out_of_stock)
                                    <span class="badge text-bg-danger">Out of stock</span>
                                @else
                                    {{ $product->total_stock }}
                                @endif
                            </td>
                            <td>{{ $product->min_price ? '$'.number_format($product->min_price, 2) : '—' }}</td>
                            <td><span class="badge {{ $product->status->badgeClass() }}">{{ $product->status->label() }}</span></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Toggle status">
                                            <i class="bi bi-toggle2-on"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.products.duplicate', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Duplicate"><i class="bi bi-copy"></i></button>
                                    </form>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this product? This cannot be undone.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="p-3 border-top">{{ $products->links() }}</div>
        @endif
    </div>
@endsection
