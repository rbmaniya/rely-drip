@extends('admin.layouts.app')

@section('page-title', 'Order '.$order->order_number)

@section('page-actions')
    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-outline-secondary">
        <i class="bi bi-receipt me-1"></i> Invoice
    </a>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Back</a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="stat-card p-0 mb-3">
                <div class="p-3 pb-0"><h2 class="h6">Products Ordered</h2></div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Variation</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td class="text-muted small">{{ $item->variation_label ?? '—' }} @if($item->sku) <br>SKU: {{ $item->sku }} @endif</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top">
                    <div class="d-flex justify-content-between"><span class="text-muted">Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Shipping</span><span>${{ number_format($order->shipping_charge, 2) }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Tax</span><span>${{ number_format($order->tax_amount, 2) }}</span></div>
                    {{-- <div class="d-flex justify-content-between"><span class="text-muted">Discount</span><span>-${{ number_format($order->discount_amount, 2) }}</span></div> --}}
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>Grand Total</span><span>${{ number_format($order->grand_total, 2) }}</span></div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="stat-card h-100">
                        <h2 class="h6 mb-2">Shipping Address</h2>
                        <p class="mb-0 small">
                            {{ $order->shipping_full_name }}<br>
                            {{ $order->shipping_mobile }}<br>
                            {{ $order->shipping_address_line }}@if($order->shipping_landmark), {{ $order->shipping_landmark }}@endif<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                            {{ $order->shipping_country }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h2 class="h6 mb-0">Billing Address</h2>
                            @if ($order->billing_same_as_shipping)
                                <span class="badge text-bg-secondary">Same as shipping</span>
                            @endif
                        </div>
                        <p class="mb-0 small">
                            {{ $order->billing_full_name }}<br>
                            {{ $order->billing_mobile }}<br>
                            {{ $order->billing_address_line }}<br>
                            {{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}<br>
                            {{ $order->billing_country }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="stat-card mb-3">
                <h2 class="h6 mb-2">Customer</h2>
                @if ($order->customer)
                    <p class="mb-1 fw-semibold">{{ $order->customer->name }}</p>
                    <p class="mb-0 small text-muted">{{ $order->customer->email }}</p>
                    <a href="{{ route('admin.customers.show', $order->customer) }}" class="small">View customer profile</a>
                @else
                    <p class="text-muted small mb-0">Guest customer</p>
                @endif
            </div>

            <div class="stat-card mb-3">
                <h2 class="h6 mb-2">Payment</h2>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Method</span>
                    <span class="text-uppercase">{{ $order->payment_method ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Status</span>
                    <span class="badge {{ $order->payment_status->badgeClass() }}">{{ $order->payment_status->label() }}</span>
                </div>
            </div>

            <div class="stat-card mb-3">
                <h2 class="h6 mb-3">Update Order Status</h2>
                @php($nextStatuses = $order->status->nextStatuses())

                @if (count($nextStatuses))
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                        @csrf @method('PATCH')
                        <div class="mb-2">
                            <label class="form-label small">New Status</label>
                            <select name="status" class="form-select">
                                @foreach ($nextStatuses as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Note (optional)</label>
                            <textarea name="note" rows="2" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Update Status</button>
                    </form>
                @else
                    <p class="text-muted small mb-0">This order is in a final state ({{ $order->status->label() }}) and cannot be changed further.</p>
                @endif
            </div>

            <div class="stat-card">
                <h2 class="h6 mb-3">Order Timeline</h2>
                <ul class="list-unstyled mb-0">
                    @foreach ($order->statusHistories as $history)
                        <li class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="badge {{ $history->status->badgeClass() }}">{{ $history->status->label() }}</span>
                                <span class="text-muted small">{{ $history->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            @if ($history->note)
                                <div class="small text-muted mt-1">{{ $history->note }}</div>
                            @endif
                            @if ($history->admin)
                                <div class="small text-muted">by {{ $history->admin->name }}</div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
