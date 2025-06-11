<x-app-layout>
    <x-slot name="title">Discrepancy Report</x-slot>
    <x-slot name="subtitle">Track inventory discrepancies</x-slot>

    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <!-- Filter Form & Export Button -->
        <div class="flex flex-col gap-4 mb-6">
            <div class="w-full">
                <form action="{{ route('reports.discrepancy') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-center gap-2">
                    <select name="product_id" class="w-full sm:w-auto border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ $selectedProduct == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <input type="date" name="from_date" value="{{ $fromDate }}" class="flex-1 sm:flex-none border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm" placeholder="From Date">
                        <input type="date" name="to_date" value="{{ $toDate }}" class="flex-1 sm:flex-none border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm" placeholder="To Date">
                    </div>
                    
                    <button type="submit" class="w-full sm:w-auto bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors text-sm flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </form>
            </div>
            <div class="w-full sm:w-auto flex justify-start">
                <a href="{{ route('export.pdf', 'discrepancy') }}?product_id={{ $selectedProduct }}&from_date={{ $fromDate }}&to_date={{ $toDate }}" class="w-full sm:w-auto bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-colors text-sm flex items-center justify-center">
                    <i class="fas fa-file-export mr-2"></i> Export
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
            <div class="bg-primary-50 p-3 sm:p-4 rounded-md shadow-sm transition-all duration-300 hover:shadow">
                <p class="text-xs sm:text-sm text-primary-600 font-medium">Total Discrepancies</p>
                <p class="text-xl sm:text-2xl font-bold mt-1">{{ $discrepancies->count() }}</p>
            </div>
            <div class="bg-red-50 p-3 sm:p-4 rounded-md shadow-sm transition-all duration-300 hover:shadow">
                <p class="text-xs sm:text-sm text-red-600 font-medium">Negative Discrepancies</p>
                <p class="text-xl sm:text-2xl font-bold mt-1">{{ $discrepancies->where('discrepancy', '<', 0)->count() }}</p>
            </div>
            <div class="bg-green-50 p-3 sm:p-4 rounded-md shadow-sm transition-all duration-300 hover:shadow">
                <p class="text-xs sm:text-sm text-green-600 font-medium">Positive Discrepancies</p>
                <p class="text-xl sm:text-2xl font-bold mt-1">{{ $discrepancies->where('discrepancy', '>', 0)->count() }}</p>
            </div>
        </div>

        @if($discrepancies->count() > 0)
            <!-- Mobile View - Cards -->
            <div class="lg:hidden space-y-3 mb-6">
                @foreach($discrepancies as $check)
                    <div class="bg-white border rounded-md p-3 shadow-sm {{ $check->discrepancy < 0 ? 'border-red-200' : ($check->discrepancy > 0 ? 'border-green-200' : 'border-gray-200') }}">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium">{{ $check->product->name }}</span>
                            <span class="text-sm text-gray-500">{{ $check->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <p class="text-gray-500">Check Type:</p>
                                <p class="capitalize">{{ $check->check_type }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Discrepancy:</p>
                                <p class="font-medium {{ $check->discrepancy < 0 ? 'text-red-600' : ($check->discrepancy > 0 ? 'text-green-600' : '') }}">
                                    {{ $check->discrepancy > 0 ? '+' : '' }}{{ $check->discrepancy }} ({{ $check->discrepancy_percent }}%)
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">System Stock:</p>
                                <p>{{ $check->system_stock }} {{ $check->product->unit }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Physical Stock:</p>
                                <p>{{ $check->physical_stock }} {{ $check->product->unit }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-500">Notes:</p>
                                <p class="truncate">{{ $check->notes ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop View - Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discrepancy</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($discrepancies as $check)
                            <tr class="{{ $check->discrepancy < 0 ? 'bg-red-50' : ($check->discrepancy > 0 ? 'bg-green-50' : '') }}">
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    {{ $check->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <span class="capitalize">{{ $check->check_type }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <a href="{{ route('products.show', $check->product) }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $check->product->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    {{ $check->system_stock }} {{ $check->product->unit }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    {{ $check->physical_stock }} {{ $check->product->unit }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-sm {{ $check->discrepancy < 0 ? 'text-red-600' : ($check->discrepancy > 0 ? 'text-green-600' : '') }}">
                                    {{ $check->discrepancy > 0 ? '+' : '' }}{{ $check->discrepancy }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    {{ $check->discrepancy_percent }}%
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm max-w-[150px] truncate">
                                    {{ $check->notes ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                        <!-- Totals Row -->
                        <tr class="bg-gray-50 font-semibold">
                            <td class="px-4 py-3 whitespace-nowrap text-sm" colspan="3">
                                Totals:
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                {{ $discrepancies->sum('system_stock') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                {{ $discrepancies->sum('physical_stock') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm {{ $discrepancies->sum('discrepancy') < 0 ? 'text-red-600' : ($discrepancies->sum('discrepancy') > 0 ? 'text-green-600' : '') }}">
                                {{ $discrepancies->sum('discrepancy') > 0 ? '+' : '' }}{{ $discrepancies->sum('discrepancy') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm" colspan="2"></td>
                        </tr>
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