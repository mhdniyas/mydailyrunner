<x-app-layout>
    <x-slot name="title">Add Stock</x-slot>
    <x-slot name="subtitle">Record new inventory</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Header Section -->
        <div class="mb-6 animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-plus-circle mr-2 text-accent-600"></i>
                        Add New Stock Entry
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Record incoming inventory with automatic bag weight calculations</p>
                </div>
            </div>
        </div>

        <form action="{{ route('stock-ins.store') }}" method="POST" id="stockInForm" class="space-y-6">
            @csrf

            <!-- Basic Information Section -->
            <div class="animate-fade-in" style="animation-delay: 0.1s">
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Basic Information
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
                            <select name="product_id" id="product_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400" required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->current_stock }} {{ $product->unit }} available)
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Cost *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                <input type="number" name="cost" id="cost" value="{{ old('cost') }}" 
                                       step="0.01" min="0" class="w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400" required>
                            </div>
                            @error('cost')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quantity and Bags Section -->
            <div class="animate-fade-in" style="animation-delay: 0.2s">
                <div class="bg-green-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-green-900 mb-4 flex items-center">
                        <i class="fas fa-cubes mr-2"></i>
                        Quantity & Bags
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" 
                                   step="0.01" min="0.1" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400" required>
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bags" class="block text-sm font-medium text-gray-700 mb-2">
                                Number of Bags *
                                <span id="bagSuggestion" class="text-xs text-blue-600 ml-2 hidden">(Suggested: <span id="suggestedBags">-</span>)</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="bags" id="bags" value="{{ old('bags') }}" 
                                       min="1" step="1" class="w-full pr-16 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400" required>
                                <button type="button" id="useSuggestionBtn" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-200 transition-colors hidden">
                                    Use
                                </button>
                            </div>
                            @error('bags')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bag Calculation Helper -->
                    <div class="mt-4">
                        <div class="text-xs text-gray-600 bg-white p-3 rounded border">
                            <p class="font-medium mb-1">💡 Bag Calculation Helper</p>
                            <p>Default calculation: Quantity ÷ 50 (example guideline)</p>
                            <div id="bagCalculationExample" class="mt-2 hidden">
                                <p class="text-blue-600 font-medium">Exact calculation: <span id="calculationExample"></span></p>
                                <div id="bagOptions" class="mt-2 space-y-2 hidden">
                                    <div class="flex items-center justify-between bg-yellow-50 p-2 rounded border border-yellow-200">
                                        <span class="text-gray-700 text-xs">Option 1: <span id="option1Text"></span></span>
                                        <button type="button" id="useOption1Btn" class="text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded hover:bg-yellow-300 transition-colors">
                                            Use <span id="option1Bags"></span> bags
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between bg-green-50 p-2 rounded border border-green-200">
                                        <span class="text-gray-700 text-xs">Option 2: <span id="option2Text"></span></span>
                                        <button type="button" id="useOption2Btn" class="text-xs bg-green-200 text-green-800 px-2 py-1 rounded hover:bg-green-300 transition-colors">
                                            Use <span id="option2Bags"></span> bags
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bag Weight Calculation Section -->
            <div class="animate-fade-in" style="animation-delay: 0.3s">
                <div class="bg-purple-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                        <i class="fas fa-calculator mr-2"></i>
                        Bag Weight Calculation
                    </h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <!-- Calculation Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Calculation Method</label>
                            <div class="space-y-3">
                                <label class="flex items-center p-2 bg-white rounded border transition-all duration-200 hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="calculation_method" value="formula_minus_half" 
                                           class="text-accent-600 focus:ring-accent-500" 
                                           {{ old('calculation_method', 'formula_minus_half') == 'formula_minus_half' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm font-medium">(Quantity ÷ Bags) - 0.5</span>
                                </label>
                                <label class="flex items-center p-2 bg-white rounded border transition-all duration-200 hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="calculation_method" value="formula_direct" 
                                           class="text-accent-600 focus:ring-accent-500"
                                           {{ old('calculation_method') == 'formula_direct' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm font-medium">Quantity ÷ Bags</span>
                                </label>
                                <label class="flex items-center p-2 bg-white rounded border transition-all duration-200 hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="calculation_method" value="manual" 
                                           class="text-accent-600 focus:ring-accent-500"
                                           {{ old('calculation_method') == 'manual' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm font-medium">Manual Override</span>
                                </label>
                            </div>
                        </div>

                        <!-- Calculate Button and Results -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Calculate Values</label>
                            <button type="button" id="calculateBtn" class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-all duration-200 transform hover:scale-105 mb-3">
                                <i class="fas fa-calculator mr-2"></i>Calculate
                            </button>
                            
                            <!-- Calculation Results -->
                            <div id="calculationResults" class="space-y-2">
                                <div class="text-xs bg-green-50 p-3 rounded border border-green-200 transition-all duration-300">
                                    <div class="font-medium text-green-700">Formula 1: <span id="result1" class="font-bold">-</span></div>
                                    <div class="text-green-600">(Quantity ÷ Bags) - 0.5</div>
                                </div>
                                <div class="text-xs bg-blue-50 p-3 rounded border border-blue-200 transition-all duration-300">
                                    <div class="font-medium text-blue-700">Formula 2: <span id="result2" class="font-bold">-</span></div>
                                    <div class="text-blue-600">Quantity ÷ Bags</div>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Override Input -->
                        <div id="manualOverrideDiv" class="hidden">
                            <label for="manual_bag_weight" class="block text-sm font-medium text-gray-700 mb-3">Manual Bag Weight</label>
                            <input type="number" name="manual_bag_weight" id="manual_bag_weight" 
                                   value="{{ old('manual_bag_weight') }}" 
                                   step="0.01" min="0" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400">
                            @error('manual_bag_weight')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Final Bag Weight Display -->
                    <div class="mt-4 bg-gradient-to-r from-accent-50 to-primary-50 p-4 rounded-lg border border-accent-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-weight mr-2 text-accent-600"></i>
                                Final Bag Weight:
                            </span>
                            <span id="finalBagWeight" class="text-xl font-bold text-accent-700 transition-all duration-300">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="animate-fade-in" style="animation-delay: 0.4s">
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-sticky-note mr-2"></i>
                        Additional Notes
                    </h4>
                    <textarea name="notes" id="notes" rows="3" 
                              placeholder="Add any additional notes about this stock entry..."
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-200 animate-fade-in" style="animation-delay: 0.5s">
                <a href="{{ route('stock-ins.index') }}" class="w-full sm:w-auto bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400 transition-all duration-200 transform hover:scale-105 text-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="w-full sm:w-auto bg-accent-600 text-white px-6 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>Add Stock
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

        /* Radio button enhancements */
        input[type="radio"]:checked + span {
            color: #1f2937;
            font-weight: 600;
        }

        /* Gradient backgrounds */
        .bg-gradient-to-r {
            background: linear-gradient(to right, var(--tw-gradient-stops));
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .grid-cols-1 {
                gap: 1rem;
            }
            
            .text-xl {
                font-size: 1.125rem;
            }
        }

        /* Result highlighting */
        #result1, #result2 {
            transition: all 0.3s ease;
        }

        #finalBagWeight {
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
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

        .bg-purple-50 {
            background-color: #faf5ff;
            border-left: 4px solid #8b5cf6;
        }

        .bg-gray-50 {
            background-color: #f9fafb;
            border-left: 4px solid #6b7280;
        }
    </style>

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

            // Add visual feedback to calculations
            function animateResult(element) {
                element.style.transform = 'scale(1.1)';
                element.style.color = '#059669';
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                    element.style.color = '';
                }, 300);
            }

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
                    bagsInput.value = suggested;
                    bagsInput.style.transform = 'scale(1.05)';
                    setTimeout(() => bagsInput.style.transform = 'scale(1)', 200);
                    calculateValues();
                    updateFinalBagWeight();
                }
            }

            // Use option 1 (rounded down)
            function useOption1() {
                const bags = parseInt(option1Bags.textContent);
                if (bags > 0) {
                    bagsInput.value = bags;
                    bagsInput.style.transform = 'scale(1.05)';
                    setTimeout(() => bagsInput.style.transform = 'scale(1)', 200);
                    calculateValues();
                    updateFinalBagWeight();
                }
            }

            // Use option 2 (rounded up)
            function useOption2() {
                const bags = parseInt(option2Bags.textContent);
                if (bags > 0) {
                    bagsInput.value = bags;
                    bagsInput.style.transform = 'scale(1.05)';
                    setTimeout(() => bagsInput.style.transform = 'scale(1)', 200);
                    calculateValues();
                    updateFinalBagWeight();
                }
            }

            // Show/hide manual override input
            function toggleManualOverride() {
                const selectedMethod = document.querySelector('input[name="calculation_method"]:checked').value;
                if (selectedMethod === 'manual') {
                    manualOverrideDiv.classList.remove('hidden');
                    manualOverrideDiv.style.opacity = '0';
                    manualOverrideDiv.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        manualOverrideDiv.style.transition = 'all 0.3s ease';
                        manualOverrideDiv.style.opacity = '1';
                        manualOverrideDiv.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    manualOverrideDiv.style.opacity = '0';
                    manualOverrideDiv.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        manualOverrideDiv.classList.add('hidden');
                        manualOverrideDiv.style.transition = '';
                    }, 300);
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
                    
                    // Animate results
                    animateResult(result1);
                    animateResult(result2);
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

                const displayWeight = finalWeight > 0 ? finalWeight.toFixed(2) + ' kg' : '-';
                if (finalBagWeight.textContent !== displayWeight) {
                    finalBagWeight.style.transform = 'scale(1.1)';
                    finalBagWeight.textContent = displayWeight;
                    setTimeout(() => finalBagWeight.style.transform = 'scale(1)', 300);
                }
            }

            // Enhanced calculate button with loading state
            calculateBtn.addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Calculating...';
                this.disabled = true;
                setTimeout(() => {
                    calculateValues();
                    this.innerHTML = '<i class="fas fa-calculator mr-2"></i>Calculate';
                    this.disabled = false;
                }, 500);
            });
            
            calculationMethodInputs.forEach(input => {
                input.addEventListener('change', toggleManualOverride);
            });

            manualBagWeight.addEventListener('input', updateFinalBagWeight);
            
            quantityInput.addEventListener('input', function() {
                updateBagSuggestion();
                calculateValues();
                updateFinalBagWeight();
            });
            
            bagsInput.addEventListener('input', function() {
                calculateValues();
                updateFinalBagWeight();
            });
            
            useSuggestionBtn.addEventListener('click', useSuggestedBags);
            useOption1Btn.addEventListener('click', useOption1);
            useOption2Btn.addEventListener('click', useOption2);

            // Initial setup
            toggleManualOverride();
            updateBagSuggestion();
            calculateValues();
        });
    </script>
</x-app-layout>