<x-app-layout>
    <x-slot name="title">Bag Weights Report</x-slot>
    <x-slot name="subtitle">Track average bag weights over time</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <form action="{{ route('reports.bag-weights') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <select name="product_id" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ isset($selectedProduct) && $selectedProduct == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="from_date" value="{{ $from_date ?? now()->subDays(30)->format('Y-m-d') }}" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                    <input type="date" name="to_date" value="{{ $to_date ?? now()->format('Y-m-d') }}" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Bag Weight Chart -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bag Weight Trends</h3>
            <div class="h-64">
                <canvas id="bagWeightChart"></canvas>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Avg Bag Weight</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Bag Weight</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Bag Weight</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('products.show', $product) }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($product->avg_bag_weight, 2) }} {{ $product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($product->min_bag_weight ?? 0, 2) }} {{ $product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($product->max_bag_weight ?? 0, 2) }} {{ $product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button type="button" class="text-primary-600 hover:text-primary-900 view-history-btn" data-product-id="{{ $product->id }}">
                                        <i class="fas fa-history mr-1"></i> View History
                                    </button>
                                </td>
                            </tr>
                            <tr class="product-history bg-gray-50 hidden" id="product-{{ $product->id }}-history">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock In #</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bags</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bag Weight</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($product->stockIns ?? [] as $stockIn)
                                                    <tr>
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ $stockIn->created_at->format('M d, Y') }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ $stockIn->id }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ $stockIn->quantity }} {{ $product->unit }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ $stockIn->bags }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ number_format($stockIn->avg_bag_weight, 2) }} {{ $product->unit }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No products with bag weight data found.</p>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle product history
            const viewHistoryBtns = document.querySelectorAll('.view-history-btn');
            
            viewHistoryBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const historyRow = document.getElementById(`product-${productId}-history`);
                    
                    if (historyRow.classList.contains('hidden')) {
                        // Hide all other histories first
                        document.querySelectorAll('.product-history').forEach(row => {
                            row.classList.add('hidden');
                        });
                        
                        // Show this product's history
                        historyRow.classList.remove('hidden');
                        this.innerHTML = '<i class="fas fa-history mr-1"></i> Hide History';
                    } else {
                        historyRow.classList.add('hidden');
                        this.innerHTML = '<i class="fas fa-history mr-1"></i> View History';
                    }
                });
            });
            
            // Bag Weight Chart
            const bagWeightCtx = document.getElementById('bagWeightChart').getContext('2d');
            const bagWeightChart = new Chart(bagWeightCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels ?? []) !!},
                    datasets: {!! json_encode($chartDatasets ?? []) !!}
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>