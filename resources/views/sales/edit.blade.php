<x-app-layout>
    <x-slot name="title">Edit Sale</x-slot>
    <x-slot name="subtitle">Modify sale transaction</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('sales.update', $sale) }}" method="POST" id="sale-form">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                    <select name="customer_id" id="customer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ (old('customer_id', $sale->customer_id) == $customer->id) ? 'selected' : '' }}>
                                {{ $customer->name }} {{ $customer->is_walk_in ? '(Walk-in)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sale_date" class="block text-sm font-medium text-gray-700">Sale Date</label>
                    <input type="date" name="sale_date" id="sale_date" value="{{ old('sale_date', $sale->sale_date->format('Y-m-d')) }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('sale_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Sale Items</h3>
                
                <div id="sale-items">
                    @if(old('product_id'))
                        {{-- If there are validation errors, show old input --}}
                        @foreach(old('product_id') as $index => $productId)
                            <div class="sale-item bg-gray-50 p-4 rounded-md mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Product</label>
                                        <select name="product_id[]" class="product-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
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
                                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                        <div class="flex items-center">
                                            <input type="number" name="quantity[]" class="quantity-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" 
                                                   step="0.01" min="0.01" value="{{ old('quantity')[$index] ?? '' }}" required>
                                            <span class="unit-display ml-2 text-gray-500"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Price</label>
                                        <input type="number" name="price[]" class="price-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" 
                                               step="0.01" min="0" value="{{ old('price')[$index] ?? '' }}" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                        <div class="flex items-center">
                                            <input type="text" class="subtotal-display mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm" readonly>
                                            <button type="button" class="remove-item ml-2 text-red-600 hover:text-red-900" {{ $index == 0 ? 'style=display:none;' : '' }}>
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Show existing sale items --}}
                        @foreach($sale->items as $index => $item)
                            <div class="sale-item bg-gray-50 p-4 rounded-md mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Product</label>
                                        <select name="product_id[]" class="product-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
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
                                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                        <div class="flex items-center">
                                            <input type="number" name="quantity[]" class="quantity-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" 
                                                   step="0.01" min="0.01" value="{{ $item->quantity }}" required>
                                            <span class="unit-display ml-2 text-gray-500">{{ $item->product->unit }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Price</label>
                                        <input type="number" name="price[]" class="price-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" 
                                               step="0.01" min="0" value="{{ $item->price }}" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                        <div class="flex items-center">
                                            <input type="text" class="subtotal-display mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm" value="{{ number_format($item->quantity * $item->price, 2) }}" readonly>
                                            <button type="button" class="remove-item ml-2 text-red-600 hover:text-red-900" {{ $index == 0 ? 'style=display:none;' : '' }}>
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <button type="button" id="add-item" class="mt-2 bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                    <i class="fas fa-plus mr-2"></i> Add Item
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('notes', $sale->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-gray-50 p-4 rounded-md">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Total Amount:</span>
                        <span class="text-lg font-bold" id="total-amount-display">{{ number_format($sale->total_amount, 2) }}</span>
                        <input type="hidden" name="total_amount" id="total-amount" value="{{ $sale->total_amount }}">
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="paid_amount" class="text-sm font-medium text-gray-700">Paid Amount:</label>
                        <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $sale->paid_amount) }}" 
                               step="0.01" min="0" class="w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Due Amount:</span>
                        <span class="text-lg font-semibold" id="due-amount-display">{{ number_format($sale->total_amount - $sale->paid_amount, 2) }}</span>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium text-gray-700">Payment Status:</span>
                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($sale->status == 'paid') bg-green-100 text-green-800
                            @elseif($sale->status == 'advance') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif" id="payment-status">
                            {{ ucfirst($sale->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('sales.show', $sale) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    Update Sale
                </button>
            </div>
        </form>
    </div>

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
            
            // Add new item
            addItemBtn.addEventListener('click', function() {
                const newItem = createNewSaleItem();
                saleItems.appendChild(newItem);
                initializeSaleItem(newItem);
            });
            
            // Create new sale item
            function createNewSaleItem() {
                const newItem = document.createElement('div');
                newItem.className = 'sale-item bg-gray-50 p-4 rounded-md mb-4';
                newItem.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product</label>
                            <select name="product_id[]" class="product-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->sale_price }}" data-stock="{{ $product->current_stock }}" data-unit="{{ $product->unit }}">
                                        {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }} available)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                            <div class="flex items-center">
                                <input type="number" name="quantity[]" class="quantity-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" step="0.01" min="0.01" required>
                                <span class="unit-display ml-2 text-gray-500"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" name="price[]" class="price-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" step="0.01" min="0" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                            <div class="flex items-center">
                                <input type="text" class="subtotal-display mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm" readonly>
                                <button type="button" class="remove-item ml-2 text-red-600 hover:text-red-900">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
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
                
                // Remove item
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        if (saleItems.querySelectorAll('.sale-item').length > 1) {
                            item.remove();
                            updateTotalAmount();
                        }
                    });
                }
                
                // Initial subtotal calculation
                updateSubtotal();
                
                // Update subtotal
                function updateSubtotal() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    const subtotal = quantity * price;
                    
                    subtotalDisplay.value = subtotal.toFixed(2);
                    updateTotalAmount();
                }
            }
            
            // Update total amount
            function updateTotalAmount() {
                let total = 0;
                
                document.querySelectorAll('.subtotal-display').forEach(function(input) {
                    total += parseFloat(input.value) || 0;
                });
                
                totalAmountDisplay.textContent = total.toFixed(2);
                totalAmountInput.value = total.toFixed(2);
                
                updateDueAmount();
            }
            
            // Update due amount and payment status
            function updateDueAmount() {
                const totalAmount = parseFloat(totalAmountInput.value) || 0;
                const paidAmount = parseFloat(paidAmountInput.value) || 0;
                const dueAmount = totalAmount - paidAmount;
                
                dueAmountDisplay.textContent = dueAmount.toFixed(2);
                
                // Update payment status
                if (paidAmount === 0) {
                    paymentStatus.textContent = 'Pending';
                    paymentStatus.className = 'ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
                } else if (paidAmount < totalAmount) {
                    paymentStatus.textContent = 'Advance';
                    paymentStatus.className = 'ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800';
                } else {
                    paymentStatus.textContent = 'Paid';
                    paymentStatus.className = 'ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                }
            }
            
            // Listen for paid amount changes
            paidAmountInput.addEventListener('input', updateDueAmount);
            
            // Initial calculation
            updateTotalAmount();
        });
    </script>
</x-app-layout>
