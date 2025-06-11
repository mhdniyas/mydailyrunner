<x-app-layout>
    <x-slot name="title">Customer Dues Report</x-slot>
    <x-slot name="subtitle">Track pending customer payments</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Filter Form & Export Button -->
        <div class="flex flex-col gap-4 mb-6">
            <div class="w-full">
                <form action="{{ route('reports.customer-dues') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-center gap-2">
                    <select name="customer_id" class="w-full sm:w-auto border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400">
                        <option value="">All Customers</option>
                        @foreach($allCustomers as $customer)
                            <option value="{{ $customer->id }}" {{ $selectedCustomer == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="w-full sm:w-auto bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-all duration-200 transform hover:scale-105 text-sm flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </form>
            </div>
            <div class="w-full sm:w-auto flex justify-start">
                <a href="{{ route('export.customer-dues') }}?customer_id={{ $selectedCustomer }}" class="w-full sm:w-auto bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105 text-sm flex items-center justify-center">
                    <i class="fas fa-file-export mr-2"></i> Export
                </a>
            </div>
        </div>

        <!-- Enhanced Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
            <div class="bg-red-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-red-600 font-medium">Total Due Amount</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1 text-red-700">${{ number_format($totalDueAmount, 2) }}</p>
                    </div>
                    <div class="bg-red-100 p-2 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-primary-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-primary-600 font-medium">Customers with Dues</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">{{ $customers->count() }}</p>
                    </div>
                    <div class="bg-primary-100 p-2 rounded-full">
                        <i class="fas fa-users text-primary-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-yellow-600 font-medium">Pending Sales</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">{{ $customers->sum('sales_count') }}</p>
                    </div>
                    <div class="bg-yellow-100 p-2 rounded-full">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-blue-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-blue-600 font-medium">Avg Due per Customer</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">
                            ${{ $customers->count() > 0 ? number_format($totalDueAmount / $customers->count(), 2) : '0.00' }}
                        </p>
                    </div>
                    <div class="bg-blue-100 p-2 rounded-full">
                        <i class="fas fa-calculator text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>

        @if($customers->count() > 0)
            <!-- Mobile View - Cards -->
            <div class="lg:hidden space-y-3 mb-6">
                @foreach($customers as $index => $customer)
                    <div class="bg-white border rounded-lg p-4 shadow-sm border-gray-200 transition-all duration-300 hover:shadow-md animate-fade-in" style="animation-delay: {{ ($index * 0.05) + 0.4 }}s">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-lg">{{ $customer->name }}</h3>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-phone mr-1"></i>{{ $customer->phone ?? 'N/A' }}
                                </p>
                                @if($customer->address)
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $customer->address }}
                                </p>
                                @endif
                            </div>
                            <div class="ml-4 text-right">
                                <p class="text-lg font-bold text-red-600">${{ number_format($customer->total_due, 2) }}</p>
                                <p class="text-sm text-gray-500">{{ $customer->sales_count }} pending sales</p>
                            </div>
                        </div>
                        
                        <div class="pt-3 border-t border-gray-100">
                            <button type="button" class="w-full bg-primary-50 text-primary-600 px-4 py-2 rounded-md hover:bg-primary-100 transition-colors view-details-btn text-sm" data-customer-id="{{ $customer->id }}">
                                <i class="fas fa-eye mr-1"></i> View Details
                            </button>
                        </div>
                        
                        <!-- Mobile Sales Details -->
                        <div class="customer-details hidden mt-4 pt-4 border-t border-gray-200" id="customer-{{ $customer->id }}-details">
                            <h4 class="font-medium text-gray-900 mb-3">Pending Sales</h4>
                            <div class="space-y-3">
                                @foreach($customer->pendingSales as $sale)
                                    <div class="bg-gray-50 rounded-md p-3">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-medium text-gray-900">Sale #{{ $sale->id }}</p>
                                                <p class="text-sm text-gray-500">{{ $sale->sale_date->format('M d, Y') }}</p>
                                            </div>
                                            <div class="text-right">
                                                @if($sale->status === 'advance')
                                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Partial
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Pending
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                                            <div>
                                                <p class="text-gray-500">Total:</p>
                                                <p class="font-medium">${{ number_format($sale->total_amount, 2) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Paid:</p>
                                                <p class="font-medium">${{ number_format($sale->paid_amount, 2) }}</p>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-gray-500 text-sm">Due:</p>
                                                <p class="font-bold text-red-600">${{ number_format($sale->due_amount, 2) }}</p>
                                            </div>
                                            <a href="{{ route('customer-payments.create', $sale) }}" class="bg-accent-600 text-white px-3 py-1 rounded-md hover:bg-accent-700 transition-colors text-sm">
                                                <i class="fas fa-dollar-sign mr-1"></i> Pay
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop View - Table -->
            <div class="hidden lg:block overflow-x-auto animate-fade-in" style="animation-delay: 0.4s">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Sales</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Due</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($customers as $index => $customer)
                                    <tr class="hover:bg-gray-50 transition-all duration-200 animate-fade-in" style="animation-delay: {{ ($index * 0.03) + 0.5 }}s">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $customer->phone ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm max-w-[200px] truncate">
                                            {{ $customer->address ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $customer->sales_count }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap font-medium text-red-600">
                                            ${{ number_format($customer->total_due, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <button type="button" class="text-primary-600 hover:text-primary-900 transition-colors view-details-btn" data-customer-id="{{ $customer->id }}">
                                                <i class="fas fa-eye mr-1"></i> View Details
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="customer-details bg-gray-50 hidden transition-all duration-300" id="customer-{{ $customer->id }}-details">
                                        <td colspan="6" class="px-4 py-4">
                                            <div class="bg-white rounded-lg p-4">
                                                <h4 class="font-medium text-gray-900 mb-4">Pending Sales for {{ $customer->name }}</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale #</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach($customer->pendingSales as $sale)
                                                                <tr class="hover:bg-gray-50 transition-colors">
                                                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium">{{ $sale->id }}</td>
                                                                    <td class="px-3 py-2 whitespace-nowrap text-sm">{{ $sale->sale_date->format('M d, Y') }}</td>
                                                                    <td class="px-3 py-2 whitespace-nowrap text-sm">${{ number_format($sale->total_amount, 2) }}</td>
                                                                    <td class="px-3 py-2 whitespace-nowrap text-sm">${{ number_format($sale->paid_amount, 2) }}</td>
                                                                    <td class="px-3 py-2 whitespace-nowrap font-medium text-red-600">${{ number_format($sale->due_amount, 2) }}</td>
                                                                    <td class="px-3 py-2 whitespace-nowrap">
                                                                        @if($sale->status === 'advance')
                                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                                Partial
                                                                            </span>
                                                                        @else
                                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                                Pending
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-3 py-2 whitespace-nowrap">
                                                                        <a href="{{ route('customer-payments.create', $sale) }}" class="text-accent-600 hover:text-accent-900 transition-colors">
                                                                            <i class="fas fa-dollar-sign mr-1"></i> Pay
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Totals Row -->
                                <tr class="bg-gray-50 font-semibold border-t-2 border-gray-300">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm" colspan="3">
                                        Total ({{ $customers->count() }} customers)
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $customers->sum('sales_count') }} sales
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600">
                                        ${{ number_format($totalDueAmount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                {{ $customers->links() }}
            </div>
        @else
            <div class="text-center py-12 animate-fade-in" style="animation-delay: 0.4s">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-hand-holding-usd text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-lg mb-2">No customers with pending payments</p>
                <p class="text-gray-400 text-sm">All payments are up to date!</p>
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

        /* Enhanced gradient text effect for due amounts */
        .text-red-700 {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Smooth transitions for expandable content */
        .customer-details {
            transition: all 0.3s ease-in-out;
        }

        /* Enhanced nested table styling */
        .customer-details table {
            border-radius: 8px;
            overflow: hidden;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle customer details with enhanced animations
            const viewDetailsBtns = document.querySelectorAll('.view-details-btn');
            
            viewDetailsBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const customerId = this.getAttribute('data-customer-id');
                    const detailsRow = document.getElementById(`customer-${customerId}-details`);
                    
                    if (detailsRow.classList.contains('hidden')) {
                        // Hide all other details first with animation
                        document.querySelectorAll('.customer-details').forEach(row => {
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
                        document.querySelectorAll('.view-details-btn').forEach(button => {
                            button.innerHTML = '<i class="fas fa-eye mr-1"></i> View Details';
                        });
                        
                        // Show this customer's details with animation
                        setTimeout(() => {
                            detailsRow.classList.remove('hidden');
                            detailsRow.style.opacity = '0';
                            detailsRow.style.transform = 'translateY(10px)';
                            
                            requestAnimationFrame(() => {
                                detailsRow.style.transition = 'all 0.3s ease-out';
                                detailsRow.style.opacity = '1';
                                detailsRow.style.transform = 'translateY(0)';
                            });
                            
                            this.innerHTML = '<i class="fas fa-eye-slash mr-1"></i> Hide Details';
                        }, 250);
                    } else {
                        // Hide with animation
                        detailsRow.style.opacity = '0';
                        detailsRow.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            detailsRow.classList.add('hidden');
                            detailsRow.style.opacity = '';
                            detailsRow.style.transform = '';
                            detailsRow.style.transition = '';
                        }, 200);
                        this.innerHTML = '<i class="fas fa-eye mr-1"></i> View Details';
                    }
                });
            });

            // Add loading states to payment buttons
            document.querySelectorAll('a[href*="customer-payments.create"]').forEach(link => {
                link.addEventListener('click', function() {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...';
                    this.classList.add('opacity-75', 'pointer-events-none');
                });
            });
        });
    </script>
</x-app-layout>