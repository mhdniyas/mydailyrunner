<!DOCTYPE html>
<html>
<head>
    <title>Payment Reminder</title>
</head>
<body>
    <h1>Payment Reminder - {{ $shop->name }}</h1>
    
    <p>Dear {{ $customer->name }},</p>
    
    <p>This is a friendly reminder that you have pending payments with {{ $shop->name }}.</p>
    
    <p><strong>Total Due Amount: {{ number_format($totalDue, 2) }}</strong></p>
    
    <h3>Pending Sales:</h3>
    
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Sale Date</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Sale #</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total Amount</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Paid Amount</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingSales as $sale)
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $sale->sale_date->format('Y-m-d') }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $sale->id }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($sale->total_amount, 2) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($sale->paid_amount, 2) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($sale->due_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <p>Please arrange for payment at your earliest convenience.</p>
    
    <p>If you have any questions or concerns, please contact us:</p>
    <p>
        Phone: {{ $shop->phone }}<br>
        Email: {{ $shop->email }}
    </p>
    
    <p>Thank you for your business.</p>
    
    <p>Regards,<br>{{ $shop->name }}</p>
</body>
</html>