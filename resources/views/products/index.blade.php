<x-admin-layout>
    <x-slot name="title">Products</x-slot>
    <x-slot name="subtitle">Manage your product catalog</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
            <div class="w-full md:w-auto">
                <form action="{{ route('products.index') }}" method="GET" class="flex items-center">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search products..." 
                           class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                    <button type="submit" class="ml-2 bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="w-full md:w-auto mt-4 md:mt-0">
                <a href="{{ route('products.create') }}" class="w-full flex justify-center bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-plus mr-2"></i> Add Product
                </a>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="overflow-x-auto -mx-4 sm:-mx-0">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $product->current_stock }} {{ $product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $product->min_stock_level }} {{ $product->unit }}
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('products.show', $product) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="text-accent-600 hover:text-accent-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No products found. Create your first product to get started.</p>
                <a href="{{ route('products.create') }}" class="mt-4 inline-block bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-plus mr-2"></i> Add Product
                </a>
            </div>
        @endif
    </div>
</x-admin-layout>