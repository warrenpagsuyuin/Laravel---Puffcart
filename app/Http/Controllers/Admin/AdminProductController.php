<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");

                    if (Schema::hasColumn('products', 'sku')) {
                        $query->orWhere('sku', 'like', "%{$search}%");
                    }
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $editingProduct = null;
        $categories = $this->categories();

        return view('admin.products', compact('products', 'editingProduct', 'categories'));
    }

    public function edit(Product $product)
    {
        $products = Product::latest()->paginate(12);
        $editingProduct = $product;
        $categories = $this->categories();

        return view('admin.products', compact('products', 'editingProduct', 'categories'));
    }

    public function store(ProductRequest $request)
    {
        $data = $this->prepareData($request);
        $data['reorder_level'] = $data['reorder_level'] ?? 5;
        $data['badge'] = $data['badge'] ?? 'none';
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = true;
        $data['slug'] = $this->uniqueSlug($data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product added.');
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $this->prepareData($request);
        $data['reorder_level'] = $data['reorder_level'] ?? 5;
        $data['badge'] = $data['badge'] ?? 'none';
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['slug'] = $this->uniqueSlug($data['name'], $product);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->orderItems()->exists() && Schema::hasColumn('products', 'is_active')) {
            $product->update(['is_active' => false]);

            return back()->with('success', 'Product removed from the active catalog.');
        }

        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    private function prepareData(ProductRequest $request): array
    {
        $data = $request->validated();
        $category = $this->resolveCategory($data);

        if ($category) {
            $data['category_id'] = $category->id;
            $data['category'] = $category->name;
        }

        if (array_key_exists('tags', $data)) {
            $data['tags'] = collect(explode(',', (string) $data['tags']))
                ->map(fn ($tag) => trim($tag))
                ->filter()
                ->values()
                ->all();
        }

        return $data;
    }

    private function resolveCategory(array $data): ?Category
    {
        if (!Schema::hasTable('categories')) {
            return null;
        }

        if (!empty($data['category_id'])) {
            return Category::find($data['category_id']);
        }

        $name = trim((string) ($data['category'] ?? ''));

        if ($name === '') {
            return null;
        }

        return Category::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name, 'is_active' => true]
        );
    }

    private function uniqueSlug(string $name, ?Product $product = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $suffix = 2;

        while (
            Product::where('slug', $slug)
                ->when($product, fn ($query) => $query->where('id', '!=', $product->id))
                ->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function categories()
    {
        if (Schema::hasTable('categories')) {
            return Category::active()->orderBy('name')->get();
        }

        return Product::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->get()
            ->map(fn (Product $product) => (object) ['id' => null, 'name' => $product->category]);
    }
}
