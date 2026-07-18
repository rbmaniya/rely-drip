@php $address = $address ?? null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Label</label>
        <select name="label" class="form-select">
            @foreach (['home' => 'Home', 'work' => 'Work', 'shipping' => 'Other'] as $value => $text)
                <option value="{{ $value }}" {{ optional($address)->label === $value ? 'selected' : '' }}>{{ $text }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Full Name</label>
        <input type="text" name="full_name" value="{{ optional($address)->full_name }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Mobile Number</label>
        <input type="text" name="mobile" value="{{ optional($address)->mobile }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email <span class="text-muted small">(optional)</span></label>
        <input type="email" name="email" value="{{ optional($address)->email }}" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label">Address Line</label>
        <input type="text" name="address_line" value="{{ optional($address)->address_line }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Landmark <span class="text-muted small">(optional)</span></label>
        <input type="text" name="landmark" value="{{ optional($address)->landmark }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">City</label>
        <input type="text" name="city" value="{{ optional($address)->city }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">State</label>
        <input type="text" name="state" value="{{ optional($address)->state }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Country</label>
        <input type="text" name="country" value="{{ optional($address)->country ?? 'India' }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Postal Code</label>
        <input type="text" name="postal_code" value="{{ optional($address)->postal_code }}" class="form-control" required>
    </div>
    <div class="col-12">
        <div class="form-check">
            <input type="checkbox" name="is_default" value="1" id="is_default_{{ optional($address)->id ?? 'new' }}" class="form-check-input" {{ optional($address)->is_default ? 'checked' : '' }}>
            <label class="form-check-label" for="is_default_{{ optional($address)->id ?? 'new' }}">Set as default address</label>
        </div>
    </div>
</div>
