<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class AdminMLInsightController extends Controller
{
    public function index()
    {
        $products = $this->productsWithSales();
        $averageUnitsSold = max(1, (float) $products->avg('units_sold'));

        $bestSellingProducts = $products
            ->sortByDesc('units_sold')
            ->take(5)
            ->values();

        $highDemandProducts = $products
            ->filter(fn ($product) => $product->units_sold >= $averageUnitsSold || ($product->stock <= 5 && $product->units_sold > 0))
            ->sortByDesc('units_sold')
            ->take(5)
            ->values();

        if ($highDemandProducts->isEmpty()) {
            $highDemandProducts = Product::query()
                ->orderByDesc('is_featured')
                ->orderByDesc('stock')
                ->take(3)
                ->get()
                ->map(fn ($product) => $this->attachInsightFields($product, 0));
        }

        $slowMovingProducts = $products
            ->filter(fn ($product) => $product->units_sold <= 1)
            ->sortByDesc('stock')
            ->take(5)
            ->values();

        $restockRecommendations = $products
            ->filter(fn ($product) => $product->stock <= ($product->reorder_level ?? 5))
            ->map(function ($product) {
                $product->recommended_restock = max(
                    5,
                    (($product->reorder_level ?? 5) * 2) - $product->stock,
                    $product->units_sold * 2
                );

                return $product;
            })
            ->sortByDesc('recommended_restock')
            ->values();

        $salesTrend = [
            'orders_7_days' => Schema::hasTable('orders') ? Order::where('created_at', '>=', now()->subDays(7))->count() : 0,
            'orders_30_days' => Schema::hasTable('orders') ? Order::where('created_at', '>=', now()->subDays(30))->count() : 0,
            'paid_revenue_30_days' => Schema::hasTable('payments')
                ? Payment::where('status', 'paid')->where('paid_at', '>=', now()->subDays(30))->sum('amount')
                : 0,
        ];

        $customerBehavior = $this->customerBehavior();
        $riskAlerts = $this->riskAlerts();
        $recommendations = $this->recommendations($restockRecommendations, $slowMovingProducts, $riskAlerts);

        return view('admin.ml-insights', compact(
            'bestSellingProducts',
            'highDemandProducts',
            'slowMovingProducts',
            'restockRecommendations',
            'salesTrend',
            'customerBehavior',
            'riskAlerts',
            'recommendations'
        ));
    }

    private function productsWithSales()
    {
        $query = Product::query();

        if (Schema::hasTable('order_items')) {
            $query->withSum('orderItems as units_sold', 'quantity');
        }

        return $query->get()
            ->map(fn ($product) => $this->attachInsightFields($product, (int) ($product->units_sold ?? 0)));
    }

    private function attachInsightFields(Product $product, int $unitsSold): Product
    {
        $product->units_sold = $unitsSold;
        $product->demand_label = match (true) {
            $unitsSold >= 10 => 'High',
            $unitsSold >= 3 => 'Medium',
            default => 'Low',
        };

        return $product;
    }

    private function riskAlerts()
    {
        $alerts = collect();

        if (Schema::hasColumn('users', 'verification_status')) {
            User::where('verification_status', 'rejected')
                ->latest()
                ->take(5)
                ->get()
                ->each(fn ($user) => $alerts->push([
                    'level' => 'Medium',
                    'title' => 'Rejected verification',
                    'detail' => "{$user->name} has a rejected age verification status.",
                ]));
        }

        if (Schema::hasColumn('users', 'failed_login_attempts')) {
            User::where('failed_login_attempts', '>=', 3)
                ->orderByDesc('failed_login_attempts')
                ->take(5)
                ->get()
                ->each(fn ($user) => $alerts->push([
                    'level' => 'High',
                    'title' => 'Repeated failed logins',
                    'detail' => "{$user->name} has {$user->failed_login_attempts} failed login attempts.",
                ]));
        }

        if (Schema::hasTable('orders')) {
            User::withCount(['orders as cancelled_orders_count' => fn ($query) => $query->where('status', 'cancelled')])
                ->take(5)
                ->get()
                ->filter(fn ($user) => $user->cancelled_orders_count >= 3)
                ->each(fn ($user) => $alerts->push([
                    'level' => 'Medium',
                    'title' => 'High cancellation count',
                    'detail' => "{$user->name} has {$user->cancelled_orders_count} cancelled orders.",
                ]));
        }

        return $alerts->isNotEmpty()
            ? $alerts
            : collect([[
                'level' => 'Low',
                'title' => 'No elevated risks detected',
                'detail' => 'No rejected verifications, repeated failed logins, or unusual order patterns were found.',
            ]]);
    }

    private function customerBehavior(): array
    {
        $customerCount = User::where('role', 'customer')->count();
        $orderCount = Schema::hasTable('orders') ? Order::count() : 0;
        $repeatCustomers = Schema::hasTable('orders')
            ? User::where('role', 'customer')
                ->withCount('orders')
                ->get()
                ->filter(fn ($user) => $user->orders_count > 1)
                ->count()
            : 0;

        return [
            'customers' => $customerCount,
            'repeat_customers' => $repeatCustomers,
            'average_orders' => $customerCount > 0 ? round($orderCount / $customerCount, 2) : 0,
        ];
    }

    private function recommendations($restockRecommendations, $slowMovingProducts, $riskAlerts): array
    {
        $recommendations = [];

        if ($restockRecommendations->isNotEmpty()) {
            $recommendations[] = 'Prioritize restocking products at or below reorder level before the next sales push.';
        }

        if ($slowMovingProducts->isNotEmpty()) {
            $recommendations[] = 'Review slow-moving inventory for bundle offers, price adjustments, or homepage placement changes.';
        }

        if ($riskAlerts->where('level', 'High')->isNotEmpty()) {
            $recommendations[] = 'Review high-risk customer accounts before approving orders or verifications.';
        }

        return $recommendations ?: [
            'Keep monitoring sales and stock movement as new orders come in.',
            'Use the restock table after real orders are recorded for stronger demand signals.',
        ];
    }
}
