<x-admin-layout>
    <x-slot name="title">Customers</x-slot>
    <x-slot name="subtitle">{{ $shop->name }} - Manage your customers</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <form action="{{ route('customers.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search customers..." 
                           class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                    <select name="card_type" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                        <option value="">All Card Types</option>
                        <option value="AAY" {{ $cardType == 'AAY' ? 'selected' : '' }}>AAY (Yellow)</option>
                        <option value="PHH" {{ $cardType == 'PHH' ? 'selected' : '' }}>PHH (Pink)</option>
                        <option value="NPS" {{ $cardType == 'NPS' ? 'selected' : '' }}>NPS (Blue)</option>
                        <option value="NPI" {{ $cardType == 'NPI' ? 'selected' : '' }}>NPI (Light Blue)</option>
                        <option value="NPNS" {{ $cardType == 'NPNS' ? 'selected' : '' }}>NPNS (White)</option>
                    </select>
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                        <i class="fas fa-search"></i> Search
                    </button>
                </form>
            </div>
            <div>
                <a href="{{ route('customers.create') }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-plus mr-2"></i> Add Customer
                </a>
            </div>
        </div>

        @if($customers->count() > 0)
            <div class="overflow-x-auto">
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
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                    @if($customer->is_walk_in)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Walk-in
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $customer->phone ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $customer->address ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('customers.show', $customer) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="text-accent-600 hover:text-accent-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this customer?')">
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
                {{ $customers->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No customers found. Create your first customer to get started.</p>
                <a href="{{ route('customers.create') }}" class="mt-4 inline-block bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    <i class="fas fa-plus mr-2"></i> Add Customer
                </a>
            </div>
        @endif
    </div>
</x-admin-layout>