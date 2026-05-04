<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year', now()->year);

        // Stats
        $totalSales     = Payment::where('status', 'paid')->sum('amount');
        $todayOrders    = Order::whereDate('created_at', today())->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $lowStockCount  = Product::lowStock()->count();

        // Weekly sales for chart
        $weeklySales = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklySales->push([
                'day'    => $date->format('D'),
                'amount' => Payment::where('status', 'paid')
                    ->whereDate('paid_at', $date)
                    ->sum('amount'),
            ]);
        }

        // Top products
        $topProducts = Product::withCount(['orderItems as total_sold' => fn($q) =>
            $q->selectRaw('sum(quantity)')])
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Order status counts
        $orderStatusCounts = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Payment method breakdown
        $paymentBreakdown = Payment::where('status', 'paid')
            ->selectRaw('method, count(*) as count, sum(amount) as total')
            ->groupBy('method')
            ->get();

        // Low stock items
        $lowStockItems = Product::lowStock()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalSales', 'todayOrders', 'totalCustomers', 'lowStockCount',
            'weeklySales', 'topProducts', 'recentOrders',
            'orderStatusCounts', 'paymentBreakdown', 'lowStockItems'
        ));
    }
}
