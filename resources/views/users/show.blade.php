<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="subtitle">{{ $subtitle }}</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">User Details</h2>
            <div class="flex space-x-2">
                <a href="{{ route('users.edit', $user) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('users.roles', $user) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="fas fa-user-tag mr-2"></i> Manage Roles
                </a>
                <form action="{{ route('users.invite', $user) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        <i class="fas fa-envelope mr-2"></i> Send Invitation
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                <div class="bg-gray-50 p-4 rounded-md">
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium">{{ $user->name }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Shop Access</h3>
                <div class="bg-gray-50 p-4 rounded-md">
                    @if($shopUsers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shop</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($shopUsers as $shopUser)
                                        <tr>
                                            <td class="px-4 py-2">{{ $shopUser->shop->name }}</td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($shopUser->role === 'owner') bg-purple-100 text-purple-800
                                                    @elseif($shopUser->role === 'manager') bg-blue-100 text-blue-800
                                                    @elseif($shopUser->role === 'finance') bg-green-100 text-green-800
                                                    @elseif($shopUser->role === 'stock') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($shopUser->role) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No shop access assigned.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash mr-2"></i> Delete User
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirm delete
            const deleteForm = document.querySelector('.delete-form');
            
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    this.submit();
                }
            });
        });
    </script>
</x-app-layout>