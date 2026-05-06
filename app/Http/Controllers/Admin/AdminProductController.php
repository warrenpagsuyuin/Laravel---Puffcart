<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

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
        $categories = Product::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.products', compact('products', 'editingProduct', 'categories'));
    }

    public function edit(Product $product)
    {
        $products = Product::latest()->paginate(12);
        $editingProduct = $product;
        $categories = Product::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.products', compact('products', 'editingProduct', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['reorder_level'] = $data['reorder_level'] ?? 5;
        $data['badge'] = $data['badge'] ?? 'none';
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product added.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validatedData($request, $product);
        $data['reorder_level'] = $data['reorder_level'] ?? 5;
        $data['badge'] = $data['badge'] ?? 'none';
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);

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

    private function validatedData(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($product),
            ],
            'category' => 'nullable|string|max:120',
            'brand' => 'nullable|string|max:120',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'badge' => 'nullable|in:none,new,hot,sale',
            'description' => 'nullable|string|max:3000',
            'image' => 'nullable|image|max:2048',
        ]);
    }
}
