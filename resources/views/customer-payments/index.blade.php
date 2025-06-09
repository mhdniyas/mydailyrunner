<x-app-layout>
    <x-slot name="title">Customer Payments</x-slot>
    <x-slot name="subtitle">Manage pending payments and dues</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <form action="{{ route('customer-payments.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <select name="customer_id" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $selectedCustomer == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="from_date" value="{{ $from_date }}" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                    <input type="date" name="to_date" value="{{ $to_date }}" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </form>
            </div>
        </div>

        @if($pendingSales->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingSales as $sale)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $sale->sale_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $sale->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $sale->customer->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($sale->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($sale->paid_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium {{ $sale->due_amount > 0 ? 'text-red-600' : '' }}">
                                    {{ number_format($sale->due_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('sales.show', $sale) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customer-payments.create', $sale) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-dollar-sign"></i> Pay
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $pendingSales->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No pending payments found.</p>
            </div>
        @endif
    </div>
</x-app-layout>