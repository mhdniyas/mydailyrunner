<x-app-layout>
    <x-slot name="title">{{ ucfirst($entry->type) }} Details</x-slot>
    <x-slot name="subtitle">View financial entry information</x-slot>

    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
        <!-- Header Section -->
        <div class="mb-6 animate-fade-in">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-{{ $entry->type === 'income' ? 'arrow-up' : 'arrow-down' }} mr-2 text-{{ $entry->type === 'income' ? 'green' : 'red' }}-600"></i>
                        {{ ucfirst($entry->type) }} Entry #{{ $entry->id }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $entry->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <a href="{{ route('financial-entries.edit', $entry) }}" class="bg-accent-600 text-white px-4 py-2 rounded-md hover:bg-accent-700 transition-all duration-200 transform hover:scale-105 text-center">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <form action="{{ route('financial-entries.destroy', $entry) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-all duration-200 transform hover:scale-105" 
                                onclick="return confirm('Are you sure you want to delete this entry?')">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Amount Highlight Section -->
        <div class="mb-6 animate-fade-in" style="animation-delay: 0.1s">
            <div class="bg-gradient-to-r from-{{ $entry->type === 'income' ? 'green' : 'red' }}-50 to-{{ $entry->type === 'income' ? 'emerald' : 'pink' }}-50 p-6 rounded-lg border-l-4 border-{{ $entry->type === 'income' ? 'green' : 'red' }}-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-{{ $entry->type === 'income' ? 'green' : 'red' }}-700 font-medium">{{ ucfirst($entry->type) }} Amount</p>
                        <p class="text-3xl font-bold text-{{ $entry->type === 'income' ? 'green' : 'red' }}-800 mt-1">
                            {{ $entry->type === 'income' ? '+' : '-' }}₹{{ number_format($entry->amount, 2) }}
                        </p>
                    </div>
                    <div class="bg-{{ $entry->type === 'income' ? 'green' : 'red' }}-100 p-3 rounded-full">
                        <i class="fas fa-{{ $entry->type === 'income' ? 'plus' : 'minus' }}-circle text-2xl text-{{ $entry->type === 'income' ? 'green' : 'red' }}-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Entry Details -->
        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="animate-fade-in" style="animation-delay: 0.2s">
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Basic Information
                    </h4>
                    
                    <!-- Mobile View - Cards -->
                    <div class="block lg:hidden space-y-3">
                        <div class="bg-white p-3 rounded border">
                            <p class="text-xs text-gray-500 mb-1">Type</p>
                            <div>
                                @if($entry->type === 'income')
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-up mr-1"></i> Income
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-arrow-down mr-1"></i> Expense
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-white p-3 rounded border">
                            <p class="text-xs text-gray-500 mb-1">Category</p>
                            <p class="font-medium text-gray-900">{{ $entry->category->name }}</p>
                        </div>
                        
                        <div class="bg-white p-3 rounded border">
                            <p class="text-xs text-gray-500 mb-1">Date</p>
                            <p class="font-medium text-gray-900">{{ $entry->entry_date->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $entry->entry_date->format('l') }}</p>
                        </div>
                        
                        <div class="bg-white p-3 rounded border">
                            <p class="text-xs text-gray-500 mb-1">Reference</p>
                            <p class="font-medium text-gray-900">{{ $entry->reference ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="bg-white p-3 rounded border">
                            <p class="text-xs text-gray-500 mb-1">Created By</p>
                            <p class="font-medium text-gray-900">{{ $entry->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $entry->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Desktop View - Grid -->
                    <div class="hidden lg:grid lg:grid-cols-3 gap-4">
                        <div class="bg-white p-3 rounded border">
                            <p class="text-sm text-gray-500 mb-2">Type</p>
                            <div>
                                @if($entry->type === 'income')
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-up mr-1"></i> Income
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-arrow-down mr-1"></i> Expense
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-white p-3 rounded border">
                            <p class="text-sm text-gray-500 mb-2">Category</p>
                            <p class="font-medium text-gray-900">{{ $entry->category->name }}</p>
                        </div>
                        
                        <div class="bg-white p-3 rounded border">
                            <p class="text-sm text-gray-500 mb-2">Date</p>
                            <p class="font-medium text-gray-900">{{ $entry->entry_date->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $entry->entry_date->format('l') }}</p>
                        </div>
                        
                        <div class="bg-white p-3 rounded border">
                            <p class="text-sm text-gray-500 mb-2">Reference</p>
                            <p class="font-medium text-gray-900">{{ $entry->reference ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="bg-white p-3 rounded border">
                            <p class="text-sm text-gray-500 mb-2">Created By</p>
                            <p class="font-medium text-gray-900">{{ $entry->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $entry->created_at->diffForHumans() }}</p>
                        </div>
                        
                        <div class="bg-white p-3 rounded border">
                            <p class="text-sm text-gray-500 mb-2">Last Updated</p>
                            <p class="font-medium text-gray-900">{{ $entry->updated_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $entry->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            @if($entry->description)
                <div class="animate-fade-in" style="animation-delay: 0.3s">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-file-alt mr-2"></i>
                            Description
                        </h4>
                        <div class="bg-white p-4 rounded border">
                            <p class="text-gray-700 leading-relaxed">{{ $entry->description }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Entry Statistics -->
            <div class="animate-fade-in" style="animation-delay: 0.4s">
                <div class="bg-purple-50 rounded-lg p-4">
                    <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Entry Statistics
                    </h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-white p-3 rounded border text-center">
                            <p class="text-xs text-gray-500">Entry ID</p>
                            <p class="font-bold text-lg text-purple-600">#{{ $entry->id }}</p>
                        </div>
                        <div class="bg-white p-3 rounded border text-center">
                            <p class="text-xs text-gray-500">Days Ago</p>
                            <p class="font-bold text-lg text-blue-600">{{ $entry->entry_date->diffInDays(now()) }}</p>
                        </div>
                        <div class="bg-white p-3 rounded border text-center">
                            <p class="text-xs text-gray-500">Category</p>
                            <p class="font-bold text-sm text-gray-700">{{ Str::limit($entry->category->name, 10) }}</p>
                        </div>
                        <div class="bg-white p-3 rounded border text-center">
                            <p class="text-xs text-gray-500">Status</p>
                            <p class="font-bold text-sm text-green-600">Recorded</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action History -->
            <div class="animate-fade-in" style="animation-delay: 0.5s">
                <div class="bg-yellow-50 rounded-lg p-4">
                    <h4 class="text-md font-semibold text-yellow-900 mb-4 flex items-center">
                        <i class="fas fa-history mr-2"></i>
                        Action History
                    </h4>
                    <div class="space-y-3">
                        <div class="bg-white p-3 rounded border flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-plus text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm">Entry Created</p>
                                    <p class="text-xs text-gray-500">by {{ $entry->user->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">{{ $entry->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $entry->created_at->format('h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($entry->created_at != $entry->updated_at)
                            <div class="bg-white p-3 rounded border flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-edit text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm">Entry Updated</p>
                                        <p class="text-xs text-gray-500">Last modification</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium">{{ $entry->updated_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $entry->updated_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="mt-8 pt-6 border-t border-gray-200 animate-fade-in" style="animation-delay: 0.6s">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('financial-entries.index') }}" class="w-full sm:w-auto text-primary-600 hover:text-primary-900 transition-colors flex items-center justify-center sm:justify-start">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Financial Entries
                </a>
                
                <div class="flex gap-2 w-full sm:w-auto">
                    <a href="{{ route('financial-entries.edit', $entry) }}" class="flex-1 sm:flex-none bg-accent-100 text-accent-700 px-4 py-2 rounded-md hover:bg-accent-200 transition-colors text-center">
                        <i class="fas fa-edit mr-2"></i>Quick Edit
                    </a>
                </div>
            </div>
        </div>
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

        .animate-fade-in {
            animation: fade-in 0.6s ease-out forwards;
            opacity: 0;
        }

        /* Custom hover effects */
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }

        /* Loading state for buttons */
        button:active {
            transform: scale(0.95);
        }

        /* Enhanced section styling */
        .bg-blue-50 {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
        }

        .bg-gray-50 {
            background-color: #f9fafb;
            border-left: 4px solid #6b7280;
        }

        .bg-purple-50 {
            background-color: #faf5ff;
            border-left: 4px solid #8b5cf6;
        }

        .bg-yellow-50 {
            background-color: #fefce8;
            border-left: 4px solid #eab308;
        }

        /* Gradient backgrounds */
        .bg-gradient-to-r {
            background: linear-gradient(to right, var(--tw-gradient-stops));
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .text-3xl {
                font-size: 2rem;
            }
        }

        /* Enhanced border styling */
        .border-l-4 {
            border-left-width: 4px;
        }

        /* Currency highlighting */
        .text-green-800 {
            color: #166534;
        }

        .text-red-800 {
            color: #991b1b;
        }
    </style>
</x-app-layout>