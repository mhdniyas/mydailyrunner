<x-app-layout>
    <x-slot name="title">New Financial Entry</x-slot>
    <x-slot name="subtitle">Record income or expense</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <form action="{{ route('financial-entries.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Type and Category Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div class="space-y-2">
                            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="type" id="type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base" required>
                                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Income</option>
                                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                            </select>
                            @error('type')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="financial_category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                <button type="button" id="add-category-btn" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-primary-600 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add New
                                </button>
                            </div>
                            <select name="financial_category_id" id="financial_category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base" required>
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
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Amount and Date Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div class="space-y-2">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" 
                                   step="0.01" min="0.01" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base" required>
                            @error('amount')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="entry_date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="entry_date" id="entry_date" value="{{ old('entry_date', now()->format('Y-m-d')) }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base" required>
                            @error('entry_date')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Reference (Full Width on Mobile) -->
                    <div class="space-y-2">
                        <label for="reference" class="block text-sm font-medium text-gray-700">Reference (Optional)</label>
                        <input type="text" name="reference" id="reference" value="{{ old('reference') }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base">
                        @error('reference')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description (Full Width) -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm sm:text-base">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('financial-entries.index') }}" 
                       class="w-full sm:w-auto text-center bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-colors">
                        Create Entry
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Add New Category</h3>
                </div>
                
                <form id="categoryForm" class="px-6 py-4 space-y-4">
                    @csrf
                    <div>
                        <label for="category_name" class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" id="category_name" name="name" 
                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                        <div id="category_name_error" class="text-sm text-red-600 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="category_type" class="block text-sm font-medium text-gray-700">Type</label>
                        <select id="category_type" name="type" 
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                        <div id="category_type_error" class="text-sm text-red-600 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="category_description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea id="category_description" name="description" rows="2"
                                  class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"></textarea>
                    </div>
                </form>
                
                <div class="px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                    <button type="button" id="cancelCategoryBtn" 
                            class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" form="categoryForm" id="saveCategoryBtn"
                            class="w-full sm:w-auto px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                        <span id="saveCategoryText">Save Category</span>
                        <span id="saveCategoryLoading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const categorySelect = document.getElementById('financial_category_id');
            const incomeCategories = document.getElementById('income-categories');
            const expenseCategories = document.getElementById('expense-categories');
            
            // Modal elements
            const modal = document.getElementById('categoryModal');
            const addCategoryBtn = document.getElementById('add-category-btn');
            const cancelCategoryBtn = document.getElementById('cancelCategoryBtn');
            const categoryForm = document.getElementById('categoryForm');
            const categoryTypeSelect = document.getElementById('category_type');
            const saveCategoryBtn = document.getElementById('saveCategoryBtn');
            const saveCategoryText = document.getElementById('saveCategoryText');
            const saveCategoryLoading = document.getElementById('saveCategoryLoading');
            
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
                
                // Update modal category type to match selected type
                categoryTypeSelect.value = selectedType;
            }
            
            // Modal functions
            function openModal() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                // Set the category type to match the selected type
                categoryTypeSelect.value = typeSelect.value;
            }
            
            function closeModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                categoryForm.reset();
                clearErrors();
            }
            
            function clearErrors() {
                const errorElements = ['category_name_error', 'category_type_error'];
                errorElements.forEach(id => {
                    const element = document.getElementById(id);
                    element.textContent = '';
                    element.classList.add('hidden');
                });
            }
            
            function showError(field, message) {
                const errorElement = document.getElementById(field + '_error');
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
            
            function setLoading(loading) {
                saveCategoryBtn.disabled = loading;
                if (loading) {
                    saveCategoryText.classList.add('hidden');
                    saveCategoryLoading.classList.remove('hidden');
                } else {
                    saveCategoryText.classList.remove('hidden');
                    saveCategoryLoading.classList.add('hidden');
                }
            }
            
            // Event listeners
            addCategoryBtn.addEventListener('click', openModal);
            cancelCategoryBtn.addEventListener('click', closeModal);
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
            
            // Form submission
            categoryForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                clearErrors();
                setLoading(true);
                
                const formData = new FormData(categoryForm);
                
                try {
                    const response = await fetch('{{ route("financial-categories.store") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Add the new category to the appropriate optgroup
                        const option = new Option(data.category.name, data.category.id);
                        
                        if (data.category.type === 'income') {
                            incomeCategories.appendChild(option);
                        } else {
                            expenseCategories.appendChild(option);
                        }
                        
                        // Select the new category if it matches the current type
                        if (data.category.type === typeSelect.value) {
                            categorySelect.value = data.category.id;
                        }
                        
                        closeModal();
                        
                        // Show success message
                        const successDiv = document.createElement('div');
                        successDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
                        successDiv.textContent = data.message;
                        document.body.appendChild(successDiv);
                        
                        setTimeout(() => {
                            successDiv.remove();
                        }, 3000);
                        
                    } else {
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                showError(field, data.errors[field][0]);
                            });
                        } else {
                            alert(data.message || 'An error occurred');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while creating the category');
                } finally {
                    setLoading(false);
                }
            });
            
            // Initial filter
            filterCategories();
            
            // Listen for type changes
            typeSelect.addEventListener('change', filterCategories);
        });
    </script>
</x-app-layout>