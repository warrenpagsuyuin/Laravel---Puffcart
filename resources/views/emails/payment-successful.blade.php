<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #0F172A; font-size: 15px; margin: 0; padding: 0; background: #F8FAFC; }
        .page { background: radial-gradient(circle at 10% 8%, rgba(11, 99, 246, 0.12), transparent 28%), linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 48%, #FFFFFF 100%); padding: 36px 16px; }
        .wrap { max-width: 640px; margin: 0 auto; background: rgba(255, 255, 255, 0.96); border-radius: 18px; overflow: hidden; border: 1px solid #E4ECF7; box-shadow: 0 22px 44px rgba(15, 23, 42, 0.08); }
        .header { background: radial-gradient(circle at 86% 18%, rgba(255, 255, 255, 0.22), transparent 26%), linear-gradient(135deg, #0B63F6 0%, #0F3A8A 100%); padding: 34px 32px; }
        .brand { color: #FFFFFF; font-size: 20px; font-weight: 800; margin: 0 0 20px; }
        .eyebrow { color: rgba(255, 255, 255, 0.78); font-size: 12px; font-weight: 800; letter-spacing: .08em; margin: 0 0 8px; text-transform: uppercase; }
        .header h1 { color: #FFFFFF; font-size: 30px; line-height: 1.15; margin: 0 0 10px; }
        .header p { color: rgba(255, 255, 255, 0.9); font-size: 14px; line-height: 1.65; margin: 0; }
        .body { padding: 28px 32px 32px; }
        .body p { margin: 0 0 12px; line-height: 1.65; }
        .status { display: inline-block; background: #EAF2FF; color: #0B63F6; font-weight: 800; padding: 8px 11px; border-radius: 8px; font-size: 12px; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 14px; }
        .summary { background: #FFFFFF; border: 1px solid #DDE8F7; border-radius: 12px; padding: 16px; margin: 18px 0; }
        .summary-row { display: flex; justify-content: space-between; gap: 16px; padding: 8px 0; border-bottom: 1px solid #EEF2F7; }
        .summary-row:last-child { border-bottom: 0; }
        .summary-row span { color: #64748B; }
        .summary-row strong { color: #0B63F6; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        th { text-align: left; font-size: 12px; text-transform: uppercase; color: #64748B; padding: 0 0 8px; border-bottom: 1px solid #DDE8F7; }
        td { padding: 12px 0; border-bottom: 1px solid #EEF2F7; font-size: 14px; color: #334155; vertical-align: top; }
        td strong { color: #0F172A; }
        .muted { color: #64748B; font-size: 13px; }
        .footer { background: #F3F7FC; padding: 18px 32px; text-align: center; font-size: 12px; color: #64748B; border-top: 1px solid #E4ECF7; }
    </style>
</head>
<body>
<div class="page">
    <div class="wrap">
        <div class="header">
            <p class="brand">PUFFCART</p>
            <p class="eyebrow">Payment Confirmed</p>
            <h1>Order {{ $order->order_number }} is paid</h1>
            <p>Your payment was received successfully. Your order is now moving into processing.</p>
        </div>

        <div class="body">
            <span class="status">Payment successful</span>
            <p>Hi <strong>{{ $order->user?->name ?? 'Customer' }}</strong>,</p>
            <p>Thanks for shopping with Puffcart. Here is your payment confirmation and order summary.</p>

            <div class="summary">
                <div class="summary-row">
                    <span>Payment method</span>
                    <strong>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</strong>
                </div>
                <div class="summary-row">
                    <span>Total paid</span>
                    <strong>PHP {{ number_format((float) $order->total, 2) }}</strong>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align:right">Qty</th>
                        <th style="text-align:right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @if($item->flavor_label || $item->battery_color_label || $item->bundle_description)
                                    <div class="muted" style="margin-top:3px;">
                                        @if($item->bundle_description)
                                            {{ $item->bundle_description }}
                                        @else
                                            {{ collect([
                                                $item->flavor_label ? 'Flavor: ' . $item->flavor_label : null,
                                                $item->battery_color_label ? 'Battery Color: ' . $item->battery_color_label : null,
                                            ])->filter()->implode(' / ') }}
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td style="text-align:right">{{ $item->quantity }}</td>
                            <td style="text-align:right">PHP {{ number_format((float) $item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="muted">We will send another update when your order status changes.</p>
        </div>

        <div class="footer">&copy; {{ date('Y') }} PUFFCART. All rights reserved.</div>
    </div>
</div>
</body>
</html>
