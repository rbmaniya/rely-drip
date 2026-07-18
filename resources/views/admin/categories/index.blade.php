@extends('admin.layouts.app')

@section('page-title', 'Categories')
@section('page-subtitle', 'Organize your jewellery catalog into categories')

@section('page-actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Category
    </a>
@endsection

@section('content')
    <div class="stat-card p-0">
        <div class="p-3 border-bottom">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-sm-5 col-md-4">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Category name">
                </div>
                <div class="col-6 col-sm-3 col-md-3">
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </div>
                <div class="col-6 col-sm-3 col-md-3">
                    <label class="form-label small mb-1">Sort</label>
                    <select name="sort" class="form-select">
                        <option value="latest" @selected(request('sort', 'latest') === 'latest')>Latest</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>Oldest</option>
                        <option value="alphabetical" @selected(request('sort') === 'alphabetical')>Alphabetical</option>
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
                        <th>Image</th>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Slug</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>
                                @if ($category->image)
                                    <img src="{{ asset('storage/'.$category->image) }}" class="thumb-sm" alt="{{ $category->name }}">
                                @else
                                    <div class="thumb-sm bg-light d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image"></i></div>
                                @endif
                            </td>
                            <td>{{ $category->name }}</td>
                            <td class="d-none d-md-table-cell text-muted small">{{ $category->slug }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td>
                                <span class="badge {{ $category->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this category? This cannot be undone.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">No categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="p-3 border-top">{{ $categories->links() }}</div>
        @endif
    </div>
@endsection
