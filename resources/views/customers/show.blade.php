<x-admin-layout>
    <x-slot name="title">Customer Details</x-slot>
    <x-slot name="subtitle">View customer information and transaction history</x-slot>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
        <!-- Customer Information Card -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
                <!-- Customer Info Section -->
                <div class="flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <h2 class="text-xl sm:text-2xl font-semibold text-primary-900 flex-1 min-w-0">
                            {{ $customer->name }}
                        </h2>
                        @if($customer->is_walk_in)
                            <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 flex-shrink-0">
                                Walk-in
                            </span>
                        @endif
                    </div>
                    
                    <div class="space-y-3">
                        @if($customer->phone)
                            <div class="flex items-center text-primary-600">
                                <i class="fas fa-phone text-gray-400 w-4 flex-shrink-0"></i>
                                <span class="ml-3 text-sm sm:text-base">{{ $customer->phone }}</span>
                                <a href="tel:{{ $customer->phone }}" 
                                   class="ml-2 text-primary-500 hover:text-primary-700 lg:hidden">
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            </div>
                        @endif
                        
                        @if($customer->address)
                            <div class="flex items-start text-primary-600">
                                <i class="fas fa-map-marker-alt text-gray-400 w-4 flex-shrink-0 mt-0.5"></i>
                                <span class="ml-3 text-sm sm:text-base leading-relaxed">{{ $customer->address }}</span>
                            </div>
                        @endif
                        
                        @if($customer->ration_card_number)
                            <div class="flex items-start text-primary-600">
                                <i class="fas fa-id-card text-gray-400 w-4 flex-shrink-0 mt-0.5"></i>
                                <div class="ml-3 text-sm sm:text-base">
                                    <span class="block sm:inline">Ration Card: {{ $customer->ration_card_number }}</span>
                                    @if($customer->card_type)
                                        <span class="mt-1 sm:mt-0 sm:ml-2 inline-block px-2 py-1 text-xs font-semibold rounded-full {{ 
                                            match($customer->card_type) {
                                                'AAY' => 'bg-yellow-100 text-yellow-800',
                                                'PHH' => 'bg-pink-100 text-pink-800',
                                                'NPS' => 'bg-blue-100 text-blue-800',
                                                'NPI' => 'bg-indigo-100 text-indigo-800',
                                                'NPNS' => 'bg-gray-100 text-gray-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            }
                                        }}">
                                            {{ $customer->card_type_display }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        @if($customer->notes)
                            <div class="mt-4 p-3 bg-gray-50 rounded-md">
                                <h4 class="text-sm font-medium text-primary-800 mb-2">Notes:</h4>
                                <p class="text-primary-600 text-sm leading-relaxed">{{ $customer->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Stats and Actions Section -->
                <div class="flex-shrink-0 lg:w-64">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 lg:grid-cols-1 gap-4 mb-4">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg">
                            <div class="text-center lg:text-left">
                                <span class="text-xs sm:text-sm text-blue-600 font-medium">Total Purchases</span>
                                <div class="text-lg sm:text-xl font-bold text-blue-900 mt-1">
                                    ₹{{ number_format($totalPurchases, 2) }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-{{ $totalDues > 0 ? 'red' : 'green' }}-50 to-{{ $totalDues > 0 ? 'red' : 'green' }}-100 p-4 rounded-lg">
                            <div class="text-center lg:text-left">
                                <span class="text-xs sm:text-sm text-{{ $totalDues > 0 ? 'red' : 'green' }}-600 font-medium">Outstanding Dues</span>
                                <div class="text-lg sm:text-xl font-bold text-{{ $totalDues > 0 ? 'red' : 'green' }}-900 mt-1">
                                    ₹{{ number_format($totalDues, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('customers.edit', $customer) }}" 
                           class="bg-accent-600 text-white px-4 py-2.5 rounded-md hover:bg-accent-700 text-center text-sm font-medium transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i> Edit Customer
                        </a>
                        
                        @if($customer->sales()->count() === 0)
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 text-white px-4 py-2.5 rounded-md hover:bg-red-700 text-sm font-medium transition-colors duration-200 flex items-center justify-center">
                                    <i class="fas fa-trash-alt mr-2"></i> Delete Customer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transaction History -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h3 class="text-lg sm:text-xl font-semibold text-primary-900">Transaction History</h3>
                @if(!$sales->isEmpty())
                    <a href="{{ route('sales.create') }}" 
                       class="bg-primary-600 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-md hover:bg-primary-700 text-xs sm:text-sm font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-plus mr-1"></i>
                        <span class="hidden sm:inline">New Sale</span>
                        <span class="sm:hidden">Add</span>
                    </a>
                @endif
            </div>
            
            @if($sales->isEmpty())
                <div class="text-center py-8 sm:py-12">
                    <div class="mx-auto w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-receipt text-gray-400 text-xl sm:text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h4>
                    <p class="text-gray-500 mb-6">This customer hasn't made any purchases yet.</p>
                    <a href="{{ route('sales.create') }}" 
                       class="bg-primary-600 text-white px-6 py-2.5 rounded-md hover:bg-primary-700 text-sm font-medium transition-colors duration-200 inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Create First Sale
                    </a>
                </div>
            @else
                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-primary-100">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Invoice</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Due</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-primary-100">
                            @foreach($sales as $sale)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-primary-600">
                                        {{ $sale->sale_date->format('d M, Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-primary-600 font-medium">
                                        #{{ $sale->invoice_number }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-primary-600 font-medium">
                                        ₹{{ number_format($sale->total_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ 
                                            match($sale->status) {
                                                'paid' => 'bg-green-100 text-green-800',
                                                'partial' => 'bg-yellow-100 text-yellow-800',
                                                'unpaid' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            }
                                        }}">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium {{ $sale->due_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        ₹{{ number_format($sale->due_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('sales.show', $sale) }}" 
                                               class="text-primary-600 hover:text-primary-900 p-1 hover:bg-primary-50 rounded transition-colors duration-150"
                                               title="View Sale">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($sale->due_amount > 0)
                                                <a href="{{ route('customer-payments.create', $sale) }}" 
                                                   class="text-green-600 hover:text-green-900 p-1 hover:bg-green-50 rounded transition-colors duration-150"
                                                   title="Add Payment">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden space-y-4">
                    @foreach($sales as $sale)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <!-- Transaction Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h4 class="text-sm font-semibold text-gray-900">#{{ $sale->invoice_number }}</h4>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ 
                                            match($sale->status) {
                                                'paid' => 'bg-green-100 text-green-800',
                                                'partial' => 'bg-yellow-100 text-yellow-800',
                                                'unpaid' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            }
                                        }}">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $sale->sale_date->format('d M, Y') }}</p>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-1 ml-2">
                                    <a href="{{ route('sales.show', $sale) }}" 
                                       class="text-primary-600 hover:text-primary-900 p-2 hover:bg-primary-50 rounded-md transition-colors duration-150">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($sale->due_amount > 0)
                                        <a href="{{ route('customer-payments.create', $sale) }}" 
                                           class="text-green-600 hover:text-green-900 p-2 hover:bg-green-50 rounded-md transition-colors duration-150">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Transaction Details -->
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Total Amount</span>
                                    <div class="font-semibold text-gray-900">₹{{ number_format($sale->total_amount, 2) }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500">Due Amount</span>
                                    <div class="font-semibold {{ $sale->due_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        ₹{{ number_format($sale->due_amount, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($sales->hasPages())
                    <div class="mt-6">
                        {{ $sales->links() }}
                    </div>
                @endif
                
                <!-- Bottom Action Buttons -->
                <div class="mt-6 flex flex-col sm:flex-row justify-between items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('customers.index') }}" 
                       class="bg-gray-300 text-gray-800 px-4 py-2.5 rounded-md hover:bg-gray-400 text-center text-sm font-medium transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Customers
                    </a>
                    
                    <a href="{{ route('sales.create') }}" 
                       class="bg-primary-600 text-white px-4 py-2.5 rounded-md hover:bg-primary-700 text-center text-sm font-medium transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i> New Sale
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Mobile-specific styles -->
    <style>
        @media (max-width: 1024px) {
            .container {
                max-width: 100%;
            }
        }
        
        /* Ensure phone numbers are clickable on mobile */
        @media (max-width: 1024px) {
            a[href^="tel:"] {
                color: inherit;
                text-decoration: none;
            }
        }
    </style>
</x-admin-layout>