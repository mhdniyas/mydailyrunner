<x-app-layout>
    <x-slot name="title">Record Payment</x-slot>
    <x-slot name="subtitle">Add payment for sale #{{ $sale->id }}</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Form -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <form action="{{ route('customer-payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="sale_id" value="{{ $sale->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Payment Amount</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount', $sale->due_amount) }}" 
                               step="0.01" min="0.01" max="{{ $sale->due_amount }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="mobile" {{ old('payment_method') == 'mobile' ? 'selected' : '' }}>Mobile Payment</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reference" class="block text-sm font-medium text-gray-700">Reference (Optional)</label>
                        <input type="text" name="reference" id="reference" value="{{ old('reference') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        @error('reference')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ route('sales.show', $sale) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 mr-2">
                        Cancel
                    </a>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Record Payment
                    </button>
                </div>
            </form>
        </div>

        <!-- Sale Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sale Information</h3>
            
            <div class="space-y-4 mb-6">
                <div class="flex justify-between">
                    <span class="text-gray-600">Sale #:</span>
                    <span class="font-medium">{{ $sale->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Date:</span>
                    <span class="font-medium">{{ $sale->sale_date->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Customer:</span>
                    <span class="font-medium">{{ $sale->customer->name }}</span>
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-medium">{{ number_format($sale->total_amount, 2) }}</span>
                    </div>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Paid Amount:</span>
                    <span class="font-medium text-green-600">{{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Due Amount:</span>
                    <span class="font-medium text-red-600">{{ number_format($sale->due_amount, 2) }}</span>
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between">
                        <span class="font-medium">Payment Status:</span>
                        <span>
                            @if($sale->status === 'advance')
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
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No payment records found.</p>
            @endif
        </div>
    </div>
</x-app-layout>