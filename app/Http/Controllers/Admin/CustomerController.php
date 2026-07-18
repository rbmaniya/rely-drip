<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::query()
            ->withCount('orders')
            ->withSum('orders as total_purchase', 'grand_total')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('mobile', 'like', "%{$term}%"));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer): View
    {
        $customer->load(['addresses', 'orders' => fn ($query) => $query->latest()]);

        return view('admin.customers.show', compact('customer'));
    }

    public function block(Customer $customer): RedirectResponse
    {
        $customer->update(['status' => 'blocked']);

        ActivityLog::record('customer.blocked', "Customer \"{$customer->name}\" blocked.");

        return back()->with('success', 'Customer blocked.');
    }

    public function unblock(Customer $customer): RedirectResponse
    {
        $customer->update(['status' => 'active']);

        ActivityLog::record('customer.unblocked', "Customer \"{$customer->name}\" unblocked.");

        return back()->with('success', 'Customer activated.');
    }
}
