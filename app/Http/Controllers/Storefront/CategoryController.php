<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('storefront.categories.index', [
            'categories' => Category::active()->withCount('products')->orderBy('name')->get(),
        ]);
    }
}
