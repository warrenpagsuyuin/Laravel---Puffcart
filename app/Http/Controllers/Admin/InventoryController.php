<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($request->boolean('low_stock'), fn ($query) => $query->lowStock())
            ->orderBy('stock')
            ->paginate(20)
            ->withQueryString();

        $lowStockCount = Product::lowStock()->count();

        return view('admin.inventory', compact('products', 'lowStockCount'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $product->update($data);

        return back()->with('success', 'Inventory updated.');
    }
}
