<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use App\Support\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'variations'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(fn ($q) => $q->where('title', 'like', "%{$term}%")
                    ->orWhereHas('variations', fn ($v) => $v->where('sku', 'like', "%{$term}%")));
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->string('category')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['thumbnail', 'images', 'specifications', 'variations']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_best_seller'] = $request->boolean('is_best_seller');
        $data['is_new_arrival'] = $request->boolean('is_new_arrival');

        $product = $this->productService->create(
            $data,
            $request->file('thumbnail'),
            $request->file('images', []),
            $request->input('specifications', []),
            $request->input('variations', []),
        );

        ActivityLog::record('product.created', "Product \"{$product->title}\" created.");

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $product->load(['images', 'specifications', 'variations']);
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->safe()->except(['thumbnail', 'images', 'remove_images', 'specifications', 'variations']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_best_seller'] = $request->boolean('is_best_seller');
        $data['is_new_arrival'] = $request->boolean('is_new_arrival');

        $this->productService->update(
            $product,
            $data,
            $request->file('thumbnail'),
            $request->file('images', []),
            $request->input('remove_images', []),
            $request->input('specifications', []),
            $request->input('variations', []),
        );

        ActivityLog::record('product.updated', "Product \"{$product->title}\" updated.");

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->orderItems()->exists()) {
            return back()->with('error', 'This product cannot be deleted because it has existing orders.');
        }

        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        $product->images->each(fn ($image) => Storage::disk('public')->delete($image->path));

        $product->delete();

        ActivityLog::record('product.deleted', "Product \"{$product->title}\" deleted.");

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function toggleStatus(Product $product): RedirectResponse
    {
        $product->update([
            'status' => $product->status->value === 'active' ? 'inactive' : 'active',
        ]);

        ActivityLog::record('product.status_toggled', "Product \"{$product->title}\" status set to {$product->status->value}.");

        return back()->with('success', 'Product status updated.');
    }

    public function duplicate(Product $product): RedirectResponse
    {
        $duplicate = DB::transaction(function () use ($product) {
            $copy = $product->replicate(['slug']);
            $copy->title = $product->title.' (Copy)';
            $copy->slug = Slug::unique(Product::class, $copy->title);
            $copy->status = 'draft';

            if ($product->thumbnail) {
                $copy->thumbnail = $this->duplicateFile($product->thumbnail, 'products/thumbnails');
            }

            $copy->save();

            foreach ($product->specifications as $spec) {
                $copy->specifications()->create([
                    'title' => $spec->title,
                    'value' => $spec->value,
                    'status' => $spec->status,
                    'sort_order' => $spec->sort_order,
                ]);
            }

            foreach ($product->images as $image) {
                $copy->images()->create([
                    'path' => $this->duplicateFile($image->path, 'products/gallery'),
                    'sort_order' => $image->sort_order,
                ]);
            }

            foreach ($product->variations as $variation) {
                $copy->variations()->create([
                    'metal' => $variation->metal,
                    'color' => $variation->color,
                    'gold_purity' => $variation->gold_purity,
                    'sku' => $variation->sku.'-COPY-'.strtoupper(uniqid()),
                    'price' => $variation->price,
                    'stock' => 0,
                    'min_stock_alert' => $variation->min_stock_alert,
                    'status' => 'inactive',
                ]);
            }

            return $copy;
        });

        ActivityLog::record('product.duplicated', "Product \"{$product->title}\" duplicated as \"{$duplicate->title}\".");

        return redirect()->route('admin.products.edit', $duplicate)->with('success', 'Product duplicated. Review and publish it when ready.');
    }

    private function duplicateFile(string $path, string $directory): string
    {
        $newPath = $directory.'/'.uniqid('', true).'_'.basename($path);
        Storage::disk('public')->copy($path, $newPath);

        return $newPath;
    }
}
