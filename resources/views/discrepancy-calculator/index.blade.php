<x-admin-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="subtitle">{{ $subtitle }}</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header with Description -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-calculator text-2xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Inventory Discrepancy Calculator</h2>
                <p class="text-gray-600">Quickly calculate stock discrepancies by comparing recorded quantities with physical counts</p>
            </div>

            <!-- Calculator Form -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Input Section -->
                <div class="space-y-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-edit mr-2 text-blue-600"></i>
                            Recorded Information
                        </h3>
                        
                        <!-- Product Selection -->
                        <div class="mb-4">
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Product (Optional)
                            </label>
                            <select id="product_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select a product...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-unit="{{ $product->unit }}" data-stock="{{ $product->current_stock }}">
                                        {{ $product->name }} (Current: {{ $product->current_stock }} {{ $product->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Recorded Quantity -->
                        <div class="mb-4">
                            <label for="recorded_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Recorded Quantity *
                            </label>
                            <input type="number" id="recorded_quantity" min="0" step="0.01" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter recorded quantity">
                        </div>
                    </div>

                    <!-- Physical Count Section -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-boxes mr-2 text-blue-600"></i>
                                Physical Count
                            </h3>
                            <button type="button" id="add-row-btn" class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 text-sm">
                                <i class="fas fa-plus mr-1"></i> Add Row
                            </button>
                        </div>
                        
                        <div id="rows-container" class="space-y-3">
                            <!-- Initial row -->
                            <div class="row-item bg-white p-4 rounded-md border">
                                <!-- Mobile Layout -->
                                <div class="grid grid-cols-2 gap-3 md:hidden">
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Bags per row</label>
                                        <input type="number" class="bags-per-row w-full border-gray-300 rounded px-3 py-2 text-center" 
                                               placeholder="0" min="0" step="1">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Number of rows</label>
                                        <input type="number" class="num-rows w-full border-gray-300 rounded px-3 py-2 text-center" 
                                               placeholder="0" min="0" step="1">
                                    </div>
                                    <div class="col-span-2 text-center py-2 bg-blue-50 rounded">
                                        <span class="text-xs text-gray-600">Subtotal: </span>
                                        <span class="font-bold text-lg text-blue-600 row-result">0</span>
                                    </div>
                                </div>
                                
                                <!-- Desktop Layout -->
                                <div class="hidden md:flex items-center space-x-3">
                                    <div class="flex-1">
                                        <input type="number" class="bags-per-row w-full border-gray-300 rounded px-3 py-2 text-center" 
                                               placeholder="Bags per row" min="0" step="1">
                                    </div>
                                    <span class="text-gray-500 font-mono text-lg">×</span>
                                    <div class="flex-1">
                                        <input type="number" class="num-rows w-full border-gray-300 rounded px-3 py-2 text-center" 
                                               placeholder="Number of rows" min="0" step="1">
                                    </div>
                                    <span class="text-gray-500 font-mono text-lg">=</span>
                                    <div class="w-20 text-center font-bold text-lg text-blue-600 row-result">0</div>
                                    <button type="button" class="remove-row-btn text-red-600 hover:text-red-800 p-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Mobile Remove Button -->
                                <div class="md:hidden mt-2 text-center">
                                    <button type="button" class="remove-row-btn text-red-600 hover:text-red-800 text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fas fa-trash mr-1"></i> Remove Row
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-4 bg-white rounded-md border-2 border-blue-200">
                            <div class="text-center">
                                <span class="text-sm text-gray-600">Total Physical Count: </span>
                                <span id="total-physical" class="font-bold text-xl text-blue-600">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Calculate Button -->
                    <button type="button" id="calculate-btn" class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 font-semibold">
                        <i class="fas fa-calculator mr-2"></i>
                        Calculate Discrepancy
                    </button>
                </div>

                <!-- Results Section -->
                <div class="space-y-6">
                    <!-- Results Card -->
                    <div id="results-card" class="bg-gray-50 p-6 rounded-lg hidden">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-line mr-2 text-green-600"></i>
                            Calculation Results
                        </h3>

                        <div class="space-y-4">
                            <!-- Summary -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="text-center p-4 bg-white rounded-md border">
                                    <div class="text-sm text-gray-600">Recorded Quantity</div>
                                    <div id="result-recorded" class="text-2xl font-bold text-gray-800">-</div>
                                </div>
                                <div class="text-center p-4 bg-white rounded-md border">
                                    <div class="text-sm text-gray-600">Physical Count</div>
                                    <div id="result-physical" class="text-2xl font-bold text-gray-800">-</div>
                                </div>
                            </div>

                            <!-- Discrepancy -->
                            <div class="text-center p-6 bg-white rounded-md border-2" id="discrepancy-card">
                                <div class="text-lg text-gray-600 mb-2">Discrepancy</div>
                                <div id="result-discrepancy" class="text-3xl font-bold mb-2">-</div>
                                <div id="result-percentage" class="text-sm text-gray-600">-</div>
                                <div id="result-status" class="mt-2 inline-block px-3 py-1 rounded-full text-sm font-medium">-</div>
                            </div>

                            <!-- Product Info (if selected) -->
                            <div id="product-info" class="bg-white p-4 rounded-md border hidden">
                                <h4 class="font-semibold text-gray-800 mb-2">Product Information</h4>
                                <div id="product-details" class="text-sm text-gray-600"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Card -->
                    <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-4">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Tips for Accurate Counting
                        </h3>
                        <ul class="space-y-2 text-sm text-yellow-700">
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-600 mr-2 mt-0.5"></i>
                                Count bags systematically, row by row
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-600 mr-2 mt-0.5"></i>
                                Double-check your physical count
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-600 mr-2 mt-0.5"></i>
                                Record any damaged or partial bags separately
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-600 mr-2 mt-0.5"></i>
                                Use this calculator during stock checks
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-tools mr-2 text-blue-600"></i>
                            Quick Actions
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('daily-stock-checks.index') }}" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                <i class="fas fa-clipboard-check mr-2"></i>
                                Stock Checks
                            </a>
                            <a href="{{ route('reports.discrepancy') }}" class="flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Discrepancy Report
                            </a>
                            <button type="button" id="reset-btn" class="flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                                <i class="fas fa-redo mr-2"></i>
                                Reset Calculator
                            </button>
                            <button type="button" id="save-calculation-btn" class="flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-sm hidden">
                                <i class="fas fa-save mr-2"></i>
                                Save to Notes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rowsContainer = document.getElementById('rows-container');
            const addRowBtn = document.getElementById('add-row-btn');
            const calculateBtn = document.getElementById('calculate-btn');
            const resetBtn = document.getElementById('reset-btn');
            const resultsCard = document.getElementById('results-card');
            const totalPhysical = document.getElementById('total-physical');

            let rowCounter = 1;

            // Add new row
            addRowBtn.addEventListener('click', function() {
                rowCounter++;
                const newRow = createRowElement();
                rowsContainer.appendChild(newRow);
                updateRemoveButtons();
            });

            // Calculate button
            calculateBtn.addEventListener('click', function() {
                const recordedQuantity = parseFloat(document.getElementById('recorded_quantity').value) || 0;
                const productId = document.getElementById('product_id').value;
                
                if (recordedQuantity === 0) {
                    alert('Please enter a recorded quantity');
                    return;
                }

                const rows = [];
                document.querySelectorAll('.row-item').forEach(row => {
                    const bagsPerRow = parseFloat(row.querySelector('.bags-per-row').value) || 0;
                    const numRows = parseFloat(row.querySelector('.num-rows').value) || 0;
                    if (bagsPerRow > 0 && numRows > 0) {
                        rows.push({
                            bags_per_row: bagsPerRow,
                            num_rows: numRows
                        });
                    }
                });

                if (rows.length === 0) {
                    alert('Please add at least one row with valid counts');
                    return;
                }

                // Prepare data
                const data = {
                    recorded_quantity: recordedQuantity,
                    rows: rows,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };

                if (productId) {
                    data.product_id = productId;
                }

                // Show loading
                calculateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Calculating...';
                calculateBtn.disabled = true;

                // Make API call
                fetch('{{ route("discrepancy-calculator.calculate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    displayResults(result);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while calculating. Please try again.');
                })
                .finally(() => {
                    calculateBtn.innerHTML = '<i class="fas fa-calculator mr-2"></i>Calculate Discrepancy';
                    calculateBtn.disabled = false;
                });
            });

            // Reset button
            resetBtn.addEventListener('click', function() {
                // Reset form
                document.getElementById('recorded_quantity').value = '';
                document.getElementById('product_id').value = '';
                
                // Reset rows to just one
                rowsContainer.innerHTML = '';
                rowCounter = 1;
                rowsContainer.appendChild(createRowElement());
                updateRemoveButtons();
                
                // Hide results
                resultsCard.classList.add('hidden');
                
                // Update total
                updateTotalPhysical();
            });

            // Create row element
            function createRowElement() {
                const div = document.createElement('div');
                div.className = 'row-item bg-white p-4 rounded-md border';
                div.innerHTML = `
                    <!-- Mobile Layout -->
                    <div class="grid grid-cols-2 gap-3 md:hidden">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bags per row</label>
                            <input type="number" class="bags-per-row w-full border-gray-300 rounded px-3 py-2 text-center" 
                                   placeholder="0" min="0" step="1">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Number of rows</label>
                            <input type="number" class="num-rows w-full border-gray-300 rounded px-3 py-2 text-center" 
                                   placeholder="0" min="0" step="1">
                        </div>
                        <div class="col-span-2 text-center py-2 bg-blue-50 rounded">
                            <span class="text-xs text-gray-600">Subtotal: </span>
                            <span class="font-bold text-lg text-blue-600 row-result">0</span>
                        </div>
                    </div>
                    
                    <!-- Desktop Layout -->
                    <div class="hidden md:flex items-center space-x-3">
                        <div class="flex-1">
                            <input type="number" class="bags-per-row w-full border-gray-300 rounded px-3 py-2 text-center" 
                                   placeholder="Bags per row" min="0" step="1">
                        </div>
                        <span class="text-gray-500 font-mono text-lg">×</span>
                        <div class="flex-1">
                            <input type="number" class="num-rows w-full border-gray-300 rounded px-3 py-2 text-center" 
                                   placeholder="Number of rows" min="0" step="1">
                        </div>
                        <span class="text-gray-500 font-mono text-lg">=</span>
                        <div class="w-20 text-center font-bold text-lg text-blue-600 row-result">0</div>
                        <button type="button" class="remove-row-btn text-red-600 hover:text-red-800 p-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                    <!-- Mobile Remove Button -->
                    <div class="md:hidden mt-2 text-center">
                        <button type="button" class="remove-row-btn text-red-600 hover:text-red-800 text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-trash mr-1"></i> Remove Row
                        </button>
                    </div>
                `;

                // Add event listeners
                const inputs = div.querySelectorAll('.bags-per-row, .num-rows');
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        updateRowResult(div);
                        updateTotalPhysical();
                    });
                });

                const removeBtn = div.querySelector('.remove-row-btn');
                removeBtn.addEventListener('click', function() {
                    if (rowsContainer.children.length > 1) {
                        div.remove();
                        updateRemoveButtons();
                        updateTotalPhysical();
                    }
                });

                return div;
            }

            // Update row result
            function updateRowResult(row) {
                const bagsPerRow = parseFloat(row.querySelector('.bags-per-row').value) || 0;
                const numRows = parseFloat(row.querySelector('.num-rows').value) || 0;
                const result = bagsPerRow * numRows;
                row.querySelector('.row-result').textContent = result;
            }

            // Update total physical count
            function updateTotalPhysical() {
                let total = 0;
                document.querySelectorAll('.row-item').forEach(row => {
                    const bagsPerRow = parseFloat(row.querySelector('.bags-per-row').value) || 0;
                    const numRows = parseFloat(row.querySelector('.num-rows').value) || 0;
                    total += bagsPerRow * numRows;
                });
                totalPhysical.textContent = total;
            }

            // Update remove buttons
            function updateRemoveButtons() {
                const removeButtons = document.querySelectorAll('.remove-row-btn');
                removeButtons.forEach(btn => {
                    btn.disabled = rowsContainer.children.length <= 1;
                });
            }

            // Display results
            function displayResults(result) {
                document.getElementById('result-recorded').textContent = result.recorded_quantity;
                document.getElementById('result-physical').textContent = result.total_physical;
                document.getElementById('result-discrepancy').textContent = result.discrepancy;
                document.getElementById('result-percentage').textContent = result.discrepancy_percent + '%';

                // Status styling
                const statusEl = document.getElementById('result-status');
                const discrepancyCard = document.getElementById('discrepancy-card');
                
                if (result.status === 'match') {
                    statusEl.textContent = 'Perfect Match';
                    statusEl.className = 'mt-2 inline-block px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
                    discrepancyCard.className = 'text-center p-6 bg-white rounded-md border-2 border-green-200';
                    document.getElementById('result-discrepancy').className = 'text-3xl font-bold mb-2 text-green-600';
                } else if (result.status === 'over') {
                    statusEl.textContent = 'Overcount';
                    statusEl.className = 'mt-2 inline-block px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800';
                    discrepancyCard.className = 'text-center p-6 bg-white rounded-md border-2 border-blue-200';
                    document.getElementById('result-discrepancy').className = 'text-3xl font-bold mb-2 text-blue-600';
                } else {
                    statusEl.textContent = 'Undercount';
                    statusEl.className = 'mt-2 inline-block px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800';
                    discrepancyCard.className = 'text-center p-6 bg-white rounded-md border-2 border-red-200';
                    document.getElementById('result-discrepancy').className = 'text-3xl font-bold mb-2 text-red-600';
                }

                // Product info
                if (result.product) {
                    document.getElementById('product-details').innerHTML = `
                        <p><strong>Product:</strong> ${result.product.name}</p>
                        <p><strong>Current Stock:</strong> ${result.product.current_stock} ${result.product.unit}</p>
                        <p><strong>Unit:</strong> ${result.product.unit}</p>
                    `;
                    document.getElementById('product-info').classList.remove('hidden');
                } else {
                    document.getElementById('product-info').classList.add('hidden');
                }

                resultsCard.classList.remove('hidden');
                resultsCard.scrollIntoView({ behavior: 'smooth' });
            }

            // Initialize
            updateRemoveButtons();
            updateTotalPhysical();
        });
    </script>
</x-admin-layout>
