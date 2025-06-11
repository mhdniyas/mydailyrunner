<x-app-layout>
    <x-slot name="title">{{ $title ?? 'Product Categories' }}</x-slot>
    <x-slot name="subtitle">{{ $subtitle ?? 'Manage your product categories' }}</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Enhanced Header Section -->
        <div class="mb-6 animate-fade-in">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center mb-2">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-accent-100 rounded-lg mr-3">
                            <i class="fas fa-tags text-xl text-accent-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Product Categories</h3>
                            <p class="text-sm text-gray-500">June 11, 2025 • 11:29 AM • mhdniyas</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Organize your products into categories for better management and organization</p>
                </div>
                <a href="{{ route('product-categories.create') }}" class="w-full sm:w-auto bg-accent-600 text-white px-6 py-3 rounded-lg hover:bg-accent-700 transition-all duration-200 transform hover:scale-105 shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Add New Category
                </a>
            </div>
        </div>

        <!-- Summary Statistics -->
        @if($categories->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6 animate-fade-in" style="animation-delay: 0.1s">
                <div class="bg-blue-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-blue-600 font-medium">Total Categories</p>
                            <p class="text-2xl font-bold text-blue-800">{{ $categories->total() }}</p>
                        </div>
                        <div class="bg-blue-100 p-2 rounded-full">
                            <i class="fas fa-tags text-blue-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-green-600 font-medium">Total Products</p>
                            <p class="text-2xl font-bold text-green-800">{{ $categories->sum(function($category) { return $category->products->count(); }) }}</p>
                        </div>
                        <div class="bg-green-100 p-2 rounded-full">
                            <i class="fas fa-box text-green-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-purple-600 font-medium">Active Categories</p>
                            <p class="text-2xl font-bold text-purple-800">{{ $categories->filter(function($category) { return $category->products->count() > 0; })->count() }}</p>
                        </div>
                        <div class="bg-purple-100 p-2 rounded-full">
                            <i class="fas fa-check-circle text-purple-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-orange-600 font-medium">Empty Categories</p>
                            <p class="text-2xl font-bold text-orange-800">{{ $categories->filter(function($category) { return $category->products->count() === 0; })->count() }}</p>
                        </div>
                        <div class="bg-orange-100 p-2 rounded-full">
                            <i class="fas fa-exclamation-triangle text-orange-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($categories->count() > 0)
            <!-- Mobile Cards View -->
            <div class="lg:hidden space-y-4 mb-6 animate-fade-in" style="animation-delay: 0.2s">
                @foreach($categories as $index => $category)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: {{ ($index * 0.05) + 0.3 }}s">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <div class="w-10 h-10 bg-accent-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-tag text-accent-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $category->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $category->products->count() }} product{{ $category->products->count() !== 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                @if($category->products->count() > 0)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Empty
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($category->description)
                            <div class="mb-3 p-3 bg-gray-50 rounded border">
                                <p class="text-sm text-gray-600">{{ $category->description }}</p>
                            </div>
                        @else
                            <div class="mb-3 p-3 bg-gray-50 rounded border">
                                <p class="text-sm text-gray-400 italic">No description provided</p>
                            </div>
                        @endif
                        
                        <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('product-categories.show', $category) }}" class="text-primary-600 hover:text-primary-900 p-2 rounded-full hover:bg-primary-50 transition-colors" title="View Category">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('product-categories.edit', $category) }}" class="text-accent-600 hover:text-accent-900 p-2 rounded-full hover:bg-accent-50 transition-colors" title="Edit Category">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('product-categories.destroy', $category) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-full hover:bg-red-50 transition-colors" 
                                        title="Delete Category"
                                        onclick="return confirm('Are you sure you want to delete {{ $category->name }}? This will unassign the category from all products but will not delete the products.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto animate-fade-in" style="animation-delay: 0.2s">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-tag mr-2"></i>
                                            Category Name
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-alt mr-2"></i>
                                            Description
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-box mr-2"></i>
                                            Products
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-chart-bar mr-2"></i>
                                            Status
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center justify-end">
                                            <i class="fas fa-cogs mr-2"></i>
                                            Actions
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($categories as $index => $category)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 animate-fade-in" style="animation-delay: {{ ($index * 0.03) + 0.3 }}s">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-accent-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-tag text-accent-600"></i>
                                                </div>
                                                <div class="font-medium text-gray-900">{{ $category->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-500 max-w-xs">
                                                @if($category->description)
                                                    {{ Str::limit($category->description, 50) }}
                                                    @if(strlen($category->description) > 50)
                                                        <span class="text-primary-600 cursor-pointer hover:text-primary-800" title="{{ $category->description }}">...</span>
                                                    @endif
                                                @else
                                                    <span class="italic text-gray-400">No description</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-2xl font-bold text-primary-600">{{ $category->products->count() }}</span>
                                                <span class="ml-2 text-sm text-gray-500">product{{ $category->products->count() !== 1 ? 's' : '' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($category->products->count() > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Empty
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('product-categories.show', $category) }}" class="text-primary-600 hover:text-primary-900 p-2 rounded-full hover:bg-primary-50 transition-colors" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('product-categories.edit', $category) }}" class="text-accent-600 hover:text-accent-900 p-2 rounded-full hover:bg-accent-50 transition-colors" title="Edit Category">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('product-categories.destroy', $category) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-full hover:bg-red-50 transition-colors" 
                                                            title="Delete Category"
                                                            onclick="return confirm('Are you sure you want to delete {{ $category->name }}? This will unassign the category from all products but will not delete the products.')">
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
            
            <!-- Pagination -->
            <div class="mt-6 animate-fade-in" style="animation-delay: 0.4s">
                {{ $categories->links() }}
            </div>
        @else
            <!-- Enhanced Empty State -->
            <div class="text-center py-16 animate-fade-in" style="animation-delay: 0.2s">
                <div class="mx-auto w-24 h-24 bg-accent-100 rounded-full flex items-center justify-center mb-6 shadow-lg">
                    <i class="fas fa-tags text-4xl text-accent-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Categories Yet</h3>
                <p class="text-lg text-gray-600 mb-2">Start organizing your products by creating categories.</p>
                <p class="text-sm text-gray-500 mb-8 max-w-md mx-auto">Categories help you organize and manage your products more efficiently. Create your first category to get started.</p>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('product-categories.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-accent-600 hover:bg-accent-700 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>
                        Create First Category
                    </a>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-box mr-2"></i>
                        View Products
                    </a>
                </div>
                
                <!-- Benefits Section -->
                <div class="mt-12 bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Benefits of Using Categories</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-search text-blue-600"></i>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">Easy Search</h5>
                                <p class="text-gray-600">Find products quickly by category</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <i class="fas fa-chart-line text-green-600"></i>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">Better Analytics</h5>
                                <p class="text-gray-600">Track performance by category</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-purple-100 p-2 rounded-full mr-3">
                                <i class="fas fa-cogs text-purple-600"></i>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">Organization</h5>
                                <p class="text-gray-600">Keep your inventory organized</p>
                            </div>
                        </div>
                    </div>
                </div>
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

        /* Enhanced form styling */
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Gradient backgrounds */
        .bg-gradient-to-r {
            background: linear-gradient(to right, var(--tw-gradient-stops));
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .text-2xl {
                font-size: 1.5rem;
            }
        }

        /* Enhanced shadow styling */
        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Transition enhancements */
        .transition-colors {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Tooltip enhancement */
        [title]:hover {
            position: relative;
        }

        /* Status badge styling */
        .bg-green-100 {
            background-color: #dcfce7;
        }

        .bg-orange-100 {
            background-color: #fed7aa;
        }

        .text-green-800 {
            color: #166534;
        }

        .text-orange-800 {
            color: #9a3412;
        }
    </style>
</x-app-layout>