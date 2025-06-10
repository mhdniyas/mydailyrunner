<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                            <p>{{ session('warning') }}</p>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                            <p>{{ session('info') }}</p>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Current Subscription Status</h3>
                        <div class="mt-4 flex items-center">
                            @if($user->is_subscribed && $user->is_admin_approved)
                                <div class="flex items-center p-4 bg-green-50 rounded-lg border border-green-100 w-full">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100 text-green-500 mr-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <div>
                                        <h4 class="text-md font-medium text-green-800">Active Subscription</h4>
                                        <p class="mt-1 text-sm text-green-600">You have full access to all features of the application.</p>
                                    </div>
                                </div>
                            @elseif($user->is_subscribed && !$user->is_admin_approved)
                                <div class="flex items-center p-4 bg-yellow-50 rounded-lg border border-yellow-100 w-full">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    </span>
                                    <div>
                                        <h4 class="text-md font-medium text-yellow-800">Pending Approval</h4>
                                        <p class="mt-1 text-sm text-yellow-600">Your subscription request is being reviewed by an administrator. You'll be notified when it's approved.</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-100 w-full">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 text-gray-500 mr-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <div>
                                        <h4 class="text-md font-medium text-gray-800">No Active Subscription</h4>
                                        <p class="mt-1 text-sm text-gray-600">You have limited access to features. Subscribe now to unlock all capabilities.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        @if($user->is_subscribed && $user->is_admin_approved)
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-md font-medium text-gray-900">Cancel Subscription</h4>
                                        <p class="mt-1 text-sm text-gray-600">You can cancel your subscription at any time, but you'll lose access to premium features.</p>
                                    </div>
                                    <form method="POST" action="{{ route('subscription.cancel') }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            onclick="return confirm('Are you sure you want to cancel your subscription?');">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Cancel Subscription
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @elseif($user->is_subscribed && !$user->is_admin_approved)
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div>
                                    <h4 class="text-md font-medium text-gray-900">Subscription Request Pending</h4>
                                    <p class="mt-1 text-sm text-gray-600">Your subscription request is currently being reviewed. You'll be notified once it's approved.</p>
                                    <div class="mt-4 bg-yellow-50 p-4 rounded-md">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800">Request submitted on {{ $user->updated_at->format('F j, Y') }}</h3>
                                                <div class="mt-2 text-sm text-yellow-700">
                                                    <p>Subscription requests are typically processed within 1-2 business days. If you have any questions, please contact support.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('subscription.cancel') }}" class="mt-4">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            Cancel Request
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-md font-medium text-gray-900">Subscribe Now</h4>
                                        <p class="mt-1 text-sm text-gray-600">Get full access to all features by subscribing today.</p>
                                    </div>
                                    <a href="{{ route('subscription.request') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                        </svg>
                                        Request Subscription
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-gray-200 mt-6 pt-6">
                        <h3 class="text-lg font-medium text-gray-900">Subscription Benefits</h3>
                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="relative rounded-lg border border-gray-200 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 005.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="#" class="focus:outline-none">
                                        <p class="text-sm font-medium text-gray-900">Unlimited Product Management</p>
                                        <p class="text-sm text-gray-500 truncate">Add and track unlimited products across all your shops</p>
                                    </a>
                                </div>
                            </div>
                            <div class="relative rounded-lg border border-gray-200 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="#" class="focus:outline-none">
                                        <p class="text-sm font-medium text-gray-900">Financial Tracking</p>
                                        <p class="text-sm text-gray-500 truncate">Comprehensive financial reports and analytics</p>
                                    </a>
                                </div>
                            </div>
                            <div class="relative rounded-lg border border-gray-200 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="#" class="focus:outline-none">
                                        <p class="text-sm font-medium text-gray-900">Inventory Management</p>
                                        <p class="text-sm text-gray-500 truncate">Advanced stock tracking and notifications</p>
                                    </a>
                                </div>
                            </div>
                            <div class="relative rounded-lg border border-gray-200 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="#" class="focus:outline-none">
                                        <p class="text-sm font-medium text-gray-900">Sales Management</p>
                                        <p class="text-sm text-gray-500 truncate">Complete sales tracking and customer management</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
