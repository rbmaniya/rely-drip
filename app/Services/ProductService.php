<?php

namespace App\Services;

use App\Models\Product;
use App\Support\Slug;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, UploadedFile>  $images
     * @param  array<int, array<string, mixed>>  $specifications
     * @param  array<int, array<string, mixed>>  $variations
     */
    public function create(array $data, UploadedFile $thumbnail, array $images, array $specifications, array $variations): Product
    {
        return DB::transaction(function () use ($data, $thumbnail, $images, $specifications, $variations) {
            $data['slug'] = Slug::unique(Product::class, $data['title']);
            $data['thumbnail'] = $thumbnail->store('products/thumbnails', 'public');

            $product = Product::create($data);

            $this->syncImages($product, $images);
            $this->syncSpecifications($product, $specifications);
            $this->syncVariations($product, $variations);

            return $product->fresh(['images', 'specifications', 'variations']);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, UploadedFile>  $images
     * @param  array<int, string>  $removeImageIds
     * @param  array<int, array<string, mixed>>  $specifications
     * @param  array<int, array<string, mixed>>  $variations
     */
    public function update(
        Product $product,
        array $data,
        ?UploadedFile $thumbnail,
        array $images,
        array $removeImageIds,
        array $specifications,
        array $variations
    ): Product {
        return DB::transaction(function () use ($product, $data, $thumbnail, $images, $removeImageIds, $specifications, $variations) {
            if ($data['title'] !== $product->title) {
                $data['slug'] = Slug::unique(Product::class, $data['title'], ignoreId: $product->id);
            }

            if ($thumbnail) {
                if ($product->thumbnail) {
                    Storage::disk('public')->delete($product->thumbnail);
                }
                $data['thumbnail'] = $thumbnail->store('products/thumbnails', 'public');
            }

            $product->update($data);

            $this->removeImages($product, $removeImageIds);
            $this->syncImages($product, $images);
            $this->syncSpecifications($product, $specifications);
            $this->syncVariations($product, $variations);

            return $product->fresh(['images', 'specifications', 'variations']);
        });
    }

    private function syncImages(Product $product, array $images): void
    {
        $nextOrder = $product->images()->max('sort_order') + 1;

        foreach ($images as $image) {
            $product->images()->create([
                'path' => $image->store('products/gallery', 'public'),
                'sort_order' => $nextOrder++,
            ]);
        }
    }

    private function removeImages(Product $product, array $imageIds): void
    {
        if (empty($imageIds)) {
            return;
        }

        $product->images()->whereIn('id', $imageIds)->get()->each(function ($image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        });
    }

    private function syncSpecifications(Product $product, array $specifications): void
    {
        $product->specifications()->delete();

        $order = 0;
        foreach ($specifications as $spec) {
            if (empty($spec['title']) || empty($spec['value'])) {
                continue;
            }

            $product->specifications()->create([
                'title' => $spec['title'],
                'value' => $spec['value'],
                'status' => $spec['status'] ?? 'active',
                'sort_order' => $order++,
            ]);
        }
    }

    private function syncVariations(Product $product, array $variations): void
    {
        $submittedIds = collect($variations)->pluck('id')->filter()->all();

        $product->variations()->whereNotIn('id', $submittedIds)->get()->each->delete();

        foreach ($variations as $variation) {
            $payload = [
                'metal' => $variation['metal'],
                'color' => $variation['color'],
                'gold_purity' => $variation['metal'] === 'gold' ? ($variation['gold_purity'] ?? null) : null,
                'sku' => $variation['sku'],
                'price' => $variation['price'],
                'stock' => $variation['stock'],
                'min_stock_alert' => $variation['min_stock_alert'] ?? 5,
                'status' => $variation['status'] ?? 'active',
            ];

            if (! empty($variation['id'])) {
                $product->variations()->whereKey($variation['id'])->update($payload);
            } else {
                $product->variations()->create($payload);
            }
        }
    }
}
