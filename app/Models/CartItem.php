<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

// CartItem
class CartItem extends Model {
    protected $fillable = ['user_id', 'product_id', 'product_flavor_id', 'battery_color_id', 'quantity', 'selected_flavor', 'selected_battery_color', 'product_type', 'bundle_pods', 'bundle_battery'];
    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function flavor() { return $this->belongsTo(ProductFlavor::class, 'product_flavor_id'); }
    public function batteryColor() { return $this->belongsTo(ProductFlavor::class, 'battery_color_id'); }
    public function getSubtotalAttribute() { return $this->product->price * $this->quantity; }
    public function getProductTypeLabelAttribute() { return Product::TYPE_LABELS[$this->product_type ?: 'other'] ?? 'Other'; }
    public function getFlavorLabelAttribute() { return $this->product_type === Product::TYPE_BATTERY ? null : ($this->selected_flavor ?: $this->flavor?->name); }
    public function getBatteryColorLabelAttribute() { return $this->selected_battery_color ?: $this->batteryColor?->name; }
    public function getAvailableStockAttribute() {
        if ($this->product_type === Product::TYPE_BUNDLE && $this->battery_color_id) {
            return min($this->flavor?->stock ?? 0, $this->batteryColor?->stock ?? 0);
        }

        return $this->product_flavor_id ? ($this->flavor?->stock ?? 0) : ($this->product?->available_stock ?? 0);
    }
    public function getBundleDescriptionAttribute() {
        if ($this->product_type !== Product::TYPE_BUNDLE) {
            return null;
        }

        return collect([
            $this->bundle_pods ? "Pods: {$this->bundle_pods}" : null,
            $this->selected_flavor ? "Flavor: {$this->selected_flavor}" : null,
            $this->bundle_battery ? "Battery: {$this->bundle_battery}" : null,
        ])->filter()->implode(' / ') ?: null;
    }
}
