<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTracking;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(private CustomerBehaviorService $behaviorService)
    {
    }

    public function preview(User $user, ?string $promoCode = null): array
    {
        $items = $user->cartItems()->with('product')->get();
        $subtotal = $items->sum(fn (CartItem $item) => (float) $item->product->price * $item->quantity);
        $deliveryFee = $subtotal >= 500 || $subtotal <= 0 ? 0 : 50;
        $promo = $this->resolvePromoCode($promoCode);
        $discount = $promo ? $promo->discountFor($subtotal) : 0;

        return [
            'items' => $items,
            'subtotal' => round($subtotal, 2),
            'delivery_fee' => round($deliveryFee, 2),
            'discount' => round($discount, 2),
            'total' => round(max(0, $subtotal + $deliveryFee - $discount), 2),
            'promo' => $promo,
        ];
    }

    public function placeOrder(User $user, array $data, Request $request): Order
    {
        return DB::transaction(function () use ($user, $data, $request) {
            $cartItems = CartItem::where('user_id', $user->id)
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart is empty.',
                ]);
            }

            $products = Product::whereIn('id', $cartItems->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = 0;

            foreach ($cartItems as $item) {
                $product = $products->get($item->product_id);

                if (!$product || !$product->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => 'One of the products in your cart is no longer available.',
                    ]);
                }

                if ($product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'cart' => "{$product->name} only has {$product->stock} item(s) left.",
                    ]);
                }

                $subtotal += (float) $product->price * $item->quantity;
            }

            $deliveryFee = $subtotal >= 500 ? 0 : 50;
            $promo = $this->resolvePromoCode($data['promo_code'] ?? null);

            if (!empty($data['promo_code']) && !$promo) {
                throw ValidationException::withMessages([
                    'promo_code' => 'The promo code is invalid, expired, or has reached its usage limit.',
                ]);
            }

            $discount = $promo ? $promo->discountFor($subtotal) : 0;
            $total = max(0, $subtotal + $deliveryFee - $discount);

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'subtotal' => round($subtotal, 2),
                'delivery_fee' => round($deliveryFee, 2),
                'discount' => round($discount, 2),
                'total' => round($total, 2),
                'promo_code' => $promo?->code,
                'delivery_address' => $data['delivery_address'],
                'delivery_phone' => $data['delivery_phone'],
                'notes' => $data['notes'] ?? null,
                'payment_method' => $data['payment_method'],
            ]);

            foreach ($cartItems as $item) {
                $product = $products->get($item->product_id);
                $lineSubtotal = (float) $product->price * $item->quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => round($lineSubtotal, 2),
                ]);

                $updates = ['stock' => $product->stock - $item->quantity];

                if (Schema::hasColumn('products', 'sales_count')) {
                    $updates['sales_count'] = (int) $product->sales_count + $item->quantity;
                }

                $product->update($updates);
                $this->behaviorService->purchased($user, $product, $item->quantity, $lineSubtotal, $request);
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $data['payment_method'],
                'amount' => round($total, 2),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $data['payment_method'],
                'currency' => 'PHP',
            ]);

            OrderTracking::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'message' => 'Order placed successfully and is awaiting confirmation.',
                'occurred_at' => now(),
            ]);

            if ($promo) {
                $promo->increment('used_count');
            }

            CartItem::where('user_id', $user->id)->delete();

            return $order->load('items.product', 'payment', 'tracking');
        });
    }

    private function resolvePromoCode(?string $code): ?PromoCode
    {
        $code = trim((string) $code);

        if ($code === '' || !Schema::hasTable('promo_codes')) {
            return null;
        }

        return PromoCode::available()
            ->where('code', strtoupper($code))
            ->first();
    }
}
