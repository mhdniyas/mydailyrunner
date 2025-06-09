<x-admin-layout>
    <x-slot name="title">Shops Management</x-slot>
    <x-slot name="subtitle">Manage all your shops from one place</x-slot>

    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-primary-900">Your Shops</h2>
        <a href="{{ route('shops.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Add New Shop
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="luxury-card">
        @if($shops->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-primary-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-primary-50 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 bg-primary-50 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 bg-primary-50 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Staff Count</th>
                            <th class="px-6 py-3 bg-primary-50 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 bg-primary-50 text-right text-xs font-medium text-primary-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-primary-100">
                        @foreach($shops as $shop)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-primary-900">{{ $shop->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-primary-600">{{ $shop->location ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-primary-600">{{ $shop->users()->count() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-primary-600">{{ $shop->created_at->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('shops.show', $shop) }}" class="text-primary-600 hover:text-primary-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('shops.edit', $shop) }}" class="text-accent-600 hover:text-accent-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('shops.destroy', $shop) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this shop? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-store text-primary-300 text-5xl mb-4"></i>
                <h3 class="text-primary-600 text-lg mb-2">No shops found</h3>
                <p class="text-primary-500 mb-6">Start by creating your first shop</p>
                <a href="{{ route('shops.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i> Add New Shop
                </a>
            </div>
        @endif
    </div>
</x-admin-layout>
