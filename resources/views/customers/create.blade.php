<x-admin-layout>
    <x-slot name="title">Add Customer</x-slot>
    <x-slot name="subtitle">{{ $shop->name }} - Create a new customer</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ration_card_number" class="block text-sm font-medium text-gray-700">Ration Card Number</label>
                    <input type="text" name="ration_card_number" id="ration_card_number" value="{{ old('ration_card_number') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    @error('ration_card_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="card_type" class="block text-sm font-medium text-gray-700">Card Type</label>
                    <select name="card_type" id="card_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select Card Type</option>
                        <option value="AAY" {{ old('card_type') == 'AAY' ? 'selected' : '' }}>AAY (Yellow)</option>
                        <option value="PHH" {{ old('card_type') == 'PHH' ? 'selected' : '' }}>PHH (Pink)</option>
                        <option value="NPS" {{ old('card_type') == 'NPS' ? 'selected' : '' }}>NPS (Blue)</option>
                        <option value="NPI" {{ old('card_type') == 'NPI' ? 'selected' : '' }}>NPI (Light Blue)</option>
                        <option value="NPNS" {{ old('card_type') == 'NPNS' ? 'selected' : '' }}>NPNS (White)</option>
                    </select>
                    @error('card_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" id="address" rows="2" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_walk_in" id="is_walk_in" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" {{ old('is_walk_in') ? 'checked' : '' }}>
                        <label for="is_walk_in" class="ml-2 block text-sm text-gray-700">
                            This is a walk-in customer (no account)
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <a href="{{ route('customers.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    Create Customer
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>