<x-app-layout>
    <x-slot name="title">Product Details</x-slot>
    <x-slot name="subtitle">{{ $product->name }}</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Information -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Product Information</h3>
                <div>
                    <a href="{{ route('products.edit', $product) }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 mr-2">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" 
                                onclick="return confirm('Are you sure you want to delete this product?')">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium">{{ $product->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Unit</p>
                    <p class="font-medium">{{ $product->unit }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Current Stock</p>
                    <p class="font-medium">
                        {{ $product->current_stock }} {{ $product->unit }}
                        @if($product->isOutOfStock())
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Out of Stock
                            </span>
                        @elseif($product->isLowStock())
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Low Stock
                            </span>
                        @else
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                In Stock
                            </span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Minimum Stock Level</p>
                    <p class="font-medium">{{ $product->min_stock_level }} {{ $product->unit }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Average Cost</p>
                    <p class="font-medium">{{ number_format($product->avg_cost, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Sale Price</p>
                    <p class="font-medium">{{ number_format($product->sale_price, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Value</p>
                    <p class="font-medium">{{ number_format($product->getCurrentStockValue(), 2) }}</p>
                </div>
                @if($product->avg_bag_weight)
                <div>
                    <p class="text-sm text-gray-500">Average Bag Weight</p>
                    <p class="font-medium">{{ $product->avg_bag_weight }} {{ $product->unit }}</p>
                </div>
                @endif
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Description</p>
                    <p class="font-medium">{{ $product->description ?? 'No description provided.' }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('stock-ins.create') }}" class="block w-full bg-primary-600 text-white text-center px-4 py-2 rounded-md hover:bg-primary-700">
                    <i class="fas fa-boxes-stacked mr-2"></i> Add Stock
                </a>
                <a href="{{ route('sales.create') }}" class="block w-full bg-accent-600 text-white text-center px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-receipt mr-2"></i> Create Sale
                </a>
                <a href="{{ route('daily-stock-checks.create') }}" class="block w-full bg-primary-600 text-white text-center px-4 py-2 rounded-md hover:bg-primary-700">
                    <i class="fas fa-clipboard-check mr-2"></i> Stock Check
                </a>
            </div>
        </div>

        <!-- Recent Stock Ins -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Stock Ins</h3>
            @if($stockIns->count() > 0)
                <div class="space-y-4">
                    @foreach($stockIns as $stockIn)
                        <div class="border-b border-gray-200 pb-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">{{ $stockIn->created_at->format('M d, Y') }}</span>
                                <span class="font-medium">{{ $stockIn->quantity }} {{ $product->unit }}</span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-sm">{{ $stockIn->bags }} bags</span>
                                <span class="text-sm">Cost: {{ number_format($stockIn->cost, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('stock-ins.index') }}" class="mt-4 inline-block text-primary-600 hover:text-primary-800">
                    View all stock ins <i class="fas fa-arrow-right ml-1"></i>
                </a>
            @else
                <p class="text-gray-500">No recent stock ins found.</p>
            @endif
        </div>

        <!-- Recent Sales -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Sales</h3>
            @if($saleItems->count() > 0)
                <div class="space-y-4">
                    @foreach($saleItems as $item)
                        <div class="border-b border-gray-200 pb-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">{{ $item->sale->sale_date->format('M d, Y') }}</span>
                                <span class="font-medium">{{ $item->quantity }} {{ $product->unit }}</span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-sm">{{ $item->sale->customer->name }}</span>
                                <span class="text-sm">Price: {{ number_format($item->price, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('sales.index') }}" class="mt-4 inline-block text-primary-600 hover:text-primary-800">
                    View all sales <i class="fas fa-arrow-right ml-1"></i>
                </a>
            @else
                <p class="text-gray-500">No recent sales found.</p>
            @endif
        </div>

        <!-- Recent Stock Checks -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Stock Checks</h3>
            @if($stockChecks->count() > 0)
                <div class="space-y-4">
                    @foreach($stockChecks as $check)
                        <div class="border-b border-gray-200 pb-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">
                                    {{ $check->created_at->format('M d, Y') }} 
                                    <span class="ml-2 capitalize">{{ $check->check_type }}</span>
                                </span>
                                <span class="font-medium {{ $check->discrepancy != 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $check->discrepancy > 0 ? '+' : '' }}{{ $check->discrepancy }}
                                </span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-sm">System: {{ $check->system_stock }}</span>
                                <span class="text-sm">Physical: {{ $check->physical_stock }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('daily-stock-checks.index') }}" class="mt-4 inline-block text-primary-600 hover:text-primary-800">
                    View all stock checks <i class="fas fa-arrow-right ml-1"></i>
                </a>
            @else
                <p class="text-gray-500">No recent stock checks found.</p>
            @endif
        </div>
    </div>
</x-app-layout>