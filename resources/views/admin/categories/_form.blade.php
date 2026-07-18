@csrf
@isset($category)
    @method('PUT')
@endisset

<div class="row g-3">
    <div class="col-lg-8">
        <div class="stat-card mb-3">
            <h2 class="h6 mb-3">Basic Information</h2>

            <div class="mb-3">
                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="short_description" class="form-label">Short Description</label>
                <textarea name="short_description" id="short_description" rows="3"
                          class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $category->short_description ?? '') }}</textarea>
                @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-0">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="active" @selected(old('status', $category->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $category->status ?? '') === 'inactive')>Inactive</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="stat-card">
            <h2 class="h6 mb-3">SEO</h2>
            <div class="mb-3">
                <label for="seo_title" class="form-label">SEO Title</label>
                <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $category->seo_title ?? '') }}" class="form-control">
            </div>
            <div class="mb-0">
                <label for="seo_description" class="form-label">SEO Description</label>
                <textarea name="seo_description" id="seo_description" rows="2" class="form-control">{{ old('seo_description', $category->seo_description ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="stat-card mb-3">
            <h2 class="h6 mb-3">Category Image {{ isset($category) ? '' : '*' }}</h2>

            <img id="image-preview"
                 src="{{ isset($category) && $category->image ? asset('storage/'.$category->image) : '' }}"
                 class="img-fluid rounded mb-3 {{ isset($category) && $category->image ? '' : 'd-none' }}" alt="Preview">

            <input type="file" name="image" accept="image/*" data-image-preview-input="image-preview"
                   class="form-control @error('image') is-invalid @enderror" {{ isset($category) ? '' : 'required' }}>
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">JPG, PNG or WEBP. Max 2MB.</div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</div>
