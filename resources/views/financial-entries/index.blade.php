<x-app-layout>
    <x-slot name="title">Financial Entries</x-slot>
    <x-slot name="subtitle">Manage income and expenses</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Mobile-first responsive layout -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <!-- Filter Section -->
            <div class="mb-6">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
                    <!-- Filters -->
                    <div class="w-full lg:flex-1">
                        <form action="{{ route('financial-entries.index') }}" method="GET" class="space-y-4 lg:space-y-0">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 lg:gap-2">
                                <div>
                                    <label for="type" class="block text-xs font-medium text-gray-700 mb-1 sm:hidden">Type</label>
                                    <select name="type" id="type" class="w-full text-sm border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                        <option value="all" {{ $selectedType == 'all' ? 'selected' : '' }}>All Types</option>
                                        <option value="income" {{ $selectedType == 'income' ? 'selected' : '' }}>Income</option>
                                        <option value="expense" {{ $selectedType == 'expense' ? 'selected' : '' }}>Expense</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="category_id" class="block text-xs font-medium text-gray-700 mb-1 sm:hidden">Category</label>
                                    <select name="category_id" id="category_id" class="w-full text-sm border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }} ({{ ucfirst($category->type) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="from_date" class="block text-xs font-medium text-gray-700 mb-1 sm:hidden">From Date</label>
                                    <input type="date" name="from_date" id="from_date" value="{{ $from_date }}" 
                                           class="w-full text-sm border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                </div>
                                
                                <div>
                                    <label for="to_date" class="block text-xs font-medium text-gray-700 mb-1 sm:hidden">To Date</label>
                                    <input type="date" name="to_date" id="to_date" value="{{ $to_date }}" 
                                           class="w-full text-sm border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                </div>
                                
                                <div class="sm:col-span-2 lg:col-span-1">
                                    <button type="submit" class="w-full bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors text-sm font-medium">
                                        <i class="fas fa-filter mr-1"></i> 
                                        <span class="hidden sm:inline">Filter</span>
                                        <span class="sm:hidden">Apply</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Add Entry Button -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('financial-entries.create') }}" 
                           class="w-full lg:w-auto flex items-center justify-center bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-colors font-medium">
                            <i class="fas fa-plus mr-2"></i> 
                            <span class="sm:hidden">Add Entry</span>
                            <span class="hidden sm:inline">New Entry</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-arrow-up text-green-600 text-lg"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium text-green-600">Total Income</p>
                            <p class="text-lg sm:text-2xl font-bold text-green-900 mt-1">{{ number_format($totalIncome, 2) }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-arrow-down text-red-600 text-lg"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium text-red-600">Total Expenses</p>
                            <p class="text-lg sm:text-2xl font-bold text-red-900 mt-1">{{ number_format($totalExpense, 2) }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-{{ $netBalance >= 0 ? 'green' : 'red' }}-50 p-4 rounded-lg border border-{{ $netBalance >= 0 ? 'green' : 'red' }}-200 sm:col-span-1">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-{{ $netBalance >= 0 ? 'chart-line' : 'chart-line-down' }} text-{{ $netBalance >= 0 ? 'green' : 'red' }}-600 text-lg"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium text-{{ $netBalance >= 0 ? 'green' : 'red' }}-600">Net Balance</p>
                            <p class="text-lg sm:text-2xl font-bold text-{{ $netBalance >= 0 ? 'green' : 'red' }}-900 mt-1">{{ number_format($netBalance, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Entries List -->
            @if($entries->count() > 0)
                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($entries as $entry)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $entry->entry_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $entry->category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ Str::limit($entry->description ?? 'N/A', 30) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $entry->reference ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $entry->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($entry->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('financial-entries.show', $entry) }}" 
                                               class="text-primary-600 hover:text-primary-900 p-1 rounded">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('financial-entries.edit', $entry) }}" 
                                               class="text-accent-600 hover:text-accent-900 p-1 rounded">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('financial-entries.destroy', $entry) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 p-1 rounded" 
                                                        onclick="return confirm('Are you sure you want to delete this entry?')">
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

                <!-- Mobile Card View -->
                <div class="lg:hidden space-y-4">
                    @foreach($entries as $entry)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    @if($entry->type === 'income')
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-arrow-up text-green-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Income
                                            </span>
                                            <p class="text-sm text-gray-500 mt-1">{{ $entry->entry_date->format('M d, Y') }}</p>
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-arrow-down text-red-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Expense
                                            </span>
                                            <p class="text-sm text-gray-500 mt-1">{{ $entry->entry_date->format('M d, Y') }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold {{ $entry->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($entry->amount, 2) }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Category:</span>
                                    <span class="text-gray-900 font-medium">{{ $entry->category->name }}</span>
                                </div>
                                
                                @if($entry->description)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Description:</span>
                                    <span class="text-gray-900 text-right flex-1 ml-2">{{ Str::limit($entry->description, 40) }}</span>
                                </div>
                                @endif
                                
                                @if($entry->reference)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Reference:</span>
                                    <span class="text-gray-900">{{ $entry->reference }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="flex justify-end space-x-3 mt-4 pt-3 border-t border-gray-100">
                                <a href="{{ route('financial-entries.show', $entry) }}" 
                                   class="flex items-center text-primary-600 hover:text-primary-900 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('financial-entries.edit', $entry) }}" 
                                   class="flex items-center text-accent-600 hover:text-accent-900 text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('financial-entries.destroy', $entry) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="flex items-center text-red-600 hover:text-red-900 text-sm font-medium" 
                                            onclick="return confirm('Are you sure you want to delete this entry?')">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    {{ $entries->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-money-bill-wave text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No financial entries found</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first financial entry.</p>
                    <a href="{{ route('financial-entries.create') }}" 
                       class="inline-flex items-center bg-accent-600 text-white px-6 py-3 rounded-md hover:bg-accent-700 transition-colors font-medium">
                        <i class="fas fa-plus mr-2"></i> Create First Entry
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>