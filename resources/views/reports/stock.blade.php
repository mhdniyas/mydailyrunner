<x-app-layout>
    <x-slot name="title">Stock Report</x-slot>
    <x-slot name="subtitle">Current inventory status</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <form action="{{ route('reports.stock') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <select name="product_id" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                        <option value="">All Products</option>
                        @foreach($allProducts as $product)
                            <option value="{{ $product->id }}" {{ $selectedProduct == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="stock_level" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                        <option value="all" {{ $stockLevel == 'all' ? 'selected' : '' }}>All Stock Levels</option>
                        <option value="low" {{ $stockLevel == 'low' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out" {{ $stockLevel == 'out' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="normal" {{ $stockLevel == 'normal' ? 'selected' : '' }}>Normal Stock</option>
                    </select>
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </form>
            </div>
            <div>
                <a href="{{ route('export.stock') }}?product_id={{ $selectedProduct }}&stock_level={{ $stockLevel }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-file-export mr-2"></i> Export
                </a>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="bg-primary-50 p-4 rounded-md mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-primary-600 font-medium">Total Stock Value</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($totalStockValue, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 font-medium">Products</p>
                    <p class="text-2xl font-bold mt-1">{{ $products->count() }}</p>
                </div>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Level</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('products.show', $product) }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $product->current_stock }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $product->min_stock_level }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($product->avg_cost, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($product->current_stock * $product->avg_cost, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->isOutOfStock())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    @elseif($product->isLowStock())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            In Stock
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No products found matching the selected criteria.</p>
            </div>
        @endif
    </div>
</x-app-layout>