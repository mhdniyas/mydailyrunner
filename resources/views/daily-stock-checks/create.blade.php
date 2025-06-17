<x-app-layout>
    <x-slot name="title">{{ ucfirst($checkType ?? '') }} Stock Check</x-slot>
    <x-slot name="subtitle">Record physical inventory count</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        @if(!$checkType)
            <!-- Check Type Selection -->
            <div class="text-center py-12 animate-fade-in">
                <div class="mx-auto w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-clipboard-check text-2xl text-primary-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Daily Stock Check</h3>
                <p class="text-gray-500 mb-8">Please select the type of stock check you want to perform:</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md mx-auto">
                    @if(!$morningDone)
                        <a href="{{ route('daily-stock-checks.create.type', 'morning') }}" 
                           class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white p-6 rounded-lg hover:from-yellow-500 hover:to-yellow-700 transition-all duration-200 transform hover:scale-105 animate-fade-in" 
                           style="animation-delay: 0.1s">
                            <i class="fas fa-sun text-3xl mb-3"></i>
                            <h4 class="font-semibold">Morning Check</h4>
                            <p class="text-sm opacity-90">Start of day inventory</p>
                        </a>
                    @else
                        <div class="bg-gray-300 text-gray-600 p-6 rounded-lg cursor-not-allowed animate-fade-in" style="animation-delay: 0.1s">
                            <i class="fas fa-sun text-3xl mb-3"></i>
                            <h4 class="font-semibold">Morning Check</h4>
                            <p class="text-sm">✓ Completed</p>
                        </div>
                    @endif
                    
                    @if(!$eveningDone)
                        <a href="{{ route('daily-stock-checks.create.type', 'evening') }}" 
                           class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white p-6 rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105 animate-fade-in" 
                           style="animation-delay: 0.2s">
                            <i class="fas fa-moon text-3xl mb-3"></i>
                            <h4 class="font-semibold">Evening Check</h4>
                            <p class="text-sm opacity-90">End of day inventory</p>
                        </a>
                    @else
                        <div class="bg-gray-300 text-gray-600 p-6 rounded-lg cursor-not-allowed animate-fade-in" style="animation-delay: 0.2s">
                            <i class="fas fa-moon text-3xl mb-3"></i>
                            <h4 class="font-semibold">Evening Check</h4>
                            <p class="text-sm">✓ Completed</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Stock Check Form -->
            <form action="{{ route('daily-stock-checks.store') }}" method="POST" class="space-y-6" id="stockCheckForm">
                @csrf
                <input type="hidden" name="check_type" value="{{ $checkType }}">
                
                <!-- Header Section -->
                <div class="animate-fade-in">
                    <div class="bg-gradient-to-r from-{{ $checkType === 'morning' ? 'yellow' : 'purple' }}-50 to-{{ $checkType === 'morning' ? 'orange' : 'indigo' }}-50 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="bg-{{ $checkType === 'morning' ? 'yellow' : 'purple' }}-100 p-3 rounded-full mr-4">
                                <i class="fas fa-{{ $checkType === 'morning' ? 'sun' : 'moon' }} text-{{ $checkType === 'morning' ? 'yellow' : 'purple' }}-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ ucfirst($checkType) }} Stock Check
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ now()->format('M d, Y') }} • {{ ucfirst($checkType) }} Shift
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Enter the physical stock count for each product. The system stock is shown for reference.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($products->count() > 0)
                    <!-- Mobile View - Cards -->
                    <div class="lg:hidden space-y-3 mb-6">
                        @foreach($products as $index => $product)
                            <div class="bg-white border rounded-lg p-4 shadow-sm border-gray-200 transition-all duration-300 hover:shadow-md animate-fade-in" style="animation-delay: {{ ($index * 0.05) + 0.1 }}s">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900 text-lg">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-500">Unit: {{ $product->unit }}</p>
                                        <input type="hidden" name="product_id[{{ $index }}]" value="{{ $product->id }}">
                                        <input type="hidden" id="product_{{ $product->id }}_avg_bag_weight" value="{{ $bagAverages[$product->id] ?? 0 }}">
                                        <input type="hidden" name="system_stock[{{ $index }}]" value="{{ $product->current_stock }}">
                                    </div>
                                    <div class="ml-4 text-right">
                                        <p class="text-sm text-gray-500">System Stock</p>
                                        <p class="text-lg font-bold text-primary-600">{{ $product->current_stock }}</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Physical Stock *</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="number" name="physical_stock[{{ $index }}]" value="{{ old('physical_stock.' . $index, $product->current_stock) }}" 
                                                   step="0.01" min="0" class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200" 
                                                   id="physical_stock_mobile_{{ $index }}" required>
                                            <button type="button" onclick="openBagCalculator({{ $index }}, '{{ $product->name }}', '{{ $product->unit }}', 'mobile')" 
                                                    class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-2 rounded-md text-sm whitespace-nowrap transition-colors">
                                                <i class="fas fa-calculator mr-1"></i>Calc
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                        <input type="text" name="notes[{{ $index }}]" value="{{ old('notes.' . $index) }}" 
                                               placeholder="Add any notes about this count..."
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop View - Table -->
                    <div class="hidden lg:block overflow-x-auto animate-fade-in" style="animation-delay: 0.2s">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System Stock</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical Stock</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($products as $index => $product)
                                            <tr class="hover:bg-gray-50 transition-all duration-200 animate-fade-in" style="animation-delay: {{ ($index * 0.03) + 0.3 }}s">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                                    <input type="hidden" name="product_id[{{ $index }}]" value="{{ $product->id }}">
                                                    <input type="hidden" id="product_{{ $product->id }}_avg_bag_weight" value="{{ $bagAverages[$product->id] ?? 0 }}">
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    {{ $product->unit }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <input type="hidden" name="system_stock[{{ $index }}]" value="{{ $product->current_stock }}">
                                                    <span class="font-medium text-primary-600">{{ $product->current_stock }}</span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="flex items-center space-x-2">
                                                        <input type="number" name="physical_stock[{{ $index }}]" value="{{ old('physical_stock.' . $index, $product->current_stock) }}" 
                                                               step="0.01" min="0" class="border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 flex-1 w-24 transition-all duration-200" 
                                                               id="physical_stock_desktop_{{ $index }}" required>
                                                        <button type="button" onclick="openBagCalculator({{ $index }}, '{{ $product->name }}', '{{ $product->unit }}', 'desktop')" 
                                                                class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded text-sm whitespace-nowrap transition-colors" title="Bag Calculator">
                                                            <i class="fas fa-calculator"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <input type="text" name="notes[{{ $index }}]" value="{{ old('notes.' . $index) }}" 
                                                           placeholder="Optional notes" class="border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 w-32 transition-all duration-200">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-200 animate-fade-in" style="animation-delay: 0.4s">
                        <a href="{{ route('daily-stock-checks.index') }}" class="w-full sm:w-auto bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400 transition-all duration-200 transform hover:scale-105 text-center">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="w-full sm:w-auto bg-accent-600 text-white px-6 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-check mr-2"></i>Submit Stock Check
                        </button>
                    </div>
                @else
                    <div class="text-center py-12 animate-fade-in" style="animation-delay: 0.2s">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-box-open text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 text-lg mb-2">No products found</p>
                        <p class="text-gray-400 text-sm mb-6">Add products first to perform stock checks</p>
                        <a href="{{ route('products.create') }}" class="inline-block bg-accent-600 text-white px-6 py-3 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>Add Product
                        </a>
                    </div>
                @endif
            </form>
        @endif
    </div>

    @if($checkType)
    <!-- Important Notes Section -->
    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mt-6 animate-fade-in" style="animation-delay: 0.5s">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
            Important Notes
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                <div class="flex items-center">
                    <i class="fas fa-calculator mr-2 text-blue-600"></i>
                    <span class="text-blue-800 font-medium">Automatic Calculations</span>
                </div>
                <p class="text-blue-700 text-sm mt-1">
                    The system will automatically calculate discrepancies between system stock and physical stock.
                </p>
            </div>
            <div class="bg-amber-50 p-4 rounded-lg border-l-4 border-amber-400">
                <div class="flex items-center">
                    <i class="fas fa-database mr-2 text-amber-600"></i>
                    <span class="text-amber-800 font-medium">System Stock Impact</span>
                </div>
                <p class="text-amber-700 text-sm mt-1">
                    Daily stock checks record discrepancies for reporting only. System stock is only affected by sales and stock-ins.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Enhanced Bag Calculator Modal -->
    <div id="bagCalculatorModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden transition-opacity duration-300">
        <div class="relative mx-auto p-0 sm:p-5 border-0 sm:border w-full max-w-5xl min-h-screen sm:min-h-0 sm:top-10 sm:mb-10 shadow-2xl rounded-none sm:rounded-lg bg-white transform transition-all duration-300">
            <!-- Enhanced Header -->
            <div class="flex justify-between items-center p-4 sm:p-6 border-b bg-gradient-to-r from-primary-50 to-blue-50 sticky top-0 z-10 rounded-t-none sm:rounded-t-lg">
                <div class="flex items-center">
                    <div class="bg-primary-100 p-2 rounded-full mr-3">
                        <i class="fas fa-calculator text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900" id="calculatorTitle">
                            Bag Calculator
                        </h3>
                        <p class="text-sm text-gray-600">Calculate total stock from bag counts</p>
                    </div>
                </div>
                <button type="button" onclick="closeBagCalculator()" class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Calculator Area -->
            <div class="p-4 sm:p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                <div id="calculationRows" class="space-y-3">
                    <!-- Calculation rows will be added here dynamically -->
                </div>
                <button type="button" onclick="addCalculationRow()" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>Add Row
                </button>
            </div>

            <!-- Enhanced Total Section -->
            <div class="p-4 sm:p-6 bg-gradient-to-r from-gray-50 to-blue-50 border-t">
                <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                    <p class="text-lg font-medium text-gray-700 mb-2">Total Stock Count</p>
                    <p class="text-4xl font-bold text-primary-600 mb-1" id="grandTotal">0.00</p>
                    <p class="text-sm text-gray-600" id="grandTotalUnit"></p>
                    <p class="text-xs text-gray-500 mt-2" id="grandTotalInfo"></p>
                </div>
            </div>

            <!-- Enhanced Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 p-4 sm:p-6 border-t bg-gray-50 sticky bottom-0 rounded-b-none sm:rounded-b-lg">
                <button type="button" onclick="closeBagCalculator()" class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg transition-all duration-200 order-2 sm:order-1">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="button" onclick="applyToStock()" class="w-full sm:w-auto bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg transition-all duration-200 transform hover:scale-105 order-1 sm:order-2">
                    <i class="fas fa-check mr-2"></i>Apply to Stock
                </button>
            </div>
            <!-- Success notification for calculator -->
            <div id="calculatorNotification" class="fixed bottom-4 right-4 bg-green-500 text-white p-3 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Value applied successfully!</span>
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

        .animate-fade-in {
            animation: fade-in 0.6s ease-out forwards;
            opacity: 0;
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
            .text-4xl {
                font-size: 2rem;
            }
        }

        /* Calculator row styling */
        .calc-input:focus {
            ring-color: #3b82f6;
            border-color: #3b82f6;
        }

        /* Modal animations */
        #bagCalculatorModal.hidden {
            opacity: 0;
            pointer-events: none;
        }

        #bagCalculatorModal:not(.hidden) {
            opacity: 1;
            pointer-events: auto;
        }
    </style>

    <script>
        // Global variables for the bag calculator
        let currentProductIndex = null;
        let calculationCounter = 0;
        let currentProductId = null;
        let currentProductBagAvg = 0;
        let currentProductUnit = '';
        let currentViewType = 'desktop'; // Default view type
        
        // Function to open the bag calculator modal
        function openBagCalculator(productIndex, productName, unit, viewType = 'desktop') {
            currentProductIndex = productIndex;
            currentProductId = document.querySelector(`input[name="product_id[${productIndex}]"]`).value;
            currentProductUnit = unit;
            currentViewType = viewType; // Store the view type (mobile or desktop)
            
            // Get the bag average for this product from the StockIn model data
            currentProductBagAvg = parseFloat(document.getElementById(`product_${currentProductId}_avg_bag_weight`)?.value || 0);
            
            const titleText = currentProductBagAvg > 0 
                ? `${productName} (Avg: ${currentProductBagAvg.toFixed(2)} ${unit}/bag)`
                : productName;
                
            document.getElementById('calculatorTitle').innerHTML = `<i class="fas fa-calculator mr-2"></i>${titleText}`;
            
            const modal = document.getElementById('bagCalculatorModal');
            modal.classList.remove('hidden');
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            // Reset calculator
            document.getElementById('calculationRows').innerHTML = '';
            calculationCounter = 0;
            
            // Add initial row
            addCalculationRow();
            
            // Focus on first input after animation
            setTimeout(() => {
                const firstInput = document.querySelector('#calculationRows input');
                if (firstInput) firstInput.focus();
            }, 300);
        }

        // Function to close the bag calculator modal
        function closeBagCalculator() {
            const modal = document.getElementById('bagCalculatorModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentProductIndex = null;
        }

        // Function to add a new calculation row
        function addCalculationRow() {
            calculationCounter++;
            const rowId = `calc_row_${calculationCounter}`;
            
            const rowHtml = `
                <div class="bg-gradient-to-r from-white to-blue-50 p-4 border border-blue-200 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md" id="${rowId}">
                    <!-- Mobile Layout -->
                    <div class="block sm:hidden space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 flex items-center">
                                <span class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">${calculationCounter}</span>
                                Row ${calculationCounter}
                            </span>
                            <button type="button" onclick="removeCalculationRow('${rowId}')" 
                                    class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Bags per row</label>
                                <input type="number" placeholder="0" min="0" step="1" 
                                       class="border-gray-300 rounded-lg px-3 py-2 w-full text-center calc-input transition-all duration-200" 
                                       onchange="calculateRow('${rowId}')" onkeyup="calculateRow('${rowId}')">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Number of rows</label>
                                <input type="number" placeholder="0" min="0" step="1" 
                                       class="border-gray-300 rounded-lg px-3 py-2 w-full text-center calc-input transition-all duration-200" 
                                       onchange="calculateRow('${rowId}')" onkeyup="calculateRow('${rowId}')">
                            </div>
                        </div>
                        <div class="text-center py-3 bg-gradient-to-r from-primary-50 to-blue-50 rounded-lg border border-primary-200">
                            <span class="text-xs text-gray-600">Total: </span>
                            <span class="font-bold text-xl text-primary-600 row-result">0</span>
                            <span class="block text-xs text-gray-500 row-info hidden mt-1"></span>
                        </div>
                    </div>
                    
                    <!-- Desktop Layout -->
                    <div class="hidden sm:flex items-center space-x-4">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium">
                            ${calculationCounter}
                        </div>
                        <div class="flex items-center space-x-3 flex-1">
                            <input type="number" placeholder="Bags per row" min="0" step="1" 
                                   class="border-gray-300 rounded-lg px-3 py-2 w-32 text-center calc-input transition-all duration-200" 
                                   onchange="calculateRow('${rowId}')" onkeyup="calculateRow('${rowId}')">
                            <span class="text-gray-500 font-mono text-xl">×</span>
                            <input type="number" placeholder="Number of rows" min="0" step="1" 
                                   class="border-gray-300 rounded-lg px-3 py-2 w-32 text-center calc-input transition-all duration-200" 
                                   onchange="calculateRow('${rowId}')" onkeyup="calculateRow('${rowId}')">
                            <span class="text-gray-500 font-mono text-xl">=</span>
                            <div class="w-32 text-center bg-primary-50 p-2 rounded-lg">
                                <div class="font-bold text-xl text-primary-600 row-result">0</div>
                                <div class="text-xs text-gray-500 row-info hidden"></div>
                            </div>
                        </div>
                        <button type="button" onclick="removeCalculationRow('${rowId}')" 
                                class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            document.getElementById('calculationRows').insertAdjacentHTML('beforeend', rowHtml);
            updateGrandTotal();
        }

        // Function to remove a calculation row
        function removeCalculationRow(rowId) {
            const row = document.getElementById(rowId);
            if (row) {
                row.style.opacity = '0';
                row.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    row.remove();
                    updateGrandTotal();
                }, 200);
            }
        }

        // Function to calculate values for a specific row
        function calculateRow(rowId) {
            const row = document.getElementById(rowId);
            const inputs = row.querySelectorAll('.calc-input');
            const resultDiv = row.querySelector('.row-result');
            const infoDiv = row.querySelector('.row-info');
            
            const bagsPerRow = parseFloat(inputs[0].value) || 0;
            const numberOfRows = parseFloat(inputs[1].value) || 0;
            
            // Calculate total bags
            const totalBags = bagsPerRow * numberOfRows;
            
            // Apply bag average if available
            let result = totalBags;
            if (currentProductBagAvg > 0) {
                result = totalBags * currentProductBagAvg;
                
                // Show the calculation info
                infoDiv.textContent = `${totalBags} bags × ${currentProductBagAvg.toFixed(2)} per bag`;
                infoDiv.classList.remove('hidden');
            } else {
                infoDiv.classList.add('hidden');
            }
            
            resultDiv.textContent = result.toFixed(2);
            
            // Add animation to result
            resultDiv.style.transform = 'scale(1.1)';
            setTimeout(() => resultDiv.style.transform = 'scale(1)', 200);
            
            updateGrandTotal();
        }

        // Function to update the grand total
        function updateGrandTotal() {
            const results = document.querySelectorAll('.row-result');
            let total = 0;
            let totalBags = 0;
            
            results.forEach(result => {
                total += parseFloat(result.textContent) || 0;
            });
            
            const grandTotalElement = document.getElementById('grandTotal');
            grandTotalElement.textContent = total.toFixed(2);
            
            // Add animation to grand total
            grandTotalElement.style.transform = 'scale(1.05)';
            setTimeout(() => grandTotalElement.style.transform = 'scale(1)', 200);
            
            // Update the unit and info based on whether we're using bag average
            const grandTotalUnit = document.getElementById('grandTotalUnit');
            const grandTotalInfo = document.getElementById('grandTotalInfo');
            
            if (currentProductBagAvg > 0) {
                // Count total bags for info display
                const infoTexts = document.querySelectorAll('.row-info');
                infoTexts.forEach(info => {
                    if (!info.classList.contains('hidden')) {
                        const match = info.textContent.match(/^(\d+(\.\d+)?) bags/);
                        if (match) {
                            totalBags += parseFloat(match[1]) || 0;
                        }
                    }
                });
                
                grandTotalUnit.textContent = currentProductUnit || '';
                grandTotalInfo.textContent = `${totalBags.toFixed(0)} bags × ${currentProductBagAvg.toFixed(2)} ${currentProductUnit}/bag`;
                grandTotalInfo.title = 'Using the latest recorded bag weight from stock-in records';
            } else {
                grandTotalUnit.textContent = 'bags';
                grandTotalInfo.textContent = 'Manual bag count (no automatic weight conversion)';
                grandTotalInfo.title = '';
            }
        }

        // Function to apply calculated value to stock input
        function applyToStock() {
            const total = parseFloat(document.getElementById('grandTotal').textContent) || 0;
            
            if (currentProductIndex !== null) {
                // Get the appropriate input field based on the view type
                const inputId = `physical_stock_${currentViewType}_${currentProductIndex}`;
                const physicalStockInput = document.getElementById(inputId);
                
                if (physicalStockInput) {
                    // Properly update the input value to ensure it's submitted with the form
                    physicalStockInput.value = total.toFixed(2);
                    
                    // Store the value in a data attribute to ensure it persists
                    physicalStockInput.setAttribute('data-calculated-value', total.toFixed(2));
                    
                    // Also update the corresponding input in the other view if it exists
                    const otherViewType = currentViewType === 'mobile' ? 'desktop' : 'mobile';
                    const otherInput = document.getElementById(`physical_stock_${otherViewType}_${currentProductIndex}`);
                    if (otherInput) {
                        otherInput.value = total.toFixed(2);
                        otherInput.setAttribute('data-calculated-value', total.toFixed(2));
                    }
                    
                    // Trigger an input event to ensure any event listeners know the value has changed
                    const inputEvent = new Event('input', { bubbles: true });
                    physicalStockInput.dispatchEvent(inputEvent);
                    
                    // Add animation to the updated input
                    physicalStockInput.style.transform = 'scale(1.1)';
                    physicalStockInput.style.backgroundColor = '#dcfce7';
                    setTimeout(() => {
                        physicalStockInput.style.transform = 'scale(1)';
                        physicalStockInput.style.backgroundColor = '';
                    }, 500);
                    
                    // Show success notification
                    const notification = document.getElementById('calculatorNotification');
                    notification.style.transform = 'translate(0)';
                    notification.style.opacity = '1';
                    
                    // Hide notification after 3 seconds
                    setTimeout(() => {
                        notification.style.transform = 'translateY(20px)';
                        notification.style.opacity = '0';
                    }, 3000);
                    
                    // Log to console for debugging
                    console.log('Stock value updated:', {
                        product_index: currentProductIndex,
                        view_type: currentViewType,
                        input_id: inputId,
                        new_value: total.toFixed(2)
                    });
                } else {
                    console.error(`Input with ID ${inputId} not found`);
                }
            }
            
            closeBagCalculator();
        }

        // Event listeners setup
        document.addEventListener('DOMContentLoaded', function() {
            // Modal close events
            document.getElementById('bagCalculatorModal').addEventListener('click', function(event) {
                if (event.target === this && window.innerWidth >= 640) {
                    closeBagCalculator();
                }
            });

            // Handle escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !document.getElementById('bagCalculatorModal').classList.contains('hidden')) {
                    closeBagCalculator();
                }
            });
            
            // Add entrance animations to existing elements
            const elements = document.querySelectorAll('.animate-fade-in');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
            
            // Ensure form submission captures all updated values
            const stockCheckForm = document.getElementById('stockCheckForm');
            if (stockCheckForm) {
                stockCheckForm.addEventListener('submit', function(event) {
                    // Prevent the default form submission to allow our modifications
                    event.preventDefault();
                    
                    // Keep track of processed field names to avoid duplicates
                    const processedFieldNames = new Set();
                    
                    // Process physical stock inputs (both mobile and desktop)
                    document.querySelectorAll('input[name^="physical_stock"]').forEach(input => {
                        // Skip if we've already processed an input with this name
                        if (processedFieldNames.has(input.name)) {
                            return;
                        }
                        
                        // Mark this field as processed
                        processedFieldNames.add(input.name);
                        
                        // Check if there's a stored calculated value and use it
                        const calculatedValue = input.getAttribute('data-calculated-value');
                        if (calculatedValue) {
                            // Use the calculated value from the bag calculator
                            input.value = calculatedValue;
                            console.log(`Using calculated value for ${input.name}: ${calculatedValue}`);
                        } else {
                            // Use the current input value
                            console.log(`Using manual value for ${input.name}: ${input.value}`);
                        }
                        
                        // Create a single hidden field to ensure the value gets submitted
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = input.name;
                        hiddenInput.value = input.value;
                        stockCheckForm.appendChild(hiddenInput);
                    });
                    
                    // Add a small delay to ensure all values are set properly before submitting
                    setTimeout(() => {
                        console.log("Form is being submitted with updated values");
                        stockCheckForm.submit();
                    }, 100);
                });
            }
        });
    </script>
</x-app-layout>