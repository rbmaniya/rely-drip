@extends('storefront.layouts.app')

@section('page-title', 'Order '.$order->order_number)

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-3">
            @include('storefront.account.partials.nav')
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h4 mb-0">Order {{ $order->order_number }}</h1>
                <a href="{{ route('storefront.account.orders.invoice', $order) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-printer"></i> Invoice
                </a>
            </div>

            <div class="card border mb-3">
                <div class="card-body">
                    <div class="d-flex gap-2 mb-3">
                        <span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                        <span class="badge {{ $order->payment_status->badgeClass() }}">{{ $order->payment_status->label() }} &middot; Online Payment</span>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Variation</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->variation_label }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between"><span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                            <div class="d-flex justify-content-between"><span>Shipping</span><span>${{ number_format($order->shipping_charge, 2) }}</span></div>
                            <div class="d-flex justify-content-between mb-2"><span>Tax</span><span>${{ number_format($order->tax_amount, 2) }}</span></div>
                            <div class="d-flex justify-content-between fw-bold"><span>Grand Total</span><span>${{ number_format($order->grand_total, 2) }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="card border h-100">
                        <div class="card-body">
                            <h2 class="h6 mb-2">Shipping Address</h2>
                            <p class="small mb-0">
                                {{ $order->shipping_full_name }}<br>
                                {{ $order->shipping_address_line }}@if($order->shipping_landmark), {{ $order->shipping_landmark }}@endif<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                                {{ $order->shipping_country }}<br>
                                {{ $order->shipping_mobile }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border h-100">
                        <div class="card-body">
                            <h2 class="h6 mb-2">Order Timeline</h2>
                            <ul class="list-unstyled small mb-0">
                                @foreach ($order->statusHistories as $history)
                                    <li class="mb-2">
                                        <span class="badge {{ $history->status->badgeClass() }}">{{ $history->status->label() }}</span>
                                        <span class="text-muted">{{ $history->created_at->format('d M Y, h:i A') }}</span>
                                        @if ($history->note)
                                            <div class="text-muted">{{ $history->note }}</div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
