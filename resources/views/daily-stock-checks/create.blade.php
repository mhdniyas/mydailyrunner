<x-app-layout>
    <x-slot name="title">{{ ucfirst($checkType ?? '') }} Stock Check</x-slot>
    <x-slot name="subtitle">Record physical inventory count</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        @if(!$checkType)
            <div class="text-center py-8">
                <p class="text-gray-500 mb-6">Please select the type of stock check you want to perform:</p>
                <div class="space-x-4">
                    @if(!$morningDone)
                        <a href="{{ route('daily-stock-checks.create.type', 'morning') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-md hover:bg-primary-700">
                            <i class="fas fa-sun mr-2"></i> Morning Check
                        </a>
                    @else
                        <button disabled class="inline-block bg-gray-300 text-gray-600 px-6 py-3 rounded-md cursor-not-allowed">
                            <i class="fas fa-sun mr-2"></i> Morning Check (Done)
                        </button>
                    @endif
                    
                    @if(!$eveningDone)
                        <a href="{{ route('daily-stock-checks.create.type', 'evening') }}" class="inline-block bg-accent-600 text-white px-6 py-3 rounded-md hover:bg-accent-700">
                            <i class="fas fa-moon mr-2"></i> Evening Check
                        </a>
                    @else
                        <button disabled class="inline-block bg-gray-300 text-gray-600 px-6 py-3 rounded-md cursor-not-allowed">
                            <i class="fas fa-moon mr-2"></i> Evening Check (Done)
                        </button>
                    @endif
                </div>
            </div>
        @else
            <form action="{{ route('daily-stock-checks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="check_type" value="{{ $checkType }}">
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ ucfirst($checkType) }} Stock Check - {{ now()->format('M d, Y') }}
                    </h3>
                    <p class="text-gray-600 mt-1">
                        Enter the physical stock count for each product. The system stock is shown for reference.
                    </p>
                </div>

                @if($products->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $index => $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                            <input type="hidden" name="product_id[{{ $index }}]" value="{{ $product->id }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $product->unit }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="hidden" name="system_stock[{{ $index }}]" value="{{ $product->current_stock }}">
                                            <span class="font-medium">{{ $product->current_stock }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <input type="number" name="physical_stock[{{ $index }}]" value="{{ old('physical_stock.' . $index, $product->current_stock) }}" 
                                                       step="0.01" min="0" class="border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 flex-1" 
                                                       id="physical_stock_{{ $index }}" required>
                                                <button type="button" onclick="openBagCalculator({{ $index }}, '{{ $product->name }}', '{{ $product->unit }}')" 
                                                        class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded text-sm whitespace-nowrap">
                                                    📊 Calc
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" name="notes[{{ $index }}]" value="{{ old('notes.' . $index) }}" 
                                                   placeholder="Optional notes" class="border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <a href="{{ route('daily-stock-checks.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                            Submit Stock Check
                        </button>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No products found. Add products first to perform stock checks.</p>
                        <a href="{{ route('products.create') }}" class="mt-4 inline-block bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                            <i class="fas fa-plus mr-2"></i> Add Product
                        </a>
                    </div>
                @endif
            </form>
        @endif
    </div>

    @if($checkType)
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Important Notes</h3>
        <div class="space-y-4">
            <div class="bg-primary-50 p-4 rounded-md">
                <p class="text-primary-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    The system will automatically calculate discrepancies between system stock and physical stock.
                </p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-md">
                <p class="text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    After submission, the product's current stock will be updated to match the physical stock count.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Bag Calculator Popup - Mobile Responsive -->
    <div id="bagCalculatorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative mx-auto p-3 sm:p-5 border w-full max-w-4xl min-h-screen sm:min-h-0 sm:top-10 sm:mb-10 shadow-lg rounded-none sm:rounded-md bg-white">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4 pb-3 border-b sm:border-b-0 sticky top-0 bg-white z-10">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 pr-4" id="calculatorTitle">
                    Bag Calculator - Product Name
                </h3>
                <button type="button" onclick="closeBagCalculator()" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <i class="fas fa-times text-lg sm:text-xl"></i>
                </button>
            </div>

            <!-- Calculator Area -->
            <div class="space-y-3 sm:space-y-4 mb-6">
                <div id="calculationRows" class="space-y-2 sm:space-y-3">
                    <!-- Calculation rows will be added here dynamically -->
                </div>
                <button type="button" onclick="addCalculationRow()" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-3 sm:py-2 rounded text-sm sm:text-base">
                    <i class="fas fa-plus mr-2"></i> Add Row
                </button>
            </div>

            <!-- Total Section -->
            <div class="bg-gray-50 p-4 rounded-md mb-6 sticky bottom-20 sm:static">
                <div class="text-center">
                    <p class="text-base sm:text-lg font-medium text-gray-700">Total Bags:</p>
                    <p class="text-2xl sm:text-3xl font-bold text-primary-600" id="grandTotal">0</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 sticky bottom-0 bg-white pt-3 border-t sm:border-t-0">
                <button type="button" onclick="closeBagCalculator()" class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-3 sm:py-2 rounded order-2 sm:order-1">
                    Cancel
                </button>
                <button type="button" onclick="applyToStock()" class="w-full sm:w-auto bg-primary-600 hover:bg-primary-700 text-white px-4 py-3 sm:py-2 rounded order-1 sm:order-2">
                    Apply to Stock
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentProductIndex = null;
        let calculationCounter = 0;

        function openBagCalculator(productIndex, productName, unit) {
            currentProductIndex = productIndex;
            document.getElementById('calculatorTitle').textContent = `Bag Calculator - ${productName}`;
            document.getElementById('bagCalculatorModal').classList.remove('hidden');
            
            // Prevent body scroll on mobile
            document.body.style.overflow = 'hidden';
            
            // Reset calculator
            document.getElementById('calculationRows').innerHTML = '';
            calculationCounter = 0;
            
            // Add initial row
            addCalculationRow();
            
            // Focus on first input
            setTimeout(() => {
                const firstInput = document.querySelector('#calculationRows input');
                if (firstInput) firstInput.focus();
            }, 100);
        }

        function closeBagCalculator() {
            document.getElementById('bagCalculatorModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentProductIndex = null;
        }

        function addCalculationRow() {
            calculationCounter++;
            const rowId = `calc_row_${calculationCounter}`;
            
            const rowHtml = `
                <div class="bg-white p-3 sm:p-4 border border-gray-200 rounded-lg" id="${rowId}">
                    <!-- Mobile Layout -->
                    <div class="block sm:hidden space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Row ${calculationCounter}</span>
                            <button type="button" onclick="removeCalculationRow('${rowId}')" 
                                    class="text-red-500 hover:text-red-700 p-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Bags per row</label>
                                <input type="number" placeholder="0" min="0" step="1" 
                                       class="border-gray-300 rounded px-3 py-2 w-full text-center calc-input" 
                                       onchange="calculateRow('${rowId}')" onkeyup="calculateRow('${rowId}')">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Number of rows</label>
                                <input type="number" placeholder="0" min="0" step="1" 
                                       class="border-gray-300 rounded px-3 py-2 w-full text-center calc-input" 
                                       onchange="calculateRow('${rowId}')" onkeyup="calculateRow('${rowId}')">
                            </div>
                        </div>
                        <div class="text-center py-2 bg-primary-50 rounded">
                            <span class="text-xs text-gray-600">Total: </span>
                            <span class="font-bold text-lg text-primary-600 row-result">0</span>
                        </div>
                    </div>
                    
                    <!-- Desktop Layout -->
                    <div class="hidden sm:flex items-center space-x-3">
                        <div class="flex items-center space-x-2 flex-1">
                            <input type="number" placeholder="Bags per row" min="0" step="1" 
                                   class="border-gray-300 rounded px-3 py-2 w-24 text-center calc-input" 
                                   onchange="calculateRow('${rowId}')" onkeyup="calculateRow('${rowId}')">
                            <span class="text-gray-500 font-mono">×</span>
                            <input type="number" placeholder="Number of rows" min="0" step="1" 
                                   class="border-gray-300 rounded px-3 py-2 w-24 text-center calc-input" 
                                   onchange="calculateRow('${rowId}')" onkeyup="calculateRow('${rowId}')">
                            <span class="text-gray-500 font-mono">=</span>
                            <div class="w-24 text-center font-bold text-lg text-primary-600 row-result">0</div>
                        </div>
                        <button type="button" onclick="removeCalculationRow('${rowId}')" 
                                class="text-red-500 hover:text-red-700 p-1">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            document.getElementById('calculationRows').insertAdjacentHTML('beforeend', rowHtml);
            updateGrandTotal();
        }

        function removeCalculationRow(rowId) {
            const row = document.getElementById(rowId);
            if (row) {
                row.remove();
                updateGrandTotal();
            }
        }

        function calculateRow(rowId) {
            const row = document.getElementById(rowId);
            const inputs = row.querySelectorAll('.calc-input');
            const resultDiv = row.querySelector('.row-result');
            
            const bagsPerRow = parseFloat(inputs[0].value) || 0;
            const numberOfRows = parseFloat(inputs[1].value) || 0;
            const result = bagsPerRow * numberOfRows;
            
            resultDiv.textContent = result;
            updateGrandTotal();
        }

        function updateGrandTotal() {
            const results = document.querySelectorAll('.row-result');
            let total = 0;
            
            results.forEach(result => {
                total += parseFloat(result.textContent) || 0;
            });
            
            document.getElementById('grandTotal').textContent = total;
        }

        function applyToStock() {
            const total = parseFloat(document.getElementById('grandTotal').textContent) || 0;
            
            if (currentProductIndex !== null) {
                const physicalStockInput = document.getElementById(`physical_stock_${currentProductIndex}`);
                if (physicalStockInput) {
                    physicalStockInput.value = total;
                }
            }
            
            closeBagCalculator();
        }

        // Close modal when clicking outside (only on desktop)
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
    </script>
</x-app-layout>