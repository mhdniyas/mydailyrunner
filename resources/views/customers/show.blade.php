<x-admin-layout>
    <x-slot name="title">Customer Details</x-slot>
    <x-slot name="subtitle">View customer information and transaction history</x-slot>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Customer Information Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div>
                    <h2 class="text-2xl font-semibold text-primary-900">{{ $customer->name }}</h2>
                    <div class="mt-4 space-y-2">
                        @if($customer->phone)
                            <p class="flex items-center text-primary-600">
                                <i class="fas fa-phone mr-2"></i>
                                {{ $customer->phone }}
                            </p>
                        @endif
                        
                        @if($customer->address)
                            <p class="flex items-center text-primary-600">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $customer->address }}
                            </p>
                        @endif
                        
                        @if($customer->ration_card_number)
                            <p class="flex items-center text-primary-600">
                                <i class="fas fa-id-card mr-2"></i>
                                Ration Card: {{ $customer->ration_card_number }} 
                                @if($customer->card_type)
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ 
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
                            </p>
                        @endif
                        
                        @if($customer->notes)
                            <div class="mt-3">
                                <h4 class="text-sm font-medium text-primary-800">Notes:</h4>
                                <p class="text-primary-600 mt-1">{{ $customer->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="mt-6 md:mt-0 flex flex-col items-start md:items-end space-y-3">
                    <div class="flex flex-col items-start md:items-end">
                        <span class="text-sm text-primary-500">Total Purchases</span>
                        <span class="text-xl font-semibold text-primary-900">₹{{ number_format($totalPurchases, 2) }}</span>
                    </div>
                    
                    <div class="flex flex-col items-start md:items-end">
                        <span class="text-sm text-primary-500">Outstanding Dues</span>
                        <span class="text-xl font-semibold {{ $totalDues > 0 ? 'text-red-600' : 'text-green-600' }}">
                            ₹{{ number_format($totalDues, 2) }}
                        </span>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('customers.edit', $customer) }}" class="btn-secondary">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        
                        @if($customer->sales()->count() === 0)
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transaction History -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-primary-900 mb-4">Transaction History</h3>
            
            @if($sales->isEmpty())
                <div class="text-center py-6">
                    <i class="fas fa-receipt text-4xl text-primary-200 mb-3"></i>
                    <p class="text-primary-600">No transactions found for this customer.</p>
                    <a href="{{ route('sales.create') }}" class="btn-primary mt-4">
                        <i class="fas fa-plus mr-1"></i> New Sale
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
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
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-primary-600">
                                        {{ $sale->sale_date->format('d M, Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-primary-600">
                                        #{{ $sale->invoice_number }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-primary-600">
                                        ₹{{ number_format($sale->total_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full {{ 
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
                                    <td class="px-4 py-3 whitespace-nowrap text-sm {{ $sale->due_amount > 0 ? 'text-red-600 font-medium' : 'text-green-600' }}">
                                        ₹{{ number_format($sale->due_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-primary-500">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('sales.show', $sale) }}" class="text-primary-600 hover:text-primary-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($sale->due_amount > 0)
                                                <a href="{{ route('customer-payments.create', $sale) }}" class="text-accent-600 hover:text-accent-800">
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
                
                <div class="mt-4">
                    {{ $sales->links() }}
                </div>
                
                <div class="mt-6 flex justify-between">
                    <a href="{{ route('customers.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Customers
                    </a>
                    
                    <a href="{{ route('sales.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-1"></i> New Sale
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
