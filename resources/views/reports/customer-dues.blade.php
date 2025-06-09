<x-app-layout>
    <x-slot name="title">Customer Dues Report</x-slot>
    <x-slot name="subtitle">Track pending customer payments</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <form action="{{ route('reports.customer-dues') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <select name="customer_id" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                        <option value="">All Customers</option>
                        @foreach($allCustomers as $customer)
                            <option value="{{ $customer->id }}" {{ $selectedCustomer == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </form>
            </div>
            <div>
                <a href="{{ route('export.customer-dues') }}?customer_id={{ $selectedCustomer }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-file-export mr-2"></i> Export
                </a>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="bg-primary-50 p-4 rounded-md mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-primary-600 font-medium">Total Due Amount</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($totalDueAmount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 font-medium">Customers with Dues</p>
                    <p class="text-2xl font-bold mt-1">{{ $customers->count() }}</p>
                </div>
            </div>
        </div>

        @if($customers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Sales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Due</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customers as $customer)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $customer->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $customer->phone ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $customer->address ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $customer->sales_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-red-600">
                                    {{ number_format($customer->total_due, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button type="button" class="text-primary-600 hover:text-primary-900 view-details-btn" data-customer-id="{{ $customer->id }}">
                                        <i class="fas fa-eye mr-1"></i> View Details
                                    </button>
                                </td>
                            </tr>
                            <tr class="customer-details bg-gray-50 hidden" id="customer-{{ $customer->id }}-details">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale #</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($customer->pendingSales as $sale)
                                                    <tr>
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ $sale->id }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ $sale->sale_date->format('M d, Y') }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ number_format($sale->total_amount, 2) }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap">{{ number_format($sale->paid_amount, 2) }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap font-medium text-red-600">{{ number_format($sale->due_amount, 2) }}</td>
                                                        <td class="px-4 py-2 whitespace-nowrap">
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
                                                        <td class="px-4 py-2 whitespace-nowrap">
                                                            <a href="{{ route('customer-payments.create', $sale) }}" class="text-accent-600 hover:text-accent-900">
                                                                <i class="fas fa-dollar-sign mr-1"></i> Pay
                                                            </a>
                                                        </td>
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
                {{ $customers->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No customers with pending payments found.</p>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle customer details
            const viewDetailsBtns = document.querySelectorAll('.view-details-btn');
            
            viewDetailsBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const customerId = this.getAttribute('data-customer-id');
                    const detailsRow = document.getElementById(`customer-${customerId}-details`);
                    
                    if (detailsRow.classList.contains('hidden')) {
                        // Hide all other details first
                        document.querySelectorAll('.customer-details').forEach(row => {
                            row.classList.add('hidden');
                        });
                        
                        // Show this customer's details
                        detailsRow.classList.remove('hidden');
                        this.innerHTML = '<i class="fas fa-eye-slash mr-1"></i> Hide Details';
                    } else {
                        detailsRow.classList.add('hidden');
                        this.innerHTML = '<i class="fas fa-eye mr-1"></i> View Details';
                    }
                });
            });
        });
    </script>
</x-app-layout>