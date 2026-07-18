<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function confirmation(Request $request, Order $order): View
    {
        $customer = $request->user('customer');

        $isOwner = $customer && $order->customer_id === $customer->id;
        $isJustPlaced = $request->session()->get('last_order_id') === $order->id;

        abort_unless($isOwner || $isJustPlaced, 403);

        return view('storefront.orders.confirmation', [
            'order' => $order->load('items'),
        ]);
    }
}
