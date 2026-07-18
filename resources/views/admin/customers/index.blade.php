@extends('admin.layouts.app')

@section('page-title', 'Customers')
@section('page-subtitle', 'Registered storefront customers')

@section('content')
    <div class="stat-card p-0">
        <div class="p-3 border-bottom">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-5">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name, email or mobile">
                </div>
                <div class="col-6 col-sm-3 col-md-3">
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="blocked" @selected(request('status') === 'blocked')>Blocked</option>
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
                        <th>Customer</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th class="d-none d-lg-table-cell">Phone</th>
                        <th>Orders</th>
                        <th>Total Purchase</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td class="d-none d-md-table-cell text-muted small">{{ $customer->email }}</td>
                            <td class="d-none d-lg-table-cell text-muted small">{{ $customer->mobile ?? '—' }}</td>
                            <td>{{ $customer->orders_count }}</td>
                            <td>${{ number_format($customer->total_purchase ?? 0, 2) }}</td>
                            <td>
                                <span class="badge {{ $customer->status === 'active' ? 'text-bg-success' : 'text-bg-danger' }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                    @if ($customer->status === 'active')
                                        <form action="{{ route('admin.customers.block', $customer) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Block">
                                                <i class="bi bi-slash-circle"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.customers.unblock', $customer) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Unblock">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-5">No customers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($customers->hasPages())
            <div class="p-3 border-top">{{ $customers->links() }}</div>
        @endif
    </div>
@endsection
