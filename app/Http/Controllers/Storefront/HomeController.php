<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $customer = Auth::guard('customer')->user();
        $wishlistedIds = $customer ? $customer->wishlists()->pluck('product_id') : collect();

        // return view('storefront.home.index', [
        //     'categories' => Category::active()->select('name','image','slug')->withCount('products')->orderBy('name')->take(8)->get(),
        //     'featuredProducts' => Product::active()->with(['images', 'variations'])->where('is_featured', true)->latest()->take(8)->get(),
        //     'newArrivals' => Product::active()->with(['images', 'variations'])->where('is_new_arrival', true)->latest()->take(8)->get(),
        //     'bestSellers' => Product::active()->with(['images', 'variations'])->where('is_best_seller', true)->latest()->take(8)->get(),
        //     'wishlistedIds' => $wishlistedIds,
        // ]);

        $productSelects = [
            'id',
            'title',
            'slug',
            'thumbnail',
            'category_id',
            'is_featured',
            'is_new_arrival',
            'is_best_seller'
        ];

        $productBaseQuery = Product::active()
            ->select($productSelects)
            ->with(['category:id,name'])
            ->withMin('variations as min_price', 'price')
            ->latest()
            ->take(10);

        return view('storefront.home.index', [
            // 'categories' => Category::active()->select('id', 'name', 'image', 'slug')->withCount('products')->orderBy('name')->take(10)->get(),
            'categories' => Category::active()
            ->select('id', 'name', 'image', 'slug')
            ->withCount('products')
            ->inRandomOrder()
            ->take(10)
            ->get()
            ->sortBy('name')
            ->values(),
            'featuredProducts' => (clone $productBaseQuery)->where('is_featured', true)->get(),
            'newArrivals'      => (clone $productBaseQuery)->where('is_new_arrival', true)->get(),
            'bestSellers'      => (clone $productBaseQuery)->where('is_best_seller', true)->get(),
            'wishlistedIds'    => $wishlistedIds,
        ]);
    }
}
