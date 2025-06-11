<x-app-layout>
    <x-slot name="title">Stock Check: {{ $date }}</x-slot>
    <x-slot name="subtitle">Daily inventory verification results</x-slot>

    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-6 animate-fade-in">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-calendar-check mr-2 text-primary-600"></i>
                    Stock Check: {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::parse($date)->format('l') }} • Daily inventory verification</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 text-xs font-medium rounded-full {{ ($morningChecks->count() > 0 && $eveningChecks->count() > 0) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ ($morningChecks->count() > 0 && $eveningChecks->count() > 0) ? 'Complete' : 'Partial' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <div class="bg-yellow-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-600 font-medium">Morning Status</p>
                    <p class="text-lg font-bold mt-1">{{ $morningChecks->count() > 0 ? 'Done' : 'Pending' }}</p>
                </div>
                <div class="bg-yellow-100 p-2 rounded-full">
                    <i class="fas fa-sun text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-600 font-medium">Evening Status</p>
                    <p class="text-lg font-bold mt-1">{{ $eveningChecks->count() > 0 ? 'Done' : 'Pending' }}</p>
                </div>
                <div class="bg-purple-100 p-2 rounded-full">
                    <i class="fas fa-moon text-purple-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-red-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-600 font-medium">Total Issues</p>
                    <p class="text-lg font-bold mt-1">{{ $morningDiscrepancyCount + $eveningDiscrepancyCount }}</p>
                </div>
                <div class="bg-red-100 p-2 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-blue-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.3s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 font-medium">Net Difference</p>
                    <p class="text-lg font-bold mt-1">{{ $totalMorningDiscrepancy + $totalEveningDiscrepancy }}</p>
                </div>
                <div class="bg-blue-100 p-2 rounded-full">
                    <i class="fas fa-balance-scale text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Morning Check -->
        <div class="bg-white rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl animate-fade-in" style="animation-delay: 0.4s">
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-t-lg p-4 sm:p-6 border-l-4 border-yellow-400">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-sun mr-2 text-yellow-500"></i> Morning Check
                    </h3>
                    @if($morningChecks->count() == 0)
                        <a href="{{ route('daily-stock-checks.create.type', 'morning') }}" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 transition-colors text-sm">
                            <i class="fas fa-plus mr-2"></i> Perform Check
                        </a>
                    @endif
                </div>
            </div>

            <div class="p-4 sm:p-6">
                @if($morningChecks->count() > 0)
                    <!-- Check Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Time:</span>
                                <span class="font-medium ml-2">{{ $morningChecks->first()->created_at->format('h:i A') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">By:</span>
                                <span class="font-medium ml-2">{{ $morningChecks->first()->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Discrepancies:</span>
                                <span class="font-medium ml-2 {{ $morningDiscrepancyCount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $morningDiscrepancyCount }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500">Net Difference:</span>
                                <span class="font-medium ml-2 {{ $totalMorningDiscrepancy > 0 ? 'text-green-600' : ($totalMorningDiscrepancy < 0 ? 'text-red-600' : '') }}">
                                    {{ $totalMorningDiscrepancy > 0 ? '+' : '' }}{{ $totalMorningDiscrepancy }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile View - Cards -->
                    <div class="lg:hidden space-y-3">
                        @foreach($morningChecks as $check)
                            <div class="border rounded-lg p-3 {{ $check->discrepancy != 0 ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-white' }} transition-all duration-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $check->product->name }}</h4>
                                    @if($check->discrepancy != 0)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Issue
                                        </span>
                                    @endif
                                </div>
                                <div class="grid grid-cols-3 gap-2 text-sm">
                                    <div>
                                        <p class="text-gray-500 text-xs">System:</p>
                                        <p class="font-medium">{{ $check->system_stock }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs">Physical:</p>
                                        <p class="font-medium">{{ $check->physical_stock }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs">Diff:</p>
                                        <p class="font-medium {{ $check->discrepancy > 0 ? 'text-green-600' : ($check->discrepancy < 0 ? 'text-red-600' : '') }}">
                                            {{ $check->discrepancy > 0 ? '+' : '' }}{{ $check->discrepancy }}
                                        </p>
                                    </div>
                                </div>
                                @if($check->notes)
                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                        <p class="text-xs text-gray-600 italic">{{ $check->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop View - Table -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diff</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($morningChecks as $check)
                                    <tr class="{{ $check->discrepancy != 0 ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }} transition-colors">
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $check->product->name }}</div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm">
                                            {{ $check->system_stock }} {{ $check->product->unit }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm">
                                            {{ $check->physical_stock }} {{ $check->product->unit }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm">
                                            <span class="font-medium {{ $check->discrepancy > 0 ? 'text-green-600' : ($check->discrepancy < 0 ? 'text-red-600' : '') }}">
                                                {{ $check->discrepancy > 0 ? '+' : '' }}{{ $check->discrepancy }}
                                            </span>
                                            @if($check->discrepancy != 0)
                                                <span class="ml-2 text-xs text-gray-500">({{ $check->discrepancy_percent }}%)</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($check->notes)
                                        <tr class="{{ $check->discrepancy != 0 ? 'bg-red-50' : '' }}">
                                            <td colspan="4" class="px-3 py-2 text-sm italic text-gray-600">
                                                <i class="fas fa-sticky-note mr-1"></i>{{ $check->notes }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-sun text-yellow-500"></i>
                        </div>
                        <p class="text-gray-500 mb-4">No morning check performed on this date</p>
                        <a href="{{ route('daily-stock-checks.create.type', 'morning') }}" class="inline-block bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Perform Morning Check
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Evening Check -->
        <div class="bg-white rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl animate-fade-in" style="animation-delay: 0.5s">
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-t-lg p-4 sm:p-6 border-l-4 border-purple-400">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-moon mr-2 text-purple-500"></i> Evening Check
                    </h3>
                    @if($eveningChecks->count() == 0)
                        <a href="{{ route('daily-stock-checks.create.type', 'evening') }}" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors text-sm">
                            <i class="fas fa-plus mr-2"></i> Perform Check
                        </a>
                    @endif
                </div>
            </div>

            <div class="p-4 sm:p-6">
                @if($eveningChecks->count() > 0)
                    <!-- Check Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Time:</span>
                                <span class="font-medium ml-2">{{ $eveningChecks->first()->created_at->format('h:i A') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">By:</span>
                                <span class="font-medium ml-2">{{ $eveningChecks->first()->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Discrepancies:</span>
                                <span class="font-medium ml-2 {{ $eveningDiscrepancyCount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $eveningDiscrepancyCount }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500">Net Difference:</span>
                                <span class="font-medium ml-2 {{ $totalEveningDiscrepancy > 0 ? 'text-green-600' : ($totalEveningDiscrepancy < 0 ? 'text-red-600' : '') }}">
                                    {{ $totalEveningDiscrepancy > 0 ? '+' : '' }}{{ $totalEveningDiscrepancy }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile View - Cards -->
                    <div class="lg:hidden space-y-3">
                        @foreach($eveningChecks as $check)
                            <div class="border rounded-lg p-3 {{ $check->discrepancy != 0 ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-white' }} transition-all duration-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $check->product->name }}</h4>
                                    @if($check->discrepancy != 0)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Issue
                                        </span>
                                    @endif
                                </div>
                                <div class="grid grid-cols-3 gap-2 text-sm">
                                    <div>
                                        <p class="text-gray-500 text-xs">System:</p>
                                        <p class="font-medium">{{ $check->system_stock }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs">Physical:</p>
                                        <p class="font-medium">{{ $check->physical_stock }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs">Diff:</p>
                                        <p class="font-medium {{ $check->discrepancy > 0 ? 'text-green-600' : ($check->discrepancy < 0 ? 'text-red-600' : '') }}">
                                            {{ $check->discrepancy > 0 ? '+' : '' }}{{ $check->discrepancy }}
                                        </p>
                                    </div>
                                </div>
                                @if($check->notes)
                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                        <p class="text-xs text-gray-600 italic">{{ $check->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop View - Table -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diff</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($eveningChecks as $check)
                                    <tr class="{{ $check->discrepancy != 0 ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }} transition-colors">
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $check->product->name }}</div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm">
                                            {{ $check->system_stock }} {{ $check->product->unit }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm">
                                            {{ $check->physical_stock }} {{ $check->product->unit }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm">
                                            <span class="font-medium {{ $check->discrepancy > 0 ? 'text-green-600' : ($check->discrepancy < 0 ? 'text-red-600' : '') }}">
                                                {{ $check->discrepancy > 0 ? '+' : '' }}{{ $check->discrepancy }}
                                            </span>
                                            @if($check->discrepancy != 0)
                                                <span class="ml-2 text-xs text-gray-500">({{ $check->discrepancy_percent }}%)</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($check->notes)
                                        <tr class="{{ $check->discrepancy != 0 ? 'bg-red-50' : '' }}">
                                            <td colspan="4" class="px-3 py-2 text-sm italic text-gray-600">
                                                <i class="fas fa-sticky-note mr-1"></i>{{ $check->notes }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-moon text-purple-500"></i>
                        </div>
                        <p class="text-gray-500 mb-4">No evening check performed on this date</p>
                        <a href="{{ route('daily-stock-checks.create.type', 'evening') }}" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Perform Evening Check
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Enhanced Summary Section -->
    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mt-6 animate-fade-in" style="animation-delay: 0.6s">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
            Daily Summary
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-4 rounded-lg border-l-4 border-yellow-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-700 font-medium">Morning Check</p>
                        <p class="text-2xl font-bold mt-1 text-yellow-800">
                            {{ $morningChecks->count() > 0 ? 'Completed' : 'Not Done' }}
                        </p>
                        @if($morningChecks->count() > 0)
                            <p class="text-sm mt-2 text-yellow-600">
                                {{ $morningDiscrepancyCount }} discrepancies found
                            </p>
                        @endif
                    </div>
                    <i class="fas fa-sun text-2xl text-yellow-500"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-4 rounded-lg border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-purple-700 font-medium">Evening Check</p>
                        <p class="text-2xl font-bold mt-1 text-purple-800">
                            {{ $eveningChecks->count() > 0 ? 'Completed' : 'Not Done' }}
                        </p>
                        @if($eveningChecks->count() > 0)
                            <p class="text-sm mt-2 text-purple-600">
                                {{ $eveningDiscrepancyCount }} discrepancies found
                            </p>
                        @endif
                    </div>
                    <i class="fas fa-moon text-2xl text-purple-500"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-blue-50 to-gray-50 p-4 rounded-lg border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Total Overview</p>
                        <p class="text-2xl font-bold mt-1 text-blue-800">
                            {{ $morningDiscrepancyCount + $eveningDiscrepancyCount }} Issues
                        </p>
                        <p class="text-sm mt-2 text-blue-600">
                            Net: {{ $totalMorningDiscrepancy + $totalEveningDiscrepancy > 0 ? '+' : '' }}{{ $totalMorningDiscrepancy + $totalEveningDiscrepancy }}
                        </p>
                    </div>
                    <i class="fas fa-balance-scale text-2xl text-blue-500"></i>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
            <a href="{{ route('daily-stock-checks.index') }}" class="w-full sm:w-auto text-primary-600 hover:text-primary-900 transition-colors flex items-center justify-center sm:justify-start">
                <i class="fas fa-arrow-left mr-2"></i> Back to Stock Checks
            </a>
            
            <form action="{{ route('daily-stock-checks.destroy', $date) }}" method="POST" class="w-full sm:w-auto">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full sm:w-auto text-red-600 hover:text-red-900 transition-colors flex items-center justify-center" onclick="return confirm('Are you sure you want to delete all stock checks for this date?')">
                    <i class="fas fa-trash mr-2"></i> Delete All Checks
                </button>
            </form>
        </div>
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

        /* Enhanced table hover effects */
        .hover\:bg-gray-50:hover {
            background-color: #f9fafb;
        }

        .hover\:bg-red-100:hover {
            background-color: #fee2e2;
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
            
            .text-xl {
                font-size: 1.125rem;
            }
        }

        /* Enhanced border styling */
        .border-l-4 {
            border-left-width: 4px;
        }

        /* Status badge animations */
        .bg-green-100 {
            background-color: #dcfce7;
            transition: background-color 0.2s ease;
        }

        .bg-red-100 {
            background-color: #fee2e2;
            transition: background-color 0.2s ease;
        }

        .bg-yellow-100 {
            background-color: #fef3c7;
            transition: background-color 0.2s ease;
        }

        /* Enhanced responsiveness for cards */
        @media (max-width: 1024px) {
            .grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .grid-cols-3 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
                gap: 1rem;
            }
        }
    </style>
</x-app-layout>