<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\CustomerBehaviorService;
use App\Services\ProductRecommendationService;
use App\Services\ProductSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    public function index(
        Request $request,
        ProductSearchService $searchService,
        CustomerBehaviorService $behaviorService,
        ProductRecommendationService $recommendationService
    )
    {
        $products = $searchService->search($request, 6);
        $categories = Schema::hasTable('categories')
            ? Category::withCount('products')->active()->orderBy('name')->get()
            : collect();
        $brands = Product::active()
            ->whereNotNull('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand')
            ->filter();
        $recommendedProducts = $recommendationService->personalized($request->user(), 4);

        if ($request->filled('search')) {
            $behaviorService->searched($request->string('search')->toString(), $request, $products->total());
        }

        return view('shop', compact('products', 'categories', 'brands', 'recommendedProducts'));
    }

    public function show(
        Request $request,
        Product $product,
        ProductRecommendationService $recommendationService,
        CustomerBehaviorService $behaviorService
    )
    {
        abort_if(!$product->is_active, 404);

        $product->load('reviews.user', 'availableFlavorOptions', 'availableColorOptions', 'flavors');
        $behaviorService->productViewed($product, $request);

        $related = $recommendationService->relatedProducts($product, $request->user(), 4);

        return view('product', compact('product', 'related'));
    }
}
