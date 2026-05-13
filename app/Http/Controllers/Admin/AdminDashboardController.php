<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\WalkinOrder;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $pendingVerifications = Schema::hasColumn('users', 'verification_status')
            ? User::where('verification_status', 'pending')->count()
            : 0;

        $totalOrders = (Schema::hasTable('orders') ? Order::count() : 0)
            + (Schema::hasTable('walkin_orders') ? WalkinOrder::count() : 0);
        $totalRevenue = $this->totalRevenue();

        $recentOrders = Schema::hasTable('orders')
            ? Order::with('user')->latest()->take(6)->get()
            : collect();

        $recentUsers = User::where('role', 'customer')->latest()->take(6)->get();
        $lowStockProducts = Product::lowStock()->orderBy('stock')->take(6)->get();
        $dashboardChart = $this->sevenDaySnapshot();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCustomers',
            'pendingVerifications',
            'totalOrders',
            'totalRevenue',
            'recentOrders',
            'recentUsers',
            'lowStockProducts',
            'dashboardChart'
        ));
    }

    private function totalRevenue(): float
    {
        if (Schema::hasTable('payments')) {
            $onlineRevenue = (float) Payment::where('status', 'paid')->sum('amount');
            $walkInRevenue = Schema::hasTable('walkin_orders')
                ? (float) WalkinOrder::where('status', 'completed')->sum('total')
                : 0;

            return $onlineRevenue + $walkInRevenue;
        }

        $orderRevenue = Schema::hasTable('orders')
            ? (float) Order::whereIn('status', ['delivered', 'completed'])->sum('total')
            : 0;
        $walkInRevenue = Schema::hasTable('walkin_orders')
            ? (float) WalkinOrder::where('status', 'completed')->sum('total')
            : 0;

        return $orderRevenue + $walkInRevenue;
    }

    private function sevenDaySnapshot(): array
    {
        $days = collect(range(6, 0))->map(fn (int $daysAgo) => now()->subDays($daysAgo));

        $items = $days->map(function ($date) {
            $onlineRevenue = 0;
            $onlineOrders = 0;

            if (Schema::hasTable('payments')) {
                $onlineRevenue = (float) Payment::where('status', 'paid')
                    ->where(function ($query) use ($date) {
                        $query
                            ->whereDate('paid_at', $date)
                            ->orWhere(function ($fallbackQuery) use ($date) {
                                $fallbackQuery
                                    ->whereNull('paid_at')
                                    ->whereDate('created_at', $date);
                            });
                    })
                    ->sum('amount');
            }

            if (Schema::hasTable('orders')) {
                $onlineOrders = Order::whereDate('created_at', $date)->count();
            }

            $walkInRevenue = Schema::hasTable('walkin_orders')
                ? (float) WalkinOrder::where('status', 'completed')
                    ->whereDate('created_at', $date)
                    ->sum('total')
                : 0;

            $walkInOrders = Schema::hasTable('walkin_orders')
                ? WalkinOrder::whereDate('created_at', $date)->count()
                : 0;

            return [
                'label' => $date->format('D'),
                'date' => $date->format('M j'),
                'revenue' => $onlineRevenue + $walkInRevenue,
                'orders' => $onlineOrders + $walkInOrders,
            ];
        });

        return [
            'items' => $items,
            'maxRevenue' => max(1, (float) $items->max('revenue')),
            'maxOrders' => max(1, (int) $items->max('orders')),
            'totalRevenue' => (float) $items->sum('revenue'),
            'totalOrders' => (int) $items->sum('orders'),
        ];
    }
}
