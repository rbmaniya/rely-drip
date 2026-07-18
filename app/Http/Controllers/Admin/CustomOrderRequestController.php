<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CustomOrderRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomOrderRequestController extends Controller
{
    public function index(Request $request): View
    {
        $requests = CustomOrderRequest::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.custom-order-requests.index', compact('requests'));
    }

    public function destroy(CustomOrderRequest $customOrderRequest): RedirectResponse
    {
        ActivityLog::record('custom_order_request.deleted', "Custom order request from \"{$customOrderRequest->name}\" deleted.");

        $customOrderRequest->delete();

        return back()->with('success', 'Request deleted.');
    }
}
