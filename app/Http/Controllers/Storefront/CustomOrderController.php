<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\CustomOrderStoreRequest;
use App\Models\CustomOrderRequest as CustomOrderRequestModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomOrderController extends Controller
{
    public function index(): View
    {
        return view('storefront.custom-order.index');
    }

    public function store(CustomOrderStoreRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('design_reference');

        if ($request->hasFile('design_reference')) {
            $data['design_reference'] = $request->file('design_reference')->store('custom-orders', 'public');
        }

        CustomOrderRequestModel::create($data);

        return back()->with('success', 'Custom order submitted! We will contact you within 24 hours.');
    }
}
