<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\ProductVariation;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View
    {
        return view('storefront.cart.index', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'variation_id' => ['required', 'uuid', 'exists:product_variations,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $variation = ProductVariation::findOrFail($data['variation_id']);

        if ($variation->stock <= 0) {
            return back()->with('error', 'This variation is out of stock.');
        }

        $this->cart->add($variation, $data['quantity'] ?? 1);

        if ($request->input('redirect_to') === 'checkout') {
            return redirect()->route('storefront.checkout.index');
        }

        return back()->with('success', 'Added to cart.');
    }

    public function update(Request $request, ProductVariation $variation): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $this->cart->updateQuantity($variation->id, $data['quantity']);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(ProductVariation $variation): RedirectResponse
    {
        $this->cart->remove($variation->id);

        return back()->with('success', 'Item removed from cart.');
    }
}
