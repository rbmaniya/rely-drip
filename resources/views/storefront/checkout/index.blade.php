@extends('storefront.layouts.app')

@section('page-title', 'Checkout')

@section('content')
<div class="container py-4">
    <h1 class="sec-ttl">Checkout</h1>

    <form method="POST" action="{{ route('storefront.checkout.store') }}">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border mb-3">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Shipping Address</h2>

                        @if ($addresses->isNotEmpty())
                            <div class="mb-3">
                                @foreach ($addresses as $address)
                                    <div class="form-check border rounded p-3 mb-2">
                                        <input class="form-check-input" type="radio" name="saved_address_id"
                                               id="address-{{ $address->id }}" value="{{ $address->id }}"
                                               {{ $loop->first ? 'checked' : '' }}
                                               onchange="document.getElementById('manual-address-fields').classList.add('d-none')">
                                        <label class="form-check-label" for="address-{{ $address->id }}">
                                            <strong>{{ $address->full_name }}</strong> ({{ $address->label }})<br>
                                            {{ $address->address_line }}, {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                            {{ $address->mobile }}
                                        </label>
                                    </div>
                                @endforeach

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="saved_address_id" value=""
                                           id="address-new" onchange="document.getElementById('manual-address-fields').classList.remove('d-none')">
                                    <label class="form-check-label" for="address-new">Use a different address</label>
                                </div>
                            </div>
                        @endif

                        <div id="manual-address-fields" class="row g-3 {{ $addresses->isNotEmpty() ? 'd-none' : '' }}">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="shipping_full_name" value="{{ old('shipping_full_name', $customer?->name) }}" class="form-control @error('shipping_full_name') is-invalid @enderror">
                                @error('shipping_full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="shipping_mobile" value="{{ old('shipping_mobile', $customer?->mobile) }}" class="form-control @error('shipping_mobile') is-invalid @enderror">
                                @error('shipping_mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="shipping_email" value="{{ old('shipping_email', $customer?->email) }}" class="form-control @error('shipping_email') is-invalid @enderror">
                                @error('shipping_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Landmark <span class="text-muted small">(optional)</span></label>
                                <input type="text" name="shipping_landmark" value="{{ old('shipping_landmark') }}" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address Line</label>
                                <input type="text" name="shipping_address_line" value="{{ old('shipping_address_line') }}" class="form-control @error('shipping_address_line') is-invalid @enderror">
                                @error('shipping_address_line')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">City</label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" class="form-control @error('shipping_city') is-invalid @enderror">
                                @error('shipping_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">State</label>
                                <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" class="form-control @error('shipping_state') is-invalid @enderror">
                                @error('shipping_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="shipping_country" value="{{ old('shipping_country', 'India') }}" class="form-control @error('shipping_country') is-invalid @enderror">
                                @error('shipping_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" class="form-control @error('shipping_postal_code') is-invalid @enderror">
                                @error('shipping_postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border mb-3">
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="billing_same_as_shipping" value="1" id="billing-same" checked
                                   onchange="document.getElementById('billing-fields').classList.toggle('d-none', this.checked)">
                            <label class="form-check-label fw-semibold" for="billing-same">Billing address same as shipping</label>
                        </div>

                        <div id="billing-fields" class="row g-3 d-none">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="billing_full_name" value="{{ old('billing_full_name') }}" class="form-control @error('billing_full_name') is-invalid @enderror">
                                @error('billing_full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="billing_mobile" value="{{ old('billing_mobile') }}" class="form-control @error('billing_mobile') is-invalid @enderror">
                                @error('billing_mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address Line</label>
                                <input type="text" name="billing_address_line" value="{{ old('billing_address_line') }}" class="form-control @error('billing_address_line') is-invalid @enderror">
                                @error('billing_address_line')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="billing_city" value="{{ old('billing_city') }}" class="form-control @error('billing_city') is-invalid @enderror">
                                @error('billing_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <input type="text" name="billing_state" value="{{ old('billing_state') }}" class="form-control @error('billing_state') is-invalid @enderror">
                                @error('billing_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="billing_postal_code" value="{{ old('billing_postal_code') }}" class="form-control @error('billing_postal_code') is-invalid @enderror">
                                @error('billing_postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" name="billing_country" value="{{ old('billing_country', 'India') }}" class="form-control @error('billing_country') is-invalid @enderror">
                                @error('billing_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Payment Method</h2>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" checked disabled>
                            <label class="form-check-label">Online Payment</label>
                        </div>
                        <p class="small text-muted mb-0 mt-2">Your payment is confirmed immediately once you place the order.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Order Summary</h2>

                        @foreach ($items as $item)
                            <div class="d-flex justify-content-between small mb-2">
                                <span>{{ $item->variation->product->title }} ({{ $item->variation->label }}) &times; {{ $item->quantity }}</span>
                                <span>${{ number_format($item->line_total, 2) }}</span>
                            </div>
                        @endforeach

                        <hr>
                        <div class="d-flex justify-content-between mb-1"><span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span></div>
                        <div class="d-flex justify-content-between mb-1"><span>Shipping</span><span>${{ number_format($shippingCharge, 2) }}</span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Tax</span><span>${{ number_format($taxAmount, 2) }}</span></div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-3"><span>Total</span><span>${{ number_format($grandTotal, 2) }}</span></div>

                        <button type="submit" class="btn btn-primary w-100">Pay &amp; Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
