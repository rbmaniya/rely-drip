<?php

namespace App\Http\Controllers\Storefront;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::active()->with(['category', 'images', 'variations']);

        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('variations', fn ($vq) => $vq->where('sku', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('metal') || $request->filled('color') || $request->filled('gold_purity')
            || $request->filled('min_price') || $request->filled('max_price') || $request->filled('availability')) {
            $query->whereHas('variations', function ($vq) use ($request) {
                if ($request->filled('metal')) {
                    $vq->where('metal', $request->input('metal'));
                }
                if ($request->filled('color')) {
                    $vq->where('color', $request->input('color'));
                }
                if ($request->filled('gold_purity')) {
                    $vq->where('gold_purity', $request->input('gold_purity'));
                }
                if ($request->filled('min_price')) {
                    $vq->where('price', '>=', (float) $request->input('min_price'));
                }
                if ($request->filled('max_price')) {
                    $vq->where('price', '<=', (float) $request->input('max_price'));
                }
                if ($request->input('availability') === 'in_stock') {
                    $vq->where('stock', '>', 0);
                } elseif ($request->input('availability') === 'out_of_stock') {
                    $vq->where('stock', '<=', 0);
                }
            });
        }

        if ($request->input('filter') === 'new_arrival') {
            $query->where('is_new_arrival', true);
        } elseif ($request->input('filter') === 'best_seller') {
            $query->where('is_best_seller', true);
        } elseif ($request->input('filter') === 'featured') {
            $query->where('is_featured', true);
        }

        $query->withSum('orderItems as sold_quantity', 'quantity');

        match ($request->input('sort')) {
            'price_asc' => $query->withMin('variations as min_variation_price', 'price')->orderBy('min_variation_price'),
            'price_desc' => $query->withMin('variations as min_variation_price', 'price')->orderByDesc('min_variation_price'),
            'best_selling', 'popular' => $query->orderByDesc('sold_quantity'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        $customer = Auth::guard('customer')->user();
        $wishlistedIds = $customer ? $customer->wishlists()->pluck('product_id') : collect();

        return view('storefront.products.index', [
            'products' => $products,
            'categories' => Category::active()->orderBy('name')->get(),
            'wishlistedIds' => $wishlistedIds,
        ]);
    }

    public function show(string $slug): View
    {
        $product = Product::active()
            ->with([
                'category',
                'images',
                'specifications' => fn ($q) => $q->where('status', 'active'),
                'variations' => fn ($q) => $q->where('status', 'active'),
                'reviews' => fn ($q) => $q->approved()->with('customer')->latest(),
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        $variationsPayload = $product->variations->map(fn ($variation) => [
            'id' => $variation->id,
            'metal' => $variation->metal->value,
            'color' => $variation->color->value,
            'gold_purity' => $variation->gold_purity?->value,
            'sku' => $variation->sku,
            'price' => (float) $variation->price,
            'price_formatted' => number_format((float) $variation->price, 2),
            'stock' => $variation->stock,
        ])->values();

        $relatedProducts = Product::active()
            ->with(['images', 'variations'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $customer = Auth::guard('customer')->user();
        $wishlistedIds = $customer ? $customer->wishlists()->pluck('product_id') : collect();

        $alreadyReviewed = false;
        $canReview = false;

        if ($customer) {
            $alreadyReviewed = Review::where('product_id', $product->id)->where('customer_id', $customer->id)->exists();

            $purchasedAndDelivered = OrderItem::where('product_id', $product->id)
                ->whereHas('order', fn ($q) => $q->where('customer_id', $customer->id)->where('status', OrderStatus::Delivered))
                ->exists();

            $canReview = $purchasedAndDelivered && ! $alreadyReviewed;
        }

        return view('storefront.products.show', [
            'product' => $product,
            'variationsPayload' => $variationsPayload,
            'relatedProducts' => $relatedProducts,
            'wishlistedIds' => $wishlistedIds,
            'canReview' => $canReview,
            'alreadyReviewed' => $alreadyReviewed,
        ]);
    }
}
