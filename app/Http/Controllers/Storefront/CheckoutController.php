<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\CheckoutRequest;
use App\Models\WebsiteSetting;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly CheckoutService $checkout,
    ) {}

    public function index(): View|RedirectResponse
    {
        if (! $this->cart->hasItems()) {
            return redirect()->route('storefront.cart.index')->with('error', 'Your cart is empty.');
        }

        $customer = Auth::guard('customer')->user();
        $settings = WebsiteSetting::allSettings();

        $subtotal = $this->cart->subtotal();
        $freeShippingMin = (float) ($settings->get('free_shipping_min_order') ?? 0);
        $flatShippingRate = (float) ($settings->get('shipping_flat_rate') ?? 0);
        $shippingCharge = ($freeShippingMin > 0 && $subtotal >= $freeShippingMin) ? 0.0 : $flatShippingRate;
        $taxPercentage = (float) ($settings->get('tax_percentage') ?? 0);
        $taxAmount = round($subtotal * $taxPercentage / 100, 2);

        return view('storefront.checkout.index', [
            'items' => $this->cart->items(),
            'subtotal' => $subtotal,
            'shippingCharge' => $shippingCharge,
            'taxAmount' => $taxAmount,
            'grandTotal' => round($subtotal + $shippingCharge + $taxAmount, 2),
            'addresses' => $customer?->addresses()->orderByDesc('is_default')->get() ?? collect(),
            'customer' => $customer,
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        $order = $this->checkout->placeOrder($customer, $request->validated());

        session(['last_order_id' => $order->id]);

        return redirect()->route('storefront.orders.confirmation', $order)
            ->with('success', 'Your order has been placed successfully.');
    }
}
