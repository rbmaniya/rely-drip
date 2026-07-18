@extends('storefront.layouts.app')

@section('page-title', 'Order Confirmed')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <i class="bi bi-check-circle-fill text-success display-3"></i>
        <h1 class="h3 mt-3">Thank you! Your order has been placed.</h1>
        <p class="text-muted">Order Number: <strong>{{ $order->order_number }}</strong></p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h2 class="h5 mb-3">Order Items</h2>
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

                    <div class="d-flex justify-content-between"><span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="d-flex justify-content-between"><span>Shipping</span><span>${{ number_format($order->shipping_charge, 2) }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span>Tax</span><span>${{ number_format($order->tax_amount, 2) }}</span></div>
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>Grand Total</span><span>${{ number_format($order->grand_total, 2) }}</span></div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5 mb-2">Shipping To</h2>
                    <p class="mb-0">
                        {{ $order->shipping_full_name }}<br>
                        {{ $order->shipping_address_line }}@if($order->shipping_landmark), {{ $order->shipping_landmark }}@endif<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}, {{ $order->shipping_country }}<br>
                        {{ $order->shipping_mobile }}
                    </p>
                    <p class="mt-2 mb-0"><span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                        <span class="badge {{ $order->payment_status->badgeClass() }}">{{ $order->payment_status->label() }} &middot; Online Payment</span></p>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('storefront.products.index') }}" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection
