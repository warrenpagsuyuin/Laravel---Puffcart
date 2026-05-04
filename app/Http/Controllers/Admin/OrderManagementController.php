<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTracking;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'payment')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('order_number', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        $orders = $query->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product', 'payment', 'tracking');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'  => 'required|in:pending,processing,packed,out_for_delivery,completed,cancelled',
            'message' => 'nullable|string',
        ]);

        $order->update(['status' => $request->status]);

        $messages = [
            'processing'       => 'Order confirmed and is now being processed.',
            'packed'           => 'Order has been packed and ready for pickup.',
            'out_for_delivery' => 'Order picked up by rider and is out for delivery.',
            'completed'        => 'Order successfully delivered.',
            'cancelled'        => 'Order has been cancelled.',
        ];

        OrderTracking::create([
            'order_id'    => $order->id,
            'status'      => $request->status,
            'message'     => $request->message ?? $messages[$request->status] ?? 'Status updated.',
            'occurred_at' => now(),
        ]);

        if ($request->status === 'completed' && $order->payment) {
            $order->payment->update(['status' => 'paid', 'paid_at' => now()]);
        }

        return back()->with('success', 'Order status updated.');
    }
}
