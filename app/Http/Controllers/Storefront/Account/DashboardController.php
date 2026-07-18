<?php

namespace App\Http\Controllers\Storefront\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $customer = $request->user('customer');

        return view('storefront.account.dashboard', [
            'customer' => $customer,
            'recentOrders' => $customer->orders()->latest()->take(5)->get(),
            'addressCount' => $customer->addresses()->count(),
            'wishlistCount' => $customer->wishlists()->count(),
        ]);
    }
}
