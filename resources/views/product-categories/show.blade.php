<x-app-layout>
    <x-slot name="title">{{ $title ?? 'Category Details' }}</x-slot>
    <x-slot name="subtitle">{{ $subtitle ?? 'View category information' }}</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Category Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Category Information</h3>
                <div class="space-x-2">
                    <a href="{{ route('product-categories.edit', $category) }}" class="bg-accent-600 text-white px-3 py-1 rounded-md hover:bg-accent-700 text-sm">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Category Name</p>
                    <p class="font-medium">{{ $category->name }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Description</p>
                    <p class="font-medium">{{ $category->description ?? 'No description' }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Created On</p>
                    <p class="font-medium">{{ $category->created_at->format('M d, Y') }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Last Updated</p>
                    <p class="font-medium">{{ $category->updated_at->format('M d, Y') }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Total Products</p>
                    <p class="font-medium">{{ $category->products->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Products in Category -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Products in Category</h3>
                <a href="{{ route('products.create') }}" class="bg-primary-600 text-white px-3 py-1 rounded-md hover:bg-primary-700 text-sm">
                    <i class="fas fa-plus mr-1"></i> Add Product
                </a>
            </div>
            
            @if($products->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-gray-500">{{ $product->current_stock }} {{ $product->unit }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-gray-500">{{ $product->unit }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('products.show', $product) }}" class="text-primary-600 hover:text-primary-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="text-accent-600 hover:text-accent-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
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
                    <p class="text-gray-500">No products in this category.</p>
                    <a href="{{ route('products.create') }}" class="mt-4 inline-block bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                        <i class="fas fa-plus mr-2"></i> Add Product
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
