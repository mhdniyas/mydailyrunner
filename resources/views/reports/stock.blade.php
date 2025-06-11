<x-app-layout>
    <x-slot name="title">Stock Report</x-slot>
    <x-slot name="subtitle">Current inventory status</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Filter Form & Export Button -->
        <div class="flex flex-col gap-4 mb-6">
            <div class="w-full">
                <form action="{{ route('reports.stock') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-center gap-2">
                    <select name="product_id" class="w-full sm:w-auto border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400">
                        <option value="">All Products</option>
                        @foreach($allProducts as $product)
                            <option value="{{ $product->id }}" {{ $selectedProduct == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <select name="stock_level" class="w-full sm:w-auto border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400">
                        <option value="all" {{ $stockLevel == 'all' ? 'selected' : '' }}>All Stock Levels</option>
                        <option value="low" {{ $stockLevel == 'low' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out" {{ $stockLevel == 'out' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="normal" {{ $stockLevel == 'normal' ? 'selected' : '' }}>Normal Stock</option>
                    </select>
                    
                    <button type="submit" class="w-full sm:w-auto bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-all duration-200 transform hover:scale-105 text-sm flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </form>
            </div>
            <div class="w-full sm:w-auto flex justify-start">
                <a href="{{ route('export.stock') }}?product_id={{ $selectedProduct }}&stock_level={{ $stockLevel }}" class="w-full sm:w-auto bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105 text-sm flex items-center justify-center">
                    <i class="fas fa-file-export mr-2"></i> Export
                </a>
            </div>
        </div>

        <!-- Enhanced Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
            <div class="bg-primary-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md animate-fade-in">
                <p class="text-xs sm:text-sm text-primary-600 font-medium">Total Stock Value</p>
                <p class="text-xl sm:text-2xl font-bold mt-1">${{ number_format($totalStockValue, 2) }}</p>
            </div>
            <div class="bg-blue-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md animate-fade-in" style="animation-delay: 0.1s">
                <p class="text-xs sm:text-sm text-blue-600 font-medium">Total Products</p>
                <p class="text-xl sm:text-2xl font-bold mt-1">{{ $products->count() }}</p>
            </div>
            <div class="bg-red-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md animate-fade-in" style="animation-delay: 0.2s">
                <p class="text-xs sm:text-sm text-red-600 font-medium">Out of Stock</p>
                <p class="text-xl sm:text-2xl font-bold mt-1">{{ $products->filter(function($product) { return $product->isOutOfStock(); })->count() }}</p>
            </div>
            <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md animate-fade-in" style="animation-delay: 0.3s">
                <p class="text-xs sm:text-sm text-yellow-600 font-medium">Low Stock</p>
                <p class="text-xl sm:text-2xl font-bold mt-1">{{ $products->filter(function($product) { return $product->isLowStock(); })->count() }}</p>
            </div>
        </div>

        @if($products->count() > 0)
            <!-- Mobile View - Cards -->
            <div class="lg:hidden space-y-3 mb-6">
                @foreach($products as $index => $product)
                    <div class="bg-white border rounded-lg p-4 shadow-sm border-gray-200 transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: {{ $index * 0.05 }}s">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-lg">
                                    <a href="{{ route('products.show', $product) }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                            </div>
                            <div class="ml-4">
                                @if($product->isOutOfStock())
                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                        Out of Stock
                                    </span>
                                @elseif($product->isLowStock())
                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Low Stock
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                        In Stock
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-500">Current Stock:</p>
                                <p class="font-medium">{{ $product->current_stock }} {{ $product->unit }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Min Level:</p>
                                <p class="font-medium">{{ $product->min_stock_level }} {{ $product->unit }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Avg Cost:</p>
                                <p class="font-medium">${{ number_format($product->avg_cost, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Total Value:</p>
                                <p class="font-medium text-primary-600">${{ number_format($product->current_stock * $product->avg_cost, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop View - Table -->
            <div class="hidden lg:block overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Level</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Cost</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $index => $product)
                                    <tr class="hover:bg-gray-50 transition-all duration-200 animate-fade-in" style="animation-delay: {{ $index * 0.03 }}s">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('products.show', $product) }}" class="text-primary-600 hover:text-primary-900 transition-colors font-medium">
                                                {{ $product->name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $product->current_stock }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $product->unit }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $product->min_stock_level }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            ${{ number_format($product->avg_cost, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-primary-600">
                                            ${{ number_format($product->current_stock * $product->avg_cost, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($product->isOutOfStock())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                                    Out of Stock
                                                </span>
                                            @elseif($product->isLowStock())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Low Stock
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    In Stock
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Totals Row -->
                                <tr class="bg-gray-50 font-semibold border-t-2 border-gray-300">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        Total ({{ $products->count() }} products)
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $products->sum('current_stock') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">-</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $products->sum('min_stock_level') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        ${{ number_format($products->avg('avg_cost'), 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-primary-600">
                                        ${{ number_format($totalStockValue, 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12 animate-fade-in">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-chart-line text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-lg mb-2">No products found</p>
                <p class="text-gray-400 text-sm">Try adjusting your filters to see more results</p>
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

        /* Enhanced pulse animation for critical alerts */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        /* Gradient background for value highlights */
        .text-primary-600 {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</x-app-layout>