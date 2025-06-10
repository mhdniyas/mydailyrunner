<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="subtitle">{{ $subtitle }}</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Add New User</h2>
        </div>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Shop Access</h3>
                    
                    @if($shops->count() > 0)
                        <div id="shop-access-container" class="space-y-4">
                            <div class="shop-access-row bg-gray-50 p-4 rounded-md">
                                <div class="mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Shop</label>
                                    <select name="shops[]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                                        @foreach($shops as $shop)
                                            <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Role</label>
                                    <select name="roles[]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                                        <option value="owner">Owner</option>
                                        <option value="manager">Manager</option>
                                        <option value="finance">Finance</option>
                                        <option value="stock">Stock</option>
                                        <option value="viewer">Viewer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="button" id="add-shop-btn" class="text-primary-600 hover:text-primary-900">
                                <i class="fas fa-plus-circle mr-1"></i> Add Another Shop
                            </button>
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
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('users.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                    Create User
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addShopBtn = document.getElementById('add-shop-btn');
            const shopAccessContainer = document.getElementById('shop-access-container');
            
            if (addShopBtn && shopAccessContainer) {
                addShopBtn.addEventListener('click', function() {
                    const shopRow = document.querySelector('.shop-access-row').cloneNode(true);
                    
                    // Add remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'text-red-600 hover:text-red-900 mt-2 block';
                    removeBtn.innerHTML = '<i class="fas fa-times-circle mr-1"></i> Remove';
                    removeBtn.addEventListener('click', function() {
                        shopAccessContainer.removeChild(shopRow);
                    });
                    
                    shopRow.appendChild(removeBtn);
                    shopAccessContainer.appendChild(shopRow);
                });
            }
        });
    </script>
</x-app-layout>