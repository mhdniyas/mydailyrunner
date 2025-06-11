<x-admin-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="subtitle">{{ $subtitle }}</x-slot>

    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <!-- Filter Form & Export Button -->
        <div class="flex flex-col gap-4 mb-6">
            <div class="w-full">
                <form action="{{ route('reports.category-discrepancies') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-center gap-2">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="flex-1 sm:flex-none border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm" placeholder="Start Date">
                        <input type="date" name="end_date" value="{{ $endDate }}" class="flex-1 sm:flex-none border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm" placeholder="End Date">
                    </div>
                    
                    <button type="submit" class="w-full sm:w-auto bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors text-sm flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </form>
            </div>
            <div class="w-full sm:w-auto flex justify-start">
                <a href="{{ route('export.pdf', 'category-discrepancies') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="w-full sm:w-auto bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-colors text-sm flex items-center justify-center">
                    <i class="fas fa-file-export mr-2"></i> Export
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
            <div class="bg-primary-50 p-3 sm:p-4 rounded-md shadow-sm transition-all duration-300 hover:shadow">
                <p class="text-xs sm:text-sm text-primary-600 font-medium">Total Categories</p>
                <p class="text-xl sm:text-2xl font-bold mt-1">{{ count($categoryData) }}</p>
            </div>
            <div class="bg-green-50 p-3 sm:p-4 rounded-md shadow-sm transition-all duration-300 hover:shadow">
                <p class="text-xs sm:text-sm text-green-600 font-medium">Total Products</p>
                <p class="text-xl sm:text-2xl font-bold mt-1">{{ collect($categoryData)->sum('product_count') }}</p>
            </div>
            <div class="bg-red-50 p-3 sm:p-4 rounded-md shadow-sm transition-all duration-300 hover:shadow">
                <p class="text-xs sm:text-sm text-red-600 font-medium">Total Discrepancies</p>
                <p class="text-xl sm:text-2xl font-bold mt-1">{{ collect($categoryData)->sum('discrepancy_count') }}</p>
            </div>
        </div>

        @if(count($categoryData) > 0)
            <!-- Mobile View - Cards -->
            <div class="lg:hidden space-y-3 mb-6">
                @foreach($categoryData as $id => $data)
                    <div class="bg-white border rounded-md p-3 shadow-sm border-gray-200">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium">
                                @if($id === 'uncategorized')
                                    <span class="text-gray-500">Uncategorized</span>
                                @else
                                    <a href="{{ route('product-categories.show', $data['category']) }}" class="text-primary-600 hover:text-primary-900">
                                        {{ $data['category']->name }}
                                    </a>
                                @endif
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <p class="text-gray-500">Products:</p>
                                <p>{{ $data['product_count'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Stock Checks:</p>
                                <p>{{ $data['check_count'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Discrepancies:</p>
                                <p>{{ $data['discrepancy_count'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Total Discrepancy:</p>
                                <p>{{ number_format($data['total_discrepancy'], 2) }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-500">Discrepancy Rate:</p>
                                <p>
                                    @if($data['check_count'] > 0)
                                        {{ number_format(($data['discrepancy_count'] / $data['check_count']) * 100, 1) }}%
                                    @else
                                        N/A
                                    @endif
                                </p>
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
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Products
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stock Checks
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Discrepancies
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Discrepancy
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Discrepancy Rate
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categoryData as $id => $data)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    @if($id === 'uncategorized')
                                        <span class="text-gray-500">Uncategorized</span>
                                    @else
                                        <a href="{{ route('product-categories.show', $data['category']) }}" class="text-primary-600 hover:text-primary-900">
                                            {{ $data['category']->name }}
                                        </a>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $data['product_count'] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $data['check_count'] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $data['discrepancy_count'] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($data['total_discrepancy'], 2) }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    @if($data['check_count'] > 0)
                                        {{ number_format(($data['discrepancy_count'] / $data['check_count']) * 100, 1) }}%
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <!-- Totals Row -->
                        <tr class="bg-gray-50 font-semibold">
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                Totals
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                {{ collect($categoryData)->sum('product_count') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                {{ collect($categoryData)->sum('check_count') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                {{ collect($categoryData)->sum('discrepancy_count') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                {{ number_format(collect($categoryData)->sum('total_discrepancy'), 2) }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                @php
                                    $totalChecks = collect($categoryData)->sum('check_count');
                                    $totalDisc = collect($categoryData)->sum('discrepancy_count');
                                @endphp
                                @if($totalChecks > 0)
                                    {{ number_format(($totalDisc / $totalChecks) * 100, 1) }}%
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No category discrepancy data available.</p>
            </div>
        @endif
    </div>
</x-admin-layout>