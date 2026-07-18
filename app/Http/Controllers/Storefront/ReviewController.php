<?php

namespace App\Http\Controllers\Storefront;

use App\Enums\OrderStatus;
use App\Enums\ReviewStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\ReviewRequest;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Product $product): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        if (Review::where('product_id', $product->id)->where('customer_id', $customer->id)->exists()) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        $orderItem = OrderItem::where('product_id', $product->id)
            ->whereHas('order', fn ($q) => $q->where('customer_id', $customer->id)->where('status', OrderStatus::Delivered))
            ->latest()
            ->first();

        if (! $orderItem) {
            return back()->with('error', 'Only customers who purchased and received this product can leave a review.');
        }

        Review::create([
            ...$request->validated(),
            'product_id' => $product->id,
            'customer_id' => $customer->id,
            'order_item_id' => $orderItem->id,
            'status' => ReviewStatus::Pending,
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted and is awaiting approval.');
    }
}
