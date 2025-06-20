<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Daily Check Complete') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Summary of today\'s inventory check') }}
        </p>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="rounded-md bg-green-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Daily stock check completed successfully</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>Completed by: {{ $completedBy }}</p>
                                    <p>Completed at: {{ $completedAt }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Stock Check Summary</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System Stock</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical Stock</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discrepancy</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($stockChecks as $check)
                                    <tr>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $check->product->name }}</div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($check->system_stock, 2) }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($check->physical_stock, 2) }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm {{ $check->discrepancy < 0 ? 'text-red-600' : ($check->discrepancy > 0 ? 'text-green-600' : 'text-gray-900') }}">
                                            @if($check->discrepancy != 0)
                                                {{ number_format($check->discrepancy, 2) }}
                                                ({{ number_format($check->discrepancy_percent, 1) }}%)
                                            @else
                                                No discrepancy
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-900">
                                            {{ $check->notes ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('daily-workflow.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Back to Daily Workflow
                        </a>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
