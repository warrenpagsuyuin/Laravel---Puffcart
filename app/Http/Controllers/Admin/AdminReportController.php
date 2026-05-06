<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $from = Carbon::parse($request->get('from', now()->startOfMonth()->toDateString()))->startOfDay();
        $to = Carbon::parse($request->get('to', now()->toDateString()))->endOfDay();

        $totalOrders = Schema::hasTable('orders')
            ? Order::whereBetween('created_at', [$from, $to])->count()
            : 0;

        $totalRevenue = Schema::hasTable('payments')
            ? Payment::where('status', 'paid')->whereBetween('paid_at', [$from, $to])->sum('amount')
            : 0;

        $topProducts = Schema::hasTable('order_items')
            ? Product::withSum('orderItems as units_sold', 'quantity')
                ->orderByDesc('units_sold')
                ->take(10)
                ->get()
            : collect();

        $statusCounts = Schema::hasTable('orders')
            ? Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status')
            : collect();

        $recentPayments = Schema::hasTable('payments')
            ? Payment::with('order.user')->latest()->take(10)->get()
            : collect();

        $dailySales = Schema::hasTable('payments')
            ? Payment::where('status', 'paid')
                ->whereBetween('paid_at', [$from, $to])
                ->selectRaw('date(paid_at) as date, sum(amount) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
            : collect();

        return view('admin.reports', compact(
            'from',
            'to',
            'totalOrders',
            'totalRevenue',
            'topProducts',
            'statusCounts',
            'recentPayments',
            'dailySales'
        ));
    }
}
