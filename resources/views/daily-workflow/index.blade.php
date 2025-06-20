<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Daily Workflow') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Streamlined daily operations panel') }}
        </p>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            @if(session('subscription_warning'))
                <div class="mb-4 rounded-md bg-yellow-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                {{ session('subscription_warning') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900">Daily Workflow Steps</h3>
                        
                        <div class="mt-4 flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 text-white font-bold">1</div>
                            <div class="flex-grow">
                                <h4 class="text-md font-medium">Yesterday's Sales Summary</h4>
                                <div class="mt-2 bg-gray-50 p-4 rounded-md">
                                    <p class="text-sm text-gray-700">
                                        Total sales: {{ number_format($yesterdayTotal, 2) }} {{ config('app.currency', '₹') }}
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        Number of sales: {{ $yesterdayCount }}
                                    </p>
                                    @if($yesterdaySales->isNotEmpty())
                                        <div class="mt-2">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @php
                                                        $productSummary = [];
                                                        foreach($yesterdaySales as $sale) {
                                                            foreach($sale->items as $item) {
                                                                $productId = $item->product_id;
                                                                if (!isset($productSummary[$productId])) {
                                                                    $productSummary[$productId] = [
                                                                        'name' => $item->product->name,
                                                                        'quantity' => 0,
                                                                        'amount' => 0
                                                                    ];
                                                                }
                                                                $productSummary[$productId]['quantity'] += $item->quantity;
                                                                $productSummary[$productId]['amount'] += $item->total_price;
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    @foreach($productSummary as $product)
                                                        <tr>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ $product['name'] }}</td>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($product['quantity'], 2) }}</td>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($product['amount'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-sm italic text-gray-500 mt-2">No sales recorded yesterday.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 text-white font-bold">2</div>
                            <div class="flex-grow">
                                <h4 class="text-md font-medium">Current Stock Levels</h4>
                                <div class="mt-2 bg-gray-50 p-4 rounded-md">
                                    @if($products->isNotEmpty())
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bag Average</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Based on Batch</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($products as $product)
                                                        <tr>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($product->current_stock, 2) }}</td>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($bagAverages[$product->id] ?? $product->avg_bag_weight, 2) }}</td>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                                                @if($product->batches->isNotEmpty())
                                                                    {{ $product->batches->first()->batch_date->format('d M Y') }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-sm italic text-gray-500">No products found.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 text-white font-bold">3</div>
                            <div class="flex-grow">
                                <h4 class="text-md font-medium">Record Physical Stock Count</h4>
                                <div class="mt-2">
                                    @if($todayStockCheckExists)
                                        <div class="bg-green-50 p-4 rounded-md">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm text-green-700">
                                                        Stock count has already been recorded today.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 flex justify-end">
                                            <a href="{{ route('daily-workflow.discrepancy') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                View Discrepancy Report
                                            </a>
                                        </div>
                                    @else
                                        <div class="bg-yellow-50 p-4 rounded-md">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm text-yellow-700">
                                                        No stock count has been recorded for today.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 flex justify-end">
                                            <a href="{{ route('daily-workflow.record-stock') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Record Physical Stock
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 text-white font-bold">4</div>
                            <div class="flex-grow">
                                <h4 class="text-md font-medium">Yesterday's Discrepancies</h4>
                                <div class="mt-2 bg-gray-50 p-4 rounded-md">
                                    @if($yesterdayDiscrepancies->isNotEmpty())
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System Stock</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical Stock</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discrepancy</th>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($yesterdayDiscrepancies as $check)
                                                        <tr>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ $check->product->name }}</td>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($check->system_stock, 2) }}</td>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ number_format($check->physical_stock, 2) }}</td>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm {{ $check->discrepancy < 0 ? 'text-red-600' : 'text-green-600' }}">
                                                                {{ number_format($check->discrepancy, 2) }}
                                                                ({{ number_format($check->discrepancy_percent, 1) }}%)
                                                            </td>
                                                            <td class="px-3 py-2 text-sm text-gray-900">{{ $check->notes }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-sm italic text-gray-500">No discrepancies found for yesterday.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mt-6">
                        <div class="flex justify-between">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Back to Dashboard
                            </a>
                            
                            @if($todayStockCheckExists)
                                <a href="{{ route('daily-workflow.complete') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    View Today's Summary
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
