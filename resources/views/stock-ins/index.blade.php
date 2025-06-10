<x-app-layout>
    <x-slot name="title">Stock In Records</x-slot>
    <x-slot name="subtitle">Manage your inventory additions</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Stock In History</h3>
            <a href="{{ route('stock-ins.create') }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                <i class="fas fa-plus mr-2"></i> Add Stock
            </a>
        </div>

        @if($stockIns->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bags</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bag Weight</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($stockIns as $stockIn)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $stockIn->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('products.show', $stockIn->product) }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $stockIn->product->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $stockIn->quantity }} {{ $stockIn->product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $stockIn->bags }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($stockIn->cost, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($stockIn->getActualBagWeight(), 2) }} {{ $stockIn->product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if(($stockIn->calculation_method ?? 'formula_minus_half') === 'manual') bg-yellow-100 text-yellow-800
                                        @elseif(($stockIn->calculation_method ?? 'formula_minus_half') === 'formula_direct') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        @if(($stockIn->calculation_method ?? 'formula_minus_half') === 'manual') Manual
                                        @elseif(($stockIn->calculation_method ?? 'formula_minus_half') === 'formula_direct') Direct
                                        @else Formula @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('stock-ins.show', $stockIn) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('stock-ins.edit', $stockIn) }}" class="text-accent-600 hover:text-accent-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('stock-ins.destroy', $stockIn) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this stock entry?')">
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
                {{ $stockIns->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No stock in records found.</p>
                <a href="{{ route('stock-ins.create') }}" class="mt-4 inline-block bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-plus mr-2"></i> Add Stock
                </a>
            </div>
        @endif
    </div>
</x-app-layout>