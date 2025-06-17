<x-app-layout>
    <x-slot name="title">Sales</x-slot>
    <x-slot name="subtitle">Manage your sales transactions</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 animate-fade-in">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-cash-register mr-2 text-accent-600"></i>
                    Sales Transactions
                </h3>
                <p class="text-sm text-gray-500 mt-1">Track and manage all your sales activities</p>
            </div>
            <a href="{{ route('sales.create') }}" class="w-full sm:w-auto bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105 text-center">
                <i class="fas fa-plus mr-2"></i> New Sale
            </a>
        </div>

        <!-- Filter Section -->
        <div class="mb-6 animate-fade-in" style="animation-delay: 0.1s">
            <form action="{{ route('sales.index') }}" method="GET" class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border-gray-300 focus:border-accent-500 focus:ring-accent-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-accent-400">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="advance" {{ $status == 'advance' ? 'selected' : '' }}>Partial</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" name="from_date" value="{{ $from_date }}" class="w-full border-gray-300 focus:border-accent-500 focus:ring-accent-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-accent-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" name="to_date" value="{{ $to_date }}" class="w-full border-gray-300 focus:border-accent-500 focus:ring-accent-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-accent-400">
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
        @if($sales->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                <div class="bg-blue-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-blue-600 font-medium">Total Sales</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">{{ $sales->total() }}</p>
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
                            <p class="text-xl sm:text-2xl font-bold mt-1">₹{{ number_format($sales->sum('total_amount'), 2) }}</p>
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
                            <p class="text-xl sm:text-2xl font-bold mt-1">₹{{ number_format($sales->sum('paid_amount'), 2) }}</p>
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
                            <p class="text-xl sm:text-2xl font-bold mt-1">₹{{ number_format($sales->sum('due_amount'), 2) }}</p>
                        </div>
                        <div class="bg-red-100 p-2 rounded-full">
                            <i class="fas fa-clock text-red-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($sales->count() > 0)
            <!-- Mobile View - Cards -->
            <div class="lg:hidden space-y-3 mb-6">
                @foreach($sales as $index => $sale)
                    <div class="bg-white border rounded-lg p-4 shadow-sm border-gray-200 transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: {{ ($index * 0.05) + 0.6 }}s">
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
                                @if($sale->status === 'paid')
                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                @elseif($sale->status === 'advance')
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
                        
                        <!-- Product items -->
                        <div class="mb-3 bg-gray-50 p-2 rounded-md">
                            <p class="text-xs font-medium text-gray-500 mb-1">Products:</p>
                            <div class="max-h-24 overflow-y-auto">
                                @foreach($sale->items as $item)
                                    <div class="text-sm py-1 border-b border-gray-100 last:border-b-0">
                                        <span class="font-medium text-gray-900">{{ $item->product->name }}</span>
                                        <span class="text-xs text-gray-600 ml-1">({{ $item->quantity }} x ₹{{ number_format($item->price, 2) }})</span>
                                    </div>
                                @endforeach
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
                                <p class="font-medium {{ $sale->due_amount > 0 ? 'text-red-600' : 'text-gray-600' }}">₹{{ number_format($sale->due_amount, 2) }}</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-1 pt-3 border-t border-gray-100">
                            <a href="{{ route('sales.show', $sale) }}" class="text-primary-600 hover:text-primary-900 p-2 rounded-md hover:bg-primary-50 transition-colors" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('sales.edit', $sale) }}" class="text-accent-600 hover:text-accent-900 p-2 rounded-md hover:bg-accent-50 transition-colors" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($sale->status !== 'paid')
                                <a href="{{ route('customer-payments.create', $sale) }}" class="text-green-600 hover:text-green-900 p-2 rounded-md hover:bg-green-50 transition-colors" title="Add Payment">
                                    <i class="fas fa-rupee-sign"></i>
                                </a>
                            @endif
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-md hover:bg-red-50 transition-colors" onclick="return confirm('Are you sure you want to delete this sale?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sales as $index => $sale)
                                    <tr class="hover:bg-gray-50 transition-all duration-200 animate-fade-in" style="animation-delay: {{ ($index * 0.03) + 0.7 }}s">
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
                                        <
                                            <div class="max-h-20 overflow-y-auto">
                                                @foreach($sale->items as $item)
                                                    <div class="mb-1 last:mb-0">
                                                        <span class="font-medium text-gray-900">{{ $item->product->name }}</span>
                                                        <span class="text-xs text-gray-500">({{ $item->quantity }} x ₹{{ number_format($item->price, 2) }})</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-primary-600">
                                            ₹{{ number_format($sale->total_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-green-600">
                                            ₹{{ number_format($sale->paid_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium {{ $sale->due_amount > 0 ? 'text-red-600' : 'text-gray-600' }}">
                                            ₹{{ number_format($sale->due_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($sale->status === 'paid')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Paid
                                                </span>
                                            @elseif($sale->status === 'advance')
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
                                            <div class="flex space-x-1">
                                                <a href="{{ route('sales.show', $sale) }}" class="text-primary-600 hover:text-primary-900 p-1 rounded hover:bg-primary-50 transition-all duration-200" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('sales.edit', $sale) }}" class="text-accent-600 hover:text-accent-900 p-1 rounded hover:bg-accent-50 transition-all duration-200" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($sale->status !== 'paid')
                                                    <a href="{{ route('customer-payments.create', $sale) }}" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-all duration-200" title="Add Payment">
                                                        <i class="fas fa-rupee-sign"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-all duration-200" onclick="return confirm('Are you sure you want to delete this sale?')" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Summary Row -->
                                <tr class="bg-gray-50 font-semibold border-t-2 border-gray-300">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm" colspan="4">
                                        Total ({{ $sales->count() }} sales on this page)
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-primary-600">
                                        ₹{{ number_format($sales->sum('total_amount'), 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600">
                                        ₹{{ number_format($sales->sum('paid_amount'), 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600">
                                        ₹{{ number_format($sales->sum('due_amount'), 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm" colspan="2">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                {{ $sales->links() }}
            </div>
        @else
            <div class="text-center py-12 animate-fade-in" style="animation-delay: 0.6s">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-cash-register text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-lg mb-2">No sales found</p>
                <p class="text-gray-400 text-sm mb-6">Start recording your first sale transaction</p>
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
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

        /* Action button hover effects */
        .hover\:bg-primary-50:hover {
            background-color: #eff6ff;
        }

        .hover\:bg-accent-50:hover {
            background-color: #f0f9ff;
        }

        .hover\:bg-green-50:hover {
            background-color: #f0fdf4;
        }

        .hover\:bg-red-50:hover {
            background-color: #fef2f2;
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

        /* Product list styling */
        .max-h-20 {
            max-height: 5rem;
        }

        .max-h-24 {
            max-height: 6rem;
        }

        .overflow-y-auto {
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .overflow-y-auto::-webkit-scrollbar {
            width: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</x-app-layout>