<x-app-layout>
    <x-slot name="title">Financial Report</x-slot>
    <x-slot name="subtitle">Income and expense analysis</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Filter Form & Export Button -->
        <div class="flex flex-col gap-4 mb-6">
            <div class="w-full">
                <form action="{{ route('reports.financial') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap items-center gap-2">
                    <select name="type" class="w-full sm:w-auto border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400">
                        <option value="all" {{ $selectedType == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="income" {{ $selectedType == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ $selectedType == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                    
                    <select name="category_id" class="w-full sm:w-auto border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ ucfirst($category->type) }})
                            </option>
                        @endforeach
                    </select>
                    
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <input type="date" name="from_date" value="{{ $from_date }}" class="flex-1 sm:flex-none border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400" placeholder="From Date">
                        <input type="date" name="to_date" value="{{ $to_date }}" class="flex-1 sm:flex-none border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm transition-all duration-200 hover:border-primary-400" placeholder="To Date">
                    </div>
                    
                    <button type="submit" class="w-full sm:w-auto bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-all duration-200 transform hover:scale-105 text-sm flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </form>
            </div>
            <div class="w-full sm:w-auto flex justify-start">
                <a href="{{ route('export.financial') }}?type={{ $selectedType }}&category_id={{ $selectedCategory }}&from_date={{ $from_date }}&to_date={{ $to_date }}" class="w-full sm:w-auto bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105 text-sm flex items-center justify-center">
                    <i class="fas fa-file-export mr-2"></i> Export
                </a>
            </div>
        </div>

        <!-- Enhanced Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
            <div class="bg-green-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-green-600 font-medium">Total Income</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">${{ number_format($totalIncome, 2) }}</p>
                    </div>
                    <div class="bg-green-100 p-2 rounded-full">
                        <i class="fas fa-arrow-up text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-red-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-red-600 font-medium">Total Expenses</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1">${{ number_format($totalExpense, 2) }}</p>
                    </div>
                    <div class="bg-red-100 p-2 rounded-full">
                        <i class="fas fa-arrow-down text-red-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-{{ $netBalance >= 0 ? 'green' : 'red' }}-50 p-3 sm:p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in sm:col-span-2 lg:col-span-1" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-{{ $netBalance >= 0 ? 'green' : 'red' }}-600 font-medium">Net Balance</p>
                        <p class="text-xl sm:text-2xl font-bold mt-1 {{ $netBalance >= 0 ? 'text-green-700' : 'text-red-700' }}">${{ number_format($netBalance, 2) }}</p>
                    </div>
                    <div class="bg-{{ $netBalance >= 0 ? 'green' : 'red' }}-100 p-2 rounded-full">
                        <i class="fas fa-{{ $netBalance >= 0 ? 'chart-line' : 'chart-line-down' }} text-{{ $netBalance >= 0 ? 'green' : 'red' }}-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Financial Chart -->
        <div class="mb-6 animate-fade-in" style="animation-delay: 0.3s">
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-primary-600"></i>
                    Financial Overview
                </h3>
                <div class="h-64 sm:h-80">
                    <canvas id="financialChart"></canvas>
                </div>
            </div>
        </div>

        @if($entries->count() > 0)
            <!-- Mobile View - Cards -->
            <div class="lg:hidden space-y-3 mb-6">
                @foreach($entries as $index => $entry)
                    <div class="bg-white border rounded-lg p-4 shadow-sm border-gray-200 transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: {{ ($index * 0.05) + 0.4 }}s">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    @if($entry->type === 'income')
                                        <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                            Income
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                            Expense
                                        </span>
                                    @endif
                                    <span class="text-sm text-gray-500">{{ $entry->entry_date->format('M d, Y') }}</span>
                                </div>
                                <h3 class="font-medium text-gray-900">{{ $entry->category->name }}</h3>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-bold {{ $entry->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    ${{ number_format($entry->amount, 2) }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div>
                                <p class="text-gray-500">Description:</p>
                                <p class="font-medium">{{ $entry->description ?? 'N/A' }}</p>
                            </div>
                            @if($entry->reference)
                            <div>
                                <p class="text-gray-500">Reference:</p>
                                <p class="font-medium">{{ $entry->reference }}</p>
                            </div>
                            @endif
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
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($entries as $index => $entry)
                                    <tr class="hover:bg-gray-50 transition-all duration-200 animate-fade-in" style="animation-delay: {{ ($index * 0.03) + 0.5 }}s">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $entry->entry_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($entry->type === 'income')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Income
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Expense
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $entry->category->name }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm max-w-[200px] truncate">
                                            {{ $entry->description ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $entry->reference ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap font-medium text-sm {{ $entry->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            ${{ number_format($entry->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Totals Row -->
                                <tr class="bg-gray-50 font-semibold border-t-2 border-gray-300">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm" colspan="5">
                                        Total ({{ $entries->count() }} entries)
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <div class="flex flex-col">
                                            <span class="text-green-600">+${{ number_format($totalIncome, 2) }}</span>
                                            <span class="text-red-600">-${{ number_format($totalExpense, 2) }}</span>
                                            <span class="border-t pt-1 {{ $netBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                ${{ number_format($netBalance, 2) }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                {{ $entries->links() }}
            </div>
        @else
            <div class="text-center py-12 animate-fade-in" style="animation-delay: 0.4s">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-chart-pie text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-lg mb-2">No financial entries found</p>
                <p class="text-gray-400 text-sm">Try adjusting your filters to see more results</p>
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

        /* Chart container responsiveness */
        #financialChart {
            max-width: 100%;
            height: auto !important;
        }

        /* Enhanced gradient text effect */
        .text-green-700 {
            background: linear-gradient(135deg, #15803d, #166534);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .text-red-700 {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Financial Chart
            const financialCtx = document.getElementById('financialChart').getContext('2d');
            const financialChart = new Chart(financialCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Income',
                            data: {!! json_encode($incomeData) !!},
                            backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 2,
                            borderRadius: 4,
                            borderSkipped: false,
                        },
                        {
                            label: 'Expense',
                            data: {!! json_encode($expenseData) !!},
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 2,
                            borderRadius: 4,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)',
                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        });
    </script>
</x-app-layout>