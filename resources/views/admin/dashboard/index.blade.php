@extends('admin.layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview of your store performance')

@section('content')

    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['label' => 'Total Categories', 'value' => $stats['total_categories'], 'icon' => 'bi-tags', 'color' => 'primary'],
                ['label' => 'Total Products', 'value' => $stats['total_products'], 'icon' => 'bi-box-seam', 'color' => 'info'],
                ['label' => 'Active Products', 'value' => $stats['active_products'], 'icon' => 'bi-check-circle', 'color' => 'success'],
                ['label' => 'Out of Stock', 'value' => $stats['out_of_stock_products'], 'icon' => 'bi-exclamation-triangle', 'color' => 'danger'],
                ['label' => 'Total Customers', 'value' => $stats['total_customers'], 'icon' => 'bi-people', 'color' => 'secondary'],
                ['label' => 'Total Orders', 'value' => $stats['total_orders'], 'icon' => 'bi-receipt', 'color' => 'primary'],
                ['label' => "Today's Orders", 'value' => $stats['todays_orders'], 'icon' => 'bi-calendar-day', 'color' => 'info'],
                ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'icon' => 'bi-hourglass-split', 'color' => 'warning'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card h-100 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-{{ $card['color'] }}-subtle text-{{ $card['color'] }}">
                        <i class="bi {{ $card['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ number_format($card['value']) }}</div>
                        <div class="text-muted small">{{ $card['label'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card h-100">
                <div class="text-muted small">Today's Revenue</div>
                <div class="fs-4 fw-bold">${{ number_format($stats['todays_revenue'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card h-100">
                <div class="text-muted small">Monthly Revenue</div>
                <div class="fs-4 fw-bold">${{ number_format($stats['monthly_revenue'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card h-100">
                <div class="text-muted small">Total Revenue</div>
                <div class="fs-4 fw-bold">${{ number_format($stats['total_revenue'], 2) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="stat-card h-100">
                <h2 class="h6 mb-3">Sales — Last 7 Days</h2>
                <canvas id="salesChart" height="140"></canvas>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="stat-card h-100">
                <h2 class="h6 mb-3">Order Status Breakdown</h2>
                <canvas id="orderStatusChart" height="140"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="stat-card p-0">
                <div class="p-3 pb-0 d-flex justify-content-between align-items-center">
                    <h2 class="h6 mb-0">Recent Orders</h2>
                    <a href="{{ route('admin.orders.index') }}" class="small">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                                    <td>{{ $order->customer?->name ?? '—' }}</td>
                                    <td>${{ number_format($order->grand_total, 2) }}</td>
                                    <td><span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="stat-card mb-3">
                <h2 class="h6 mb-3">Low Stock Alert</h2>
                @forelse ($lowStockVariations as $variation)
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <div>
                            <div class="small fw-semibold">{{ $variation->product?->title }}</div>
                            <div class="text-muted small">{{ $variation->label }} · SKU {{ $variation->sku }}</div>
                        </div>
                        <span class="badge text-bg-danger">{{ $variation->stock }} left</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No low stock variations.</p>
                @endforelse
            </div>

            <div class="stat-card">
                <h2 class="h6 mb-3">Latest Customers</h2>
                @forelse ($latestCustomers as $customer)
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <div class="small">{{ $customer->name }}</div>
                        <div class="text-muted small">{{ $customer->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No customers yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="stat-card p-0">
                <div class="p-3 pb-0"><h2 class="h6 mb-0">Best Selling Products</h2></div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Units Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bestSellingProducts as $row)
                                <tr>
                                    <td>{{ $row->product_name }}</td>
                                    <td>{{ $row->units_sold }}</td>
                                    <td>${{ number_format($row->revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">No sales data yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const salesLabels = @json($salesChart->pluck('label'));
        const salesData = @json($salesChart->pluck('total'));

        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Sales ($)',
                    data: salesData,
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124, 58, 237, 0.15)',
                    tension: 0.35,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } },
            },
        });

        const statusLabels = @json($orderStatusChart->pluck('label'));
        const statusCounts = @json($orderStatusChart->pluck('count'));

        new Chart(document.getElementById('orderStatusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusCounts,
                    backgroundColor: ['#64748b', '#0891b2', '#7c3aed', '#2563eb', '#d97706', '#16a34a', '#dc2626'],
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } },
            },
        });
    });
</script>
@endpush
