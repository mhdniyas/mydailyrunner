<x-app-layout>
    <x-slot name="title">Edit Sale</x-slot>
    <x-slot name="subtitle">Modify sale transaction</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Header Section -->
        <div class="mb-6 animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-edit mr-2 text-accent-600"></i>
                        Edit Sale #{{ $sale->id }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Modify existing sale transaction details</p>
                </div>
                <div class="hidden sm:block">
                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ 
                        $sale->status === 'paid' ? 'bg-green-100 text-green-800' : 
                        ($sale->status === 'advance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') 
                    }}">
                        Current: {{ ucfirst($sale->status) }}
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('sales.update', $sale) }}" method="POST" id="sale-form" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div class="animate-fade-in" style="animation-delay: 0.1s">
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Basic Information
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Customer *</label>
                            <select name="customer_id" id="customer_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400" required>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ (old('customer_id', $sale->customer_id) == $customer->id) ? 'selected' : '' }}>
                                        {{ $customer->name }} {{ $customer->is_walk_in ? '(Walk-in)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sale_date" class="block text-sm font-medium text-gray-700 mb-2">Sale Date *</label>
                            <input type="date" name="sale_date" id="sale_date" value="{{ old('sale_date', $sale->sale_date->format('Y-m-d')) }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400" required>
                            @error('sale_date')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sale Items Section -->
            <div class="animate-fade-in" style="animation-delay: 0.2s">
                <div class="bg-green-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-green-900 mb-4 flex items-center">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Sale Items
                    </h4>
                    
                    <div id="sale-items" class="space-y-4">
                        @if(old('product_id'))
                            {{-- If there are validation errors, show old input --}}
                            @foreach(old('product_id') as $index => $productId)
                                <div class="sale-item bg-white p-4 rounded-lg border border-gray-200 shadow-sm transition-all duration-300 hover:shadow-md">
                                    <!-- Mobile Layout -->
                                    <div class="block lg:hidden space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-700">Item {{ $index + 1 }}</span>
                                            <button type="button" class="remove-item text-red-500 hover:text-red-700 p-2" {{ $index == 0 ? 'style=display:none;' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                                            <select name="product_id[]" class="product-select w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" required>
                                                <option value="">Select a product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                            data-price="{{ $product->sale_price }}" 
                                                            data-stock="{{ $product->current_stock }}" 
                                                            data-unit="{{ $product->unit }}"
                                                            {{ $productId == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }} available)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                                <div class="flex items-center">
                                                    <input type="number" name="quantity[]" class="quantity-input flex-1 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" 
                                                           step="0.01" min="0.01" value="{{ old('quantity')[$index] ?? '' }}" required>
                                                    <span class="unit-display ml-2 text-gray-500 text-xs"></span>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                                    <input type="number" name="price[]" class="price-input w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" 
                                                           step="0.01" min="0" value="{{ old('price')[$index] ?? '' }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-primary-50 p-3 rounded border">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                                                <span class="subtotal-display text-lg font-bold text-primary-600">₹0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Desktop Layout -->
                                    <div class="hidden lg:grid lg:grid-cols-5 gap-4 items-end">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                                            <select name="product_id[]" class="product-select w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" required>
                                                <option value="">Select a product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                            data-price="{{ $product->sale_price }}" 
                                                            data-stock="{{ $product->current_stock }}" 
                                                            data-unit="{{ $product->unit }}"
                                                            {{ $productId == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }} available)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                            <div class="flex items-center">
                                                <input type="number" name="quantity[]" class="quantity-input flex-1 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" 
                                                       step="0.01" min="0.01" value="{{ old('quantity')[$index] ?? '' }}" required>
                                                <span class="unit-display ml-2 text-gray-500 text-sm"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                                <input type="number" name="price[]" class="price-input w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" 
                                                       step="0.01" min="0" value="{{ old('price')[$index] ?? '' }}" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                                            <div class="bg-primary-50 border border-primary-200 rounded-md p-2 text-center">
                                                <span class="subtotal-display font-bold text-primary-600">₹0.00</span>
                                            </div>
                                        </div>
                                        <div class="flex justify-center">
                                            <button type="button" class="remove-item text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors" {{ $index == 0 ? 'style=display:none;' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Show existing sale items --}}
                            @foreach($sale->items as $index => $item)
                                <div class="sale-item bg-white p-4 rounded-lg border border-gray-200 shadow-sm transition-all duration-300 hover:shadow-md">
                                    <!-- Mobile Layout -->
                                    <div class="block lg:hidden space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-700">Item {{ $index + 1 }}</span>
                                            <button type="button" class="remove-item text-red-500 hover:text-red-700 p-2" {{ $index == 0 ? 'style=display:none;' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                                            <select name="product_id[]" class="product-select w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" required>
                                                <option value="">Select a product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                            data-price="{{ $product->sale_price }}" 
                                                            data-stock="{{ $product->current_stock + ($product->id == $item->product_id ? $item->quantity : 0) }}" 
                                                            data-unit="{{ $product->unit }}"
                                                            {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} ({{ $product->current_stock + ($product->id == $item->product_id ? $item->quantity : 0) }} {{ $product->unit }} available)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                                <div class="flex items-center">
                                                    <input type="number" name="quantity[]" class="quantity-input flex-1 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" 
                                                           step="0.01" min="0.01" value="{{ $item->quantity }}" required>
                                                    <span class="unit-display ml-2 text-gray-500 text-xs">{{ $item->product->unit }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                                    <input type="number" name="price[]" class="price-input w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" 
                                                           step="0.01" min="0" value="{{ $item->price }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-primary-50 p-3 rounded border">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                                                <span class="subtotal-display text-lg font-bold text-primary-600">₹{{ number_format($item->quantity * $item->price, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Desktop Layout -->
                                    <div class="hidden lg:grid lg:grid-cols-5 gap-4 items-end">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                                            <select name="product_id[]" class="product-select w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" required>
                                                <option value="">Select a product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                            data-price="{{ $product->sale_price }}" 
                                                            data-stock="{{ $product->current_stock + ($product->id == $item->product_id ? $item->quantity : 0) }}" 
                                                            data-unit="{{ $product->unit }}"
                                                            {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} ({{ $product->current_stock + ($product->id == $item->product_id ? $item->quantity : 0) }} {{ $product->unit }} available)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                            <div class="flex items-center">
                                                <input type="number" name="quantity[]" class="quantity-input flex-1 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" 
                                                       step="0.01" min="0.01" value="{{ $item->quantity }}" required>
                                                <span class="unit-display ml-2 text-gray-500 text-sm">{{ $item->product->unit }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                                <input type="number" name="price[]" class="price-input w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" 
                                                       step="0.01" min="0" value="{{ $item->price }}" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                                            <div class="bg-primary-50 border border-primary-200 rounded-md p-2 text-center">
                                                <span class="subtotal-display font-bold text-primary-600">₹{{ number_format($item->quantity * $item->price, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex justify-center">
                                            <button type="button" class="remove-item text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors" {{ $index == 0 ? 'style=display:none;' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <button type="button" id="add-item" class="mt-4 w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i> Add Item
                    </button>
                </div>
            </div>

            <!-- Notes and Payment Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-fade-in" style="animation-delay: 0.3s">
                <!-- Notes -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-sticky-note mr-2"></i>
                        Notes
                    </h4>
                    <textarea name="notes" id="notes" rows="4" 
                              placeholder="Add any additional notes about this sale..."
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200">{{ old('notes', $sale->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Summary -->
                <div class="bg-gradient-to-r from-accent-50 to-primary-50 p-4 rounded-lg border border-accent-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-calculator mr-2 text-accent-600"></i>
                        Payment Summary
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-2 bg-white rounded border">
                            <span class="text-sm font-medium text-gray-700">Total Amount:</span>
                            <span class="text-lg font-bold text-primary-600" id="total-amount-display">₹{{ number_format($sale->total_amount, 2) }}</span>
                            <input type="hidden" name="total_amount" id="total-amount" value="{{ $sale->total_amount }}">
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded border">
                            <label for="paid_amount" class="text-sm font-medium text-gray-700">Paid Amount:</label>
                            <div class="relative w-32">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $sale->paid_amount) }}" 
                                       step="0.01" min="0" class="w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" required>
                            </div>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded border">
                            <span class="text-sm font-medium text-gray-700">Due Amount:</span>
                            <span class="text-lg font-semibold text-red-600" id="due-amount-display">₹{{ number_format($sale->total_amount - $sale->paid_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded border">
                            <span class="text-sm font-medium text-gray-700">Payment Status:</span>
                            <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full 
                                @if($sale->status == 'paid') bg-green-100 text-green-800
                                @elseif($sale->status == 'advance') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif" id="payment-status">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-200 animate-fade-in" style="animation-delay: 0.4s">
                <a href="{{ route('sales.show', $sale) }}" class="w-full sm:w-auto bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400 transition-all duration-200 transform hover:scale-105 text-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="w-full sm:w-auto bg-accent-600 text-white px-6 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Update Sale
                </button>
            </div>
        </form>
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
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Gradient backgrounds */
        .bg-gradient-to-r {
            background: linear-gradient(to right, var(--tw-gradient-stops));
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .text-lg {
                font-size: 1rem;
            }
        }

        /* Enhanced section styling */
        .bg-blue-50 {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
        }

        .bg-green-50 {
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
        }

        .bg-gray-50 {
            background-color: #f9fafb;
            border-left: 4px solid #6b7280;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const saleItems = document.getElementById('sale-items');
            const addItemBtn = document.getElementById('add-item');
            const totalAmountDisplay = document.getElementById('total-amount-display');
            const totalAmountInput = document.getElementById('total-amount');
            const paidAmountInput = document.getElementById('paid_amount');
            const dueAmountDisplay = document.getElementById('due-amount-display');
            const paymentStatus = document.getElementById('payment-status');
            
            // Initialize existing items
            saleItems.querySelectorAll('.sale-item').forEach(initializeSaleItem);
            
            // Add new item with animation
            addItemBtn.addEventListener('click', function() {
                const newItem = createNewSaleItem();
                newItem.style.opacity = '0';
                newItem.style.transform = 'translateY(20px)';
                saleItems.appendChild(newItem);
                
                // Animate in
                setTimeout(() => {
                    newItem.style.transition = 'all 0.3s ease-out';
                    newItem.style.opacity = '1';
                    newItem.style.transform = 'translateY(0)';
                }, 10);
                
                initializeSaleItem(newItem);
            });
            
            // Create new sale item
            function createNewSaleItem() {
                const newItem = document.createElement('div');
                newItem.className = 'sale-item bg-white p-4 rounded-lg border border-gray-200 shadow-sm transition-all duration-300 hover:shadow-md';
                newItem.innerHTML = `
                    <!-- Mobile Layout -->
                    <div class="block lg:hidden space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">New Item</span>
                            <button type="button" class="remove-item text-red-500 hover:text-red-700 p-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                            <select name="product_id[]" class="product-select w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->sale_price }}" data-stock="{{ $product->current_stock }}" data-unit="{{ $product->unit }}">
                                        {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }} available)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                <div class="flex items-center">
                                    <input type="number" name="quantity[]" class="quantity-input flex-1 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" step="0.01" min="0.01" required>
                                    <span class="unit-display ml-2 text-gray-500 text-xs"></span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">₹</span>
                                    <input type="number" name="price[]" class="price-input w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="bg-primary-50 p-3 rounded border">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                                <span class="subtotal-display text-lg font-bold text-primary-600">₹0.00</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desktop Layout -->
                    <div class="hidden lg:grid lg:grid-cols-5 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                            <select name="product_id[]" class="product-select w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->sale_price }}" data-stock="{{ $product->current_stock }}" data-unit="{{ $product->unit }}">
                                        {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }} available)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <div class="flex items-center">
                                <input type="number" name="quantity[]" class="quantity-input flex-1 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" step="0.01" min="0.01" required>
                                <span class="unit-display ml-2 text-gray-500 text-sm"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                <input type="number" name="price[]" class="price-input w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                            <div class="bg-primary-50 border border-primary-200 rounded-md p-2 text-center">
                                <span class="subtotal-display font-bold text-primary-600">₹0.00</span>
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <button type="button" class="remove-item text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                return newItem;
            }
            
            // Initialize sale item events
            function initializeSaleItem(item) {
                const productSelect = item.querySelector('.product-select');
                const quantityInput = item.querySelector('.quantity-input');
                const priceInput = item.querySelector('.price-input');
                const subtotalDisplay = item.querySelector('.subtotal-display');
                const unitDisplay = item.querySelector('.unit-display');
                const removeBtn = item.querySelector('.remove-item');
                
                // Product selection change
                productSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const price = selectedOption.dataset.price || 0;
                    const unit = selectedOption.dataset.unit || '';
                    
                    priceInput.value = price;
                    unitDisplay.textContent = unit;
                    
                    updateSubtotal();
                });
                
                // Quantity or price change
                quantityInput.addEventListener('input', updateSubtotal);
                priceInput.addEventListener('input', updateSubtotal);
                
                // Remove item with animation
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        if (saleItems.querySelectorAll('.sale-item').length > 1) {
                            item.style.opacity = '0';
                            item.style.transform = 'translateX(-20px)';
                            setTimeout(() => {
                                item.remove();
                                updateTotalAmount();
                            }, 300);
                        }
                    });
                }
                
                // Initial subtotal calculation
                updateSubtotal();
                
                // Update subtotal with animation
                function updateSubtotal() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    const subtotal = quantity * price;
                    
                    subtotalDisplay.style.transform = 'scale(1.1)';
                    subtotalDisplay.textContent = '₹' + subtotal.toFixed(2);
                    setTimeout(() => subtotalDisplay.style.transform = 'scale(1)', 200);
                    
                    updateTotalAmount();
                }
            }
            
            // Update total amount
            function updateTotalAmount() {
                let total = 0;
                
                document.querySelectorAll('.subtotal-display').forEach(function(display) {
                    const value = display.textContent.replace('₹', '') || '0';
                    total += parseFloat(value) || 0;
                });
                
                totalAmountDisplay.style.transform = 'scale(1.1)';
                totalAmountDisplay.textContent = '₹' + total.toFixed(2);
                totalAmountInput.value = total.toFixed(2);
                setTimeout(() => totalAmountDisplay.style.transform = 'scale(1)', 200);
                
                updateDueAmount();
            }
            
            // Update due amount and payment status
            function updateDueAmount() {
                const totalAmount = parseFloat(totalAmountInput.value) || 0;
                const paidAmount = parseFloat(paidAmountInput.value) || 0;
                const dueAmount = totalAmount - paidAmount;
                
                dueAmountDisplay.textContent = '₹' + dueAmount.toFixed(2);
                
                // Update payment status with animation
                let newStatus, newClass;
                if (paidAmount === 0) {
                    newStatus = 'Pending';
                    newClass = 'px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800';
                } else if (paidAmount < totalAmount) {
                    newStatus = 'Partial';
                    newClass = 'px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800';
                } else {
                    newStatus = 'Paid';
                    newClass = 'px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800';
                }
                
                if (paymentStatus.textContent !== newStatus) {
                    paymentStatus.style.transform = 'scale(1.1)';
                    paymentStatus.textContent = newStatus;
                    paymentStatus.className = newClass;
                    setTimeout(() => paymentStatus.style.transform = 'scale(1)', 200);
                }
            }
            
            // Listen for paid amount changes
            paidAmountInput.addEventListener('input', updateDueAmount);
            
            // Initial calculation
            updateTotalAmount();
        });
    </script>
</x-app-layout>