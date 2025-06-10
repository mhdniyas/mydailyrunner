<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="subtitle">{{ $subtitle }}</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Manage Shop Access for {{ $user->name }}</h2>
        </div>

        <form action="{{ route('users.roles.update', $user) }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-md mb-4">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i> 
                        Assign shops and roles to this user. Each role provides different permissions:
                    </p>
                    <ul class="mt-2 text-sm text-gray-600 list-disc list-inside space-y-1">
                        <li><strong>Owner:</strong> Full access to all features</li>
                        <li><strong>Manager:</strong> Can manage products, sales, and view reports</li>
                        <li><strong>Finance:</strong> Can manage financial entries and view financial reports</li>
                        <li><strong>Stock:</strong> Can manage stock operations and view stock reports</li>
                        <li><strong>Viewer:</strong> Can only view data, no editing capabilities</li>
                    </ul>
                </div>

                @if($shops->count() > 0)
                    <div id="shop-access-container" class="space-y-4">
                        @foreach($shops as $shop)
                            <div class="shop-access-row bg-gray-50 p-4 rounded-md">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Shop</label>
                                            <input type="hidden" name="shops[]" value="{{ $shop->id }}">
                                            <div class="mt-1 text-gray-900">{{ $shop->name }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Role</label>
                                            <select name="roles[]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                                <option value="owner" {{ isset($userShops[$shop->id]) && $userShops[$shop->id] === 'owner' ? 'selected' : '' }}>Owner</option>
                                                <option value="manager" {{ isset($userShops[$shop->id]) && $userShops[$shop->id] === 'manager' ? 'selected' : '' }}>Manager</option>
                                                <option value="finance" {{ isset($userShops[$shop->id]) && $userShops[$shop->id] === 'finance' ? 'selected' : '' }}>Finance</option>
                                                <option value="stock" {{ isset($userShops[$shop->id]) && $userShops[$shop->id] === 'stock' ? 'selected' : '' }}>Stock</option>
                                                <option value="viewer" {{ isset($userShops[$shop->id]) && $userShops[$shop->id] === 'viewer' ? 'selected' : '' }}>Viewer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="shop_enabled[{{ $shop->id }}]" class="shop-enabled-checkbox rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" {{ isset($userShops[$shop->id]) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">Enable Access</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @error('shops')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('roles')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @else
                    <div class="bg-yellow-50 p-4 rounded-md">
                        <p class="text-yellow-800">You don't have any shops to assign to this user. Please create a shop first.</p>
                    </div>
                @endif
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('users.show', $user) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.shop-enabled-checkbox');
            
            checkboxes.forEach(checkbox => {
                const row = checkbox.closest('.shop-access-row');
                const select = row.querySelector('select');
                
                // Initial state
                select.disabled = !checkbox.checked;
                if (!checkbox.checked) {
                    row.classList.add('opacity-50');
                }
                
                // Handle change
                checkbox.addEventListener('change', function() {
                    select.disabled = !this.checked;
                    if (this.checked) {
                        row.classList.remove('opacity-50');
                    } else {
                        row.classList.add('opacity-50');
                    }
                });
            });
        });
    </script>
</x-app-layout>