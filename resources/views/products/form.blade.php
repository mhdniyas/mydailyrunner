<x-app-layout>
    <x-slot name="title">{{ $product ? 'Edit Product' : 'Add Product' }}</x-slot>
    <x-slot name="subtitle">{{ $product ? 'Modify product information' : 'Create a new product' }}</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ $product ? route('products.update', $product) : route('products.store') }}" method="POST">
            @csrf
            @if($product)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product ? $product->name : '') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700">Unit of Measurement</label>
                    <input type="text" name="unit" id="unit" value="{{ old('unit', $product ? $product->unit : '') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="min_stock_level" class="block text-sm font-medium text-gray-700">Minimum Stock Level</label>
                    <input type="number" name="min_stock_level" id="min_stock_level" value="{{ old('min_stock_level', $product ? $product->min_stock_level : 0) }}" 
                           step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('min_stock_level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="current_stock" class="block text-sm font-medium text-gray-700">Current Stock</label>
                    <input type="number" name="current_stock" id="current_stock" value="{{ old('current_stock', $product ? $product->current_stock : 0) }}" 
                           step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('current_stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="avg_cost" class="block text-sm font-medium text-gray-700">Average Cost</label>
                    <input type="number" name="avg_cost" id="avg_cost" value="{{ old('avg_cost', $product ? $product->avg_cost : 0) }}" 
                           step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    @error('avg_cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price</label>
                    <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price', $product ? $product->sale_price : 0) }}" 
                           step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    @error('sale_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">No Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product ? $product->category_id : '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('description', $product ? $product->description : '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <a href="{{ route('products.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    {{ $product ? 'Update Product' : 'Create Product' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>