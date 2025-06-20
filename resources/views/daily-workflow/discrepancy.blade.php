<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Discrepancy Report') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Review and resolve inventory discrepancies') }}
        </p>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($stockChecks->isEmpty())
                        <div class="rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        No discrepancies found in today's stock check. All inventory matches system records.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-between">
                            <a href="{{ route('daily-workflow.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Back to Daily Workflow
                            </a>
                            <a href="{{ route('daily-workflow.complete') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Complete Daily Check
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('daily-workflow.store-discrepancy') }}">
                            @csrf
                            
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Resolve Discrepancies</h3>
                                <p class="mt-1 text-sm text-gray-600">Enter a reason for each discrepancy</p>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System Stock</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical Stock</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discrepancy</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($stockChecks as $check)
                                            <tr>
                                                <td class="px-3 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $check->product->name }}</div>
                                                    <input type="hidden" name="stock_check_id[]" value="{{ $check->id }}">
                                                </td>
                                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ number_format($check->system_stock, 2) }}
                                                </td>
                                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ number_format($check->physical_stock, 2) }}
                                                </td>
                                                <td class="px-3 py-4 whitespace-nowrap text-sm {{ $check->discrepancy < 0 ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ number_format($check->discrepancy, 2) }}
                                                    ({{ number_format($check->discrepancy_percent, 1) }}%)
                                                </td>
                                                <td class="px-3 py-4 whitespace-nowrap">
                                                    <select name="reason[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                                        <option value="">Select a reason</option>
                                                        <option value="Damage">Damage</option>
                                                        <option value="Theft">Theft</option>
                                                        <option value="Miscount">Miscount</option>
                                                        <option value="Sale not recorded">Sale not recorded</option>
                                                        <option value="Return not recorded">Return not recorded</option>
                                                        <option value="Stock adjustment">Stock adjustment</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </td>
                                                <td class="px-3 py-4 whitespace-nowrap">
                                                    <input type="text" name="notes[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Additional notes">
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
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Resolve Discrepancies & Complete
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
