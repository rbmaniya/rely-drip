@php($s = $specification ?? null)
<div class="row g-2 align-items-end mb-2" data-repeater-row>
    <input type="hidden" name="specifications[{{ $index }}][id]" value="{{ old("specifications.$index.id", $s->id ?? '') }}" data-repeater-id>
    <div class="col-4">
        <input type="text" name="specifications[{{ $index }}][title]" placeholder="e.g. Stone"
               value="{{ old("specifications.$index.title", $s->title ?? '') }}" class="form-control form-control-sm">
    </div>
    <div class="col-4">
        <input type="text" name="specifications[{{ $index }}][value]" placeholder="e.g. Diamond"
               value="{{ old("specifications.$index.value", $s->value ?? '') }}" class="form-control form-control-sm">
    </div>
    <div class="col-3">
        <select name="specifications[{{ $index }}][status]" class="form-select form-select-sm">
            <option value="active" @selected(old("specifications.$index.status", $s->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old("specifications.$index.status", $s->status ?? '') === 'inactive')>Inactive</option>
        </select>
    </div>
    <div class="col-1 d-grid">
        <button type="button" class="btn btn-sm btn-outline-danger" data-repeater-remove title="Remove"><i class="bi bi-trash"></i></button>
    </div>
</div>
