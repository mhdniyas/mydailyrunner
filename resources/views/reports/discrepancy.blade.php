<x-app-layout>
    <x-slot name="title">Discrepancy Report</x-slot>
    <x-slot name="subtitle">Track inventory discrepancies</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <form action="{{ route('reports.discrepancy') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <select name="product_id" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ $selectedProduct == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="from_date" value="{{ $from_date }}" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                    <input type="date" name="to_date" value="{{ $to_date }}" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </form>
            </div>
            <div>
                <a href="{{ route('export.pdf', 'discrepancy') }}?product_id={{ $selectedProduct }}&from_date={{ $from_date }}&to_date={{ $to_date }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-file-export mr-2"></i> Export
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-primary-50 p-4 rounded-md">
                <p class="text-sm text-primary-600 font-medium">Total Discrepancies</p>
                <p class="text-2xl font-bold mt-1">{{ $discrepancyCount }}</p>
            </div>
            <div class="bg-red-50 p-4 rounded-md">
                <p class="text-sm text-red-600 font-medium">Negative Discrepancies</p>
                <p class="text-2xl font-bold mt-1">{{ $negativeCount }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-md">
                <p class="text-sm text-green-600 font-medium">Positive Discrepancies</p>
                <p class="text-2xl font-bold mt-1">{{ $positiveCount }}</p>
            </div>
        </div>

        @if($discrepancies->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discrepancy</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($discrepancies as $check)
                            <tr class="{{ $check->discrepancy < 0 ? 'bg-red-50' : ($check->discrepancy > 0 ? 'bg-green-50' : '') }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $check->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="capitalize">{{ $check->check_type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('products.show', $check->product) }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $check->product->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $check->system_stock }} {{ $check->product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $check->physical_stock }} {{ $check->product->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium {{ $check->discrepancy < 0 ? 'text-red-600' : ($check->discrepancy > 0 ? 'text-green-600' : '') }}">
                                    {{ $check->discrepancy > 0 ? '+' : '' }}{{ $check->discrepancy }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $check->discrepancy_percent }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $check->notes ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $discrepancies->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No discrepancies found for the selected criteria.</p>
            </div>
        @endif
    </div>
</x-app-layout>