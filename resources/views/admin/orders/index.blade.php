@extends('admin.layouts.app')

@section('page-title', 'Orders')
@section('page-subtitle', 'Track and process customer orders')

@section('content')
    <div class="stat-card p-0">
        <div class="p-3 border-bottom">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-sm-5 col-md-4">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Order # or customer">
                </div>
                <div class="col-6 col-sm-3 col-md-3">
                    <label class="form-label small mb-1">Order Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        @foreach (\App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-sm-3 col-md-3">
                    <label class="form-label small mb-1">Payment</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All</option>
                        @foreach (\App\Enums\PaymentStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(request('payment_status') === $status->value)>{{ $status->label() }}</option>
                        @endforeach
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
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th class="d-none d-md-table-cell">Payment</th>
                        <th>Status</th>
                        <th class="d-none d-lg-table-cell">Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->order_number }}</td>
                            <td>{{ $order->customer?->name ?? '—' }}</td>
                            <td>${{ number_format($order->grand_total, 2) }}</td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge {{ $order->payment_status->badgeClass() }}">{{ $order->payment_status->label() }}</span>
                            </td>
                            <td><span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></td>
                            <td class="d-none d-lg-table-cell text-muted small">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-5">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="p-3 border-top">{{ $orders->links() }}</div>
        @endif
    </div>
@endsection
