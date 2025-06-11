<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Status') }}
        </h2>
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

                    @if (session('warning'))
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-r-lg shadow-sm animate-fade-in" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <p>{{ session('warning') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-r-lg shadow-sm animate-fade-in" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                <p>{{ session('info') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Header Section -->
                    <div class="mb-8 text-center animate-fade-in">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-full mb-4 shadow-lg">
                            <i class="fas fa-crown text-3xl text-indigo-600"></i>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Subscription Management</h1>
                        <p class="text-gray-600 text-sm sm:text-base">Manage your subscription and access premium features</p>
                        
                        <!-- User Info -->
                        <div class="mt-4 inline-flex items-center bg-gray-50 px-4 py-2 rounded-full">
                            <i class="fas fa-user-circle text-indigo-600 mr-2"></i>
                            <span class="text-sm font-medium text-gray-700">Welcome, mhdniyas</span>
                            <span class="mx-2 text-gray-400">•</span>
                            <span class="text-xs text-gray-500">June 11, 2025</span>
                        </div>
                    </div>

                    <!-- Current Status Section -->
                    <div class="mb-8 animate-fade-in" style="animation-delay: 0.1s">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-chart-line mr-2 text-indigo-600"></i>
                            Current Subscription Status
                        </h3>
                        
                        @if($user->is_subscribed && $user->is_admin_approved)
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-lg border-l-4 border-green-400 shadow-sm transition-all duration-300 hover:shadow-md">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-green-100 shadow-sm">
                                            <i class="fas fa-crown text-xl text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h4 class="text-lg font-semibold text-green-800 flex items-center">
                                            <span>Active Premium Subscription</span>
                                            <span class="ml-2 px-2 py-1 text-xs bg-green-200 text-green-800 rounded-full">ACTIVE</span>
                                        </h4>
                                        <p class="mt-1 text-sm text-green-700">You have full access to all premium features and capabilities.</p>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <span class="inline-flex items-center px-2 py-1 text-xs bg-green-200 text-green-800 rounded-full">
                                                <i class="fas fa-check mr-1"></i> Unlimited Products
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs bg-green-200 text-green-800 rounded-full">
                                                <i class="fas fa-check mr-1"></i> Advanced Reports
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs bg-green-200 text-green-800 rounded-full">
                                                <i class="fas fa-check mr-1"></i> Priority Support
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($user->is_subscribed && !$user->is_admin_approved)
                            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-lg border-l-4 border-yellow-400 shadow-sm transition-all duration-300 hover:shadow-md">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 shadow-sm animate-pulse">
                                            <i class="fas fa-clock text-xl text-yellow-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h4 class="text-lg font-semibold text-yellow-800 flex items-center">
                                            <span>Subscription Pending Approval</span>
                                            <span class="ml-2 px-2 py-1 text-xs bg-yellow-200 text-yellow-800 rounded-full animate-pulse">PENDING</span>
                                        </h4>
                                        <p class="mt-1 text-sm text-yellow-700">Your subscription request is being reviewed by our team. You'll be notified when approved.</p>
                                        <div class="mt-3 bg-yellow-100 p-3 rounded-md">
                                            <p class="text-xs text-yellow-800">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Submitted on {{ $user->updated_at->format('F j, Y \a\t g:i A') }} • Typical processing time: 1-2 business days
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-6 rounded-lg border-l-4 border-gray-400 shadow-sm transition-all duration-300 hover:shadow-md">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 shadow-sm">
                                            <i class="fas fa-lock text-xl text-gray-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                                            <span>Free Account</span>
                                            <span class="ml-2 px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded-full">LIMITED</span>
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-600">You have access to basic features. Upgrade to premium for full capabilities.</p>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <span class="inline-flex items-center px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded-full">
                                                <i class="fas fa-times mr-1"></i> Limited Products
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded-full">
                                                <i class="fas fa-times mr-1"></i> Basic Reports
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded-full">
                                                <i class="fas fa-times mr-1"></i> Standard Support
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Section -->
                    <div class="border-t border-gray-200 pt-6 mb-8 animate-fade-in" style="animation-delay: 0.2s">
                        @if($user->is_subscribed && $user->is_admin_approved)
                            <div class="bg-white p-6 rounded-lg border border-red-200 shadow-sm">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                            <i class="fas fa-times-circle mr-2 text-red-600"></i>
                                            Cancel Subscription
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-600">You can cancel your subscription at any time. You'll lose access to premium features immediately.</p>
                                        <div class="mt-2 text-xs text-gray-500">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            This action cannot be undone. You'll need to request a new subscription to regain access.
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('subscription.cancel') }}" class="flex-shrink-0">
                                        @csrf
                                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105"
                                                onclick="return confirm('Are you sure you want to cancel your subscription? This action cannot be undone.');">
                                            <i class="fas fa-ban mr-2"></i>
                                            Cancel Subscription
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @elseif($user->is_subscribed && !$user->is_admin_approved)
                            <div class="bg-white p-6 rounded-lg border border-yellow-200 shadow-sm">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-hourglass-half mr-2 text-yellow-600"></i>
                                        Subscription Request Status
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-600">Your subscription request is currently under review by our administrators.</p>
                                    
                                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-alt text-yellow-600 mr-2"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-yellow-800">Submitted</p>
                                                    <p class="text-xs text-yellow-700">{{ $user->updated_at->format('M j, Y \a\t g:i A') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                            <div class="flex items-center">
                                                <i class="fas fa-user-shield text-blue-600 mr-2"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-blue-800">Status</p>
                                                    <p class="text-xs text-blue-700">Under Admin Review</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <form method="POST" action="{{ route('subscription.cancel') }}" class="mt-6">
                                        @csrf
                                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                                            <i class="fas fa-times mr-2"></i>
                                            Cancel Request
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="bg-white p-6 rounded-lg border border-indigo-200 shadow-sm">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                            <i class="fas fa-rocket mr-2 text-indigo-600"></i>
                                            Upgrade to Premium
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-600">Unlock all features and get the most out of your inventory management system.</p>
                                        <div class="mt-2 text-xs text-gray-500">
                                            <i class="fas fa-zap mr-1"></i>
                                            Instant access to advanced features upon approval
                                        </div>
                                    </div>
                                    <a href="{{ route('subscription.request') }}" class="flex-shrink-0 w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                                        <i class="fas fa-crown mr-2"></i>
                                        Request Premium Access
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Subscription Benefits -->
                    <div class="border-t border-gray-200 pt-6 animate-fade-in" style="animation-delay: 0.3s">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-star mr-2 text-yellow-500"></i>
                            Premium Subscription Benefits
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2">
                            <div class="relative rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.4s">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-infinity text-xl text-indigo-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900">Unlimited Product Management</h4>
                                        <p class="text-sm text-gray-500 mt-1">Add and track unlimited products across all your shops with advanced categorization</p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.5s">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-chart-line text-xl text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900">Advanced Financial Tracking</h4>
                                        <p class="text-sm text-gray-500 mt-1">Comprehensive financial reports, analytics, and profit/loss statements</p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.6s">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-boxes text-xl text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900">Smart Inventory Management</h4>
                                        <p class="text-sm text-gray-500 mt-1">Real-time stock tracking, low stock alerts, and automated reorder points</p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.7s">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-users text-xl text-purple-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900">Complete Sales & Customer Management</h4>
                                        <p class="text-sm text-gray-500 mt-1">Customer database, sales tracking, payment management, and detailed customer insights</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Benefits -->
                        <div class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-lg border border-indigo-200">
                            <h4 class="text-md font-semibold text-indigo-900 mb-4 flex items-center">
                                <i class="fas fa-gift mr-2"></i>
                                Additional Premium Features
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                                <div class="flex items-center text-indigo-700">
                                    <i class="fas fa-check-circle mr-2 text-indigo-600"></i>
                                    Priority customer support
                                </div>
                                <div class="flex items-center text-indigo-700">
                                    <i class="fas fa-check-circle mr-2 text-indigo-600"></i>
                                    Data export capabilities
                                </div>
                                <div class="flex items-center text-indigo-700">
                                    <i class="fas fa-check-circle mr-2 text-indigo-600"></i>
                                    Advanced search & filters
                                </div>
                                <div class="flex items-center text-indigo-700">
                                    <i class="fas fa-check-circle mr-2 text-indigo-600"></i>
                                    Custom report generation
                                </div>
                                <div class="flex items-center text-indigo-700">
                                    <i class="fas fa-check-circle mr-2 text-indigo-600"></i>
                                    Multi-location support
                                </div>
                                <div class="flex items-center text-indigo-700">
                                    <i class="fas fa-check-circle mr-2 text-indigo-600"></i>
                                    API access for integrations
                                </div>
                            </div>
                        </div>
                    </div>
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

        /* Enhanced border styling */
        .border-l-4 {
            border-left-width: 4px;
        }

        /* Pulse animation for pending status */
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
            .text-3xl {
                font-size: 2rem;
            }
            
            .text-2xl {
                font-size: 1.5rem;
            }
        }

        /* Enhanced card styling */
        .hover\:shadow-md:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Status badge animations */
        .bg-green-200 {
            background-color: #bbf7d0;
        }

        .bg-yellow-200 {
            background-color: #fef08a;
        }

        .bg-gray-200 {
            background-color: #e5e7eb;
        }
    </style>
</x-app-layout>