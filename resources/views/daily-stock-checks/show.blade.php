<x-app-layout>
    <x-slot name="title">Stock Check: {{ $date }}</x-slot>
    <x-slot name="subtitle">Daily inventory verification results</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Morning Check -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-sun mr-2 text-yellow-500"></i> Morning Check
                </h3>
                @if($morningChecks->count() == 0)
                    <a href="{{ route('daily-stock-checks.create.type', 'morning') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                        <i class="fas fa-plus mr-2"></i> Perform Check
                    </a>
                @endif
            </div>

            @if($morningChecks->count() > 0)
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-500 mb-2">
                        <span>Time: {{ $morningChecks->first()->created_at->format('h:i A') }}</span>
                        <span>By: {{ $morningChecks->first()->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Total Discrepancies: {{ $morningDiscrepancyCount }}</span>
                        <span class="text-sm">Net Difference: {{ $totalMorningDiscrepancy }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diff</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($morningChecks as $check)
                                <tr class="{{ $check->discrepancy != 0 ? 'bg-red-50' : '' }}">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $check->product->name }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ $check->system_stock }} {{ $check->product->unit }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ $check->physical_stock }} {{ $check->product->unit }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="{{ $check->discrepancy > 0 ? 'text-green-600' : ($check->discrepancy < 0 ? 'text-red-600' : '') }}">
                                            {{ $check->discrepancy > 0 ? '+' : '' }}{{ $check->discrepancy }}
                                        </span>
                                        @if($check->discrepancy != 0)
                                            <span class="ml-2 text-xs text-gray-500">({{ $check->discrepancy_percent }}%)</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($check->notes)
                                    <tr class="{{ $check->discrepancy != 0 ? 'bg-red-50' : '' }}">
                                        <td colspan="4" class="px-4 py-2 text-sm italic text-gray-600">
                                            Note: {{ $check->notes }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">No morning check performed on this date.</p>
                </div>
            @endif
        </div>

        <!-- Evening Check -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-moon mr-2 text-indigo-500"></i> Evening Check
                </h3>
                @if($eveningChecks->count() == 0)
                    <a href="{{ route('daily-stock-checks.create.type', 'evening') }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                        <i class="fas fa-plus mr-2"></i> Perform Check
                    </a>
                @endif
            </div>

            @if($eveningChecks->count() > 0)
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-500 mb-2">
                        <span>Time: {{ $eveningChecks->first()->created_at->format('h:i A') }}</span>
                        <span>By: {{ $eveningChecks->first()->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Total Discrepancies: {{ $eveningDiscrepancyCount }}</span>
                        <span class="text-sm">Net Difference: {{ $totalEveningDiscrepancy }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diff</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($eveningChecks as $check)
                                <tr class="{{ $check->discrepancy != 0 ? 'bg-red-50' : '' }}">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $check->product->name }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ $check->system_stock }} {{ $check->product->unit }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ $check->physical_stock }} {{ $check->product->unit }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="{{ $check->discrepancy > 0 ? 'text-green-600' : ($check->discrepancy < 0 ? 'text-red-600' : '') }}">
                                            {{ $check->discrepancy > 0 ? '+' : '' }}{{ $check->discrepancy }}
                                        </span>
                                        @if($check->discrepancy != 0)
                                            <span class="ml-2 text-xs text-gray-500">({{ $check->discrepancy_percent }}%)</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($check->notes)
                                    <tr class="{{ $check->discrepancy != 0 ? 'bg-red-50' : '' }}">
                                        <td colspan="4" class="px-4 py-2 text-sm italic text-gray-600">
                                            Note: {{ $check->notes }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">No evening check performed on this date.</p>
                </div>
            @endif
        </div>

        <!-- Summary -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Summary</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-primary-50 p-4 rounded-md">
                    <p class="text-sm text-primary-600 font-medium">Morning Check</p>
                    <p class="text-2xl font-bold mt-1">
                        {{ $morningChecks->count() > 0 ? 'Completed' : 'Not Done' }}
                    </p>
                    @if($morningChecks->count() > 0)
                        <p class="text-sm mt-2">
                            {{ $morningDiscrepancyCount }} discrepancies found
                        </p>
                    @endif
                </div>
                
                <div class="bg-accent-50 p-4 rounded-md">
                    <p class="text-sm text-accent-600 font-medium">Evening Check</p>
                    <p class="text-2xl font-bold mt-1">
                        {{ $eveningChecks->count() > 0 ? 'Completed' : 'Not Done' }}
                    </p>
                    @if($eveningChecks->count() > 0)
                        <p class="text-sm mt-2">
                            {{ $eveningDiscrepancyCount }} discrepancies found
                        </p>
                    @endif
                </div>
                
                <div class="bg-gray-50 p-4 rounded-md">
                    <p class="text-sm text-gray-600 font-medium">Total Discrepancies</p>
                    <p class="text-2xl font-bold mt-1">
                        {{ $morningDiscrepancyCount + $eveningDiscrepancyCount }}
                    </p>
                    <p class="text-sm mt-2">
                        Net difference: {{ $totalMorningDiscrepancy + $totalEveningDiscrepancy }}
                    </p>
                </div>
            </div>
            
            <div class="mt-6 flex justify-between">
                <a href="{{ route('daily-stock-checks.index') }}" class="text-primary-600 hover:text-primary-900">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Stock Checks
                </a>
                
                <form action="{{ route('daily-stock-checks.destroy', $date) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete all stock checks for this date?')">
                        <i class="fas fa-trash mr-1"></i> Delete All Checks
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>