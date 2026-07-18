@extends('admin.layouts.app')

@section('page-title', $customer->name)

@section('page-actions')
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">Back</a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="stat-card mb-3">
                <h2 class="h6 mb-2">Profile</h2>
                <p class="mb-1"><strong>{{ $customer->name }}</strong></p>
                <p class="mb-1 small text-muted">{{ $customer->email }}</p>
                <p class="mb-2 small text-muted">{{ $customer->mobile ?? 'No phone on file' }}</p>
                <span class="badge {{ $customer->status === 'active' ? 'text-bg-success' : 'text-bg-danger' }}">
                    {{ ucfirst($customer->status) }}
                </span>
                <hr>
                @if ($customer->status === 'active')
                    <form action="{{ route('admin.customers.block', $customer) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">Block Customer</button>
                    </form>
                @else
                    <form action="{{ route('admin.customers.unblock', $customer) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-outline-success btn-sm w-100">Activate Customer</button>
                    </form>
                @endif
            </div>

            <div class="stat-card">
                <h2 class="h6 mb-3">Addresses</h2>
                @forelse ($customer->addresses as $address)
                    <div class="border rounded p-2 mb-2 small">
                        <div class="fw-semibold">{{ $address->full_name }} <span class="text-muted">({{ ucfirst($address->label) }})</span></div>
                        <div>{{ $address->address_line }}, {{ $address->city }}</div>
                        <div>{{ $address->state }}, {{ $address->country }} - {{ $address->postal_code }}</div>
                        <div>{{ $address->mobile }}</div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No saved addresses.</p>
                @endforelse
            </div>
        </div>

        <div class="col-lg-8">
            <div class="stat-card p-0">
                <div class="p-3 pb-0"><h2 class="h6">Order History</h2></div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customer->orders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                                    <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>
                                    <td>${{ number_format($order->grand_total, 2) }}</td>
                                    <td><span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
