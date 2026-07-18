<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Support\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', "%{$request->string('search')}%"))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when(
                $request->string('sort')->toString(),
                fn ($query, $sort) => match ($sort) {
                    'oldest' => $query->oldest(),
                    'alphabetical' => $query->orderBy('name'),
                    default => $query->latest(),
                },
                fn ($query) => $query->latest()
            )
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Slug::unique(Category::class, $data['name']);
        $data['image'] = $request->file('image')->store('categories', 'public');

        $category = Category::create($data);

        ActivityLog::record('category.created', "Category \"{$category->name}\" created.");

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        if ($data['name'] !== $category->name) {
            $data['slug'] = Slug::unique(Category::class, $data['name'], ignoreId: $category->id);
        }

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        ActivityLog::record('category.updated', "Category \"{$category->name}\" updated.");

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'This category cannot be deleted because products are assigned to it.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        ActivityLog::record('category.deleted', "Category \"{$category->name}\" deleted.");

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
