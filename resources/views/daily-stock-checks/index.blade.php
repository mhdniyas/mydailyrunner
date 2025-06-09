<x-app-layout>
    <x-slot name="title">Daily Stock Checks</x-slot>
    <x-slot name="subtitle">Track and verify your inventory</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Stock Check History</h3>
            <div>
                <a href="{{ route('daily-stock-checks.create.type', 'morning') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 mr-2">
                    <i class="fas fa-sun mr-2"></i> Morning Check
                </a>
                <a href="{{ route('daily-stock-checks.create.type', 'evening') }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-moon mr-2"></i> Evening Check
                </a>
            </div>
        </div>

        @if($dates->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Morning Check</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evening Check</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discrepancies</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($dates as $date)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($date->check_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($summaries[$date->check_date]['morning'])
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Completed
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-times mr-1"></i> Not Done
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($summaries[$date->check_date]['evening'])
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Completed
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-times mr-1"></i> Not Done
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($summaries[$date->check_date]['discrepancies'] > 0)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $summaries[$date->check_date]['discrepancies'] }} Found
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            None
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('daily-stock-checks.show', $date->check_date) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <form action="{{ route('daily-stock-checks.destroy', $date->check_date) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete all stock checks for this date?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $dates->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No stock checks found.</p>
                <div class="mt-4 space-x-2">
                    <a href="{{ route('daily-stock-checks.create.type', 'morning') }}" class="inline-block bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                        <i class="fas fa-sun mr-2"></i> Morning Check
                    </a>
                    <a href="{{ route('daily-stock-checks.create.type', 'evening') }}" class="inline-block bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                        <i class="fas fa-moon mr-2"></i> Evening Check
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>