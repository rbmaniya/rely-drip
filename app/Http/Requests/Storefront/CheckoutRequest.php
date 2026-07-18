<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $usingSavedAddress = $this->filled('saved_address_id');

        return [
            'saved_address_id' => ['nullable', 'uuid', 'exists:customer_addresses,id'],

            'shipping_full_name' => [$usingSavedAddress ? 'nullable' : 'required', 'string', 'max:255'],
            'shipping_mobile' => [$usingSavedAddress ? 'nullable' : 'required', 'string', 'max:20'],
            'shipping_email' => [$usingSavedAddress ? 'nullable' : 'required', 'email', 'max:255'],
            'shipping_address_line' => [$usingSavedAddress ? 'nullable' : 'required', 'string', 'max:255'],
            'shipping_landmark' => ['nullable', 'string', 'max:255'],
            'shipping_city' => [$usingSavedAddress ? 'nullable' : 'required', 'string', 'max:255'],
            'shipping_state' => [$usingSavedAddress ? 'nullable' : 'required', 'string', 'max:255'],
            'shipping_country' => [$usingSavedAddress ? 'nullable' : 'required', 'string', 'max:255'],
            'shipping_postal_code' => [$usingSavedAddress ? 'nullable' : 'required', 'string', 'max:20'],

            'billing_same_as_shipping' => ['nullable', 'boolean'],
            'billing_full_name' => ['required_unless:billing_same_as_shipping,1', 'nullable', 'string', 'max:255'],
            'billing_mobile' => ['required_unless:billing_same_as_shipping,1', 'nullable', 'string', 'max:20'],
            'billing_email' => ['nullable', 'email', 'max:255'],
            'billing_address_line' => ['required_unless:billing_same_as_shipping,1', 'nullable', 'string', 'max:255'],
            'billing_landmark' => ['nullable', 'string', 'max:255'],
            'billing_city' => ['required_unless:billing_same_as_shipping,1', 'nullable', 'string', 'max:255'],
            'billing_state' => ['required_unless:billing_same_as_shipping,1', 'nullable', 'string', 'max:255'],
            'billing_country' => ['required_unless:billing_same_as_shipping,1', 'nullable', 'string', 'max:255'],
            'billing_postal_code' => ['required_unless:billing_same_as_shipping,1', 'nullable', 'string', 'max:20'],
        ];
    }
}
