<?php

namespace App\Services;

use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\ProductFlavor;
use Illuminate\Support\Facades\Schema;

class InventoryLogService
{
    public function productEvent(string $event, Product $product, array $metadata = [], ?int $before = null, ?int $after = null): void
    {
        $this->write([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'event' => $event,
            'quantity_before' => $before,
            'quantity_after' => $after,
            'quantity_delta' => $before !== null && $after !== null ? $after - $before : null,
            'metadata' => $metadata,
        ]);
    }

    public function flavorStockChanged(ProductFlavor $flavor, int $before, int $after, string $event = 'stock_changed', array $metadata = []): void
    {
        if ($before === $after && $event === 'stock_changed') {
            return;
        }

        $this->write([
            'product_id' => $flavor->product_id,
            'product_flavor_id' => $flavor->id,
            'user_id' => auth()->id(),
            'event' => $event,
            'quantity_before' => $before,
            'quantity_after' => $after,
            'quantity_delta' => $after - $before,
            'metadata' => $metadata + ['option' => $flavor->name],
        ]);
    }

    private function write(array $data): void
    {
        if (Schema::hasTable('inventory_logs')) {
            InventoryLog::create($data);
        }
    }
}
