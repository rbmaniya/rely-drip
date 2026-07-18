<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_categories' => Category::count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'out_of_stock_products' => Product::whereHas('variations')
                ->whereDoesntHave('variations', fn ($q) => $q->where('stock', '>', 0))
                ->count(),
            'total_customers' => Customer::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', OrderStatus::Pending)->count(),
            'processing_orders' => Order::where('status', OrderStatus::Processing)->count(),
            'delivered_orders' => Order::where('status', OrderStatus::Delivered)->count(),
            'cancelled_orders' => Order::where('status', OrderStatus::Cancelled)->count(),
            'todays_orders' => Order::whereDate('created_at', today())->count(),
            'todays_revenue' => Order::whereDate('created_at', today())->where('status', '!=', OrderStatus::Cancelled)->sum('grand_total'),
            'monthly_revenue' => Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('status', '!=', OrderStatus::Cancelled)->sum('grand_total'),
            'total_revenue' => Order::where('status', '!=', OrderStatus::Cancelled)->sum('grand_total'),
        ];

        $salesByDay = Order::query()
            ->where('status', '!=', OrderStatus::Cancelled)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy(fn ($row) => $row->date);

        $salesChart = collect(range(6, 0))->map(function (int $daysAgo) use ($salesByDay) {
            $date = now()->subDays($daysAgo)->format('Y-m-d');

            return [
                'label' => now()->subDays($daysAgo)->format('D'),
                'total' => (float) ($salesByDay[$date]->total ?? 0),
            ];
        });

        $orderStatusChart = collect(OrderStatus::cases())->map(fn (OrderStatus $status) => [
            'label' => $status->label(),
            'count' => Order::where('status', $status)->count(),
        ]);

        $recentOrders = Order::with('customer')->latest()->take(8)->get();
        $latestCustomers = Customer::latest()->take(5)->get();

        $bestSellingProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', '!=', OrderStatus::Cancelled->value)
            ->select('order_items.product_name')
            ->selectRaw('SUM(order_items.quantity) as units_sold')
            ->selectRaw('SUM(order_items.total_price) as revenue')
            ->groupBy('order_items.product_name')
            ->orderByDesc('units_sold')
            ->take(5)
            ->get();

        $lowStockVariations = ProductVariation::with('product')
            ->whereColumn('stock', '<=', 'min_stock_alert')
            ->orderBy('stock')
            ->take(8)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'salesChart',
            'orderStatusChart',
            'recentOrders',
            'latestCustomers',
            'bestSellingProducts',
            'lowStockVariations',
        ));
    }
}
