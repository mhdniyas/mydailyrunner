<x-app-layout>
    <x-slot name="title">Financial Entries</x-slot>
    <x-slot name="subtitle">Manage income and expenses</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <form action="{{ route('financial-entries.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <select name="type" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                        <option value="all" {{ $selectedType == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="income" {{ $selectedType == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ $selectedType == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                    <select name="category_id" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ ucfirst($category->type) }})
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
                <a href="{{ route('financial-entries.create') }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-plus mr-2"></i> New Entry
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-green-50 p-4 rounded-md">
                <p class="text-sm text-green-600 font-medium">Total Income</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($totalIncome, 2) }}</p>
            </div>
            <div class="bg-red-50 p-4 rounded-md">
                <p class="text-sm text-red-600 font-medium">Total Expenses</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($totalExpense, 2) }}</p>
            </div>
            <div class="bg-{{ $netBalance >= 0 ? 'green' : 'red' }}-50 p-4 rounded-md">
                <p class="text-sm text-{{ $netBalance >= 0 ? 'green' : 'red' }}-600 font-medium">Net Balance</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($netBalance, 2) }}</p>
            </div>
        </div>

        @if($entries->count() > 0)
            <div class="overflow-x-auto">
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
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $entry->category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $entry->description ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $entry->reference ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium {{ $entry->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($entry->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('financial-entries.show', $entry) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('financial-entries.edit', $entry) }}" class="text-accent-600 hover:text-accent-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('financial-entries.destroy', $entry) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this entry?')">
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
                {{ $entries->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No financial entries found.</p>
                <a href="{{ route('financial-entries.create') }}" class="mt-4 inline-block bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-plus mr-2"></i> New Entry
                </a>
            </div>
        @endif
    </div>
</x-app-layout>