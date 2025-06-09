<x-app-layout>
    <x-slot name="title">Sale Details</x-slot>
    <x-slot name="subtitle">View sale transaction information</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sale Information -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Sale #{{ $sale->id }}</h3>
                <div class="flex items-center">
                    @if($sale->status !== 'paid')
                        <a href="{{ route('customer-payments.create', $sale) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 mr-2">
                            <i class="fas fa-dollar-sign mr-2"></i> Record Payment
                        </a>
                    @endif
                    <a href="{{ route('sales.edit', $sale) }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 mr-2">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" 
                                onclick="return confirm('Are you sure you want to delete this sale?')">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Customer</p>
                    <p class="font-medium">{{ $sale->customer->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Sale Date</p>
                    <p class="font-medium">{{ $sale->sale_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="font-medium">
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
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Created By</p>
                    <p class="font-medium">{{ $sale->user->name }}</p>
                </div>
                @if($sale->notes)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Notes</p>
                    <p class="font-medium">{{ $sale->notes }}</p>
                </div>
                @endif
            </div>

            <h4 class="font-semibold text-gray-900 mb-4">Sale Items</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sale->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('products.show', $item->product) }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $item->product->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $item->quantity }} {{ $item->product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($item->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($item->subtotal, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right font-medium">Total:</td>
                            <td class="px-6 py-3 font-bold">{{ number_format($sale->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
            
            <div class="space-y-4 mb-6">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Amount:</span>
                    <span class="font-medium">{{ number_format($sale->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Paid Amount:</span>
                    <span class="font-medium text-green-600">{{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Due Amount:</span>
                    <span class="font-medium {{ $sale->due_amount > 0 ? 'text-red-600' : '' }}">{{ number_format($sale->due_amount, 2) }}</span>
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between">
                        <span class="font-medium">Payment Status:</span>
                        <span>
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
            </div>

            <h4 class="font-semibold text-gray-900 mb-4">Payment History</h4>
            @if($sale->payments->count() > 0)
                <div class="space-y-4">
                    @foreach($sale->payments as $payment)
                        <div class="border-b border-gray-200 pb-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</span>
                                <span class="font-medium">{{ number_format($payment->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-sm">
                                    @if($payment->payment_method === 'cash')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Cash</span>
                                    @elseif($payment->payment_method === 'bank')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Bank</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Mobile</span>
                                    @endif
                                </span>
                                <span class="text-sm">By: {{ $payment->user->name }}</span>
                            </div>
                            @if($payment->reference)
                                <div class="mt-1 text-sm">Ref: {{ $payment->reference }}</div>
                            @endif
                            @if($payment->notes)
                                <div class="mt-1 text-sm italic">{{ $payment->notes }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No payment records found.</p>
            @endif

            @if($sale->status !== 'paid')
                <div class="mt-6">
                    <a href="{{ route('customer-payments.create', $sale) }}" class="block w-full bg-green-600 text-white text-center px-4 py-2 rounded-md hover:bg-green-700">
                        <i class="fas fa-dollar-sign mr-2"></i> Record Payment
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>