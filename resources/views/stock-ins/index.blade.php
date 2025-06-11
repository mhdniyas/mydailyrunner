<x-app-layout>
    <x-slot name="title">Stock In Records</x-slot>
    <x-slot name="subtitle">Manage your inventory additions</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 animate-fade-in">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-truck mr-2 text-primary-600"></i>
                    Stock In History
                </h3>
                <p class="text-sm text-gray-500 mt-1">Track all inventory additions and purchases</p>
            </div>
            <a href="{{ route('stock-ins.create') }}" class="w-full sm:w-auto bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105 text-sm flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i> Add Stock
            </a>
        </div>

        <!-- Enhanced Summary Cards -->
        @if($stockIns->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                <div class="bg-blue-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-blue-600 font-medium">Total Entries</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">{{ $stockIns->total() }}</p>
                        </div>
                        <div class="bg-blue-100 p-2 rounded-full">
                            <i class="fas fa-clipboard-list text-blue-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-green-600 font-medium">Total Cost</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">${{ number_format($stockIns->sum('cost'), 2) }}</p>
                        </div>
                        <div class="bg-green-100 p-2 rounded-full">
                            <i class="fas fa-dollar-sign text-green-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-purple-600 font-medium">Total Quantity</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">{{ number_format($stockIns->sum('quantity'), 0) }}</p>
                        </div>
                        <div class="bg-purple-100 p-2 rounded-full">
                            <i class="fas fa-cubes text-purple-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-orange-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-orange-600 font-medium">Total Bags</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">{{ number_format($stockIns->sum('bags'), 0) }}</p>
                        </div>
                        <div class="bg-orange-100 p-2 rounded-full">
                            <i class="fas fa-shopping-bag text-orange-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($stockIns->count() > 0)
            <!-- Mobile View - Cards -->
            <div class="lg:hidden space-y-3 mb-6">
                @foreach($stockIns as $index => $stockIn)
                    <div class="bg-white border rounded-lg p-4 shadow-sm border-gray-200 transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: {{ ($index * 0.05) + 0.4 }}s">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-lg">
                                    <a href="{{ route('products.show', $stockIn->product) }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $stockIn->product->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500">{{ $stockIn->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="ml-4 text-right">
                                <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full 
                                    @if(($stockIn->calculation_method ?? 'formula_minus_half') === 'manual') bg-yellow-100 text-yellow-800
                                    @elseif(($stockIn->calculation_method ?? 'formula_minus_half') === 'formula_direct') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    @if(($stockIn->calculation_method ?? 'formula_minus_half') === 'manual') Manual
                                    @elseif(($stockIn->calculation_method ?? 'formula_minus_half') === 'formula_direct') Direct
                                    @else Formula @endif
                                </span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                            <div>
                                <p class="text-gray-500">Quantity:</p>
                                <p class="font-medium">{{ $stockIn->quantity }} {{ $stockIn->product->unit }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Bags:</p>
                                <p class="font-medium">{{ $stockIn->bags }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Cost:</p>
                                <p class="font-medium text-green-600">${{ number_format($stockIn->cost, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Bag Weight:</p>
                                <p class="font-medium">{{ number_format($stockIn->getActualBagWeight(), 2) }} {{ $stockIn->product->unit }}</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('stock-ins.show', $stockIn) }}" class="text-primary-600 hover:text-primary-900 p-2 rounded-md hover:bg-primary-50 transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('stock-ins.edit', $stockIn) }}" class="text-accent-600 hover:text-accent-900 p-2 rounded-md hover:bg-accent-50 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('stock-ins.destroy', $stockIn) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-md hover:bg-red-50 transition-colors" onclick="return confirm('Are you sure you want to delete this stock entry?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop View - Table -->
            <div class="hidden lg:block overflow-x-auto animate-fade-in" style="animation-delay: 0.4s">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bags</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bag Weight</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($stockIns as $index => $stockIn)
                                    <tr class="hover:bg-gray-50 transition-all duration-200 animate-fade-in" style="animation-delay: {{ ($index * 0.03) + 0.5 }}s">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <div class="flex flex-col">
                                                <span class="font-medium">{{ $stockIn->created_at->format('M d, Y') }}</span>
                                                <span class="text-xs text-gray-500">{{ $stockIn->created_at->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('products.show', $stockIn->product) }}" class="text-primary-600 hover:text-primary-900 transition-colors font-medium">
                                                {{ $stockIn->product->name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <span class="font-medium">{{ $stockIn->quantity }}</span>
                                            <span class="text-gray-500">{{ $stockIn->product->unit }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $stockIn->bags }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-green-600">
                                            ${{ number_format($stockIn->cost, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <span class="font-medium">{{ number_format($stockIn->getActualBagWeight(), 2) }}</span>
                                            <span class="text-gray-500">{{ $stockIn->product->unit }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if(($stockIn->calculation_method ?? 'formula_minus_half') === 'manual') bg-yellow-100 text-yellow-800
                                                @elseif(($stockIn->calculation_method ?? 'formula_minus_half') === 'formula_direct') bg-blue-100 text-blue-800
                                                @else bg-green-100 text-green-800 @endif">
                                                @if(($stockIn->calculation_method ?? 'formula_minus_half') === 'manual') Manual
                                                @elseif(($stockIn->calculation_method ?? 'formula_minus_half') === 'formula_direct') Direct
                                                @else Formula @endif
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-1">
                                                <a href="{{ route('stock-ins.show', $stockIn) }}" class="text-primary-600 hover:text-primary-900 p-1 rounded hover:bg-primary-50 transition-all duration-200" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('stock-ins.edit', $stockIn) }}" class="text-accent-600 hover:text-accent-900 p-1 rounded hover:bg-accent-50 transition-all duration-200" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('stock-ins.destroy', $stockIn) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-all duration-200" onclick="return confirm('Are you sure you want to delete this stock entry?')" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Summary Row -->
                                <tr class="bg-gray-50 font-semibold border-t-2 border-gray-300">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm" colspan="2">
                                        Total ({{ $stockIns->count() }} entries on this page)
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ number_format($stockIns->sum('quantity'), 0) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ number_format($stockIns->sum('bags'), 0) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600">
                                        ${{ number_format($stockIns->sum('cost'), 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $stockIns->count() > 0 ? number_format($stockIns->sum('quantity') / $stockIns->sum('bags'), 2) : '0.00' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm" colspan="2">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                {{ $stockIns->links() }}
            </div>
        @else
            <div class="text-center py-12 animate-fade-in">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-truck-loading text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-lg mb-2">No stock in records found</p>
                <p class="text-gray-400 text-sm mb-6">Start adding inventory to track your stock movements</p>
                <a href="{{ route('stock-ins.create') }}" class="inline-block bg-accent-600 text-white px-6 py-3 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Add Stock
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

        /* Enhanced badge styling */
        .bg-yellow-100 {
            background-color: #fef3c7;
        }
        .text-yellow-800 {
            color: #92400e;
        }
        .bg-blue-100 {
            background-color: #dbeafe;
        }
        .text-blue-800 {
            color: #1e40af;
        }
        .bg-green-100 {
            background-color: #dcfce7;
        }
        .text-green-800 {
            color: #166534;
        }

        /* Table hover enhancement */
        .hover\:bg-gray-50:hover {
            background-color: #f9fafb;
        }

        /* Action button hover effects */
        .hover\:bg-primary-50:hover {
            background-color: #eff6ff;
        }
        .hover\:bg-accent-50:hover {
            background-color: #f0f9ff;
        }
        .hover\:bg-red-50:hover {
            background-color: #fef2f2;
        }

        /* Date column styling */
        .text-xs {
            font-size: 0.75rem;
        }

        /* Enhanced responsiveness for very small screens */
        @media (max-width: 640px) {
            .grid-cols-2 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
        }
    </style>
</x-app-layout>