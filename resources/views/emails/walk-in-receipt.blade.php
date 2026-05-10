<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #1a1a1a; font-size: 15px; margin: 0; padding: 0; background: #f9f9f9; }
        .wrap { max-width: 560px; margin: 32px auto; background: #ffffff; border-radius: 10px; overflow: hidden; border: 1px solid #e0e0e0; }
        .header { background: #0066ff; padding: 28px 32px; }
        .header h1 { color: #fff; font-size: 22px; margin: 0; }
        .header p  { color: #cce0ff; font-size: 13px; margin: 6px 0 0; }
        .body { padding: 28px 32px; }
        .body p { margin: 0 0 12px; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        th { text-align: left; font-size: 12px; text-transform: uppercase; color: #999; padding: 0 0 8px; border-bottom: 1px solid #e0e0e0; }
        td { padding: 10px 0; border-bottom: 1px solid #f0f0f0; font-size: 14px; color: #444; }
        td strong { color: #1a1a1a; }
        .total-row td { font-weight: 700; font-size: 16px; color: #0066ff; border-bottom: none; padding-top: 14px; }
        .footer { background: #f9f9f9; padding: 18px 32px; text-align: center; font-size: 12px; color: #aaa; border-top: 1px solid #e0e0e0; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>PuffCart</h1>
        <p>Walk-In Order Receipt — {{ $orderNumber }}</p>
    </div>
    <div class="body">
        <p>Hi <strong>{{ $customerName }}</strong>, thank you for your purchase!</p>
        <p>Here's a summary of your order:</p>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="text-align:right">Qty</th>
                    <th style="text-align:right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lines as $line)
                <tr>
                    <td><strong>{{ $line['product']->name }}</strong></td>
                    <td style="text-align:right">{{ $line['qty'] }}</td>
                    <td style="text-align:right">₱{{ number_format($line['subtotal'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2">Total</td>
                    <td style="text-align:right">₱{{ number_format($total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <p><strong>Payment:</strong> {{ ucfirst($paymentMethod) }}</p>
        <p style="color:#666;font-size:13px;">If you have any questions about your order, please contact us at your nearest PuffCart store.</p>
    </div>
    <div class="footer">© {{ date('Y') }} PuffCart. All rights reserved.</div>
</div>
</body>
</html>