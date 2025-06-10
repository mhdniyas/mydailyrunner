<x-app-layout>
    <x-slot name="title">Add Stock</x-slot>
    <x-slot name="subtitle">Record new inventory</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('stock-ins.store') }}" method="POST" id="stockInForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                    <select name="product_id" id="product_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                        <option value="">Select a product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
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
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" 
                           step="0.01" min="0.1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bags" class="block text-sm font-medium text-gray-700">
                        Number of Bags
                        <span id="bagSuggestion" class="text-xs text-blue-600 ml-2 hidden">(Suggested: <span id="suggestedBags">-</span>)</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="bags" id="bags" value="{{ old('bags') }}" 
                               min="1" step="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 pr-20" required>
                        <button type="button" id="useSuggestionBtn" 
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-200 hidden">
                            Use
                        </button>
                    </div>
                    <div class="mt-1">
                        <p class="text-xs text-gray-600">Default calculation: Quantity ÷ 50 (example guideline)</p>
                        <div id="bagCalculationExample" class="text-xs mt-2 hidden">
                            <p class="text-blue-600 font-medium">Exact calculation: <span id="calculationExample"></span></p>
                            <div id="bagOptions" class="mt-2 space-y-1 hidden">
                                <div class="flex items-center justify-between bg-yellow-50 p-2 rounded border">
                                    <span class="text-gray-700">Option 1: <span id="option1Text"></span></span>
                                    <button type="button" id="useOption1Btn" class="text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded hover:bg-yellow-300">
                                        Use <span id="option1Bags"></span> bags
                                    </button>
                                </div>
                                <div class="flex items-center justify-between bg-green-50 p-2 rounded border">
                                    <span class="text-gray-700">Option 2: <span id="option2Text"></span></span>
                                    <button type="button" id="useOption2Btn" class="text-xs bg-green-200 text-green-800 px-2 py-1 rounded hover:bg-green-300">
                                        Use <span id="option2Bags"></span> bags
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('bags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700">Cost</label>
                    <input type="number" name="cost" id="cost" value="{{ old('cost') }}" 
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
                                               {{ old('calculation_method', 'formula_minus_half') == 'formula_minus_half' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">(Quantity ÷ Bags) - 0.5</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="calculation_method" value="formula_direct" 
                                               class="text-primary-600 focus:ring-primary-500"
                                               {{ old('calculation_method') == 'formula_direct' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">Quantity ÷ Bags</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="calculation_method" value="manual" 
                                               class="text-primary-600 focus:ring-primary-500"
                                               {{ old('calculation_method') == 'manual' ? 'checked' : '' }}>
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
                                <div id="calculationResults" class="mt-3 space-y-2">
                                    <div class="text-xs bg-green-50 p-2 rounded border border-green-200">
                                        <div class="font-medium text-green-700">Formula 1: <span id="result1">-</span></div>
                                        <div class="text-green-600">(Quantity ÷ Bags) - 0.5</div>
                                    </div>
                                    <div class="text-xs bg-blue-50 p-2 rounded border border-blue-200">
                                        <div class="font-medium text-blue-700">Formula 2: <span id="result2">-</span></div>
                                        <div class="text-blue-600">Quantity ÷ Bags</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Override Input -->
                            <div id="manualOverrideDiv" class="hidden">
                                <label for="manual_bag_weight" class="block text-sm font-medium text-gray-700">Manual Bag Weight</label>
                                <input type="number" name="manual_bag_weight" id="manual_bag_weight" 
                                       value="{{ old('manual_bag_weight') }}" 
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
                                <span id="finalBagWeight" class="text-lg font-semibold text-primary-900">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('notes') }}</textarea>
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
                    Add Stock
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
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
            const bagSuggestion = document.getElementById('bagSuggestion');
            const suggestedBags = document.getElementById('suggestedBags');
            const useSuggestionBtn = document.getElementById('useSuggestionBtn');
            const bagCalculationExample = document.getElementById('bagCalculationExample');
            const calculationExample = document.getElementById('calculationExample');
            const bagOptions = document.getElementById('bagOptions');
            const option1Text = document.getElementById('option1Text');
            const option2Text = document.getElementById('option2Text');
            const option1Bags = document.getElementById('option1Bags');
            const option2Bags = document.getElementById('option2Bags');
            const useOption1Btn = document.getElementById('useOption1Btn');
            const useOption2Btn = document.getElementById('useOption2Btn');

            // Calculate suggested number of bags
            function updateBagSuggestion() {
                const quantity = parseFloat(quantityInput.value) || 0;
                
                if (quantity > 0) {
                    const exactBags = quantity / 50;
                    const roundedDown = Math.floor(exactBags);
                    const roundedUp = Math.ceil(exactBags);
                    
                    // Show exact calculation
                    calculationExample.textContent = `${quantity} ÷ 50 = ${exactBags.toFixed(2)} bags`;
                    bagCalculationExample.classList.remove('hidden');
                    
                    // If it's not a whole number, show options
                    if (exactBags % 1 !== 0) {
                        // Option 1: Round down (fewer bags, heavier per bag)
                        const option1Weight = roundedDown > 0 ? (quantity / roundedDown).toFixed(2) : 0;
                        option1Text.textContent = `${roundedDown} bags = ${option1Weight} kg per bag (heavier)`;
                        option1Bags.textContent = roundedDown;
                        
                        // Option 2: Round up (more bags, lighter per bag)
                        const option2Weight = (quantity / roundedUp).toFixed(2);
                        option2Text.textContent = `${roundedUp} bags = ${option2Weight} kg per bag (lighter)`;
                        option2Bags.textContent = roundedUp;
                        
                        bagOptions.classList.remove('hidden');
                        
                        // Use the rounded up value as the main suggestion
                        suggestedBags.textContent = roundedUp;
                        bagSuggestion.classList.remove('hidden');
                        useSuggestionBtn.classList.remove('hidden');
                    } else {
                        // Exact whole number - hide options, show simple suggestion
                        bagOptions.classList.add('hidden');
                        suggestedBags.textContent = exactBags;
                        bagSuggestion.classList.remove('hidden');
                        useSuggestionBtn.classList.remove('hidden');
                    }
                } else {
                    bagSuggestion.classList.add('hidden');
                    useSuggestionBtn.classList.add('hidden');
                    bagCalculationExample.classList.add('hidden');
                    bagOptions.classList.add('hidden');
                }
            }

            // Use suggested bags
            function useSuggestedBags() {
                const suggested = parseInt(suggestedBags.textContent);
                if (suggested > 0) {
                    bagsInput.value = suggested; // This should be a whole number like 14
                    calculateValues(); // Auto-calculate when suggestion is used
                    updateFinalBagWeight();
                }
            }

            // Use option 1 (rounded down)
            function useOption1() {
                const bags = parseInt(option1Bags.textContent);
                if (bags > 0) {
                    bagsInput.value = bags;
                    calculateValues();
                    updateFinalBagWeight();
                }
            }

            // Use option 2 (rounded up)
            function useOption2() {
                const bags = parseInt(option2Bags.textContent);
                if (bags > 0) {
                    bagsInput.value = bags;
                    calculateValues();
                    updateFinalBagWeight();
                }
            }

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
                } else {
                    result1.textContent = '-';
                    result2.textContent = '-';
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
            
            quantityInput.addEventListener('input', function() {
                updateBagSuggestion();
                calculateValues(); // Auto-calculate when quantity changes
                updateFinalBagWeight();
            });
            
            bagsInput.addEventListener('input', function() {
                calculateValues(); // Auto-calculate when bags change
                updateFinalBagWeight();
            });
            
            useSuggestionBtn.addEventListener('click', useSuggestedBags);
            useOption1Btn.addEventListener('click', useOption1);
            useOption2Btn.addEventListener('click', useOption2);

            // Initial setup
            toggleManualOverride();
            updateBagSuggestion();
            calculateValues(); // Calculate on page load if values exist
        });
    </script>
</x-app-layout>