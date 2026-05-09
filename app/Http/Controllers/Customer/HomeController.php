<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductRecommendationService;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index(ProductRecommendationService $recommendationService)
    {
        $featuredProducts = Product::active()->featured()->with('availableFlavorOptions', 'availableColorOptions')->take(8)->get();
        $categories = Schema::hasTable('categories')
            ? Category::withCount('products')->active()->get()
            : collect();
        $newArrivals = Product::active()->where('badge', 'new')->with('availableFlavorOptions', 'availableColorOptions')->take(4)->get();
        $recommendedProducts = $recommendationService->personalized(auth()->user(), 4);

        return view('home', compact('featuredProducts', 'categories', 'newArrivals', 'recommendedProducts'));
    }
}
