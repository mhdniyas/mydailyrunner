<x-app-layout>
    <x-slot name="title">New Financial Entry</x-slot>
    <x-slot name="subtitle">Record income or expense</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('financial-entries.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="financial_category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="financial_category_id" id="financial_category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                        <option value="">Select a category</option>
                        <optgroup label="Income Categories" id="income-categories">
                            @foreach($incomeCategories as $category)
                                <option value="{{ $category->id }}" {{ old('financial_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Expense Categories" id="expense-categories">
                            @foreach($expenseCategories as $category)
                                <option value="{{ $category->id }}" {{ old('financial_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                    @error('financial_category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" 
                           step="0.01" min="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="entry_date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="entry_date" id="entry_date" value="{{ old('entry_date', now()->format('Y-m-d')) }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                    @error('entry_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700">Reference (Optional)</label>
                    <input type="text" name="reference" id="reference" value="{{ old('reference') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    @error('reference')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <a href="{{ route('financial-entries.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700">
                    Create Entry
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const categorySelect = document.getElementById('financial_category_id');
            const incomeCategories = document.getElementById('income-categories');
            const expenseCategories = document.getElementById('expense-categories');
            
            // Function to filter categories based on selected type
            function filterCategories() {
                const selectedType = typeSelect.value;
                
                if (selectedType === 'income') {
                    incomeCategories.style.display = 'block';
                    expenseCategories.style.display = 'none';
                    
                    // Select first income category if none selected
                    if (categorySelect.selectedIndex === 0 || categorySelect.options[categorySelect.selectedIndex].parentNode.id !== 'income-categories') {
                        for (let i = 0; i < categorySelect.options.length; i++) {
                            if (categorySelect.options[i].parentNode.id === 'income-categories') {
                                categorySelect.selectedIndex = i;
                                break;
                            }
                        }
                    }
                } else {
                    incomeCategories.style.display = 'none';
                    expenseCategories.style.display = 'block';
                    
                    // Select first expense category if none selected
                    if (categorySelect.selectedIndex === 0 || categorySelect.options[categorySelect.selectedIndex].parentNode.id !== 'expense-categories') {
                        for (let i = 0; i < categorySelect.options.length; i++) {
                            if (categorySelect.options[i].parentNode.id === 'expense-categories') {
                                categorySelect.selectedIndex = i;
                                break;
                            }
                        }
                    }
                }
            }
            
            // Initial filter
            filterCategories();
            
            // Listen for type changes
            typeSelect.addEventListener('change', filterCategories);
        });
    </script>
</x-app-layout>