<x-admin-layout>
    <x-slot name="title">Products</x-slot>
    <x-slot name="subtitle">Manage your product catalog</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Filter Form & Add Button -->
        <div class="flex flex-col gap-4 mb-6">
            <div class="w-full">
                <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-center gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search products..." 
                           class="w-full sm:w-auto border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400 focus:scale-105">
                    
                    <select name="category_id" class="w-full sm:w-auto border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="w-full sm:w-auto bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-all duration-200 transform hover:scale-105 text-sm flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                </form>
            </div>
            <div class="w-full sm:w-auto flex justify-start">
                <a href="{{ route('products.create') }}" class="w-full sm:w-auto bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105 text-sm flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i> Add Product
                </a>
            </div>
        </div>

        @if($products->count() > 0)
            <!-- Mobile View - Cards -->
            <div class="lg:hidden space-y-3 mb-6">
                @foreach($products as $index => $product)
                    <div class="bg-white border rounded-lg p-4 shadow-sm border-gray-200 transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: {{ $index * 0.1 }}s">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-lg">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-500">
                                    @if($product->category)
                                        <a href="{{ route('product-categories.show', $product->category) }}" class="text-primary-600 hover:text-primary-900">
                                            {{ $product->category->name }}
                                        </a>
                                    @else
                                        Uncategorized
                                    @endif
                                </p>
                            </div>
                            <div class="ml-4">
                                @if($product->isOutOfStock())
                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                @elseif($product->isLowStock())
                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Low Stock
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                        In Stock
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                            <div>
                                <p class="text-gray-500">Current Stock:</p>
                                <p class="font-medium">{{ $product->current_stock }} {{ $product->unit }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Min Level:</p>
                                <p class="font-medium">{{ $product->min_stock_level }} {{ $product->unit }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Unit:</p>
                                <p class="font-medium">{{ $product->unit }}</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-2 pt-2 border-t border-gray-100">
                            <a href="{{ route('products.show', $product) }}" class="text-primary-600 hover:text-primary-900 p-2 rounded-md hover:bg-primary-50 transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product) }}" class="text-accent-600 hover:text-accent-900 p-2 rounded-md hover:bg-accent-50 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-md hover:bg-red-50 transition-colors" onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop View - Table -->
            <div class="hidden lg:block overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Level</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $index => $product)
                                    <tr class="hover:bg-gray-50 transition-all duration-200 animate-fade-in" style="animation-delay: {{ $index * 0.05 }}s">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($product->category)
                                                <a href="{{ route('product-categories.show', $product->category) }}" class="text-primary-600 hover:text-primary-900 transition-colors">
                                                    {{ $product->category->name }}
                                                </a>
                                            @else
                                                <span class="text-gray-500">Uncategorized</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $product->current_stock }} {{ $product->unit }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $product->unit }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $product->min_stock_level }} {{ $product->unit }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($product->isOutOfStock())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
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
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-1">
                                                <a href="{{ route('products.show', $product) }}" class="text-primary-600 hover:text-primary-900 p-1 rounded hover:bg-primary-50 transition-all duration-200">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('products.edit', $product) }}" class="text-accent-600 hover:text-accent-900 p-1 rounded hover:bg-accent-50 transition-all duration-200">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-all duration-200" onclick="return confirm('Are you sure you want to delete this product?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12 animate-fade-in">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-box-open text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 mb-6">No products found. Create your first product to get started.</p>
                <a href="{{ route('products.create') }}" class="inline-block bg-accent-600 text-white px-6 py-3 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Add Product
                </a>
            </div>
        @endif
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

        /* Smooth scrolling for mobile */
        @media (max-width: 1024px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Custom hover effects */
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }

        .hover\:-translate-y-1:hover {
            transform: translateY(-4px);
        }

        /* Loading state for buttons */
        button:active {
            transform: scale(0.95);
        }

        /* Status badge animations */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
    </style>
</x-admin-layout>