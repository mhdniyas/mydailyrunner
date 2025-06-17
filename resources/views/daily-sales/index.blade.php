<x-admin-layout>
    <x-slot name="title">Daily Sales</x-slot>
    <x-slot name="subtitle">Today's Sales Summary</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 animate-fade-in">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-accent-600"></i>
                    Daily Sales Report
                </h3>
                <p class="text-sm text-gray-500 mt-1">{{ $date->format('l, F j, Y') }}</p>
                <p class="text-xs text-gray-500 mt-1">Shows sales data with stock check discrepancies and bag calculations</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('daily-stock-checks.create') }}" class="w-full sm:w-auto bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-all duration-200 transform hover:scale-105 text-center">
                    <i class="fas fa-clipboard-check mr-2"></i> New Stock Check
                </a>
                <a href="{{ route('sales.create') }}" class="w-full sm:w-auto bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105 text-center">
                    <i class="fas fa-plus mr-2"></i> New Sale
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
            <div class="bg-blue-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-blue-600 font-medium">Total Sales</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">{{ $totalSales }}</p>
                    </div>
                    <div class="bg-blue-100 p-2 rounded-full">
                        <i class="fas fa-receipt text-blue-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-green-600 font-medium">Total Revenue</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">₹{{ number_format($totalAmount, 2) }}</p>
                    </div>
                    <div class="bg-green-100 p-2 rounded-full">
                        <i class="fas fa-rupee-sign text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-yellow-600 font-medium">Amount Paid</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">₹{{ number_format($totalPaid, 2) }}</p>
                    </div>
                    <div class="bg-yellow-100 p-2 rounded-full">
                        <i class="fas fa-check-circle text-yellow-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-red-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.5s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-red-600 font-medium">Amount Due</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">₹{{ number_format($totalDue, 2) }}</p>
                    </div>
                    <div class="bg-red-100 p-2 rounded-full">
                        <i class="fas fa-clock text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend for metrics -->
        <div class="mb-6 bg-gray-50 p-3 rounded-lg shadow-sm border border-gray-200 animate-fade-in" style="animation-delay: 0.55s">
            <h4 class="font-medium text-gray-900 mb-2">Understanding the Metrics</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
                <div>
                    <span class="font-medium text-gray-700">Morning/Evening Stock:</span>
                    <span class="text-gray-600">Physical stock recorded during stock checks</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">System Stock:</span>
                    <span class="text-gray-600">Expected stock in the system</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Discrepancy:</span>
                    <span class="text-gray-600">Difference between physical and system stock</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Bag Average:</span>
                    <span class="text-gray-600">Average quantity per bag</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Est. Bags:</span>
                    <span class="text-gray-600">Estimated bags sold based on quantity and average</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Colors:</span>
                    <span class="text-red-600">Negative</span> / 
                    <span class="text-green-600">Positive</span> discrepancies
                </div>
            </div>
        </div>

        <!-- Category-wise Sales -->
        <div class="space-y-6">
            @foreach($categoryTotals as $categoryId => $category)
                <div class="bg-white border rounded-lg shadow-sm animate-fade-in" style="animation-delay: {{ 0.6 + ($loop->index * 0.1) }}s">
                    <div class="border-b bg-gray-50 px-4 py-3 rounded-t-lg">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                            <h4 class="text-lg font-semibold text-gray-900">{{ $category['name'] }}</h4>
                            <div class="flex flex-wrap gap-3 mt-2 sm:mt-0">
                                <div class="bg-primary-50 px-3 py-1 rounded-full">
                                    <span class="text-sm text-primary-600 font-medium">₹{{ number_format($category['amount'], 2) }}</span>
                                </div>
                                <div class="bg-gray-100 px-3 py-1 rounded-full">
                                    <span class="text-sm text-gray-700 font-medium">{{ $category['quantity'] }} items</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Sold</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Morning Stock</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evening Stock</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System Stock</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Morning Disc.</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evening Disc.</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bag Avg</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Est. Bags</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($category['products'] as $productId => $product)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <span class="font-medium text-gray-900">{{ $product['name'] }}</span>
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm">
                                                {{ $product['quantity'] }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-primary-600 font-medium">
                                                ₹{{ number_format($product['amount'], 2) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm">
                                                {{ $product['morning_stock'] ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm">
                                                {{ $product['evening_stock'] ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm">
                                                {{ $product['system_stock'] ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm {{ isset($product['morning_discrepancy']) && $product['morning_discrepancy'] < 0 ? 'text-red-600' : (isset($product['morning_discrepancy']) && $product['morning_discrepancy'] > 0 ? 'text-green-600' : '') }}">
                                                {{ isset($product['morning_discrepancy']) ? number_format($product['morning_discrepancy'], 2) : '-' }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm {{ isset($product['evening_discrepancy']) && $product['evening_discrepancy'] < 0 ? 'text-red-600' : (isset($product['evening_discrepancy']) && $product['evening_discrepancy'] > 0 ? 'text-green-600' : '') }}">
                                                {{ isset($product['evening_discrepancy']) ? number_format($product['evening_discrepancy'], 2) : '-' }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm">
                                                {{ isset($product['bag_average']) ? number_format($product['bag_average'], 2) : '-' }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium {{ isset($product['calculated_bags']) ? 'text-accent-600' : '' }}">
                                                {{ isset($product['calculated_bags']) ? number_format($product['calculated_bags'], 1) : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-semibold">
                                            Total
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-semibold">
                                            {{ $category['quantity'] }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-semibold text-primary-600">
                                            ₹{{ number_format($category['amount'], 2) }}
                                        </td>
                                        <td colspan="7" class="px-3 py-2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Sales Message -->
        @if($totalSales === 0)
            <div class="text-center py-12 animate-fade-in" style="animation-delay: 0.6s">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-chart-line text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-lg mb-2">No sales recorded today</p>
                <p class="text-gray-400 text-sm mb-6">Start your day by recording your first sale</p>
                <a href="{{ route('sales.create') }}" class="inline-block bg-accent-600 text-white px-6 py-3 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Create New Sale
                </a>
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

        /* Table styling for better responsiveness */
        .overflow-x-auto {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Color coding for discrepancy values */
        .text-red-600 {
            color: #dc2626;
        }

        .text-green-600 {
            color: #16a34a;
        }

        .text-accent-600 {
            color: #0284c7; /* Sky blue */
        }

        /* Responsive font sizes */
        @media (max-width: 640px) {
            .px-3 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            table {
                font-size: 0.75rem;
            }
        }
    </style>
</x-admin-layout>
