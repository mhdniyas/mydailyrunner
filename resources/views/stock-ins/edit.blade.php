<x-app-layout>
    <x-slot name="title">Edit Stock Entry</x-slot>
    <x-slot name="subtitle">Modify stock information</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('stock-ins.update', $stockIn) }}" method="POST" id="stockInEditForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                    <select name="product_id" id="product_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                        <option value="">Select a product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id', $stockIn->product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }} available)
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $stockIn->quantity) }}" 
                           step="0.01" min="0.1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bags" class="block text-sm font-medium text-gray-700">Number of Bags</label>
                    <input type="number" name="bags" id="bags" value="{{ old('bags', $stockIn->bags) }}" 
                           min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('bags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700">Cost</label>
                    <input type="number" name="cost" id="cost" value="{{ old('cost', $stockIn->cost) }}" 
                           step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bag Weight Calculation Section -->
                <div class="md:col-span-2">
                    <div class="bg-gray-50 p-4 rounded-md">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Bag Weight Calculation</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <!-- Formula Options -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Calculation Method</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="calculation_method" value="formula_minus_half" 
                                               class="text-primary-600 focus:ring-primary-500" 
                                               {{ old('calculation_method', $stockIn->calculation_method ?? 'formula_minus_half') == 'formula_minus_half' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">(Quantity ÷ Bags) - 0.5</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="calculation_method" value="formula_direct" 
                                               class="text-primary-600 focus:ring-primary-500"
                                               {{ old('calculation_method', $stockIn->calculation_method) == 'formula_direct' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">Quantity ÷ Bags</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="calculation_method" value="manual" 
                                               class="text-primary-600 focus:ring-primary-500"
                                               {{ old('calculation_method', $stockIn->calculation_method) == 'manual' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">Manual Override</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Calculate Button and Results -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Calculate Values</label>
                                <button type="button" id="calculateBtn" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-calculator mr-2"></i>Calculate
                                </button>
                                
                                <!-- Calculation Results -->
                                <div id="calculationResults" class="mt-3 space-y-2 hidden">
                                    <div class="text-xs bg-white p-2 rounded border">
                                        <div class="font-medium text-gray-700">Formula 1: <span id="result1">-</span></div>
                                        <div class="text-gray-500">(Quantity ÷ Bags) - 0.5</div>
                                    </div>
                                    <div class="text-xs bg-white p-2 rounded border">
                                        <div class="font-medium text-gray-700">Formula 2: <span id="result2">-</span></div>
                                        <div class="text-gray-500">Quantity ÷ Bags</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Override Input -->
                            <div id="manualOverrideDiv" class="hidden">
                                <label for="manual_bag_weight" class="block text-sm font-medium text-gray-700">Manual Bag Weight</label>
                                <input type="number" name="manual_bag_weight" id="manual_bag_weight" 
                                       value="{{ old('manual_bag_weight', $stockIn->manual_bag_weight) }}" 
                                       step="0.01" min="0" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                @error('manual_bag_weight')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Final Bag Weight Display -->
                        <div class="bg-primary-50 p-3 rounded-md">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-primary-700">Final Bag Weight:</span>
                                <span id="finalBagWeight" class="text-lg font-semibold text-primary-900">{{ number_format($stockIn->getActualBagWeight(), 2) }}</span>
                            </div>
                            <div class="mt-1 text-xs text-primary-600">
                                Current method: {{ $stockIn->getCalculationMethodDisplay() }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('notes', $stockIn->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <a href="{{ route('stock-ins.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    Update Stock Entry
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Important Note</h3>
        <div class="bg-yellow-50 p-4 rounded-md">
            <p class="text-yellow-800">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Editing this stock entry will update the product's current stock and average cost. Please ensure the information is correct.
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const bagsInput = document.getElementById('bags');
            const calculateBtn = document.getElementById('calculateBtn');
            const calculationResults = document.getElementById('calculationResults');
            const result1 = document.getElementById('result1');
            const result2 = document.getElementById('result2');
            const finalBagWeight = document.getElementById('finalBagWeight');
            const manualBagWeight = document.getElementById('manual_bag_weight');
            const manualOverrideDiv = document.getElementById('manualOverrideDiv');
            const calculationMethodInputs = document.querySelectorAll('input[name="calculation_method"]');

            // Show/hide manual override input
            function toggleManualOverride() {
                const selectedMethod = document.querySelector('input[name="calculation_method"]:checked').value;
                if (selectedMethod === 'manual') {
                    manualOverrideDiv.classList.remove('hidden');
                } else {
                    manualOverrideDiv.classList.add('hidden');
                }
                updateFinalBagWeight();
            }

            // Calculate values
            function calculateValues() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const bags = parseInt(bagsInput.value) || 1;

                if (quantity > 0 && bags > 0) {
                    const formula1Result = (quantity / bags) - 0.5;
                    const formula2Result = quantity / bags;

                    result1.textContent = formula1Result.toFixed(2);
                    result2.textContent = formula2Result.toFixed(2);
                    
                    calculationResults.classList.remove('hidden');
                } else {
                    result1.textContent = '-';
                    result2.textContent = '-';
                    calculationResults.classList.add('hidden');
                }
                
                updateFinalBagWeight();
            }

            // Update final bag weight display
            function updateFinalBagWeight() {
                const selectedMethod = document.querySelector('input[name="calculation_method"]:checked').value;
                const quantity = parseFloat(quantityInput.value) || 0;
                const bags = parseInt(bagsInput.value) || 1;
                let finalWeight = 0;

                if (quantity > 0 && bags > 0) {
                    switch(selectedMethod) {
                        case 'formula_minus_half':
                            finalWeight = (quantity / bags) - 0.5;
                            break;
                        case 'formula_direct':
                            finalWeight = quantity / bags;
                            break;
                        case 'manual':
                            finalWeight = parseFloat(manualBagWeight.value) || 0;
                            break;
                    }
                }

                finalBagWeight.textContent = finalWeight > 0 ? finalWeight.toFixed(2) : '-';
            }

            // Event listeners
            calculateBtn.addEventListener('click', calculateValues);
            
            calculationMethodInputs.forEach(input => {
                input.addEventListener('change', toggleManualOverride);
            });

            manualBagWeight.addEventListener('input', updateFinalBagWeight);
            quantityInput.addEventListener('input', updateFinalBagWeight);
            bagsInput.addEventListener('input', updateFinalBagWeight);

            // Initial setup
            toggleManualOverride();
        });
    </script>
</x-app-layout>