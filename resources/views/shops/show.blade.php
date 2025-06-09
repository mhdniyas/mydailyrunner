<x-admin-layout>
    <x-slot name="title">Shop Details</x-slot>
    <x-slot name="subtitle">{{ $shop->name }}</x-slot>

    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('shops.index') }}" class="text-primary-600 hover:text-primary-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Shops
        </a>
        @if(Auth::id() === $shop->owner_id)
            <div class="flex space-x-3">
                <a href="{{ route('shops.edit', $shop) }}" class="btn-secondary">
                    <i class="fas fa-edit mr-2"></i> Edit Shop
                </a>
                <form action="{{ route('shops.destroy', $shop) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this shop? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-lg transition-all duration-300 text-sm sm:text-base">
                        <i class="fas fa-trash-alt mr-2"></i> Delete Shop
                    </button>
                </form>
            </div>
        @endif
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Shop Information -->
        <div class="luxury-card">
            <h3 class="font-serif text-xl font-semibold text-primary-800 mb-4">Shop Information</h3>
            
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm text-primary-500">Shop Name</h4>
                    <p class="text-lg text-primary-900">{{ $shop->name }}</p>
                </div>
                
                <div>
                    <h4 class="text-sm text-primary-500">Location</h4>
                    <p class="text-lg text-primary-900">{{ $shop->location ?? 'Not specified' }}</p>
                </div>
                
                <div>
                    <h4 class="text-sm text-primary-500">Owner</h4>
                    <p class="text-lg text-primary-900">{{ $shop->owner->name }}</p>
                </div>
                
                <div>
                    <h4 class="text-sm text-primary-500">Created On</h4>
                    <p class="text-lg text-primary-900">{{ $shop->created_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Shop Statistics -->
        <div class="luxury-card">
            <h3 class="font-serif text-xl font-semibold text-primary-800 mb-4">Shop Statistics</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-primary-50 p-4 rounded-lg">
                    <h4 class="text-sm text-primary-500">Products</h4>
                    <p class="text-2xl font-bold text-primary-900">{{ $shop->products->count() }}</p>
                </div>
                
                <div class="bg-primary-50 p-4 rounded-lg">
                    <h4 class="text-sm text-primary-500">Staff Members</h4>
                    <p class="text-2xl font-bold text-primary-900">{{ $shop->users->count() }}</p>
                </div>
                
                <div class="bg-primary-50 p-4 rounded-lg">
                    <h4 class="text-sm text-primary-500">Customers</h4>
                    <p class="text-2xl font-bold text-primary-900">{{ $shop->customers->count() }}</p>
                </div>
                
                <div class="bg-primary-50 p-4 rounded-lg">
                    <h4 class="text-sm text-primary-500">Sales</h4>
                    <p class="text-2xl font-bold text-primary-900">{{ $shop->sales->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Shop Staff -->
    <div class="luxury-card mt-6">
        <h3 class="font-serif text-xl font-semibold text-primary-800 mb-4">Shop Staff</h3>
        
        @if($shop->users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-primary-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-primary-50 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 bg-primary-50 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 bg-primary-50 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 bg-primary-50 text-left text-xs font-medium text-primary-500 uppercase tracking-wider">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-primary-100">
                        @foreach($shop->users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-primary-900">{{ $user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-primary-600">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $user->pivot->role === 'owner' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $user->pivot->role === 'manager' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $user->pivot->role === 'stock_checker' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $user->pivot->role === 'finance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $user->pivot->role === 'viewer' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($user->pivot->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-primary-600">{{ $user->pivot->created_at->format('M d, Y') }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-users text-primary-300 text-5xl mb-4"></i>
                <h3 class="text-primary-600 text-lg mb-2">No staff members found</h3>
                <p class="text-primary-500">Add staff members to help manage this shop</p>
            </div>
        @endif
    </div>
</x-admin-layout>
