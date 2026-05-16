<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OrderItem extends Model {
    protected $fillable = ['order_id','product_id','product_flavor_id','battery_color_id','product_name','price','quantity','selected_flavor','selected_battery_color','product_type','bundle_pods','bundle_battery','subtotal'];
    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function flavor() { return $this->belongsTo(ProductFlavor::class, 'product_flavor_id'); }
    public function batteryColor() { return $this->belongsTo(ProductFlavor::class, 'battery_color_id'); }
    public function getProductTypeLabelAttribute() { return Product::TYPE_LABELS[$this->product_type ?: 'other'] ?? 'Other'; }
    public function getFlavorLabelAttribute() { return $this->product_type === Product::TYPE_BATTERY ? null : ($this->selected_flavor ?: $this->flavor?->name); }
    public function getBatteryColorLabelAttribute() { return $this->selected_battery_color ?: $this->batteryColor?->name; }
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
