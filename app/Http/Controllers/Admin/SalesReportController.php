<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to', now()->toDateString());

        $totalSales = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$from, $to])
            ->sum('amount');

        $totalOrders = Order::whereBetween('created_at', [$from, $to])->count();

        $topProducts = Product::withCount(['orderItems as units_sold' => fn($q) =>
            $q->selectRaw('sum(order_items.quantity)')
              ->whereHas('order', fn($o) => $o->whereBetween('created_at', [$from, $to]))])
            ->orderByDesc('units_sold')
            ->take(10)
            ->get();

        $salesByDay = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$from, $to])
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $paymentBreakdown = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$from, $to])
            ->selectRaw('method, count(*) as count, sum(amount) as total')
            ->groupBy('method')
            ->get();

        return view('admin.reports.index', compact(
            'from', 'to', 'totalSales', 'totalOrders',
            'topProducts', 'salesByDay', 'paymentBreakdown'
        ));
    }

    public function exportPdf(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to', now()->toDateString());

        $data = $this->getReportData($from, $to);
        $pdf  = Pdf::loadView('admin.reports.pdf', array_merge($data, compact('from', 'to')));

        return $pdf->download("puffcart-sales-report-{$from}-to-{$to}.pdf");
    }

    public function exportCsv(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to', now()->toDateString());

        $orders = Order::with('user', 'payment')
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=puffcart-orders-{$from}-to-{$to}.csv",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order #', 'Customer', 'Status', 'Payment Method', 'Total', 'Date']);
            foreach ($orders as $o) {
                fputcsv($file, [
                    $o->order_number,
                    $o->user->name,
                    $o->status_label,
                    strtoupper($o->payment_method),
                    $o->total,
                    $o->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getReportData(string $from, string $to): array
    {
        return [
            'totalSales'  => Payment::where('status', 'paid')->whereBetween('paid_at', [$from, $to])->sum('amount'),
            'totalOrders' => Order::whereBetween('created_at', [$from, $to])->count(),
            'topProducts' => Product::withCount(['orderItems as units_sold' => fn($q) =>
                $q->selectRaw('sum(order_items.quantity)')])->orderByDesc('units_sold')->take(10)->get(),
        ];
    }
}
