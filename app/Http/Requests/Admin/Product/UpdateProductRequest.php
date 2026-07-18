<?php

namespace App\Http\Requests\Admin\Product;

use App\Enums\GoldPurity;
use App\Enums\Metal;
use App\Enums\MetalColor;
use App\Enums\ProductStatus;
use App\Models\ProductVariation;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255', Rule::unique('products', 'title')->ignore($this->route('product'))],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['uuid'],
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
            'variations.*.id' => ['nullable', 'uuid'],
            'variations.*.metal' => ['required', Rule::enum(Metal::class)],
            'variations.*.color' => ['required', Rule::enum(MetalColor::class)],
            'variations.*.gold_purity' => ['nullable', 'required_if:variations.*.metal,gold', Rule::enum(GoldPurity::class)],
            'variations.*.sku' => ['required', 'string', 'max:100'],
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
        ];
    }

    public function withValidator(ValidatorContract $validator): void
    {
        $validator->after(function (ValidatorContract $validator): void {
            $skus = collect($this->input('variations', []));

            $duplicates = $skus->pluck('sku')->filter()->duplicates();
            foreach ($duplicates as $index => $sku) {
                $validator->errors()->add('variations', "SKU \"{$sku}\" is used more than once in this product.");
            }

            $skus->each(function (array $row, int $index) use ($validator): void {
                if (empty($row['sku'])) {
                    return;
                }

                $exists = ProductVariation::query()
                    ->where('sku', $row['sku'])
                    ->when(! empty($row['id']), fn ($query) => $query->whereKeyNot($row['id']))
                    ->exists();

                if ($exists) {
                    $validator->errors()->add("variations.{$index}.sku", 'This SKU is already used by another variation.');
                }
            });
        });
    }
}
