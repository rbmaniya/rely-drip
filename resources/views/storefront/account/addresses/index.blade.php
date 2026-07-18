@extends('storefront.layouts.app')

@section('page-title', 'My Addresses')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-3">
            @include('storefront.account.partials.nav')
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h4 mb-0">My Addresses</h1>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="bi bi-plus-lg"></i> Add Address
                </button>
            </div>

            <div class="row g-3">
                @forelse ($addresses as $address)
                    <div class="col-md-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="badge text-bg-secondary text-uppercase">{{ $address->label }}</span>
                                    @if ($address->is_default)
                                        <span class="badge text-bg-success">Default</span>
                                    @endif
                                </div>
                                <p class="fw-semibold mt-2 mb-1">{{ $address->full_name }}</p>
                                <p class="small mb-1">{{ $address->address_line }}@if($address->landmark), {{ $address->landmark }}@endif</p>
                                <p class="small mb-1">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                <p class="small mb-3">{{ $address->country }} &middot; {{ $address->mobile }}</p>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editAddressModal{{ $address->id }}">Edit</button>

                                    @unless ($address->is_default)
                                        <form method="POST" action="{{ route('storefront.account.addresses.set-default', $address) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Set Default</button>
                                        </form>
                                    @endunless

                                    <form method="POST" action="{{ route('storefront.account.addresses.destroy', $address) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @push('modals')
                        <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('storefront.account.addresses.update', $address) }}">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Address</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            @include('storefront.account.addresses.partials.fields', ['address' => $address])
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endpush
                @empty
                    <p class="text-muted">You haven't saved any addresses yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('modals')
    <div class="modal fade" id="addAddressModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('storefront.account.addresses.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('storefront.account.addresses.partials.fields', ['address' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
@endsection
