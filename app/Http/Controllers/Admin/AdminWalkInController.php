<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WalkinOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminWalkInController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'price', 'stock', 'image']);

        $recentOrders = WalkinOrder::with('items')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.walk-in', compact('products', 'recentOrders'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'payment_method' => 'required|in:cash,gcash,maya',
            'mobile_number'  => 'required_if:payment_method,gcash,maya|nullable|string|max:20',
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
        ]);

        $items    = $request->input('items');
        $products = Product::whereIn('id', array_column($items, 'id'))->get()->keyBy('id');
        $lines    = [];
        $subtotal = 0;

        foreach ($items as $item) {
            $product = $products[$item['id']] ?? null;
            if (! $product) continue;

            $qty       = (int) $item['qty'];
            $lineTotal = $product->price * $qty;

            if ($product->stock < $qty) {
                return back()
                    ->withInput()
                    ->with('error', "Insufficient stock for \"{$product->name}\". Only {$product->stock} left.");
            }

            $lines[]   = ['product' => $product, 'qty' => $qty, 'subtotal' => $lineTotal];
            $subtotal += $lineTotal;
        }

        // Deduct stock
        foreach ($lines as $line) {
            $line['product']->decrement('stock', $line['qty']);
        }

        $order = WalkinOrder::create([
            'order_number'   => 'WI-' . strtoupper(Str::random(8)),
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'payment_method' => $request->payment_method,
            'mobile_number'  => $request->mobile_number,
            'status'         => 'completed',
            'subtotal'       => $subtotal,
            'total'          => $subtotal,
            'notes'          => null,
        ]);

        foreach ($lines as $line) {
            $order->items()->create([
                'product_id'   => $line['product']->id,
                'product_name' => $line['product']->name,
                'price'        => $line['product']->price,
                'quantity'     => $line['qty'],
                'subtotal'     => $line['subtotal'],
            ]);
        }

        // Send receipt email
        try {
            Mail::send('emails.walk-in-receipt', [
                'orderNumber'   => $order->order_number,
                'customerName'  => $request->customer_name,
                'lines'         => $lines,
                'total'         => $subtotal,
                'paymentMethod' => $request->payment_method,
            ], function ($mail) use ($request, $order) {
                $mail->to($request->customer_email, $request->customer_name)
                     ->subject("Your PuffCart Receipt — {$order->order_number}");
            });
        } catch (\Throwable $e) {
            // Email failure should not block the transaction
        }

        return back()->with('success', "Walk-in order {$order->order_number} completed! Receipt sent to {$request->customer_email}.");
    }
}