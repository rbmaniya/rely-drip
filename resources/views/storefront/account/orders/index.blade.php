@extends('storefront.layouts.app')

@section('page-title', 'My Orders')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-3">
            @include('storefront.account.partials.nav')
        </div>

        <div class="col-lg-9">
            <h1 class="h4 mb-4">My Orders</h1>

            <div class="card border">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th class="text-end">Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td><span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></td>
                                    <td><span class="badge {{ $order->payment_status->badgeClass() }}">{{ $order->payment_status->label() }}</span></td>
                                    <td class="text-end">${{ number_format($order->grand_total, 2) }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('storefront.account.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">{{ $orders->links() }}</div>
        </div>
    </div>
</div>
@endsection
