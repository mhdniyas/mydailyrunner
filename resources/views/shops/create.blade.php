<x-admin-layout>
    <x-slot name="title">Create New Shop</x-slot>
    <x-slot name="subtitle">Add a new shop to your management portfolio</x-slot>

    <div class="luxury-card">
        <form action="{{ route('shops.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="name" class="label-luxury">Shop Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                       class="input-luxury" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="location" class="label-luxury">Location</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}" 
                       class="input-luxury">
                @error('location')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end gap-4">
                <a href="{{ route('shops.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-store mr-2"></i> Create Shop
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
