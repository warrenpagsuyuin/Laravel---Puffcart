<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()->featured()->with('category')->take(8)->get();
        $categories       = Category::withCount('products')->get();
        $newArrivals      = Product::active()->where('badge', 'new')->take(4)->get();
        return view('customer.home', compact('featuredProducts', 'categories', 'newArrivals'));
    }
}
