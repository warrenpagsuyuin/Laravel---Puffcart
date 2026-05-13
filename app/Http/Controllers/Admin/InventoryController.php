<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array((int) $request->input('per_page', 5), [5, 10], true)
            ? (int) $request->input('per_page', 5)
            : 5;

        $products = Product::query()
            ->with('flavors')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhereHas('flavors', fn ($flavorQuery) => $flavorQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('filter'), function ($query) use ($request) {
                $filter = $request->string('filter')->toString();

                // map friendly filter keys to stored category names
                $map = [
                    'accessories' => 'Accessories',
                    'coils-pods' => 'Coils & Pods',
                    'devices' => 'Devices',
                    'e-liquids' => 'E-liquids',
                ];

                if ($filter === 'low_stock') {
                    $query->lowStock();
                } elseif (isset($map[$filter])) {
                    $query->where('category', 'like', "%{$map[$filter]}%");
                }
            })
            ->when($request->boolean('low_stock') && !$request->filled('filter'), fn ($query) => $query->lowStock())
            ->orderBy('stock')
            ->paginate($perPage)
            ->withQueryString();

        $lowStockCount = Product::lowStock()->count();

        return view('admin.inventory', compact('products', 'lowStockCount'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'reorder_level' => 'required|integer|min:0',
            'flavors' => 'required|array|min:1',
            'flavors.*.id' => 'required|integer|exists:product_flavors,id',
            'flavors.*.stock' => 'required|integer|min:0',
            'flavors.*.reorder_level' => 'required|integer|min:0',
        ]);

        $product->update(['reorder_level' => $data['reorder_level']]);

        foreach ($data['flavors'] as $flavorData) {
            $product->flavors()
                ->whereKey($flavorData['id'])
                ->update([
                    'stock' => $flavorData['stock'],
                    'reorder_level' => $flavorData['reorder_level'],
                ]);
        }

        $product->syncStockFromFlavors();

        return back()->with('success', 'Inventory updated.');
    }
}
