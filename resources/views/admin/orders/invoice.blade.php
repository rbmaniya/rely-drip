<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->invoice->invoice_number ?? $order->order_number }}</title>
    @vite(['resources/css/app.scss'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
        }
        body { background: #f4f5fb; }
    </style>
</head>
<body>
    <div class="container py-4" style="max-width: 800px;">
        <div class="d-flex justify-content-end gap-2 mb-3 no-print">
            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer me-1"></i>Print / Download PDF</button>
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">Back to Order</a>
        </div>

        <div class="bg-white rounded p-4 p-md-5 shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="h4 mb-0">{{ config('app.name', 'Jewellery Store') }}</h1>
                    <p class="text-muted small mb-0">Tax Invoice</p>
                </div>
                <div class="text-end">
                    <div class="fw-semibold">Invoice #{{ $order->invoice->invoice_number ?? '—' }}</div>
                    <div class="text-muted small">Order #{{ $order->order_number }}</div>
                    <div class="text-muted small">{{ optional($order->invoice?->issued_at)->format('d M Y') ?? $order->created_at->format('d M Y') }}</div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <div class="text-muted small text-uppercase mb-1">Billed To</div>
                    <div class="fw-semibold">{{ $order->customer?->name ?? $order->shipping_full_name }}</div>
                    <div class="small">{{ $order->shipping_address_line }}, {{ $order->shipping_city }}</div>
                    <div class="small">{{ $order->shipping_state }}, {{ $order->shipping_country }} - {{ $order->shipping_postal_code }}</div>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }} <div class="text-muted small">{{ $item->variation_label }}</div></td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-end">${{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row justify-content-end">
                <div class="col-6">
                    <div class="d-flex justify-content-between"><span class="text-muted">Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Shipping</span><span>${{ number_format($order->shipping_charge, 2) }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Tax</span><span>${{ number_format($order->tax_amount, 2) }}</span></div>
                    {{-- <div class="d-flex justify-content-between"><span class="text-muted">Discount</span><span>-${{ number_format($order->discount_amount, 2) }}</span></div> --}}
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>Grand Total</span><span>${{ number_format($order->grand_total, 2) }}</span></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
