<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Record Physical Stock') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Enter today\'s physical inventory count') }}
        </p>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('daily-workflow.store-stock') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Quick Stock Entry</h3>
                            <p class="mt-1 text-sm text-gray-600">Enter the physical count for each product</p>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System Stock</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bag Average</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimated Bags</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical Stock</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($products as $index => $product)
                                        <tr>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                <input type="number" step="0.01" name="system_stock[]" class="system-stock block w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ $product->current_stock }}" readonly>
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ number_format($bagAverages[$product->id] ?? $product->avg_bag_weight, 2) }}
                                                    <input type="hidden" class="bag-average" value="{{ $bagAverages[$product->id] ?? $product->avg_bag_weight }}">
                                                </div>
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 estimated-bags">
                                                    {{ number_format($product->current_stock / ($bagAverages[$product->id] ?? $product->avg_bag_weight), 1) }}
                                                </div>
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <input type="number" step="0.01" name="physical_stock[]" class="physical-stock block w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                                    <div class="ml-2 flex flex-col">
                                                        <button type="button" class="bag-entry-btn inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            Enter Bags
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6 flex justify-between">
                            <a href="{{ route('daily-workflow.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Back
                            </a>
                            <div>
                                <button type="submit" name="action" value="save_draft" class="mr-2 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save Draft
                                </button>
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Submit & Check Discrepancies
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bag Entry Modal -->
    <div id="bagEntryModal" class="fixed inset-0 z-10 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Enter Bags
                            </h3>
                            <div class="mt-4">
                                <p id="productName" class="text-sm text-gray-500 mb-2"></p>
                                <div class="flex items-center justify-center mb-4">
                                    <label for="bags" class="block text-sm font-medium text-gray-700 mr-2">Bags:</label>
                                    <input type="number" id="bags" name="bags" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" min="0" step="0.5">
                                </div>
                                <p class="text-sm text-gray-500">Calculated quantity: <span id="calculatedQuantity">0</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="applyBagBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Apply
                    </button>
                    <button type="button" id="cancelBagBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('bagEntryModal');
            const bagEntryBtns = document.querySelectorAll('.bag-entry-btn');
            const applyBagBtn = document.getElementById('applyBagBtn');
            const cancelBagBtn = document.getElementById('cancelBagBtn');
            const bagsInput = document.getElementById('bags');
            const calculatedQuantity = document.getElementById('calculatedQuantity');
            const productNameEl = document.getElementById('productName');
            
            let currentRow = null;
            
            // Open modal when bag entry button is clicked
            bagEntryBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    currentRow = this.closest('tr');
                    const productName = currentRow.querySelector('.text-sm.font-medium').textContent;
                    const bagAverage = parseFloat(currentRow.querySelector('.bag-average').value);
                    
                    productNameEl.textContent = `Product: ${productName} (Bag Avg: ${bagAverage.toFixed(2)})`;
                    bagsInput.value = '';
                    calculatedQuantity.textContent = '0';
                    
                    modal.classList.remove('hidden');
                    
                    // Update calculated quantity when bags value changes
                    bagsInput.addEventListener('input', function() {
                        const bags = parseFloat(this.value) || 0;
                        const bagAvg = parseFloat(currentRow.querySelector('.bag-average').value);
                        const calculated = bags * bagAvg;
                        calculatedQuantity.textContent = calculated.toFixed(2);
                    });
                });
            });
            
            // Apply bag calculation
            applyBagBtn.addEventListener('click', function() {
                if (currentRow) {
                    const bags = parseFloat(bagsInput.value) || 0;
                    const bagAvg = parseFloat(currentRow.querySelector('.bag-average').value);
                    const calculated = bags * bagAvg;
                    
                    currentRow.querySelector('.physical-stock').value = calculated.toFixed(2);
                }
                
                modal.classList.add('hidden');
            });
            
            // Close modal
            cancelBagBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
