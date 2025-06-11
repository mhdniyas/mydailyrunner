<x-app-layout>
    <x-slot name="title">Bag Weights Report</x-slot>
    <x-slot name="subtitle">Track average bag weights over time</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Filter Form -->
        <div class="flex flex-col gap-4 mb-6">
            <div class="w-full">
                <form action="{{ route('reports.bag-weights') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-center gap-2">
                    <select name="product_id" class="w-full sm:w-auto border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ isset($selectedProduct) && $selectedProduct == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <input type="date" name="from_date" value="{{ $from_date ?? now()->subDays(30)->format('Y-m-d') }}" class="flex-1 sm:flex-none border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400" placeholder="From Date">
                        <input type="date" name="to_date" value="{{ $to_date ?? now()->format('Y-m-d') }}" class="flex-1 sm:flex-none border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400" placeholder="To Date">
                    </div>
                    
                    <button type="submit" class="w-full sm:w-auto bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-all duration-200 transform hover:scale-105 text-sm flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Enhanced Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
            <div class="bg-blue-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-blue-600 font-medium">Total Products</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">{{ $products->count() }}</p>
                    </div>
                    <div class="bg-blue-100 p-2 rounded-full">
                        <i class="fas fa-boxes text-blue-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-green-600 font-medium">Avg Bag Weight</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">
                            {{ $products->count() > 0 ? number_format($products->avg('avg_bag_weight'), 2) : '0.00' }}
                        </p>
                    </div>
                    <div class="bg-green-100 p-2 rounded-full">
                        <i class="fas fa-weight text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-purple-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-purple-600 font-medium">Heaviest Bag</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">
                            {{ $products->count() > 0 ? number_format($products->max('max_bag_weight') ?? 0, 2) : '0.00' }}
                        </p>
                    </div>
                    <div class="bg-purple-100 p-2 rounded-full">
                        <i class="fas fa-arrow-up text-purple-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-orange-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-orange-600 font-medium">Lightest Bag</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">
                            {{ $products->count() > 0 ? number_format($products->min('min_bag_weight') ?? 0, 2) : '0.00' }}
                        </p>
                    </div>
                    <div class="bg-orange-100 p-2 rounded-full">
                        <i class="fas fa-arrow-down text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Bag Weight Chart -->
        <div class="mb-6 animate-fade-in" style="animation-delay: 0.4s">
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-primary-600"></i>
                    Bag Weight Trends
                </h3>
                <div class="h-64 sm:h-80">
                    <canvas id="bagWeightChart"></canvas>
                </div>
            </div>
        </div>

        @if($products->count() > 0)
            <!-- Mobile View - Cards -->
            <div class="lg:hidden space-y-3 mb-6">
                @foreach($products as $index => $product)
                    <div class="bg-white border rounded-lg p-4 shadow-sm border-gray-200 transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: {{ ($index * 0.05) + 0.5 }}s">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-lg">
                                    <a href="{{ route('products.show', $product) }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500">Unit: {{ $product->unit }}</p>
                            </div>
                            <div class="ml-4 text-right">
                                <p class="text-lg font-bold text-primary-600">{{ number_format($product->avg_bag_weight, 2) }}</p>
                                <p class="text-sm text-gray-500">{{ $product->unit }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                            <div>
                                <p class="text-gray-500">Min Weight:</p>
                                <p class="font-medium">{{ number_format($product->min_bag_weight ?? 0, 2) }} {{ $product->unit }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Max Weight:</p>
                                <p class="font-medium">{{ number_format($product->max_bag_weight ?? 0, 2) }} {{ $product->unit }}</p>
                            </div>
                        </div>
                        
                        <div class="pt-3 border-t border-gray-100">
                            <button type="button" class="w-full bg-primary-50 text-primary-600 px-4 py-2 rounded-md hover:bg-primary-100 transition-colors view-history-btn text-sm" data-product-id="{{ $product->id }}">
                                <i class="fas fa-history mr-1"></i> View History
                            </button>
                        </div>
                        
                        <!-- Mobile History Details -->
                        <div class="product-history hidden mt-4 pt-4 border-t border-gray-200" id="product-{{ $product->id }}-history">
                            <h4 class="font-medium text-gray-900 mb-3">Weight History</h4>
                            <div class="space-y-3 max-h-64 overflow-y-auto">
                                @foreach($product->stockIns ?? [] as $stockIn)
                                    <div class="bg-gray-50 rounded-md p-3">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-medium text-gray-900">Stock In #{{ $stockIn->id }}</p>
                                                <p class="text-sm text-gray-500">{{ $stockIn->created_at->format('M d, Y') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-primary-600">{{ number_format($stockIn->avg_bag_weight, 2) }}</p>
                                                <p class="text-sm text-gray-500">{{ $product->unit }}</p>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <div>
                                                <p class="text-gray-500">Quantity:</p>
                                                <p class="font-medium">{{ $stockIn->quantity }} {{ $product->unit }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Bags:</p>
                                                <p class="font-medium">{{ $stockIn->bags }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop View - Table -->
            <div class="hidden lg:block overflow-x-auto animate-fade-in" style="animation-delay: 0.5s">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Avg Bag Weight</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Bag Weight</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Bag Weight</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $index => $product)
                                    <tr class="hover:bg-gray-50 transition-all duration-200 animate-fade-in" style="animation-delay: {{ ($index * 0.03) + 0.6 }}s">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('products.show', $product) }}" class="text-primary-600 hover:text-primary-900 transition-colors font-medium">
                                                {{ $product->name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $product->unit }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-primary-600">
                                            {{ number_format($product->avg_bag_weight, 2) }} {{ $product->unit }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ number_format($product->min_bag_weight ?? 0, 2) }} {{ $product->unit }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ number_format($product->max_bag_weight ?? 0, 2) }} {{ $product->unit }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <button type="button" class="text-primary-600 hover:text-primary-900 transition-colors view-history-btn" data-product-id="{{ $product->id }}">
                                                <i class="fas fa-history mr-1"></i> View History
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="product-history bg-gray-50 hidden transition-all duration-300" id="product-{{ $product->id }}-history">
                                        <td colspan="6" class="px-4 py-4">
                                            <div class="bg-white rounded-lg p-4">
                                                <h4 class="font-medium text-gray-900 mb-4">Weight History for {{ $product->name }}</h4>
                                                <div class="overflow-x-auto max-h-64">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock In #</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bags</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bag Weight</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach($product->stockIns ?? [] as $stockIn)
                                                                <tr class="hover:bg-gray-50 transition-colors">
                                                                    <td class="px-3 py-2 whitespace-nowrap text-sm">{{ $stockIn->created_at->format('M d, Y') }}</td>
                                                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium">{{ $stockIn->id }}</td>
                                                                    <td class="px-3 py-2 whitespace-nowrap text-sm">{{ $stockIn->quantity }} {{ $product->unit }}</td>
                                                                    <td class="px-3 py-2 whitespace-nowrap text-sm">{{ $stockIn->bags }}</td>
                                                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-primary-600">{{ number_format($stockIn->avg_bag_weight, 2) }} {{ $product->unit }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Summary Row -->
                                <tr class="bg-gray-50 font-semibold border-t-2 border-gray-300">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm" colspan="2">
                                        Summary ({{ $products->count() }} products)
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-primary-600">
                                        Avg: {{ $products->count() > 0 ? number_format($products->avg('avg_bag_weight'), 2) : '0.00' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        Min: {{ $products->count() > 0 ? number_format($products->min('min_bag_weight') ?? 0, 2) : '0.00' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        Max: {{ $products->count() > 0 ? number_format($products->max('max_bag_weight') ?? 0, 2) : '0.00' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12 animate-fade-in" style="animation-delay: 0.5s">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-weight-hanging text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-lg mb-2">No bag weight data found</p>
                <p class="text-gray-400 text-sm">Try adjusting your filters or adding stock entries with bag weights</p>
            </div>
        @endif
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out forwards;
            opacity: 0;
        }

        /* Smooth scrolling for mobile */
        @media (max-width: 1024px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Custom hover effects */
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }

        .hover\:-translate-y-1:hover {
            transform: translateY(-4px);
        }

        /* Loading state for buttons */
        button:active {
            transform: scale(0.95);
        }

        /* Chart container responsiveness */
        #bagWeightChart {
            max-width: 100%;
            height: auto !important;
        }

        /* Smooth transitions for expandable content */
        .product-history {
            transition: all 0.3s ease-in-out;
        }

        /* Enhanced nested table styling */
        .product-history table {
            border-radius: 8px;
            overflow: hidden;
        }

        /* Custom scrollbar for history sections */
        .overflow-y-auto::-webkit-scrollbar {
            width: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 2px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle product history with enhanced animations
            const viewHistoryBtns = document.querySelectorAll('.view-history-btn');
            
            viewHistoryBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const historyRow = document.getElementById(`product-${productId}-history`);
                    
                    if (historyRow.classList.contains('hidden')) {
                        // Hide all other histories first with animation
                        document.querySelectorAll('.product-history').forEach(row => {
                            if (!row.classList.contains('hidden')) {
                                row.style.opacity = '0';
                                row.style.transform = 'translateY(-10px)';
                                setTimeout(() => {
                                    row.classList.add('hidden');
                                    row.style.opacity = '';
                                    row.style.transform = '';
                                }, 200);
                            }
                        });
                        
                        // Reset all button texts
                        document.querySelectorAll('.view-history-btn').forEach(button => {
                            button.innerHTML = '<i class="fas fa-history mr-1"></i> View History';
                        });
                        
                        // Show this product's history with animation
                        setTimeout(() => {
                            historyRow.classList.remove('hidden');
                            historyRow.style.opacity = '0';
                            historyRow.style.transform = 'translateY(10px)';
                            
                            requestAnimationFrame(() => {
                                historyRow.style.transition = 'all 0.3s ease-out';
                                historyRow.style.opacity = '1';
                                historyRow.style.transform = 'translateY(0)';
                            });
                            
                            this.innerHTML = '<i class="fas fa-history mr-1"></i> Hide History';
                        }, 250);
                    } else {
                        // Hide with animation
                        historyRow.style.opacity = '0';
                        historyRow.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            historyRow.classList.add('hidden');
                            historyRow.style.opacity = '';
                            historyRow.style.transform = '';
                            historyRow.style.transition = '';
                        }, 200);
                        this.innerHTML = '<i class="fas fa-history mr-1"></i> View History';
                    }
                });
            });
            
            // Enhanced Bag Weight Chart
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
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' kg';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)',
                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return value.toFixed(2) + ' kg';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    elements: {
                        line: {
                            tension: 0.3
                        },
                        point: {
                            radius: 4,
                            hoverRadius: 6
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>