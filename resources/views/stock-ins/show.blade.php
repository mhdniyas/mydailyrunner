<x-app-layout>
    <x-slot name="title">Stock In Details</x-slot>
    <x-slot name="subtitle">View stock entry information</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Stock Entry Information</h3>
            <div>
                <a href="{{ route('stock-ins.edit', $stockIn) }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 mr-2">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <form action="{{ route('stock-ins.destroy', $stockIn) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" 
                            onclick="return confirm('Are you sure you want to delete this stock entry?')">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Date</p>
                <p class="font-medium">{{ $stockIn->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Product</p>
                <p class="font-medium">
                    <a href="{{ route('products.show', $stockIn->product) }}" class="text-primary-600 hover:text-primary-900">
                        {{ $stockIn->product->name }}
                    </a>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Quantity</p>
                <p class="font-medium">{{ $stockIn->quantity }} {{ $stockIn->product->unit }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Number of Bags</p>
                <p class="font-medium">{{ $stockIn->bags }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Cost</p>
                <p class="font-medium">{{ number_format($stockIn->cost, 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Average Bag Weight</p>
                <p class="font-medium">{{ number_format($stockIn->getActualBagWeight(), 2) }} {{ $stockIn->product->unit }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Calculation Method</p>
                <p class="font-medium">{{ $stockIn->getCalculationMethodDisplay() }}</p>
                @if(($stockIn->calculation_method ?? 'formula_minus_half') === 'manual')
                    <p class="text-xs text-gray-400">Manual value: {{ number_format($stockIn->manual_bag_weight, 2) }} {{ $stockIn->product->unit }}</p>
                @endif
            </div>
            <div>
                <p class="text-sm text-gray-500">Recorded By</p>
                <p class="font-medium">{{ $stockIn->user->name }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Notes</p>
                <p class="font-medium">{{ $stockIn->notes ?? 'No notes provided.' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Product Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Current Stock</p>
                    <p class="font-medium">{{ $stockIn->product->current_stock }} {{ $stockIn->product->unit }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Minimum Stock Level</p>
                    <p class="font-medium">{{ $stockIn->product->min_stock_level }} {{ $stockIn->product->unit }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Average Cost</p>
                    <p class="font-medium">{{ number_format($stockIn->product->avg_cost, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Average Bag Weight</p>
                    <p class="font-medium">{{ number_format($stockIn->product->avg_bag_weight, 2) }} {{ $stockIn->product->unit }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('products.show', $stockIn->product) }}" class="text-primary-600 hover:text-primary-900">
                    View product details <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('stock-ins.create') }}" class="block w-full bg-primary-600 text-white text-center px-4 py-2 rounded-md hover:bg-primary-700">
                    <i class="fas fa-plus mr-2"></i> Add More Stock
                </a>
                <a href="{{ route('daily-stock-checks.create') }}" class="block w-full bg-accent-600 text-white text-center px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-clipboard-check mr-2"></i> Perform Stock Check
                </a>
                <a href="{{ route('stock-ins.index') }}" class="block w-full bg-gray-300 text-gray-800 text-center px-4 py-2 rounded-md hover:bg-gray-400">
                    <i class="fas fa-list mr-2"></i> View All Stock Entries
                </a>
            </div>
        </div>
    </div>
</x-app-layout>