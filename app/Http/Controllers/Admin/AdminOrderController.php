<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user', 'payment')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($query) use ($search) {
                    $query->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product', 'items.flavor', 'items.batteryColor', 'payment', 'tracking');

        return view('admin.order-show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,packed,out_for_delivery,completed,cancelled',
            'message' => 'nullable|string|max:1000',
        ]);

        $order->update(['status' => $data['status']]);

        if (Schema::hasTable('order_tracking')) {
            OrderTracking::create([
                'order_id' => $order->id,
                'status' => $data['status'],
                'message' => $data['message'] ?? $this->statusMessage($data['status']),
                'occurred_at' => now(),
            ]);
        }

        if ($data['status'] === 'completed' && $order->payment) {
            $order->payment->update([
                'status' => 'paid',
                'payment_status' => 'paid',
                'paid_at' => $order->payment->paid_at ?? now(),
            ]);
        }

        return back()->with('success', 'Order status updated.');
    }

    private function statusMessage(string $status): string
    {
        return match ($status) {
            'processing' => 'Order is being prepared.',
            'packed' => 'Order has been packed and is ready for pickup.',
            'out_for_delivery' => 'Order is out for delivery.',
            'completed' => 'Order has been completed.',
            'cancelled' => 'Order has been cancelled.',
            default => 'Order is pending confirmation.',
        };
    }
}
