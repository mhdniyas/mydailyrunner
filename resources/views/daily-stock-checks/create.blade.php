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
                                            <input type="number" name="physical_stock[{{ $index }}]" value="{{ old('physical_stock.' . $index, $product->current_stock) }}" 
                                                   step="0.01" min="0" class="border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
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
</x-app-layout>