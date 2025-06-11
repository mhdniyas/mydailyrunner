<x-admin-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="subtitle">{{ $subtitle }}</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <div class="max-w-6xl mx-auto">
            <!-- Enhanced Header with Description -->
            <div class="mb-8 text-center animate-fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full mb-4 shadow-lg">
                    <i class="fas fa-calculator text-3xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3">Inventory Discrepancy Calculator</h2>
                <p class="text-gray-600 text-sm sm:text-base max-w-2xl mx-auto">Quickly calculate stock discrepancies by comparing recorded quantities with physical counts. Perfect for daily stock checks and inventory audits.</p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 max-w-2xl mx-auto">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <p class="text-xs text-blue-600 font-medium">Available Products</p>
                        <p class="text-lg font-bold text-blue-800">{{ count($products) }}</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <p class="text-xs text-green-600 font-medium">Current User</p>
                        <p class="text-sm font-bold text-green-800">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <p class="text-xs text-purple-600 font-medium">Date</p>
                        <p class="text-sm font-bold text-purple-800">{{ now()->format('M d') }}</p>
                    </div>
                    <div class="bg-orange-50 p-3 rounded-lg">
                        <p class="text-xs text-orange-600 font-medium">Time</p>
                        <p class="text-sm font-bold text-orange-800">{{ now()->format('h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Calculator Form -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Input Section -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Recorded Information -->
                    <div class="animate-fade-in" style="animation-delay: 0.1s">
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-4 sm:p-6 rounded-lg border-l-4 border-blue-400">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-edit mr-2 text-blue-600"></i>
                                Recorded Information
                            </h3>
                            
                            <!-- Product Selection -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Product (Optional)
                                    </label>
                                    <select id="product_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-blue-400">
                                        <option value="">Select a product...</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-unit="{{ $product->unit }}" data-stock="{{ $product->current_stock }}">
                                                {{ $product->name }} (Current: {{ $product->current_stock }} {{ $product->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Recorded Quantity -->
                                <div>
                                    <label for="recorded_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                        Recorded Quantity *
                                    </label>
                                    <input type="number" id="recorded_quantity" min="0" step="0.01" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-blue-400"
                                           placeholder="Enter recorded quantity">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Physical Count Section -->
                    <div class="animate-fade-in" style="animation-delay: 0.2s">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 sm:p-6 rounded-lg border-l-4 border-blue-500">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-boxes mr-2 text-blue-600"></i>
                                    Physical Count
                                </h3>
                                <button type="button" id="add-row-btn" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 text-sm">
                                    <i class="fas fa-plus mr-2"></i> Add Row
                                </button>
                            </div>
                            
                            <div id="rows-container" class="space-y-3">
                                <!-- Initial row will be added by JavaScript -->
                            </div>
                            
                            <!-- Total Display -->
                            <div class="mt-4 p-4 bg-white rounded-lg border-2 border-blue-200 shadow-sm">
                                <div class="text-center">
                                    <span class="text-sm text-gray-600">Total Physical Count: </span>
                                    <span id="total-physical" class="font-bold text-2xl text-blue-600 animate-pulse">0</span>
                                    <span class="text-sm text-gray-500 ml-2">bags</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calculate Button -->
                    <div class="animate-fade-in" style="animation-delay: 0.3s">
                        <button type="button" id="calculate-btn" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 font-semibold text-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-calculator mr-2"></i>
                            Calculate Discrepancy
                        </button>
                    </div>
                </div>

                <!-- Results and Tools Section -->
                <div class="space-y-6">
                    <!-- Results Card -->
                    <div id="results-card" class="bg-gradient-to-r from-gray-50 to-green-50 p-4 sm:p-6 rounded-lg border-l-4 border-green-400 hidden animate-fade-in">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-line mr-2 text-green-600"></i>
                            Calculation Results
                        </h3>

                        <div class="space-y-4">
                            <!-- Summary -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="text-center p-4 bg-white rounded-lg border shadow-sm">
                                    <div class="text-xs text-gray-500 mb-1">Recorded</div>
                                    <div id="result-recorded" class="text-xl font-bold text-gray-800">-</div>
                                </div>
                                <div class="text-center p-4 bg-white rounded-lg border shadow-sm">
                                    <div class="text-xs text-gray-500 mb-1">Physical</div>
                                    <div id="result-physical" class="text-xl font-bold text-gray-800">-</div>
                                </div>
                            </div>

                            <!-- Discrepancy -->
                            <div class="text-center p-6 bg-white rounded-lg border-2 shadow-sm" id="discrepancy-card">
                                <div class="text-sm text-gray-600 mb-2">Discrepancy</div>
                                <div id="result-discrepancy" class="text-3xl font-bold mb-2">-</div>
                                <div id="result-percentage" class="text-sm text-gray-600 mb-3">-</div>
                                <div id="result-status" class="inline-block px-4 py-2 rounded-full text-sm font-medium">-</div>
                            </div>

                            <!-- Product Info (if selected) -->
                            <div id="product-info" class="bg-white p-4 rounded-lg border shadow-sm hidden">
                                <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                                    <i class="fas fa-box mr-2 text-blue-600"></i>
                                    Product Information
                                </h4>
                                <div id="product-details" class="text-sm text-gray-600"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Card -->
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-4 sm:p-6 rounded-lg border-l-4 border-yellow-400 animate-fade-in" style="animation-delay: 0.4s">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-4 flex items-center">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Counting Tips
                        </h3>
                        <ul class="space-y-2 text-sm text-yellow-700">
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                Count bags systematically, row by row
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                Double-check your physical count
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                Record damaged/partial bags separately
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                Use during daily stock checks
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gradient-to-r from-gray-50 to-purple-50 p-4 sm:p-6 rounded-lg border-l-4 border-gray-400 animate-fade-in" style="animation-delay: 0.5s">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-tools mr-2 text-purple-600"></i>
                            Quick Actions
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('daily-stock-checks.index') }}" class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-clipboard-check mr-2"></i>
                                Stock Checks
                            </a>
                            <a href="{{ route('reports.discrepancy') }}" class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Reports
                            </a>
                            <button type="button" id="reset-btn" class="flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-redo mr-2"></i>
                                Reset All
                            </button>
                            <button type="button" id="save-calculation-btn" class="flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm transition-all duration-200 transform hover:scale-105 hidden">
                                <i class="fas fa-save mr-2"></i>
                                Save Notes
                            </button>
                        </div>
                    </div>
                </div>
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
        input:focus, select:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Gradient backgrounds */
        .bg-gradient-to-r {
            background: linear-gradient(to right, var(--tw-gradient-stops));
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .text-3xl {
                font-size: 2rem;
            }
            
            .text-2xl {
                font-size: 1.5rem;
            }
        }

        /* Enhanced border styling */
        .border-l-4 {
            border-left-width: 4px;
        }

        /* Row animation */
        .row-item {
            transition: all 0.3s ease;
        }

        .row-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Pulse animation for total */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .7;
            }
        }
    </style>

    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rowsContainer = document.getElementById('rows-container');
            const addRowBtn = document.getElementById('add-row-btn');
            const calculateBtn = document.getElementById('calculate-btn');
            const resetBtn = document.getElementById('reset-btn');
            const resultsCard = document.getElementById('results-card');
            const totalPhysical = document.getElementById('total-physical');

            let rowCounter = 0;

            // Initialize with first row
            addRow();

            // Add new row with animation
            addRowBtn.addEventListener('click', function() {
                addRow();
                // Add button animation
                addRowBtn.style.transform = 'scale(0.95)';
                setTimeout(() => addRowBtn.style.transform = 'scale(1)', 150);
            });

            // Calculate button
            calculateBtn.addEventListener('click', function() {
                const recordedQuantity = parseFloat(document.getElementById('recorded_quantity').value) || 0;
                const productId = document.getElementById('product_id').value;
                
                if (recordedQuantity === 0) {
                    showAlert('Please enter a recorded quantity', 'warning');
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
                    showAlert('Please add at least one row with valid counts', 'warning');
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
                    showAlert('Calculation completed successfully!', 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('An error occurred while calculating. Please try again.', 'error');
                })
                .finally(() => {
                    calculateBtn.innerHTML = '<i class="fas fa-calculator mr-2"></i>Calculate Discrepancy';
                    calculateBtn.disabled = false;
                });
            });

            // Reset button with confirmation
            resetBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to reset all calculations?')) {
                    resetCalculator();
                    showAlert('Calculator reset successfully', 'info');
                }
            });

            // Add row function
            function addRow() {
                rowCounter++;
                const newRow = createRowElement();
                
                // Add with animation
                newRow.style.opacity = '0';
                newRow.style.transform = 'translateY(20px)';
                rowsContainer.appendChild(newRow);
                
                setTimeout(() => {
                    newRow.style.transition = 'all 0.3s ease';
                    newRow.style.opacity = '1';
                    newRow.style.transform = 'translateY(0)';
                }, 10);
                
                updateRemoveButtons();
            }

            // Create row element
            function createRowElement() {
                const div = document.createElement('div');
                div.className = 'row-item bg-white p-4 rounded-lg border shadow-sm hover:shadow-md transition-all duration-300';
                div.innerHTML = `
                    <!-- Mobile Layout -->
                    <div class="block lg:hidden space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700 flex items-center">
                                <span class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">${rowCounter}</span>
                                Row ${rowCounter}
                            </span>
                            <button type="button" class="remove-row-btn text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Bags per row</label>
                                <input type="number" class="bags-per-row w-full border-gray-300 rounded-lg px-3 py-2 text-center transition-all duration-200 hover:border-blue-400 focus:border-blue-500" 
                                       placeholder="0" min="0" step="1">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Number of rows</label>
                                <input type="number" class="num-rows w-full border-gray-300 rounded-lg px-3 py-2 text-center transition-all duration-200 hover:border-blue-400 focus:border-blue-500" 
                                       placeholder="0" min="0" step="1">
                            </div>
                        </div>
                        <div class="text-center py-3 bg-blue-50 rounded-lg border border-blue-200">
                            <span class="text-xs text-gray-600">Subtotal: </span>
                            <span class="font-bold text-lg text-blue-600 row-result">0</span>
                            <span class="text-xs text-gray-500 ml-1">bags</span>
                        </div>
                    </div>
                    
                    <!-- Desktop Layout -->
                    <div class="hidden lg:flex items-center space-x-4">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium">
                            ${rowCounter}
                        </div>
                        <div class="flex-1">
                            <input type="number" class="bags-per-row w-full border-gray-300 rounded-lg px-3 py-2 text-center transition-all duration-200 hover:border-blue-400 focus:border-blue-500" 
                                   placeholder="Bags per row" min="0" step="1">
                        </div>
                        <span class="text-gray-500 font-mono text-xl">×</span>
                        <div class="flex-1">
                            <input type="number" class="num-rows w-full border-gray-300 rounded-lg px-3 py-2 text-center transition-all duration-200 hover:border-blue-400 focus:border-blue-500" 
                                   placeholder="Number of rows" min="0" step="1">
                        </div>
                        <span class="text-gray-500 font-mono text-xl">=</span>
                        <div class="w-24 text-center bg-blue-50 p-2 rounded-lg">
                            <div class="font-bold text-xl text-blue-600 row-result">0</div>
                            <div class="text-xs text-gray-500">bags</div>
                        </div>
                        <button type="button" class="remove-row-btn text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;

                // Add event listeners
                const inputs = div.querySelectorAll('.bags-per-row, .num-rows');
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        updateRowResult(div);
                        updateTotalPhysical();
                        // Add visual feedback
                        this.style.transform = 'scale(1.05)';
                        setTimeout(() => this.style.transform = 'scale(1)', 200);
                    });
                });

                const removeBtn = div.querySelector('.remove-row-btn');
                removeBtn.addEventListener('click', function() {
                    if (rowsContainer.children.length > 1) {
                        // Remove with animation
                        div.style.opacity = '0';
                        div.style.transform = 'translateX(-20px)';
                        setTimeout(() => {
                            div.remove();
                            updateRemoveButtons();
                            updateTotalPhysical();
                        }, 300);
                    }
                });

                return div;
            }

            // Update row result with animation
            function updateRowResult(row) {
                const bagsPerRow = parseFloat(row.querySelector('.bags-per-row').value) || 0;
                const numRows = parseFloat(row.querySelector('.num-rows').value) || 0;
                const result = bagsPerRow * numRows;
                
                const resultEl = row.querySelector('.row-result');
                resultEl.style.transform = 'scale(1.1)';
                resultEl.textContent = result;
                setTimeout(() => resultEl.style.transform = 'scale(1)', 200);
            }

            // Update total physical count with animation
            function updateTotalPhysical() {
                let total = 0;
                document.querySelectorAll('.row-item').forEach(row => {
                    const bagsPerRow = parseFloat(row.querySelector('.bags-per-row').value) || 0;
                    const numRows = parseFloat(row.querySelector('.num-rows').value) || 0;
                    total += bagsPerRow * numRows;
                });
                
                totalPhysical.style.transform = 'scale(1.1)';
                totalPhysical.textContent = total;
                setTimeout(() => totalPhysical.style.transform = 'scale(1)', 200);
            }

            // Update remove buttons
            function updateRemoveButtons() {
                const removeButtons = document.querySelectorAll('.remove-row-btn');
                removeButtons.forEach(btn => {
                    btn.disabled = rowsContainer.children.length <= 1;
                    if (btn.disabled) {
                        btn.style.opacity = '0.5';
                        btn.style.cursor = 'not-allowed';
                    } else {
                        btn.style.opacity = '1';
                        btn.style.cursor = 'pointer';
                    }
                });
            }

            // Display results with enhanced animations
            function displayResults(result) {
                document.getElementById('result-recorded').textContent = result.recorded_quantity;
                document.getElementById('result-physical').textContent = result.total_physical;
                document.getElementById('result-discrepancy').textContent = result.discrepancy;
                document.getElementById('result-percentage').textContent = result.discrepancy_percent + '%';

                // Status styling with enhanced colors
                const statusEl = document.getElementById('result-status');
                const discrepancyCard = document.getElementById('discrepancy-card');
                
                if (result.status === 'match') {
                    statusEl.textContent = '✓ Perfect Match';
                    statusEl.className = 'inline-block px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800';
                    discrepancyCard.className = 'text-center p-6 bg-white rounded-lg border-2 border-green-200 shadow-sm';
                    document.getElementById('result-discrepancy').className = 'text-3xl font-bold mb-2 text-green-600';
                } else if (result.status === 'over') {
                    statusEl.textContent = '↑ Overcount';
                    statusEl.className = 'inline-block px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800';
                    discrepancyCard.className = 'text-center p-6 bg-white rounded-lg border-2 border-blue-200 shadow-sm';
                    document.getElementById('result-discrepancy').className = 'text-3xl font-bold mb-2 text-blue-600';
                } else {
                    statusEl.textContent = '↓ Undercount';
                    statusEl.className = 'inline-block px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800';
                    discrepancyCard.className = 'text-center p-6 bg-white rounded-lg border-2 border-red-200 shadow-sm';
                    document.getElementById('result-discrepancy').className = 'text-3xl font-bold mb-2 text-red-600';
                }

                // Product info
                if (result.product) {
                    document.getElementById('product-details').innerHTML = `
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                            <p><strong>Product:</strong> ${result.product.name}</p>
                            <p><strong>Unit:</strong> ${result.product.unit}</p>
                            <p><strong>Current Stock:</strong> ${result.product.current_stock} ${result.product.unit}</p>
                            <p><strong>Calculated:</strong> {{ now()->format('h:i A') }}</p>
                        </div>
                    `;
                    document.getElementById('product-info').classList.remove('hidden');
                } else {
                    document.getElementById('product-info').classList.add('hidden');
                }

                // Show results with animation
                resultsCard.classList.remove('hidden');
                resultsCard.style.opacity = '0';
                resultsCard.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    resultsCard.style.transition = 'all 0.5s ease';
                    resultsCard.style.opacity = '1';
                    resultsCard.style.transform = 'translateY(0)';
                    resultsCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);

                // Show save button
                document.getElementById('save-calculation-btn').classList.remove('hidden');
            }

            // Reset calculator function
            function resetCalculator() {
                // Reset form
                document.getElementById('recorded_quantity').value = '';
                document.getElementById('product_id').value = '';
                
                // Reset rows to just one
                rowsContainer.innerHTML = '';
                rowCounter = 0;
                addRow();
                
                // Hide results
                resultsCard.classList.add('hidden');
                document.getElementById('save-calculation-btn').classList.add('hidden');
                
                // Update total
                updateTotalPhysical();
            }

            // Show alert function
            function showAlert(message, type) {
                const alertDiv = document.createElement('div');
                const colors = {
                    success: 'bg-green-100 text-green-800 border-green-200',
                    error: 'bg-red-100 text-red-800 border-red-200',
                    warning: 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    info: 'bg-blue-100 text-blue-800 border-blue-200'
                };
                
                alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-lg border ${colors[type]} shadow-lg transition-all duration-300`;
                alertDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation' : 'info'}-circle mr-2"></i>
                        ${message}
                    </div>
                `;
                
                document.body.appendChild(alertDiv);
                
                setTimeout(() => {
                    alertDiv.style.opacity = '0';
                    alertDiv.style.transform = 'translateX(100%)';
                    setTimeout(() => alertDiv.remove(), 300);
                }, 3000);
            }

            // Initialize
            updateRemoveButtons();
            updateTotalPhysical();
        });
    </script>
</x-admin-layout>