<x-admin-layout>
    <x-slot name="title">Customers</x-slot>
    <x-slot name="subtitle">{{ $shop->name }} - Manage your customers</x-slot>

    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <!-- Search and Filter Section -->
            <div class="w-full lg:flex-1">
                <form action="{{ route('customers.index') }}" method="GET" class="space-y-3 sm:space-y-0 sm:flex sm:flex-wrap sm:items-center sm:gap-3">
                    <!-- Search Input -->
                    <div class="flex-1 min-w-0">
                        <input type="text" name="search" value="{{ $search }}" 
                               placeholder="Search customers..." 
                               class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm">
                    </div>
                    
                    <!-- Card Type Filter -->
                    <div class="sm:min-w-[180px]">
                        <select name="card_type" 
                                class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm">
                            <option value="">All Card Types</option>
                            <option value="AAY" {{ $cardType == 'AAY' ? 'selected' : '' }}>AAY (Yellow)</option>
                            <option value="PHH" {{ $cardType == 'PHH' ? 'selected' : '' }}>PHH (Pink)</option>
                            <option value="NPS" {{ $cardType == 'NPS' ? 'selected' : '' }}>NPS (Blue)</option>
                            <option value="NPI" {{ $cardType == 'NPI' ? 'selected' : '' }}>NPI (Light Blue)</option>
                            <option value="NPNS" {{ $cardType == 'NPNS' ? 'selected' : '' }}>NPNS (White)</option>
                        </select>
                    </div>
                    
                    <!-- Search Button -->
                    <div>
                        <button type="submit" 
                                class="w-full sm:w-auto bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors duration-200 text-sm font-medium">
                            <i class="fas fa-search mr-1"></i>
                            <span class="sm:hidden">Search</span>
                            <span class="hidden sm:inline">Search</span>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Add Customer Button -->
            <div class="w-full sm:w-auto">
                <a href="{{ route('customers.create') }}" 
                   class="w-full sm:w-auto bg-accent-600 text-white px-4 py-2.5 rounded-md hover:bg-accent-700 transition-colors duration-200 flex items-center justify-center text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i> Add Customer
                </a>
            </div>
        </div>

        @if($customers->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ration Card</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Card Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customers as $customer)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                    @if($customer->is_walk_in)
                                        <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Walk-in
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $customer->phone ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $customer->ration_card_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($customer->card_type)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($customer->card_type == 'AAY') bg-yellow-100 text-yellow-800
                                            @elseif($customer->card_type == 'PHH') bg-pink-100 text-pink-800
                                            @elseif($customer->card_type == 'NPS') bg-blue-100 text-blue-800
                                            @elseif($customer->card_type == 'NPI') bg-indigo-100 text-indigo-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $customer->card_type_display }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="max-w-xs truncate" title="{{ $customer->address }}">
                                        {{ $customer->address ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('customers.show', $customer) }}" 
                                           class="text-primary-600 hover:text-primary-900 p-1 hover:bg-primary-50 rounded transition-colors duration-150"
                                           title="View Customer">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" 
                                           class="text-accent-600 hover:text-accent-900 p-1 hover:bg-accent-50 rounded transition-colors duration-150"
                                           title="Edit Customer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded transition-colors duration-150"
                                                    onclick="return confirm('Are you sure you want to delete this customer?')"
                                                    title="Delete Customer">
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
                @foreach($customers as $customer)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <!-- Customer Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $customer->name }}</h3>
                                <div class="flex items-center space-x-2 mt-1">
                                    @if($customer->is_walk_in)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Walk-in
                                        </span>
                                    @endif
                                    @if($customer->card_type)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($customer->card_type == 'AAY') bg-yellow-100 text-yellow-800
                                            @elseif($customer->card_type == 'PHH') bg-pink-100 text-pink-800
                                            @elseif($customer->card_type == 'NPS') bg-blue-100 text-blue-800
                                            @elseif($customer->card_type == 'NPI') bg-indigo-100 text-indigo-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $customer->card_type_display }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-1 ml-2">
                                <a href="{{ route('customers.show', $customer) }}" 
                                   class="text-primary-600 hover:text-primary-900 p-2 hover:bg-primary-50 rounded-md transition-colors duration-150">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}" 
                                   class="text-accent-600 hover:text-accent-900 p-2 hover:bg-accent-50 rounded-md transition-colors duration-150">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-md transition-colors duration-150"
                                            onclick="return confirm('Are you sure you want to delete this customer?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Customer Details -->
                        <div class="space-y-2">
                            @if($customer->phone)
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-phone text-gray-400 w-4"></i>
                                    <span class="ml-2 text-gray-900">{{ $customer->phone }}</span>
                                </div>
                            @endif
                            
                            @if($customer->ration_card_number)
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-id-card text-gray-400 w-4"></i>
                                    <span class="ml-2 text-gray-900">{{ $customer->ration_card_number }}</span>
                                </div>
                            @endif
                            
                            @if($customer->address)
                                <div class="flex items-start text-sm">
                                    <i class="fas fa-map-marker-alt text-gray-400 w-4 mt-0.5"></i>
                                    <span class="ml-2 text-gray-900 leading-relaxed">{{ $customer->address }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $customers->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-users text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No customers found</h3>
                <p class="text-gray-500 mb-6">
                    @if($search || $cardType)
                        No customers match your search criteria. Try adjusting your filters.
                    @else
                        Create your first customer to get started.
                    @endif
                </p>
                
                @if($search || $cardType)
                    <a href="{{ route('customers.index') }}" 
                       class="inline-block bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors duration-200 mr-3">
                        <i class="fas fa-times mr-2"></i> Clear Filters
                    </a>
                @endif
                
                <a href="{{ route('customers.create') }}" 
                   class="inline-block bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i> Add Customer
                </a>
            </div>
        @endif
    </div>

    <!-- Mobile-specific styles -->
    <style>
        @media (max-width: 1024px) {
            .truncate {
                max-width: 200px;
            }
        }
        
        @media (max-width: 640px) {
            .truncate {
                max-width: 150px;
            }
        }
    </style>
</x-admin-layout>