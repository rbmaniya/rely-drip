<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Mail\OrderConfirmationMail;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(private readonly CartService $cart) {}

    /**
     * @param  array<string, mixed>  $data  Validated CheckoutRequest data.
     */
    public function placeOrder(?Customer $customer, array $data): Order
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            throw ValidationException::withMessages(['cart' => 'Your cart is empty.']);
        }

        foreach ($items as $item) {
            if ($item->insufficient_stock || $item->quantity <= 0) {
                throw ValidationException::withMessages([
                    'cart' => "\"{$item->variation->product->title}\" no longer has enough stock. Please update your cart.",
                ]);
            }
        }

        $shipping = $this->resolveShippingAddress($customer, $data);
        $billingSameAsShipping = (bool) ($data['billing_same_as_shipping'] ?? true);

        return DB::transaction(function () use ($customer, $items, $shipping, $billingSameAsShipping, $data) {
            $settings = WebsiteSetting::allSettings();

            $subtotal = (float) $items->sum('line_total');
            $freeShippingMin = (float) ($settings->get('free_shipping_min_order') ?? 0);
            $flatShippingRate = (float) ($settings->get('shipping_flat_rate') ?? 0);
            $shippingCharge = ($freeShippingMin > 0 && $subtotal >= $freeShippingMin) ? 0.0 : $flatShippingRate;
            $taxPercentage = (float) ($settings->get('tax_percentage') ?? 0);
            $taxAmount = round($subtotal * $taxPercentage / 100, 2);
            $grandTotal = round($subtotal + $shippingCharge + $taxAmount, 2);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_id' => $customer?->id,
                'status' => OrderStatus::Confirmed,
                'payment_status' => PaymentStatus::Paid,
                'payment_method' => 'online',
                'subtotal' => $subtotal,
                'shipping_charge' => $shippingCharge,
                'tax_amount' => $taxAmount,
                'discount_amount' => 0,
                'grand_total' => $grandTotal,
                'shipping_full_name' => $shipping['full_name'],
                'shipping_mobile' => $shipping['mobile'],
                'shipping_email' => $shipping['email'],
                'shipping_address_line' => $shipping['address_line'],
                'shipping_landmark' => $shipping['landmark'],
                'shipping_city' => $shipping['city'],
                'shipping_state' => $shipping['state'],
                'shipping_country' => $shipping['country'],
                'shipping_postal_code' => $shipping['postal_code'],
                'billing_same_as_shipping' => $billingSameAsShipping,
                'billing_full_name' => $billingSameAsShipping ? $shipping['full_name'] : $data['billing_full_name'],
                'billing_mobile' => $billingSameAsShipping ? $shipping['mobile'] : $data['billing_mobile'],
                'billing_email' => $billingSameAsShipping ? $shipping['email'] : ($data['billing_email'] ?? null),
                'billing_address_line' => $billingSameAsShipping ? $shipping['address_line'] : $data['billing_address_line'],
                'billing_landmark' => $billingSameAsShipping ? $shipping['landmark'] : ($data['billing_landmark'] ?? null),
                'billing_city' => $billingSameAsShipping ? $shipping['city'] : $data['billing_city'],
                'billing_state' => $billingSameAsShipping ? $shipping['state'] : $data['billing_state'],
                'billing_country' => $billingSameAsShipping ? $shipping['country'] : $data['billing_country'],
                'billing_postal_code' => $billingSameAsShipping ? $shipping['postal_code'] : $data['billing_postal_code'],
                'placed_at' => now(),
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item->variation->product_id,
                    'product_variation_id' => $item->variation->id,
                    'product_name' => $item->variation->product->title,
                    'variation_label' => $item->variation->label,
                    'sku' => $item->variation->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->variation->price,
                    'total_price' => $item->line_total,
                ]);

                ProductVariation::whereKey($item->variation->id)->decrement('stock', $item->quantity);
            }

            $order->transactions()->create([
                'gateway' => 'online',
                'amount' => $grandTotal,
                'status' => 'success',
            ]);

            $this->cart->clear();

            $order = $order->fresh(['items']);

            if ($order->shipping_email) {
                Mail::to($order->shipping_email)->send(new OrderConfirmationMail($order));
            }

            return $order;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, ?string>
     */
    private function resolveShippingAddress(?Customer $customer, array $data): array
    {
        if ($customer && ! empty($data['saved_address_id'])) {
            /** @var CustomerAddress $address */
            $address = $customer->addresses()->whereKey($data['saved_address_id'])->firstOrFail();

            return [
                'full_name' => $address->full_name,
                'mobile' => $address->mobile,
                'email' => $address->email ?: $customer->email,
                'address_line' => $address->address_line,
                'landmark' => $address->landmark,
                'city' => $address->city,
                'state' => $address->state,
                'country' => $address->country,
                'postal_code' => $address->postal_code,
            ];
        }

        return [
            'full_name' => $data['shipping_full_name'],
            'mobile' => $data['shipping_mobile'],
            'email' => $data['shipping_email'],
            'address_line' => $data['shipping_address_line'],
            'landmark' => $data['shipping_landmark'] ?? null,
            'city' => $data['shipping_city'],
            'state' => $data['shipping_state'],
            'country' => $data['shipping_country'],
            'postal_code' => $data['shipping_postal_code'],
        ];
    }
}
