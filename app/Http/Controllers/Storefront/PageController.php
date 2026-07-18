<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('storefront.pages.about');
    }

    public function culture(): View
    {
        return view('storefront.pages.culture');
    }

    public function lookbook(): View
    {
        return view('storefront.pages.lookbook');
    }
}
