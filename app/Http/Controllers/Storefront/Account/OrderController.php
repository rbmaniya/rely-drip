<?php

namespace App\Http\Controllers\Storefront\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user('customer')->orders()->latest()->paginate(10);

        return view('storefront.account.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorizeOrder($request, $order);

        $order->load(['items', 'statusHistories']);

        return view('storefront.account.orders.show', compact('order'));
    }

    public function invoice(Request $request, Order $order): View
    {
        $this->authorizeOrder($request, $order);

        $order->load(['items', 'invoice']);

        return view('storefront.account.orders.invoice', compact('order'));
    }

    private function authorizeOrder(Request $request, Order $order): void
    {
        abort_unless($order->customer_id === $request->user('customer')->id, 403);
    }
}
