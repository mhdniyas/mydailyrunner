<x-app-layout>
    <x-slot name="title">{{ ucfirst($entry->type) }} Details</x-slot>
    <x-slot name="subtitle">View financial entry information</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900">
                {{ ucfirst($entry->type) }} Entry #{{ $entry->id }}
            </h3>
            <div>
                <a href="{{ route('financial-entries.edit', $entry) }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 mr-2">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <form action="{{ route('financial-entries.destroy', $entry) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" 
                            onclick="return confirm('Are you sure you want to delete this entry?')">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Type</p>
                <p class="font-medium">
                    @if($entry->type === 'income')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Income
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Expense
                        </span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Category</p>
                <p class="font-medium">{{ $entry->category->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Amount</p>
                <p class="font-medium {{ $entry->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($entry->amount, 2) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Date</p>
                <p class="font-medium">{{ $entry->entry_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Reference</p>
                <p class="font-medium">{{ $entry->reference ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Created By</p>
                <p class="font-medium">{{ $entry->user->name }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Description</p>
                <p class="font-medium">{{ $entry->description ?? 'No description provided.' }}</p>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('financial-entries.index') }}" class="text-primary-600 hover:text-primary-900">
                <i class="fas fa-arrow-left mr-1"></i> Back to Financial Entries
            </a>
        </div>
    </div>
</x-app-layout>