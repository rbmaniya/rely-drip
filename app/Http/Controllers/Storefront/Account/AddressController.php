<?php

namespace App\Http\Controllers\Storefront\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\Account\AddressRequest;
use App\Models\CustomerAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(Request $request): View
    {
        return view('storefront.account.addresses.index', [
            'addresses' => $request->user('customer')->addresses()->orderByDesc('is_default')->get(),
        ]);
    }

    public function store(AddressRequest $request): RedirectResponse
    {
        $customer = $request->user('customer');
        $data = $request->validated();

        if (! empty($data['is_default'])) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $customer->addresses()->create($data);

        return back()->with('success', 'Address added successfully.');
    }

    public function update(AddressRequest $request, CustomerAddress $address): RedirectResponse
    {
        $this->authorizeAddress($request, $address);

        $data = $request->validated();

        if (! empty($data['is_default'])) {
            $request->user('customer')->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($data);

        return back()->with('success', 'Address updated successfully.');
    }

    public function destroy(Request $request, CustomerAddress $address): RedirectResponse
    {
        $this->authorizeAddress($request, $address);

        $address->delete();

        return back()->with('success', 'Address removed.');
    }

    public function setDefault(Request $request, CustomerAddress $address): RedirectResponse
    {
        $this->authorizeAddress($request, $address);

        $request->user('customer')->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Default address updated.');
    }

    private function authorizeAddress(Request $request, CustomerAddress $address): void
    {
        abort_unless($address->customer_id === $request->user('customer')->id, 403);
    }
}
