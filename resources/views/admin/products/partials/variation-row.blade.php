@php
    $v = $variation ?? null;
    $metalValue = old("variations.$index.metal", $v->metal->value ?? '');
@endphp
<div class="row g-2 align-items-end border rounded p-2 mb-2" data-repeater-row>
    <input type="hidden" name="variations[{{ $index }}][id]" value="{{ old("variations.$index.id", $v->id ?? '') }}" data-repeater-id>

    <div class="col-6 col-md-2">
        <label class="form-label small mb-1">Metal</label>
        <select name="variations[{{ $index }}][metal]" class="form-select form-select-sm" data-metal-select required>
            <option value="">Select</option>
            @foreach (\App\Enums\Metal::cases() as $metal)
                <option value="{{ $metal->value }}" @selected($metalValue === $metal->value)>{{ $metal->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-6 col-md-2">
        <label class="form-label small mb-1">Color</label>
        <select name="variations[{{ $index }}][color]" class="form-select form-select-sm" required>
            <option value="">Select</option>
            @foreach (\App\Enums\MetalColor::cases() as $color)
                <option value="{{ $color->value }}" @selected(old("variations.$index.color", $v->color->value ?? '') === $color->value)>{{ $color->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-6 col-md-2 {{ $metalValue === 'gold' ? '' : 'd-none' }}" data-purity-wrap>
        <label class="form-label small mb-1">Gold Purity</label>
        <select name="variations[{{ $index }}][gold_purity]" class="form-select form-select-sm">
            <option value="">—</option>
            @foreach (\App\Enums\GoldPurity::cases() as $purity)
                <option value="{{ $purity->value }}" @selected(old("variations.$index.gold_purity", $v->gold_purity->value ?? '') === $purity->value)>{{ $purity->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-6 col-md-2">
        <label class="form-label small mb-1">SKU</label>
        <input type="text" name="variations[{{ $index }}][sku]" value="{{ old("variations.$index.sku", $v->sku ?? '') }}"
               class="form-control form-control-sm" required>
    </div>

    <div class="col-6 col-md-1">
        <label class="form-label small mb-1">Price ($)</label>
        <input type="number" step="0.01" min="0" name="variations[{{ $index }}][price]"
               value="{{ old("variations.$index.price", $v->price ?? '') }}" class="form-control form-control-sm" required>
    </div>

    <div class="col-4 col-md-1">
        <label class="form-label small mb-1">Stock</label>
        <input type="number" min="0" name="variations[{{ $index }}][stock]"
               value="{{ old("variations.$index.stock", $v->stock ?? 0) }}" class="form-control form-control-sm" required>
    </div>

    <div class="col-4 col-md-1">
        <label class="form-label small mb-1">Min Alert</label>
        <input type="number" min="0" name="variations[{{ $index }}][min_stock_alert]"
               value="{{ old("variations.$index.min_stock_alert", $v->min_stock_alert ?? 5) }}" class="form-control form-control-sm">
    </div>

    <div class="col-3 col-md-1">
        <label class="form-label small mb-1">Status</label>
        <select name="variations[{{ $index }}][status]" class="form-select form-select-sm">
            <option value="active" @selected(old("variations.$index.status", $v->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old("variations.$index.status", $v->status ?? '') === 'inactive')>Inactive</option>
        </select>
    </div>

    <div class="col-1 d-grid">
        <button type="button" class="btn btn-sm btn-outline-danger" data-repeater-remove title="Remove variation">
            <i class="bi bi-trash"></i>
        </button>
    </div>
</div>
