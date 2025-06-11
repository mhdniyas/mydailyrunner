<x-admin-layout>
    <x-slot name="title">Daily Stock Checks</x-slot>
    <x-slot name="subtitle">Track and verify your inventory</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4 animate-fade-in">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-clipboard-check mr-2 text-primary-600"></i>
                    Stock Check History
                </h3>
                <p class="text-sm text-gray-500 mt-1">Monitor daily inventory verification activities</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('daily-stock-checks.create.type', 'morning') }}" class="bg-gradient-to-r from-yellow-500 to-orange-600 text-white px-4 py-2 rounded-md hover:from-yellow-600 hover:to-orange-700 text-center transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-sun mr-2"></i> Morning Check
                </a>
                <a href="{{ route('daily-stock-checks.create.type', 'evening') }}" class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-4 py-2 rounded-md hover:from-purple-600 hover:to-indigo-700 text-center transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-moon mr-2"></i> Evening Check
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        @if($dates->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                <div class="bg-blue-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-blue-600 font-medium">Total Dates</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">{{ $dates->total() }}</p>
                        </div>
                        <div class="bg-blue-100 p-2 rounded-full">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-green-600 font-medium">Completed</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">
                                {{ collect($summaries)->where('morning', true)->where('evening', true)->count() }}
                            </p>
                        </div>
                        <div class="bg-green-100 p-2 rounded-full">
                            <i class="fas fa-check-double text-green-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-yellow-600 font-medium">Partial</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">
                                {{ collect($summaries)->filter(function($summary) { 
                                    return ($summary['morning'] && !$summary['evening']) || (!$summary['morning'] && $summary['evening']); 
                                })->count() }}
                            </p>
                        </div>
                        <div class="bg-yellow-100 p-2 rounded-full">
                            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-red-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-red-600 font-medium">Issues</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">
                                {{ collect($summaries)->sum('discrepancies') }}
                            </p>
                        </div>
                        <div class="bg-red-100 p-2 rounded-full">
                            <i class="fas fa-exclamation-circle text-red-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($dates->count() > 0)
            <!-- Mobile View - Cards -->
            <div class="lg:hidden space-y-3 mb-6">
                @foreach($dates as $index => $date)
                    <div class="bg-white border rounded-lg p-4 shadow-sm border-gray-200 transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: {{ ($index * 0.05) + 0.4 }}s">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-lg">
                                    {{ \Carbon\Carbon::parse($date->check_date)->format('M d, Y') }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($date->check_date)->format('l') }}
                                </p>
                            </div>
                            <div class="ml-4 text-right">
                                @if($summaries[$date->check_date]['discrepancies'] > 0)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $summaries[$date->check_date]['discrepancies'] }} Issues
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        No Issues
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                            <div class="bg-yellow-50 p-2 rounded border">
                                <p class="text-gray-500 text-xs">Morning Check:</p>
                                @if($summaries[$date->check_date]['morning'])
                                    <p class="font-medium text-green-600 flex items-center">
                                        <i class="fas fa-check mr-1"></i> Completed
                                    </p>
                                @else
                                    <p class="font-medium text-red-600 flex items-center">
                                        <i class="fas fa-times mr-1"></i> Pending
                                    </p>
                                @endif
                            </div>
                            <div class="bg-purple-50 p-2 rounded border">
                                <p class="text-gray-500 text-xs">Evening Check:</p>
                                @if($summaries[$date->check_date]['evening'])
                                    <p class="font-medium text-green-600 flex items-center">
                                        <i class="fas fa-check mr-1"></i> Completed
                                    </p>
                                @else
                                    <p class="font-medium text-red-600 flex items-center">
                                        <i class="fas fa-times mr-1"></i> Pending
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('daily-stock-checks.show', $date->check_date) }}" class="text-primary-600 hover:text-primary-900 p-2 rounded-md hover:bg-primary-50 transition-colors" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('daily-stock-checks.destroy', $date->check_date) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-md hover:bg-red-50 transition-colors" onclick="return confirm('Are you sure you want to delete all stock checks for this date?')" title="Delete">
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
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Morning</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evening</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issues</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($dates as $index => $date)
                                    <tr class="hover:bg-gray-50 transition-all duration-200 animate-fade-in" style="animation-delay: {{ ($index * 0.03) + 0.5 }}s">
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex flex-col">
                                                <span class="font-medium text-primary-600">
                                                    {{ \Carbon\Carbon::parse($date->check_date)->format('M d, Y') }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($date->check_date)->format('l') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($summaries[$date->check_date]['morning'])
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 transition-all duration-200 hover:bg-green-200">
                                                    <i class="fas fa-sun mr-1"></i> Done
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                    <i class="fas fa-times mr-1"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($summaries[$date->check_date]['evening'])
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 transition-all duration-200 hover:bg-green-200">
                                                    <i class="fas fa-moon mr-1"></i> Done
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                    <i class="fas fa-times mr-1"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($summaries[$date->check_date]['discrepancies'] > 0)
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 animate-pulse">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    {{ $summaries[$date->check_date]['discrepancies'] }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i> 0
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium">
                                            <div class="flex space-x-1">
                                                <a href="{{ route('daily-stock-checks.show', $date->check_date) }}" class="text-primary-600 hover:text-primary-900 p-1 rounded hover:bg-primary-50 transition-all duration-200" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('daily-stock-checks.destroy', $date->check_date) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-all duration-200" onclick="return confirm('Are you sure you want to delete all stock checks for this date?')" title="Delete">
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
                {{ $dates->links() }}
            </div>
        @else
            <div class="text-center py-12 animate-fade-in">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-clipboard-list text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Stock Checks Found</h3>
                <p class="text-gray-500 mb-6">Start by performing your first daily stock check</p>
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('daily-stock-checks.create.type', 'morning') }}" class="inline-block bg-gradient-to-r from-yellow-500 to-orange-600 text-white px-6 py-3 rounded-md hover:from-yellow-600 hover:to-orange-700 text-center transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-sun mr-2"></i> Start Morning Check
                    </a>
                    <a href="{{ route('daily-stock-checks.create.type', 'evening') }}" class="inline-block bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-6 py-3 rounded-md hover:from-purple-600 hover:to-indigo-700 text-center transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-moon mr-2"></i> Start Evening Check
                    </a>
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

        /* Enhanced badge styling */
        .bg-green-100:hover {
            background-color: #dcfce7;
        }

        .bg-red-100 {
            background-color: #fee2e2;
        }

        .bg-yellow-100 {
            background-color: #fef3c7;
        }

        .bg-gray-100 {
            background-color: #f3f4f6;
        }

        /* Table hover enhancement */
        .hover\:bg-gray-50:hover {
            background-color: #f9fafb;
        }

        /* Action button hover effects */
        .hover\:bg-primary-50:hover {
            background-color: #eff6ff;
        }

        .hover\:bg-red-50:hover {
            background-color: #fef2f2;
        }

        /* Gradient button enhancements */
        .bg-gradient-to-r {
            background: linear-gradient(to right, var(--tw-gradient-stops));
        }

        /* Pulse animation for critical alerts */
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

        /* Enhanced responsiveness for very small screens */
        @media (max-width: 640px) {
            .text-xl {
                font-size: 1.125rem;
            }
            
            .text-2xl {
                font-size: 1.5rem;
            }
        }

        /* Card border enhancements */
        .border-gray-200 {
            border-color: #e5e7eb;
        }

        /* Status indicator animations */
        .bg-green-100 {
            background-color: #dcfce7;
            transition: background-color 0.2s ease;
        }

        .bg-red-100 {
            background-color: #fee2e2;
            transition: background-color 0.2s ease;
        }
    </style>
</x-admin-layout>