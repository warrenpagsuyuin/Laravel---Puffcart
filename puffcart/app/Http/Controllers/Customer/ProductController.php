<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $sort = $request->get('sort', 'featured');
        match ($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest'     => $query->latest(),
            'rating'     => $query->orderByDesc('rating'),
            default      => $query->orderByDesc('is_featured'),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();
        $brands     = Product::active()->distinct()->pluck('brand')->filter();

        return view('customer.shop', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        $product->load('category', 'reviews.user');
        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)->get();
        return view('customer.product-detail', compact('product', 'related'));
    }
}
