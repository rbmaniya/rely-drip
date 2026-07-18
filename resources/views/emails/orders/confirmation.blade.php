<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #262220;">
    <h2>Thank you for your order, {{ $order->shipping_full_name }}!</h2>

    <p>Your order <strong>{{ $order->order_number }}</strong> has been placed successfully and is now <strong>{{ $order->status->label() }}</strong>.</p>

    <table cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse; margin-top: 16px;">
        <thead>
            <tr style="background: #f4ecd8;">
                <th align="left">Product</th>
                <th align="left">Variation</th>
                <th align="center">Qty</th>
                <th align="right">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr style="border-bottom: 1px solid #e5e5e5;">
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->variation_label }}</td>
                    <td align="center">{{ $item->quantity }}</td>
                    <td align="right">${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table cellpadding="4" cellspacing="0" style="margin-top: 16px;">
        <tr><td>Subtotal</td><td align="right">${{ number_format($order->subtotal, 2) }}</td></tr>
        <tr><td>Shipping</td><td align="right">${{ number_format($order->shipping_charge, 2) }}</td></tr>
        <tr><td>Tax</td><td align="right">${{ number_format($order->tax_amount, 2) }}</td></tr>
        <tr><td><strong>Grand Total</strong></td><td align="right"><strong>${{ number_format($order->grand_total, 2) }}</strong></td></tr>
    </table>

    <h3 style="margin-top: 24px;">Shipping Address</h3>
    <p>
        {{ $order->shipping_full_name }}<br>
        {{ $order->shipping_address_line }}@if($order->shipping_landmark), {{ $order->shipping_landmark }}@endif<br>
        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
        {{ $order->shipping_country }}<br>
        {{ $order->shipping_mobile }}
    </p>

    <p>Payment Method: <strong>Online Payment (Paid)</strong></p>

    <p>We'll notify you as your order progresses. Thank you for shopping with us!</p>
</body>
</html>
