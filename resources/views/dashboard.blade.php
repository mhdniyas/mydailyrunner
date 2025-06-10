<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="subtitle">Shop overview and analytics</x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-primary-100 text-primary-800">
                    <i class="fas fa-boxes fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 font-medium">Stock Value</p>
                    <p class="text-xl font-semibold">{{ number_format($totalStockValue, 2) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-accent-100 text-accent-800">
                    <i class="fas fa-receipt fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 font-medium">Today's Sales</p>
                    <p class="text-xl font-semibold">{{ number_format($todaySales, 2) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-800">
                    <i class="fas fa-arrow-down fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 font-medium">Cash In</p>
                    <p class="text-xl font-semibold">{{ number_format($cashIn, 2) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-800">
                    <i class="fas fa-arrow-up fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 font-medium">Cash Out</p>
                    <p class="text-xl font-semibold">{{ number_format($cashOut, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Chart -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Sales</h3>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Low Stock Alerts</h3>
                <a href="{{ route('reports.stock') }}?stock_level=low" class="text-primary-600 hover:text-primary-900 text-sm">
                    View All
                </a>
            </div>
            
            @if($lowStockProducts->count() > 0)
                <div class="space-y-4">
                    @foreach($lowStockProducts as $product)
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                            <div>
                                <a href="{{ route('products.show', $product) }}" class="font-medium text-primary-600 hover:text-primary-900">
                                    {{ $product->name }}
                                </a>
                                <p class="text-sm text-gray-500">
                                    {{ $product->current_stock }} / {{ $product->min_stock_level }} {{ $product->unit }}
                                </p>
                            </div>
                            <a href="{{ route('stock-ins.create') }}" class="text-accent-600 hover:text-accent-900">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No low stock products found.</p>
            @endif
            
            <div class="mt-4">
                <h4 class="font-medium text-gray-900 mb-2">Out of Stock</h4>
                @if($outOfStockProducts->count() > 0)
                    <div class="space-y-2">
                        @foreach($outOfStockProducts as $product)
                            <div class="flex justify-between items-center">
                                <a href="{{ route('products.show', $product) }}" class="text-red-600 hover:text-red-900">
                                    {{ $product->name }}
                                </a>
                                <a href="{{ route('stock-ins.create') }}" class="text-accent-600 hover:text-accent-900">
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No out of stock products.</p>
                @endif
            </div>
        </div>

        <!-- Recent Discrepancies -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Discrepancies</h3>
                <a href="{{ route('reports.discrepancy') }}" class="text-primary-600 hover:text-primary-900 text-sm">
                    View All
                </a>
            </div>
            
            @if($discrepancies->count() > 0)
                <div class="space-y-4">
                    @foreach($discrepancies as $check)
                        <div class="border-b border-gray-200 pb-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">{{ $check->created_at->format('M d, Y') }}</span>
                                <span class="font-medium {{ $check->discrepancy > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $check->discrepancy > 0 ? '+' : '' }}{{ $check->discrepancy }}
                                </span>
                            </div>
                            <p class="font-medium">{{ $check->product->name }}</p>
                            <div class="flex justify-between mt-1 text-sm">
                                <span>System: {{ $check->system_stock }}</span>
                                <span>Physical: {{ $check->physical_stock }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No recent discrepancies found.</p>
            @endif
        </div>

        <!-- Recent Sales -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Sales</h3>
                <a href="{{ route('sales.index') }}" class="text-primary-600 hover:text-primary-900 text-sm">
                    View All
                </a>
            </div>
            
            @if($recentSales->count() > 0)
                <div class="space-y-4">
                    @foreach($recentSales as $sale)
                        <div class="border-b border-gray-200 pb-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">{{ $sale->sale_date->format('M d, Y') }}</span>
                                <span class="font-medium">{{ number_format($sale->total_amount, 2) }}</span>
                            </div>
                            <p class="font-medium">{{ $sale->customer->name }}</p>
                            <div class="flex justify-between mt-1">
                                <span class="text-sm">
                                    {{ $sale->items->count() }} items
                                </span>
                                <span class="text-sm">
                                    @if($sale->status === 'paid')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                    @elseif($sale->status === 'advance')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Partial
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Pending
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No recent sales found.</p>
            @endif
        </div>

        <!-- Pending Payments -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Pending Payments</h3>
                <a href="{{ route('customer-payments.index') }}" class="text-primary-600 hover:text-primary-900 text-sm">
                    View All
                </a>
            </div>
            
            @if($pendingPayments->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingPayments as $sale)
                        <div class="border-b border-gray-200 pb-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Sale #{{ $sale->id }}</span>
                                <span class="font-medium text-red-600">{{ number_format($sale->due_amount, 2) }}</span>
                            </div>
                            <p class="font-medium">{{ $sale->customer->name }}</p>
                            <div class="flex justify-between mt-1">
                                <span class="text-sm">{{ $sale->sale_date->format('M d, Y') }}</span>
                                <a href="{{ route('customer-payments.create', $sale) }}" class="text-accent-600 hover:text-accent-900 text-sm">
                                    Record Payment
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No pending payments found.</p>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($salesChartLabels) !!},
                    datasets: [{
                        label: 'Monthly Sales',
                        data: {!! json_encode($salesChartData) !!},
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>