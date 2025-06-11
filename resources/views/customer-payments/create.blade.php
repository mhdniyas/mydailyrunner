<x-app-layout>
    <x-slot name="title">Record Payment</x-slot>
    <x-slot name="subtitle">Add payment for sale #{{ $sale->id }}</x-slot>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Payment Form -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 xl:col-span-2 transition-all duration-300 hover:shadow-xl animate-fade-in">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>
                            Record Payment
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Add payment for Sale #{{ $sale->id }}</p>
                    </div>
                    <div class="hidden sm:block">
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            Due: ₹{{ number_format($sale->due_amount, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('customer-payments.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="sale_id" value="{{ $sale->id }}">

                <!-- Payment Details Section -->
                <div class="bg-green-50 rounded-lg p-4">
                    <h4 class="text-md font-semibold text-green-900 mb-4 flex items-center">
                        <i class="fas fa-credit-card mr-2"></i>
                        Payment Details
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Payment Amount *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                <input type="number" name="amount" id="amount" value="{{ old('amount', $sale->due_amount) }}" 
                                       step="0.01" min="0.01" max="{{ $sale->due_amount }}" 
                                       class="w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-green-400" required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Maximum: ₹{{ number_format($sale->due_amount, 2) }}</p>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                            <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-green-400" required>
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                            <select name="payment_method" id="payment_method" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-green-400" required>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                                <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                                <option value="mobile" {{ old('payment_method') == 'mobile' ? 'selected' : '' }}>📱 Mobile Payment</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reference" class="block text-sm font-medium text-gray-700 mb-2">Reference (Optional)</label>
                            <input type="text" name="reference" id="reference" value="{{ old('reference') }}" 
                                   placeholder="Transaction ID, Check #, etc."
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-green-400">
                            @error('reference')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-sticky-note mr-2"></i>
                        Additional Notes
                    </h4>
                    <textarea name="notes" id="notes" rows="3" 
                              placeholder="Add any additional notes about this payment..."
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-green-400">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Summary -->
                <div class="bg-gradient-to-r from-green-50 to-blue-50 p-4 rounded-lg border border-green-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-calculator mr-2 text-green-600"></i>
                        Payment Summary
                    </h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-white p-3 rounded border">
                            <p class="text-gray-500">Payment Amount:</p>
                            <p class="font-bold text-green-600 text-lg" id="payment-preview">₹{{ number_format($sale->due_amount, 2) }}</p>
                        </div>
                        <div class="bg-white p-3 rounded border">
                            <p class="text-gray-500">Remaining Due:</p>
                            <p class="font-bold text-red-600 text-lg" id="remaining-due">₹0.00</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('sales.show', $sale) }}" class="w-full sm:w-auto bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400 transition-all duration-200 transform hover:scale-105 text-center">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>Record Payment
                    </button>
                </div>
            </form>
        </div>

        <!-- Sale Information -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl animate-fade-in" style="animation-delay: 0.2s">
            <!-- Sale Info Header -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-receipt mr-2 text-primary-600"></i>
                    Sale Information
                </h3>
            </div>
            
            <!-- Sale Details -->
            <div class="space-y-4 mb-6">
                <div class="bg-primary-50 p-3 rounded-lg border border-primary-200">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Sale Number:</span>
                        <span class="font-bold text-primary-600">#{{ $sale->id }}</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-gray-50 p-2 rounded">
                        <p class="text-gray-500 text-xs">Date:</p>
                        <p class="font-medium">{{ $sale->sale_date->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded">
                        <p class="text-gray-500 text-xs">Customer:</p>
                        <p class="font-medium">{{ $sale->customer->name }}</p>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="bg-gradient-to-r from-blue-50 to-green-50 p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-900 mb-3 text-sm">Financial Summary</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600 text-sm">Total Amount:</span>
                            <span class="font-bold text-primary-600">₹{{ number_format($sale->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 text-sm">Paid Amount:</span>
                            <span class="font-bold text-green-600">₹{{ number_format($sale->paid_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <span class="text-gray-600 text-sm font-medium">Due Amount:</span>
                            <span class="font-bold text-red-600 text-lg">₹{{ number_format($sale->due_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-3 rounded border">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-sm">Payment Status:</span>
                        <span>
                            @if($sale->status === 'advance')
                                <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Partial
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                    Pending
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="border-t pt-4">
                <h4 class="font-semibold text-gray-900 mb-4 flex items-center text-sm">
                    <i class="fas fa-history mr-2 text-gray-600"></i>
                    Payment History
                </h4>
                @if($sale->payments->count() > 0)
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach($sale->payments as $index => $payment)
                            <div class="bg-white border rounded-lg p-3 transition-all duration-200 hover:shadow-sm animate-fade-in" style="animation-delay: {{ ($index * 0.1) + 0.3 }}s">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <span class="text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</span>
                                            <span class="font-bold text-green-600">₹{{ number_format($payment->amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div>
                                        @if($payment->payment_method === 'cash')
                                            <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">💵 Cash</span>
                                        @elseif($payment->payment_method === 'bank')
                                            <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-blue-100 text-blue-800">🏦 Bank</span>
                                        @else
                                            <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-purple-100 text-purple-800">📱 Mobile</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $payment->user->name }}</span>
                                </div>
                                @if($payment->reference)
                                    <div class="mt-2 pt-2 border-t border-gray-100">
                                        <p class="text-xs text-gray-600">Ref: {{ $payment->reference }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-credit-card text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 text-sm">No payment records found</p>
                        <p class="text-gray-400 text-xs">This will be the first payment</p>
                    </div>
                @endif
            </div>
        </div>
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

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }

        /* Custom hover effects */
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }

        /* Loading state for buttons */
        button:active {
            transform: scale(0.95);
        }

        /* Enhanced form styling */
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        /* Gradient backgrounds */
        .bg-gradient-to-r {
            background: linear-gradient(to right, var(--tw-gradient-stops));
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

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .text-lg {
                font-size: 1rem;
            }
        }

        /* Enhanced section styling */
        .bg-green-50 {
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
        }

        .bg-gray-50 {
            background-color: #f9fafb;
            border-left: 4px solid #6b7280;
        }

        /* Custom scrollbar for payment history */
        .overflow-y-auto::-webkit-scrollbar {
            width: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 2px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const paymentPreview = document.getElementById('payment-preview');
            const remainingDue = document.getElementById('remaining-due');
            const totalDue = {{ $sale->due_amount }};

            function updateSummary() {
                const paymentAmount = parseFloat(amountInput.value) || 0;
                const remaining = totalDue - paymentAmount;

                // Update payment preview with animation
                paymentPreview.style.transform = 'scale(1.1)';
                paymentPreview.textContent = '₹' + paymentAmount.toFixed(2);
                setTimeout(() => paymentPreview.style.transform = 'scale(1)', 200);

                // Update remaining due with animation
                remainingDue.style.transform = 'scale(1.1)';
                remainingDue.textContent = '₹' + remaining.toFixed(2);
                
                // Change color based on remaining amount
                if (remaining <= 0) {
                    remainingDue.className = 'font-bold text-green-600 text-lg';
                } else {
                    remainingDue.className = 'font-bold text-red-600 text-lg';
                }
                
                setTimeout(() => remainingDue.style.transform = 'scale(1)', 200);
            }

            // Update summary when amount changes
            amountInput.addEventListener('input', updateSummary);

            // Quick amount buttons functionality
            function addQuickAmountButtons() {
                const amountContainer = amountInput.parentElement.parentElement;
                const quickAmounts = document.createElement('div');
                quickAmounts.className = 'mt-2 flex flex-wrap gap-2';
                quickAmounts.innerHTML = `
                    <button type="button" onclick="setAmount(${totalDue})" class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full hover:bg-green-200 transition-colors">
                        Full Amount
                    </button>
                    <button type="button" onclick="setAmount(${totalDue / 2})" class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors">
                        Half
                    </button>
                `;
                amountContainer.appendChild(quickAmounts);
            }

            // Set amount function
            window.setAmount = function(amount) {
                amountInput.value = amount.toFixed(2);
                amountInput.style.transform = 'scale(1.05)';
                setTimeout(() => amountInput.style.transform = 'scale(1)', 200);
                updateSummary();
            };

            // Add quick amount buttons
            addQuickAmountButtons();

            // Initial summary update
            updateSummary();
        });
    </script>
</x-app-layout>