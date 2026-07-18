@extends('admin.layouts.app')

@section('page-title', 'Website Settings')
@section('page-subtitle', 'General configuration used across the storefront')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="stat-card mb-3">
                    <h2 class="h6 mb-3">General Settings</h2>
                    <div class="row g-3">
                        {{-- <div class="col-md-6">
                            <label class="form-label">Website Name <span class="text-danger">*</span></label>
                            <input type="text" name="site_name" value="{{ old('site_name', $settings->get('site_name')) }}"
                                   class="form-control @error('site_name') is-invalid @enderror" required>
                            @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $settings->get('company_name')) }}" class="form-control">
                        </div> --}}
                        <div class="col-md-6">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings->get('contact_email')) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings->get('contact_phone')) }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Office Address</label>
                            <textarea name="office_address" rows="2" class="form-control">{{ old('office_address', $settings->get('office_address')) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="stat-card mb-3">
                    <h2 class="h6 mb-3">Social Media</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Facebook URL</label>
                            <input type="url" name="social_facebook" value="{{ old('social_facebook', $settings->get('social_facebook')) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Instagram URL</label>
                            <input type="url" name="social_instagram" value="{{ old('social_instagram', $settings->get('social_instagram')) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">YouTube URL</label>
                            <input type="url" name="social_youtube" value="{{ old('social_youtube', $settings->get('social_youtube')) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pinterest URL</label>
                            <input type="url" name="social_pinterest" value="{{ old('social_pinterest', $settings->get('social_pinterest')) }}" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <h2 class="h6 mb-3">SEO & Footer</h2>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Homepage SEO Title</label>
                            <input type="text" name="seo_homepage_title" value="{{ old('seo_homepage_title', $settings->get('seo_homepage_title')) }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Meta Description</label>
                            <textarea name="seo_meta_description" rows="2" class="form-control">{{ old('seo_meta_description', $settings->get('seo_meta_description')) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Footer Copyright Text</label>
                            <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings->get('footer_copyright')) }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="stat-card mb-3">
                    <h2 class="h6 mb-3">Branding</h2>
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        @if ($settings->get('logo'))
                            <img src="{{ asset('storage/'.$settings->get('logo')) }}" class="d-block mb-2" style="max-height:60px">
                        @endif
                        <input type="file" name="logo" accept="image/*" class="form-control form-control-sm">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Favicon</label>
                        @if ($settings->get('favicon'))
                            <img src="{{ asset('storage/'.$settings->get('favicon')) }}" class="d-block mb-2" style="max-height:32px">
                        @endif
                        <input type="file" name="favicon" accept="image/*" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="stat-card mb-3">
                    <h2 class="h6 mb-3">Tax & Shipping</h2>
                    <div class="mb-3">
                        <label class="form-label small">Tax Percentage (%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="tax_percentage"
                               value="{{ old('tax_percentage', $settings->get('tax_percentage')) }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Flat Shipping Rate ($)</label>
                        <input type="number" step="0.01" min="0" name="shipping_flat_rate"
                               value="{{ old('shipping_flat_rate', $settings->get('shipping_flat_rate')) }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Free Shipping Above ($)</label>
                        <input type="number" step="0.01" min="0" name="free_shipping_min_order"
                               value="{{ old('free_shipping_min_order', $settings->get('free_shipping_min_order')) }}" class="form-control">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small">Default Low Stock Threshold</label>
                        <input type="number" min="0" name="low_stock_threshold"
                               value="{{ old('low_stock_threshold', $settings->get('low_stock_threshold')) }}" class="form-control">
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Settings</button>
                </div>
            </div>
        </div>
    </form>
@endsection
