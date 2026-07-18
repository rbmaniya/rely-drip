<?php

namespace App\Http\Requests\Admin\Product;

use App\Enums\GoldPurity;
use App\Enums\Metal;
use App\Enums\MetalColor;
use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255', Rule::unique('products', 'title')],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['required', 'string'],
            'thumbnail' => ['required', 'image', 'max:4096'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'weight_unit' => ['nullable', 'string', Rule::in(['gram', 'kg'])],
            'status' => ['required', Rule::enum(ProductStatus::class)],
            'is_featured' => ['sometimes', 'boolean'],
            'is_best_seller' => ['sometimes', 'boolean'],
            'is_new_arrival' => ['sometimes', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],

            'specifications' => ['nullable', 'array'],
            'specifications.*.title' => ['required_with:specifications.*.value', 'nullable', 'string', 'max:150'],
            'specifications.*.value' => ['required_with:specifications.*.title', 'nullable', 'string', 'max:255'],
            'specifications.*.status' => ['nullable', Rule::in(['active', 'inactive'])],

            'variations' => ['required', 'array', 'min:1'],
            'variations.*.metal' => ['required', Rule::enum(Metal::class)],
            'variations.*.color' => ['required', Rule::enum(MetalColor::class)],
            'variations.*.gold_purity' => ['nullable', 'required_if:variations.*.metal,gold', Rule::enum(GoldPurity::class)],
            'variations.*.sku' => ['required', 'string', 'max:100', 'distinct', Rule::unique('product_variations', 'sku')],
            'variations.*.price' => ['required', 'numeric', 'min:0.01'],
            'variations.*.stock' => ['required', 'integer', 'min:0'],
            'variations.*.min_stock_alert' => ['nullable', 'integer', 'min:0'],
            'variations.*.status' => ['nullable', Rule::in(['active', 'inactive'])],
        ];
    }

    public function messages(): array
    {
        return [
            'variations.required' => 'Add at least one product variation (Metal / Color / Price / Stock).',
            'variations.*.gold_purity.required_if' => 'Gold Purity is required when Metal is Gold.',
            'variations.*.sku.distinct' => 'Variation SKUs must be unique within this product.',
        ];
    }
}
