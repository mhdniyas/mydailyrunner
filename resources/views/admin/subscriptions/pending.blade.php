<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pending Subscription Approvals') }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800">
                    <i class="fas fa-shield-alt w-4 h-4 mr-1"></i>
                    Super Admin Access
                </span>
                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                    <i class="fas fa-user mr-1"></i>
                    mhdniyas
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg transition-all duration-300 hover:shadow-xl">
                <div class="p-4 sm:p-6">
                    <!-- Enhanced Alerts -->
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-sm animate-fade-in" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-sm animate-fade-in" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Enhanced Header Section -->
                    <div class="mb-8 animate-fade-in">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center mb-3">
                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg mr-3">
                                        <i class="fas fa-hourglass-half text-xl text-yellow-600 animate-pulse"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Pending Subscription Requests</h3>
                                        <p class="text-sm text-gray-500">June 11, 2025 • 11:26 AM</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600">As a super admin, you can approve or reject user subscription requests. All pending requests require your review.</p>
                                
                                <!-- Quick Stats -->
                                <div class="mt-4 inline-flex items-center bg-yellow-50 px-4 py-2 rounded-full border border-yellow-200">
                                    <i class="fas fa-clock mr-2 text-yellow-600"></i>
                                    <span class="text-sm font-medium text-yellow-800">{{ $users->count() }} Pending Request{{ $users->count() !== 1 ? 's' : '' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.subscriptions.index') }}" class="w-full lg:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to All Subscriptions
                            </a>
                        </div>
                    </div>

                    <!-- Priority Queue Notice -->
                    @if($users->count() > 0)
                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 animate-fade-in" style="animation-delay: 0.1s">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-900">Priority Review Queue</h4>
                                    <p class="text-sm text-blue-700 mt-1">Requests are ordered by submission date. Older requests appear first for priority processing.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($users->count() > 0)
                        <!-- Mobile Cards View -->
                        <div class="lg:hidden space-y-4 animate-fade-in" style="animation-delay: 0.2s">
                            @foreach($users as $index => $user)
                                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-4 shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 animate-fade-in" style="animation-delay: {{ ($index * 0.1) + 0.3 }}s">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-yellow-600"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-gray-900">{{ $user->name }}</h3>
                                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 animate-pulse">
                                            <i class="fas fa-clock mr-1"></i>
                                            Pending
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                                        <div class="bg-white p-3 rounded border">
                                            <p class="text-gray-500 text-xs mb-1">Request Date</p>
                                            <p class="font-medium">{{ $user->updated_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="bg-white p-3 rounded border">
                                            <p class="text-gray-500 text-xs mb-1">User Since</p>
                                            <p class="font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('admin.subscriptions.approve', $user->id) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 rounded-lg text-sm font-medium text-green-700 bg-white hover:bg-green-50 transition-all duration-200 transform hover:scale-105">
                                                <i class="fas fa-check mr-2"></i>
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.subscriptions.reject', $user->id) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-all duration-200 transform hover:scale-105"
                                            onclick="return confirm('Are you sure you want to reject {{ $user->name }}\'s subscription request?');">
                                                <i class="fas fa-times mr-2"></i>
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop Table View -->
                        <div class="hidden lg:block animate-fade-in" style="animation-delay: 0.2s">
                            <div class="shadow-lg overflow-hidden border border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <i class="fas fa-user mr-2"></i>
                                                    User Information
                                                </div>
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <i class="fas fa-calendar-plus mr-2"></i>
                                                    Request Date
                                                </div>
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <i class="fas fa-user-plus mr-2"></i>
                                                    Member Since
                                                </div>
                                            </th>
                                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center justify-end">
                                                    <i class="fas fa-cogs mr-2"></i>
                                                    Actions
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($users as $index => $user)
                                            <tr class="bg-yellow-50 hover:bg-yellow-100 transition-colors duration-200 animate-fade-in" style="animation-delay: {{ ($index * 0.05) + 0.3 }}s">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4 shadow-sm">
                                                            <i class="fas fa-user text-yellow-600"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 font-medium">{{ $user->updated_at->format('M d, Y') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $user->updated_at->format('l \a\t g:i A') }}</div>
                                                    <div class="text-xs text-yellow-600 font-medium">{{ $user->updated_at->diffForHumans() }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 font-medium">{{ $user->created_at->format('M d, Y') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $user->created_at->format('l \a\t g:i A') }}</div>
                                                    <div class="text-xs text-blue-600 font-medium">{{ $user->created_at->diffForHumans() }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex justify-end gap-2">
                                                        <form method="POST" action="{{ route('admin.subscriptions.approve', $user->id) }}">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-green-300 rounded-lg text-xs font-medium text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105 shadow-sm">
                                                                <i class="fas fa-check mr-2"></i>
                                                                Approve Request
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.subscriptions.reject', $user->id) }}">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-xs font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105 shadow-sm"
                                                            onclick="return confirm('Are you sure you want to reject {{ $user->name }}\'s subscription request? This action cannot be undone.');">
                                                                <i class="fas fa-times mr-2"></i>
                                                                Reject Request
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6 animate-fade-in" style="animation-delay: 0.4s">
                            {{ $users->links() }}
                        </div>

                        <!-- Bulk Actions (if multiple users) -->
                        @if($users->count() > 1)
                            <div class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200 animate-fade-in" style="animation-delay: 0.5s">
                                <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-layer-group mr-2 text-gray-600"></i>
                                    Bulk Actions
                                </h4>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <p class="text-sm text-gray-600 flex-1">Process multiple requests at once to save time.</p>
                                    <div class="flex gap-2">
                                        <button type="button" class="inline-flex items-center px-4 py-2 border border-green-300 rounded-lg text-sm font-medium text-green-700 bg-white hover:bg-green-50 transition-all duration-200 transform hover:scale-105"
                                                onclick="approveAll()">
                                            <i class="fas fa-check-double mr-2"></i>
                                            Approve All
                                        </button>
                                        <button type="button" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-all duration-200 transform hover:scale-105"
                                                onclick="rejectAll()">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Reject All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Enhanced Empty State -->
                        <div class="text-center py-16 animate-fade-in" style="animation-delay: 0.2s">
                            <div class="mx-auto w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-6 shadow-lg">
                                <i class="fas fa-check-circle text-4xl text-green-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">All Caught Up!</h3>
                            <p class="text-lg text-gray-600 mb-2">No pending subscription requests at this time.</p>
                            <p class="text-sm text-gray-500 mb-8 max-w-md mx-auto">All subscription requests have been processed. New requests will appear here for your review.</p>
                            
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-200 transform hover:scale-105">
                                    <i class="fas fa-users-cog mr-2"></i>
                                    View All Subscriptions
                                </a>
                                <button type="button" onclick="location.reload()" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 transform hover:scale-105">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    Refresh Page
                                </button>
                            </div>
                            
                            <!-- Quick Stats for Empty State -->
                            <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-lg mx-auto">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ \App\Models\User::where('is_subscribed', true)->where('is_admin_approved', true)->count() }}</div>
                                    <div class="text-sm text-blue-700">Active Subscriptions</div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-600">{{ \App\Models\User::where('is_subscribed', false)->count() }}</div>
                                    <div class="text-sm text-gray-700">Free Users</div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ \App\Models\User::count() }}</div>
                                    <div class="text-sm text-green-700">Total Users</div>
                                </div>
                            </div>
                        </div>
                    @endif
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

        .hover\:-translate-y-1:hover {
            transform: translateY(-4px);
        }

        /* Loading state for buttons */
        button:active {
            transform: scale(0.95);
        }

        /* Gradient backgrounds */
        .bg-gradient-to-r {
            background: linear-gradient(to right, var(--tw-gradient-stops));
        }

        /* Pulse animation for pending items */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .7;
            }
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .text-xl {
                font-size: 1.125rem;
            }
        }

        /* Enhanced shadow styling */
        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Transition enhancements */
        .transition-colors {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
    </style>

    <script>
        // Bulk action functions
        function approveAll() {
            if (confirm('Are you sure you want to approve all pending subscription requests?')) {
                // Implementation would go here - this would require backend support
                console.log('Approve all requests');
            }
        }

        function rejectAll() {
            if (confirm('Are you sure you want to reject all pending subscription requests? This action cannot be undone.')) {
                // Implementation would go here - this would require backend support
                console.log('Reject all requests');
            }
        }

        // Auto-refresh functionality
        let refreshInterval;
        
        function startAutoRefresh() {
            refreshInterval = setInterval(() => {
                // Only refresh if there are pending requests
                if ({{ $users->count() }} > 0) {
                    location.reload();
                }
            }, 60000); // Refresh every minute
        }

        function stopAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        }

        // Start auto-refresh when page loads
        document.addEventListener('DOMContentLoaded', function() {
            if ({{ $users->count() }} > 0) {
                startAutoRefresh();
            }
        });

        // Stop auto-refresh when page is hidden
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRefresh();
            } else {
                startAutoRefresh();
            }
        });
    </script>
</x-app-layout>