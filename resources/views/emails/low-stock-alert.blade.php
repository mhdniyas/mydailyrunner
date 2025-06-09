<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Alert</title>
</head>
<body>
    <h1>Low Stock Alert - {{ $shop->name }}</h1>
    
    <p>The following products are running low on stock:</p>
    
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Product</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Current Stock</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Min Stock Level</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Unit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStockProducts as $product)
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $product->name }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $product->current_stock }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $product->min_stock_level }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $product->unit }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <p>Please restock these items soon to avoid stockouts.</p>
    
    <p>
        <a href="{{ url('/products') }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">
            View Products
        </a>
    </p>
    
    <p>Thank you,<br>Shop Stock & Financial Manager</p>
</body>
</html>