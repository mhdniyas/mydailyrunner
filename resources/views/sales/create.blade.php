<x-app-layout>
    <x-slot name="title">New Sale</x-slot>
    <x-slot name="subtitle">Create a new sales transaction</x-slot>

    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <form action="{{ route('sales.store') }}" method="POST" id="sale-form">
            @csrf

            <!-- Customer and Date Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                    <select name="customer_id" id="customer_id" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm" 
                            required>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} {{ $customer->is_walk_in ? '(Walk-in)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sale_date" class="block text-sm font-medium text-gray-700 mb-2">Sale Date</label>
                    <input type="date" name="sale_date" id="sale_date" 
                           value="{{ old('sale_date', now()->format('Y-m-d')) }}" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm" 
                           required>
                    @error('sale_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sale Items Section -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Sale Items</h3>
                    <button type="button" id="add-item" 
                            class="bg-primary-600 text-white px-3 py-1.5 rounded-md hover:bg-primary-700 text-sm flex items-center">
                        <i class="fas fa-plus mr-1"></i>
                        <span class="hidden sm:inline">Add Item</span>
                        <span class="sm:hidden">Add</span>
                    </button>
                </div>
                
                <div id="sale-items" class="space-y-4">
                    <!-- Sale Item Template -->
                    <div class="sale-item bg-gray-50 p-3 sm:p-4 rounded-md border">
                        <!-- Mobile: Stack all fields vertically -->
                        <div class="space-y-4 md:space-y-0 md:grid md:grid-cols-5 md:gap-4">
                            <!-- Product Selection -->
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                                <select name="product_id[]" 
                                        class="product-select w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm" 
                                        required>
                                    <option value="">Select a product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                data-price="{{ $product->sale_price }}" 
                                                data-stock="{{ $product->current_stock }}" 
                                                data-unit="{{ $product->unit }}">
                                            {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }} available)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Customer Current Balance -->
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Balance</label>
                                <div class="flex items-center space-x-2">
                                    <input type="number" 
                                           class="current-qty-input flex-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm" 
                                           step="0.01" min="0" placeholder="Optional">
                                    <span class="unit-display-current text-gray-500 text-sm min-w-0 flex-shrink-0"></span>
                                </div>
                                <small class="text-gray-500 text-xs mt-1 block">Customer's current product balance</small>
                            </div>

                            <!-- Sale Quantity -->
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sale Quantity</label>
                                <div class="flex items-center space-x-2">
                                    <input type="number" name="quantity[]" 
                                           class="quantity-input flex-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm" 
                                           step="0.01" min="0.01" required>
                                    <span class="unit-display text-gray-500 text-sm min-w-0 flex-shrink-0"></span>
                                </div>
                                <small class="text-gray-500 text-xs mt-1 block">Quantity to sell to customer</small>
                            </div>

                            <!-- Price -->
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                <input type="number" name="price[]" 
                                       class="price-input w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm" 
                                       step="0.01" min="0" required>
                            </div>

                            <!-- Subtotal and Remove -->
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                                <div class="flex items-center space-x-2">
                                    <input type="text" 
                                           class="subtotal-display flex-1 bg-gray-100 border-gray-300 rounded-md shadow-sm text-sm" 
                                           readonly>
                                    <button type="button" 
                                            class="remove-item text-red-600 hover:text-red-900 p-1.5 hover:bg-red-50 rounded-md flex-shrink-0 hidden" 
                                            title="Remove Item">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes and Total Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
                <!-- Notes -->
                <div class="order-2 lg:order-1">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4" 
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm"
                              placeholder="Add any additional notes...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Summary -->
                <div class="order-1 lg:order-2">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-lg border">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Payment Summary</h4>
                        
                        <!-- Total Amount -->
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-700">Total Amount:</span>
                            <span class="text-lg font-bold text-gray-900" id="total-amount-display">₹0.00</span>
                            <input type="hidden" name="total_amount" id="total-amount" value="0">
                        </div>
                        
                        <!-- Paid Amount -->
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <label for="paid_amount" class="text-sm font-medium text-gray-700">Paid Amount:</label>
                            <input type="number" name="paid_amount" id="paid_amount" 
                                   value="{{ old('paid_amount', 0) }}" 
                                   step="0.01" min="0" 
                                   class="w-24 sm:w-32 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm text-right" 
                                   required>
                        </div>
                        
                        <!-- Due Amount -->
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-700">Due Amount:</span>
                            <span class="text-lg font-semibold text-red-600" id="due-amount-display">₹0.00</span>
                        </div>
                        
                        <!-- Payment Status -->
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-700">Payment Status:</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800" id="payment-status">
                                Pending
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('sales.index') }}" 
                   class="bg-gray-300 text-gray-800 px-4 py-2.5 rounded-md hover:bg-gray-400 text-center text-sm font-medium transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-accent-600 text-white px-6 py-2.5 rounded-md hover:bg-accent-700 text-sm font-medium transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Create Sale
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
            
            // Initialize the first item
            initializeSaleItem(saleItems.querySelector('.sale-item'));
            
            // Add new item
            addItemBtn.addEventListener('click', function() {
                const newItem = saleItems.querySelector('.sale-item').cloneNode(true);
                
                // Clear values
                newItem.querySelectorAll('input').forEach(input => {
                    input.value = '';
                });
                newItem.querySelector('select').selectedIndex = 0;
                newItem.querySelector('.unit-display').textContent = '';
                newItem.querySelector('.unit-display-current').textContent = '';
                
                // Show remove button for new items
                const removeBtn = newItem.querySelector('.remove-item');
                removeBtn.classList.remove('hidden');
                
                saleItems.appendChild(newItem);
                initializeSaleItem(newItem);
                
                // Scroll to new item on mobile
                if (window.innerWidth < 768) {
                    newItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
            
            // Initialize sale item events
            function initializeSaleItem(item) {
                const productSelect = item.querySelector('.product-select');
                const currentQtyInput = item.querySelector('.current-qty-input');
                const quantityInput = item.querySelector('.quantity-input');
                const priceInput = item.querySelector('.price-input');
                const subtotalDisplay = item.querySelector('.subtotal-display');
                const unitDisplay = item.querySelector('.unit-display');
                const unitDisplayCurrent = item.querySelector('.unit-display-current');
                const removeBtn = item.querySelector('.remove-item');
                
                // Product selection change
                productSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const price = selectedOption.dataset.price || 0;
                    const unit = selectedOption.dataset.unit || '';
                    const availableStock = parseFloat(selectedOption.dataset.stock) || 0;
                    
                    priceInput.value = price;
                    unitDisplay.textContent = unit;
                    unitDisplayCurrent.textContent = unit;
                    
                    // Auto-calculate quantity if current qty is entered
                    calculateSaleQuantity();
                    updateSubtotal();
                });
                
                // Current quantity change - auto-calculate sale quantity
                currentQtyInput.addEventListener('input', function() {
                    calculateSaleQuantity();
                });
                
                // Calculate sale quantity based on available stock - current quantity
                function calculateSaleQuantity() {
                    const selectedOption = productSelect.options[productSelect.selectedIndex];
                    if (!selectedOption || !selectedOption.value) return;
                    
                    const availableStock = parseFloat(selectedOption.dataset.stock) || 0;
                    const currentQty = parseFloat(currentQtyInput.value) || 0;
                    
                    if (currentQty > 0) {
                        const saleQty = Math.max(0, availableStock - currentQty);
                        quantityInput.value = saleQty.toFixed(2);
                        updateSubtotal();
                    }
                }
                
                // Quantity or price change
                quantityInput.addEventListener('input', updateSubtotal);
                priceInput.addEventListener('input', updateSubtotal);
                
                // Remove item
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        if (saleItems.children.length > 1) {
                            item.remove();
                            updateTotalAmount();
                        } else {
                            // Don't allow removing the last item, just clear it
                            item.querySelectorAll('input').forEach(input => {
                                if (!input.classList.contains('subtotal-display')) {
                                    input.value = '';
                                }
                            });
                            productSelect.selectedIndex = 0;
                            unitDisplay.textContent = '';
                            unitDisplayCurrent.textContent = '';
                            updateSubtotal();
                        }
                    });
                }
                
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
                
                totalAmountDisplay.textContent = '₹' + total.toFixed(2);
                totalAmountInput.value = total.toFixed(2);
                
                updateDueAmount();
            }
            
            // Update due amount and payment status
            function updateDueAmount() {
                const totalAmount = parseFloat(totalAmountInput.value) || 0;
                const paidAmount = parseFloat(paidAmountInput.value) || 0;
                const dueAmount = totalAmount - paidAmount;
                
                dueAmountDisplay.textContent = '₹' + dueAmount.toFixed(2);
                
                // Update payment status
                if (paidAmount === 0) {
                    paymentStatus.textContent = 'Pending';
                    paymentStatus.className = 'px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800';
                } else if (paidAmount < totalAmount) {
                    paymentStatus.textContent = 'Partial';
                    paymentStatus.className = 'px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800';
                } else {
                    paymentStatus.textContent = 'Paid';
                    paymentStatus.className = 'px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
                }
            }
            
            // Listen for paid amount changes
            paidAmountInput.addEventListener('input', updateDueAmount);
            
            // Handle form submission validation
            document.getElementById('sale-form').addEventListener('submit', function(e) {
                const hasValidItems = Array.from(document.querySelectorAll('.product-select')).some(select => select.value);
                
                if (!hasValidItems) {
                    e.preventDefault();
                    alert('Please add at least one product to the sale.');
                    return false;
                }
            });
        });
    </script>
</x-app-layout>