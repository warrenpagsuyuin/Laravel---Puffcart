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
        $featuredProducts = Product::active()->featured()->with('category', 'availableFlavorOptions', 'availableColorOptions')->take(8)->get();
        $heroDeviceProduct = Product::active()
            ->with('category')
            ->where(function ($query) {
                $query
                    ->whereIn('product_type', [
                        Product::TYPE_PODS,
                        Product::TYPE_BATTERY,
                        Product::TYPE_BUNDLE,
                    ])
                    ->orWhere('category', 'like', '%device%')
                    ->orWhere('category', 'like', '%pod%')
                    ->orWhereHas('category', function ($categoryQuery) {
                        $categoryQuery
                            ->where('name', 'like', '%device%')
                            ->orWhere('name', 'like', '%pod%')
                            ->orWhere('slug', 'like', '%device%')
                            ->orWhere('slug', 'like', '%pod%');
                    });
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->first();
        $categories = Schema::hasTable('categories')
            ? Category::withCount(['products' => fn ($query) => $query->active()])
                ->whereHas('products', fn ($query) => $query->active())
                ->active()
                ->get()
            : collect();
        $newArrivals = Product::active()->where('badge', 'new')->with('availableFlavorOptions', 'availableColorOptions')->take(4)->get();
        $recommendedProducts = $recommendationService->personalized(auth()->user(), 4);

        return view('home', compact('featuredProducts', 'heroDeviceProduct', 'categories', 'newArrivals', 'recommendedProducts'));
    }
}
