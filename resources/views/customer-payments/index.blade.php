<x-app-layout>
    <x-slot name="title">Customer Payments</x-slot>
    <x-slot name="subtitle">Manage pending payments and dues</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 animate-fade-in">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-hand-holding-usd mr-2 text-green-600"></i>
                    Customer Payments
                </h3>
                <p class="text-sm text-gray-500 mt-1">Track and collect pending customer payments</p>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="mb-6 animate-fade-in" style="animation-delay: 0.1s">
            <form action="{{ route('customer-payments.index') }}" method="GET" class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                        <select name="customer_id" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-green-400">
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $selectedCustomer == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" name="from_date" value="{{ $from_date }}" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-green-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" name="to_date" value="{{ $to_date }}" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-green-400">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-all duration-200 transform hover:scale-105 text-sm">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        @if($pendingSales->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                <div class="bg-red-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-red-600 font-medium">Pending Sales</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">{{ $pendingSales->total() }}</p>
                        </div>
                        <div class="bg-red-100 p-2 rounded-full">
                            <i class="fas fa-exclamation-circle text-red-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-yellow-600 font-medium">Total Due</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">₹{{ number_format($pendingSales->sum('due_amount'), 2) }}</p>
                        </div>
                        <div class="bg-yellow-100 p-2 rounded-full">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-green-600 font-medium">Partial Payments</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">{{ $pendingSales->where('status', 'advance')->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-2 rounded-full">
                            <i class="fas fa-chart-pie text-green-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.5s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-blue-600 font-medium">Customers</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">{{ $pendingSales->pluck('customer_id')->unique()->count() }}</p>
                        </div>
                        <div class="bg-blue-100 p-2 rounded-full">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($pendingSales->count() > 0)
            <!-- Mobile View - Cards -->
            <div class="lg:hidden space-y-3 mb-6">
                @foreach($pendingSales as $index => $sale)
                    <div class="bg-white border rounded-lg p-4 shadow-sm {{ $sale->due_amount > 0 ? 'border-red-200 bg-red-50' : 'border-gray-200' }} transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: {{ ($index * 0.05) + 0.6 }}s">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-lg flex items-center">
                                    <span class="bg-primary-100 text-primary-600 px-2 py-1 rounded text-xs mr-2">
                                        #{{ $sale->id }}
                                    </span>
                                    {{ $sale->customer->name }}
                                </h3>
                                <p class="text-sm text-gray-500">{{ $sale->sale_date->format('M d, Y') }}</p>
                            </div>
                            <div class="ml-4">
                                @if($sale->status === 'advance')
                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Partial
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-3 text-sm mb-3">
                            <div>
                                <p class="text-gray-500">Total:</p>
                                <p class="font-medium text-primary-600">₹{{ number_format($sale->total_amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Paid:</p>
                                <p class="font-medium text-green-600">₹{{ number_format($sale->paid_amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Due:</p>
                                <p class="font-bold {{ $sale->due_amount > 0 ? 'text-red-600' : 'text-gray-600' }}">₹{{ number_format($sale->due_amount, 2) }}</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('sales.show', $sale) }}" class="text-primary-600 hover:text-primary-900 p-2 rounded-md hover:bg-primary-50 transition-colors" title="View Sale">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('customer-payments.create', $sale) }}" class="bg-green-600 text-white px-3 py-2 rounded-md hover:bg-green-700 transition-colors text-sm">
                                <i class="fas fa-rupee-sign mr-1"></i>Pay
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop View - Table -->
            <div class="hidden lg:block overflow-x-auto animate-fade-in" style="animation-delay: 0.6s">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale #</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendingSales as $index => $sale)
                                    <tr class="{{ $sale->due_amount > 0 ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }} transition-all duration-200 animate-fade-in" style="animation-delay: {{ ($index * 0.03) + 0.7 }}s">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <div class="flex flex-col">
                                                <span class="font-medium">{{ $sale->sale_date->format('M d, Y') }}</span>
                                                <span class="text-xs text-gray-500">{{ $sale->sale_date->format('l') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="bg-primary-100 text-primary-800 px-2 py-1 rounded text-xs font-medium">
                                                #{{ $sale->id }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $sale->customer->name }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-primary-600">
                                            ₹{{ number_format($sale->total_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-green-600">
                                            ₹{{ number_format($sale->paid_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold {{ $sale->due_amount > 0 ? 'text-red-600' : 'text-gray-600' }}">
                                            ₹{{ number_format($sale->due_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($sale->status === 'advance')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Partial
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('sales.show', $sale) }}" class="text-primary-600 hover:text-primary-900 p-1 rounded hover:bg-primary-50 transition-all duration-200" title="View Sale">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('customer-payments.create', $sale) }}" class="bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700 transition-all duration-200 transform hover:scale-105 text-xs">
                                                    <i class="fas fa-rupee-sign mr-1"></i>Pay
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Summary Row -->
                                <tr class="bg-gray-50 font-semibold border-t-2 border-gray-300">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm" colspan="3">
                                        Total ({{ $pendingSales->count() }} pending sales)
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-primary-600">
                                        ₹{{ number_format($pendingSales->sum('total_amount'), 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600">
                                        ₹{{ number_format($pendingSales->sum('paid_amount'), 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600">
                                        ₹{{ number_format($pendingSales->sum('due_amount'), 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm" colspan="2">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                {{ $pendingSales->links() }}
            </div>
        @else
            <div class="text-center py-12 animate-fade-in" style="animation-delay: 0.6s">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-check-circle text-2xl text-green-400"></i>
                </div>
                <p class="text-gray-500 text-lg mb-2">No pending payments found</p>
                <p class="text-gray-400 text-sm mb-6">All customer payments are up to date!</p>
                <a href="{{ route('sales.index') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-cash-register mr-2"></i>View All Sales
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

        /* Enhanced form styling */
        input:focus, select:focus {
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        /* Status badge animations */
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

        /* Table hover enhancement */
        .hover\:bg-gray-50:hover {
            background-color: #f9fafb;
        }

        .hover\:bg-red-100:hover {
            background-color: #fee2e2;
        }

        /* Action button hover effects */
        .hover\:bg-primary-50:hover {
            background-color: #eff6ff;
        }

        /* Enhanced responsiveness for very small screens */
        @media (max-width: 640px) {
            .text-xl {
                font-size: 1.125rem;
            }
            
            .text-2xl {
                font-size: 1.5rem;
            }
        }

        /* Enhanced status indicators */
        .bg-green-100 {
            background-color: #dcfce7;
        }

        .bg-yellow-100 {
            background-color: #fef3c7;
        }

        .bg-red-100 {
            background-color: #fee2e2;
        }

        /* Currency highlighting */
        .text-primary-600 {
            color: #2563eb;
        }

        .text-green-600 {
            color: #16a34a;
        }

        .text-red-600 {
            color: #dc2626;
        }

        /* Enhanced border for overdue payments */
        .border-red-200 {
            border-color: #fecaca;
        }

        .bg-red-50 {
            background-color: #fef2f2;
        }
    </style>
</x-app-layout>