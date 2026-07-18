<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $customer = Auth::guard('customer')->user();

        $products = Product::active()
            ->with(['category', 'images', 'variations'])
            ->whereHas('wishlists', fn ($q) => $q->where('customer_id', $customer->id))
            ->get();

        return view('storefront.account.wishlist.index', [
            'products' => $products,
            'wishlistedIds' => $products->pluck('id'),
        ]);
    }

    public function store(Product $product): RedirectResponse
    {
        Auth::guard('customer')->user()->wishlists()->firstOrCreate(['product_id' => $product->id]);

        return back()->with('success', 'Added to wishlist.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Auth::guard('customer')->user()->wishlists()->where('product_id', $product->id)->delete();

        return back()->with('success', 'Removed from wishlist.');
    }
}
