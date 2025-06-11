<x-admin-layout>
    <x-slot name="title">Add Customer</x-slot>
    <x-slot name="subtitle">{{ $shop->name }} - Create a new customer</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Header Section -->
        <div class="mb-6 animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user-plus mr-2 text-accent-600"></i>
                        Add New Customer
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Create a new customer profile for {{ $shop->name }}</p>
                </div>
                <div class="hidden sm:block">
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                        New Customer
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Basic Information Section -->
            <div class="animate-fade-in" style="animation-delay: 0.1s">
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                        <i class="fas fa-user mr-2"></i>
                        Basic Information
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   placeholder="Enter customer's full name"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" 
                                   placeholder="Enter mobile number"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ration Card Information Section -->
            <div class="animate-fade-in" style="animation-delay: 0.2s">
                <div class="bg-green-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-green-900 mb-4 flex items-center">
                        <i class="fas fa-id-card mr-2"></i>
                        Ration Card Information
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="ration_card_number" class="block text-sm font-medium text-gray-700 mb-2">Ration Card Number</label>
                            <input type="text" name="ration_card_number" id="ration_card_number" value="{{ old('ration_card_number') }}" 
                                   placeholder="Enter ration card number"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400">
                            @error('ration_card_number')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="card_type" class="block text-sm font-medium text-gray-700 mb-2">Card Type</label>
                            <select name="card_type" id="card_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400">
                                <option value="">Select Card Type</option>
                                <option value="AAY" {{ old('card_type') == 'AAY' ? 'selected' : '' }}>🟨 AAY (Yellow)</option>
                                <option value="PHH" {{ old('card_type') == 'PHH' ? 'selected' : '' }}>🟪 PHH (Pink)</option>
                                <option value="NPS" {{ old('card_type') == 'NPS' ? 'selected' : '' }}>🟦 NPS (Blue)</option>
                                <option value="NPI" {{ old('card_type') == 'NPI' ? 'selected' : '' }}>🔷 NPI (Light Blue)</option>
                                <option value="NPNS" {{ old('card_type') == 'NPNS' ? 'selected' : '' }}>⬜ NPNS (White)</option>
                            </select>
                            @error('card_type')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Card Type Info -->
                    <div class="mt-3 p-3 bg-white rounded border">
                        <div class="text-xs text-gray-600">
                            <p class="font-medium mb-1">Card Type Information:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <p>• AAY: Antyodaya Anna Yojana</p>
                                <p>• PHH: Priority Household</p>
                                <p>• NPS: Non-Priority State</p>
                                <p>• NPI: Non-Priority Income</p>
                                <p>• NPNS: Non-Priority Non-Subsidized</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address and Notes Section -->
            <div class="animate-fade-in" style="animation-delay: 0.3s">
                <div class="bg-purple-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Address & Additional Information
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="address" id="address" rows="3" 
                                      placeholder="Enter customer's complete address"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      placeholder="Add any additional notes about the customer"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-accent-500 focus:border-accent-500 transition-all duration-200 hover:border-accent-400">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Type Section -->
            <div class="animate-fade-in" style="animation-delay: 0.4s">
                <div class="bg-yellow-50 rounded-lg p-4 mb-6">
                    <h4 class="text-md font-semibold text-yellow-900 mb-4 flex items-center">
                        <i class="fas fa-user-tag mr-2"></i>
                        Customer Type
                    </h4>
                    <div class="bg-white p-4 rounded border">
                        <div class="flex items-start">
                            <input type="checkbox" name="is_walk_in" id="is_walk_in" 
                                   class="mt-1 rounded border-gray-300 text-accent-600 shadow-sm focus:border-accent-300 focus:ring focus:ring-accent-200 focus:ring-opacity-50" 
                                   {{ old('is_walk_in') ? 'checked' : '' }}>
                            <div class="ml-3">
                                <label for="is_walk_in" class="block text-sm font-medium text-gray-700">
                                    Walk-in Customer
                                </label>
                                <p class="text-xs text-gray-500 mt-1">
                                    Check this if the customer doesn't have a regular account (one-time purchase)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="animate-fade-in" style="animation-delay: 0.5s">
                <div class="bg-gradient-to-r from-accent-50 to-primary-50 p-4 rounded-lg border border-accent-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-eye mr-2 text-accent-600"></i>
                        Customer Preview
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="bg-white p-3 rounded border">
                            <p class="text-gray-500">Name:</p>
                            <p class="font-medium" id="preview-name">-</p>
                        </div>
                        <div class="bg-white p-3 rounded border">
                            <p class="text-gray-500">Phone:</p>
                            <p class="font-medium" id="preview-phone">-</p>
                        </div>
                        <div class="bg-white p-3 rounded border">
                            <p class="text-gray-500">Card Number:</p>
                            <p class="font-medium" id="preview-card">-</p>
                        </div>
                        <div class="bg-white p-3 rounded border">
                            <p class="text-gray-500">Card Type:</p>
                            <p class="font-medium" id="preview-type">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-200 animate-fade-in" style="animation-delay: 0.6s">
                <a href="{{ route('customers.index') }}" class="w-full sm:w-auto bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400 transition-all duration-200 transform hover:scale-105 text-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="w-full sm:w-auto bg-accent-600 text-white px-6 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Create Customer
                </button>
            </div>
        </form>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }

        /* Custom hover effects */
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }

        /* Loading state for buttons */
        button:active {
            transform: scale(0.95);
        }

        /* Enhanced form styling */
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Gradient backgrounds */
        .bg-gradient-to-r {
            background: linear-gradient(to right, var(--tw-gradient-stops));
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .text-lg {
                font-size: 1rem;
            }
        }

        /* Enhanced section styling */
        .bg-blue-50 {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
        }

        .bg-green-50 {
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
        }

        .bg-purple-50 {
            background-color: #faf5ff;
            border-left: 4px solid #8b5cf6;
        }

        .bg-yellow-50 {
            background-color: #fefce8;
            border-left: 4px solid #eab308;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form elements
            const nameInput = document.getElementById('name');
            const phoneInput = document.getElementById('phone');
            const cardInput = document.getElementById('ration_card_number');
            const cardTypeSelect = document.getElementById('card_type');
            
            // Preview elements
            const previewName = document.getElementById('preview-name');
            const previewPhone = document.getElementById('preview-phone');
            const previewCard = document.getElementById('preview-card');
            const previewType = document.getElementById('preview-type');

            // Update preview function
            function updatePreview() {
                // Update name with animation
                const nameValue = nameInput.value || '-';
                if (previewName.textContent !== nameValue) {
                    previewName.style.transform = 'scale(1.1)';
                    previewName.textContent = nameValue;
                    setTimeout(() => previewName.style.transform = 'scale(1)', 200);
                }

                // Update phone with animation
                const phoneValue = phoneInput.value || '-';
                if (previewPhone.textContent !== phoneValue) {
                    previewPhone.style.transform = 'scale(1.1)';
                    previewPhone.textContent = phoneValue;
                    setTimeout(() => previewPhone.style.transform = 'scale(1)', 200);
                }

                // Update card number with animation
                const cardValue = cardInput.value || '-';
                if (previewCard.textContent !== cardValue) {
                    previewCard.style.transform = 'scale(1.1)';
                    previewCard.textContent = cardValue;
                    setTimeout(() => previewCard.style.transform = 'scale(1)', 200);
                }

                // Update card type with animation
                const typeValue = cardTypeSelect.selectedOptions[0]?.text || '-';
                if (previewType.textContent !== typeValue) {
                    previewType.style.transform = 'scale(1.1)';
                    previewType.textContent = typeValue;
                    setTimeout(() => previewType.style.transform = 'scale(1)', 200);
                }
            }

            // Add event listeners
            nameInput.addEventListener('input', updatePreview);
            phoneInput.addEventListener('input', updatePreview);
            cardInput.addEventListener('input', updatePreview);
            cardTypeSelect.addEventListener('change', updatePreview);

            // Phone number formatting
            phoneInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, ''); // Remove non-digits
                if (value.length > 10) {
                    value = value.substring(0, 10); // Limit to 10 digits
                }
                this.value = value;
            });

            // Form validation enhancement
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
                submitButton.disabled = true;
            });

            // Initial preview update
            updatePreview();
        });
    </script>
</x-admin-layout>