<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="subtitle">Shop overview and analytics</x-slot>

    <!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
    <!-- Stock Value Card -->
    <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-purple-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        
        <!-- Shimmer Effect -->
        <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
        
        <div class="relative p-6">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-white shadow-lg shadow-blue-200 group-hover:shadow-blue-300 transition-shadow duration-300">
                        <i class="fas fa-boxes text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                    </div>
                    <!-- Pulse Ring -->
                    <div class="absolute inset-0 rounded-2xl bg-blue-400 opacity-20 group-hover:animate-ping"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 mb-1 group-hover:text-blue-600 transition-colors duration-300">
                        Stock Value
                    </p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 group-hover:text-blue-700 transition-colors duration-300 truncate">
                        ₹{{ number_format($totalStockValue, 2) }}
                    </p>
                    <div class="mt-2 flex items-center text-xs text-green-600">
                        <i class="fas fa-chart-line mr-1"></i>
                        <span>Total Inventory</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Corner Decoration -->
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-blue-200/20 to-blue-400/30 rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:scale-125 transition-transform duration-500"></div>
    </div>
    
    <!-- Today's Sales Card -->
    <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 to-green-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        
        <!-- Shimmer Effect -->
        <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
        
        <div class="relative p-6">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-600 text-white shadow-lg shadow-emerald-200 group-hover:shadow-emerald-300 transition-shadow duration-300">
                        <i class="fas fa-receipt text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                    </div>
                    <!-- Pulse Ring -->
                    <div class="absolute inset-0 rounded-2xl bg-emerald-400 opacity-20 group-hover:animate-ping"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 mb-1 group-hover:text-emerald-600 transition-colors duration-300">
                        Today's Sales
                    </p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 group-hover:text-emerald-700 transition-colors duration-300 truncate">
                        ₹{{ number_format($todaySales, 2) }}
                    </p>
                    <div class="mt-2 flex items-center text-xs text-emerald-600">
                        <i class="fas fa-calendar-day mr-1"></i>
                        <span>{{ date('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Corner Decoration -->
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-emerald-200/20 to-green-400/30 rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:scale-125 transition-transform duration-500"></div>
    </div>
    
    <!-- Cash In Card -->
    <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-green-50/50 to-emerald-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        
        <!-- Shimmer Effect -->
        <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
        
        <div class="relative p-6">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-green-400 to-green-600 text-white shadow-lg shadow-green-200 group-hover:shadow-green-300 transition-shadow duration-300">
                        <i class="fas fa-arrow-down text-2xl group-hover:animate-bounce transition-transform duration-300"></i>
                    </div>
                    <!-- Pulse Ring -->
                    <div class="absolute inset-0 rounded-2xl bg-green-400 opacity-20 group-hover:animate-ping"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 mb-1 group-hover:text-green-600 transition-colors duration-300">
                        Cash In
                    </p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 group-hover:text-green-700 transition-colors duration-300 truncate">
                        ₹{{ number_format($cashIn, 2) }}
                    </p>
                    <div class="mt-2 flex items-center text-xs text-green-600">
                        <i class="fas fa-plus-circle mr-1"></i>
                        <span>Inflow</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Corner Decoration -->
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-green-200/20 to-green-400/30 rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:scale-125 transition-transform duration-500"></div>
    </div>
    
    <!-- Cash Out Card -->
    <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-red-50/50 to-pink-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        
        <!-- Shimmer Effect -->
        <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
        
        <div class="relative p-6">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-red-400 to-red-600 text-white shadow-lg shadow-red-200 group-hover:shadow-red-300 transition-shadow duration-300">
                        <i class="fas fa-arrow-up text-2xl group-hover:animate-bounce transition-transform duration-300"></i>
                    </div>
                    <!-- Pulse Ring -->
                    <div class="absolute inset-0 rounded-2xl bg-red-400 opacity-20 group-hover:animate-ping"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 mb-1 group-hover:text-red-600 transition-colors duration-300">
                        Cash Out
                    </p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 group-hover:text-red-700 transition-colors duration-300 truncate">
                        ₹{{ number_format($cashOut, 2) }}
                    </p>
                    <div class="mt-2 flex items-center text-xs text-red-600">
                        <i class="fas fa-minus-circle mr-1"></i>
                        <span>Outflow</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Corner Decoration -->
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-red-200/20 to-red-400/30 rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:scale-125 transition-transform duration-500"></div>
    </div>
</div>

<style>
    /* Animation keyframes */
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    
    /* Responsive text sizing */
    @media (max-width: 640px) {
        .group .text-xl {
            font-size: 1.125rem; /* Slightly smaller on mobile */
        }
    }
    
    /* Ensure proper spacing on mobile */
    @media (max-width: 480px) {
        .group {
            margin-bottom: 0.5rem;
        }
        
        .group .p-6 {
            padding: 1rem;
        }
        
        .group .space-x-4 > * + * {
            margin-left: 0.75rem;
        }
    }
</style>
    <!-- Product Inventory Cards -->
<div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl p-6 mb-6 border border-gray-100 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-r from-blue-50/30 to-purple-50/30 opacity-50"></div>
    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100/20 to-purple-100/20 rounded-full -translate-y-16 translate-x-16"></div>
    
    <div class="relative z-10">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-boxes text-white text-lg"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                    Product Inventory
                </h3>
            </div>
            <a href="{{ route('products.index') }}" class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span>View All</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $products = $allProducts->take(6);
                
                // Function to get initials from product name
                function getInitials($name) {
                    $words = explode(' ', trim($name));
                    if (count($words) == 1) {
                        return $name;
                    }
                    $initials = '';
                    foreach ($words as $word) {
                        $initials .= strtoupper(substr($word, 0, 1));
                    }
                    return $initials;
                }
            @endphp
            
            @foreach($products as $index => $product)
                @php
                    $lastDiscrepancy = isset($productDiscrepancies[$product->id]) ? $productDiscrepancies[$product->id]->first() : null;
                    $isLowStock = $product->current_stock <= $product->min_stock_level;
                    $displayName = getInitials($product->name);
                @endphp
                
                <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden"
                     style="animation: slideUp 0.6s ease-out {{ $index * 0.1 }}s both;">
                    
                    <!-- Card Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent to-gray-50/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <!-- Shimmer Effect -->
                    <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                    
                    <div class="relative p-6">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br {{ $isLowStock ? 'from-red-400 to-red-600' : 'from-green-400 to-blue-500' }} rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                    {{ $displayName }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-lg group-hover:text-blue-600 transition-colors duration-300" title="{{ $product->name }}">
                                        {{ $displayName }}
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $product->unit }}</p>
                                </div>
                            </div>
                            
                            <div class="relative">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300 {{ $isLowStock ? 'bg-red-100 text-red-800 shadow-red-200' : 'bg-green-100 text-green-800 shadow-green-200' }} shadow-lg">
                                    <div class="w-2 h-2 rounded-full {{ $isLowStock ? 'bg-red-500 animate-pulse' : 'bg-green-500' }} mr-2"></div>
                                    {{ $isLowStock ? 'Low Stock' : 'In Stock' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Stock Information -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-4 group-hover:bg-gray-100 transition-colors duration-300">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Stock:</span>
                                <span class="text-lg font-bold {{ $isLowStock ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($product->current_stock) }} {{ $product->unit }}
                                </span>
                            </div>
                            
                            <!-- Stock Level Indicator -->
                            <div class="mt-3">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Stock Level</span>
                                    <span>Min: {{ $product->min_stock_level }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    @php
                                        $percentage = $product->min_stock_level > 0 ? min(($product->current_stock / $product->min_stock_level) * 100, 100) : 100;
                                    @endphp
                                    <div class="h-2 rounded-full transition-all duration-1000 {{ $isLowStock ? 'bg-gradient-to-r from-red-400 to-red-600' : 'bg-gradient-to-r from-green-400 to-blue-500' }}"
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Last Discrepancy -->
                        @if($lastDiscrepancy)
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 mb-4 border border-blue-100">
                                <p class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-chart-line mr-2 text-blue-500"></i>
                                    Last Discrepancy:
                                </p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-600">{{ $lastDiscrepancy->created_at->format('M d, Y') }}</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $lastDiscrepancy->discrepancy > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas {{ $lastDiscrepancy->discrepancy > 0 ? 'fa-arrow-up text-green-500' : 'fa-arrow-down text-red-500' }} mr-1"></i>
                                        {{ $lastDiscrepancy->discrepancy > 0 ? '+' : '' }}{{ $lastDiscrepancy->discrepancy }} {{ $product->unit }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-xl p-4 mb-4 border border-gray-200">
                                <p class="text-sm text-gray-500 text-center flex items-center justify-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    No Discrepancy Recorded
                                </p>
                            </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="flex space-x-3">
                            <a href="{{ route('products.show', $product) }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 group">
                                <i class="fas fa-eye mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                                Details
                            </a>
                            
                            <a href="{{ route('stock-ins.create') }}?product_id={{ $product->id }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 group">
                                <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                                Add Stock
                            </a>
                        </div>
                    </div>
                    
                    <!-- Corner Decoration -->
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br {{ $isLowStock ? 'from-red-400/10 to-red-600/20' : 'from-blue-400/10 to-purple-600/20' }} rounded-bl-full transform translate-x-10 -translate-y-10 group-hover:scale-110 transition-transform duration-500"></div>
                </div>
            @endforeach
        </div>
    </div>
    
    <style>
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .group:hover .fa-eye {
            animation: pulse 1s infinite;
        }
        
        .group:hover .fa-plus-circle {
            animation: bounce 1s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
    </style>
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
                                    CS: {{ $product->current_stock }} {{ $product->unit }}
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