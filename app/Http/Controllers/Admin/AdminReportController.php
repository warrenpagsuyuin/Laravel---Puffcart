<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\WalkinOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $from = Carbon::parse($request->get('from', now()->startOfMonth()->toDateString()))->startOfDay();
        $to = Carbon::parse($request->get('to', now()->toDateString()))->endOfDay();

        return view('admin.reports', $this->reportData($from, $to));
    }

    public function exportPdf(Request $request)
    {
        $from = Carbon::parse($request->get('from', now()->startOfMonth()->toDateString()))->startOfDay();
        $to = Carbon::parse($request->get('to', now()->toDateString()))->endOfDay();
        $data = $this->reportData($from, $to);

        $pdf = Pdf::loadView('admin.reports-pdf', $data)->setPaper('a4', 'portrait');

        return $pdf->download('puffcart-report-' . $from->toDateString() . '-to-' . $to->toDateString() . '.pdf');
    }

    private function reportData(Carbon $from, Carbon $to): array
    {
        $totalOrders = (Schema::hasTable('orders')
            ? Order::whereBetween('created_at', [$from, $to])->count()
            : 0)
            + (Schema::hasTable('walkin_orders')
                ? WalkinOrder::whereBetween('created_at', [$from, $to])->count()
                : 0);

        $onlineRevenue = Schema::hasTable('payments')
            ? Payment::where('status', 'paid')->whereBetween('paid_at', [$from, $to])->sum('amount')
            : 0;
        $walkInRevenue = Schema::hasTable('walkin_orders')
            ? WalkinOrder::where('status', 'completed')->whereBetween('created_at', [$from, $to])->sum('total')
            : 0;
        $totalRevenue = $onlineRevenue + $walkInRevenue;

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
                ->mapWithKeys(fn ($day) => [$day->date => (float) $day->total])
            : collect();

        if (Schema::hasTable('walkin_orders')) {
            WalkinOrder::where('status', 'completed')
                ->whereBetween('created_at', [$from, $to])
                ->selectRaw('date(created_at) as date, sum(total) as total')
                ->groupBy('date')
                ->get()
                ->each(function ($day) use ($dailySales) {
                    $dailySales[$day->date] = ($dailySales[$day->date] ?? 0) + (float) $day->total;
                });
        }

        $dailySales = $dailySales
            ->sortKeys()
            ->map(fn ($total, $date) => (object) ['date' => $date, 'total' => $total])
            ->values();

        return compact(
            'from',
            'to',
            'totalOrders',
            'totalRevenue',
            'topProducts',
            'statusCounts',
            'recentPayments',
            'dailySales'
        );
    }
}
