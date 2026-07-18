@csrf
@isset($product)
    @method('PUT')
@endisset

<div class="row g-3">
    <div class="col-lg-8">

        {{-- Basic information --}}
        <div class="stat-card mb-3">
            <h2 class="h6 mb-3">Basic Information</h2>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Product Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $product->title ?? '') }}"
                           class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') === $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="short_description" class="form-label">Short Description</label>
                    <textarea name="short_description" id="short_description" rows="2"
                              class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                    @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" rows="5"
                              class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Media --}}
        <div class="stat-card mb-3">
            <h2 class="h6 mb-3">Media</h2>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Thumbnail Image {{ isset($product) ? '' : '*' }}</label>
                    <img id="thumbnail-preview"
                         src="{{ isset($product) && $product->thumbnail ? asset('storage/'.$product->thumbnail) : '' }}"
                         class="img-fluid rounded mb-2 {{ isset($product) && $product->thumbnail ? '' : 'd-none' }}" alt="Thumbnail">
                    <input type="file" name="thumbnail" accept="image/*" data-image-preview-input="thumbnail-preview"
                           class="form-control form-control-sm @error('thumbnail') is-invalid @enderror" {{ isset($product) ? '' : 'required' }}>
                    @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label">Video URL (optional)</label>
                    <input type="url" name="video_url" value="{{ old('video_url', $product->video_url ?? '') }}"
                           class="form-control @error('video_url') is-invalid @enderror" placeholder="https://youtube.com/...">
                    @error('video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            @isset($product)
                @if ($product->images->isNotEmpty())
                    <label class="form-label">Existing Gallery Images</label>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach ($product->images as $image)
                            <div class="position-relative" data-existing-image style="width:90px">
                                <img src="{{ asset('storage/'.$image->path) }}" class="thumb-lg" alt="Gallery image">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 p-0 px-1"
                                        data-remove-existing-image="remove_images" data-image-id="{{ $image->id }}" title="Remove">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endisset

            <label class="form-label">Add Gallery Images</label>
            <input type="file" name="images[]" multiple accept="image/*" class="form-control @error('images') is-invalid @enderror">
            @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">You can select multiple images. JPG, PNG or WEBP. Max 4MB each.</div>
        </div>

        @php
            // When redisplaying after a validation error, rebuild the row count from the
            // submitted (old) input so dynamically-added rows aren't lost on re-render.
            $specModels = isset($product) ? $product->specifications->values() : collect();
            $specCount = old('specifications') ? count(old('specifications')) : max($specModels->count(), 1);

            $variationModels = isset($product) ? $product->variations->values() : collect();
            $variationCount = old('variations') ? count(old('variations')) : max($variationModels->count(), 1);
        @endphp

        {{-- Specifications --}}
        <div class="stat-card mb-3" data-repeater data-repeater-start-index="{{ $specCount }}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 mb-0">Additional Details</h2>
                <button type="button" class="btn btn-sm btn-outline-primary" data-repeater-add><i class="bi bi-plus-lg"></i> Add More</button>
            </div>

            <div class="row g-2 text-muted small mb-1">
                <div class="col-4">Title</div>
                <div class="col-4">Value</div>
                <div class="col-3">Status</div>
            </div>

            <div data-repeater-body>
                @for ($i = 0; $i < $specCount; $i++)
                    @include('admin.products.partials.specification-row', ['index' => $i, 'specification' => $specModels->get($i)])
                @endfor
            </div>

            <template>
                @include('admin.products.partials.specification-row', ['index' => '__INDEX__', 'specification' => null])
            </template>
        </div>

        {{-- Variations --}}
        <div class="stat-card mb-3" data-repeater data-repeater-start-index="{{ $variationCount }}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 mb-0">Product Variations <span class="text-danger">*</span></h2>
                <button type="button" class="btn btn-sm btn-outline-primary" data-repeater-add><i class="bi bi-plus-lg"></i> Add Variation</button>
            </div>
            @error('variations')<div class="alert alert-danger py-2">{{ $message }}</div>@enderror

            <div data-repeater-body>
                @for ($i = 0; $i < $variationCount; $i++)
                    @include('admin.products.partials.variation-row', ['index' => $i, 'variation' => $variationModels->get($i)])
                @endfor
            </div>

            <template>
                @include('admin.products.partials.variation-row', ['index' => '__INDEX__', 'variation' => null])
            </template>
        </div>

        {{-- SEO --}}
        <div class="stat-card">
            <h2 class="h6 mb-3">SEO Information</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">SEO Title</label>
                    <input type="text" name="seo_title" value="{{ old('seo_title', $product->seo_title ?? '') }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords ?? '') }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Meta Description</label>
                    <textarea name="seo_description" rows="2" class="form-control">{{ old('seo_description', $product->seo_description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="stat-card mb-3">
            <h2 class="h6 mb-3">Publishing</h2>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    @foreach (\App\Enums\ProductStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $product->status->value ?? 'draft') === $status->value)>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-check mb-2">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" class="form-check-input"
                       @checked(old('is_featured', $product->is_featured ?? false))>
                <label for="is_featured" class="form-check-label">Featured Product</label>
            </div>
            <div class="form-check mb-2">
                <input type="checkbox" name="is_best_seller" id="is_best_seller" value="1" class="form-check-input"
                       @checked(old('is_best_seller', $product->is_best_seller ?? false))>
                <label for="is_best_seller" class="form-check-label">Best Seller</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="is_new_arrival" id="is_new_arrival" value="1" class="form-check-input"
                       @checked(old('is_new_arrival', $product->is_new_arrival ?? false))>
                <label for="is_new_arrival" class="form-check-label">New Arrival</label>
            </div>
        </div>

        <div class="stat-card mb-3">
            <h2 class="h6 mb-3">Weight</h2>
            <div class="row g-2">
                <div class="col-7">
                    <label class="form-label small">Weight</label>
                    <input type="number" step="0.001" min="0" name="weight" value="{{ old('weight', $product->weight ?? '') }}" class="form-control">
                </div>
                <div class="col-5">
                    <label class="form-label small">Unit</label>
                    <select name="weight_unit" class="form-select">
                        <option value="gram" @selected(old('weight_unit', $product->weight_unit ?? 'gram') === 'gram')>Gram</option>
                        <option value="kg" @selected(old('weight_unit', $product->weight_unit ?? '') === 'kg')>Kg</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</div>
