<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Subscriptions') }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800">
                    <i class="fas fa-shield-alt w-4 h-4 mr-1"></i>
                    Super Admin Panel
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
                    <!-- Enhanced Success Alert -->
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-sm animate-fade-in" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Enhanced Header Section -->
                    <div class="mb-8 animate-fade-in">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center mb-2">
                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg mr-3">
                                        <i class="fas fa-users-cog text-xl text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Subscription Management Dashboard</h3>
                                        <p class="text-sm text-gray-500">June 11, 2025 • 10:54 AM</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">Manage subscription statuses for all users in the system. Only super admins can perform these actions.</p>
                            </div>
                            <a href="{{ route('admin.subscriptions.pending') }}" class="w-full lg:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-hourglass-half mr-2"></i>
                                Pending Approvals
                                @php
                                    $pendingCount = \App\Models\User::where('subscription_status', 'pending')->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-white text-indigo-600 animate-pulse">{{ $pendingCount }}</span>
                                @endif
                            </a>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
                            <div class="bg-green-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-green-600 font-medium">Active</p>
                                        <p class="text-2xl font-bold text-green-800">{{ $users->where('subscription_status', 'active')->count() }}</p>
                                    </div>
                                    <div class="bg-green-100 p-2 rounded-full">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-yellow-600 font-medium">Pending</p>
                                        <p class="text-2xl font-bold text-yellow-800">{{ $users->where('subscription_status', 'pending')->count() }}</p>
                                    </div>
                                    <div class="bg-yellow-100 p-2 rounded-full">
                                        <i class="fas fa-clock text-yellow-600"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-600 font-medium">Free Users</p>
                                        <p class="text-2xl font-bold text-gray-800">{{ $users->filter(function($user) { 
                                                return $user->subscription_status === 'expired' || $user->subscription_status === null; 
                                            })->count() }}</p>
                                    </div>
                                    <div class="bg-gray-100 p-2 rounded-full">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-blue-600 font-medium">Total Users</p>
                                        <p class="text-2xl font-bold text-blue-800">{{ $users->count() }}</p>
                                    </div>
                                    <div class="bg-blue-100 p-2 rounded-full">
                                        <i class="fas fa-users text-blue-600"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Subscriptions Section -->
                    <div class="mb-8 animate-fade-in" style="animation-delay: 0.1s">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 sm:p-6 rounded-lg border-l-4 border-green-400 mb-4">
                            <h4 class="text-lg font-semibold text-green-900 mb-2 flex items-center">
                                <i class="fas fa-crown mr-2"></i>
                                Active Premium Subscriptions
                                <span class="ml-3 text-sm bg-green-200 text-green-800 px-3 py-1 rounded-full">
                                    {{ $users->where('subscription_status', 'active')->count() }} Active
                                </span>
                            </h4>
                            <p class="text-sm text-green-700">Users with full premium access and approved subscriptions</p>
                        </div>
                        
                        <!-- Mobile Cards View -->
                        <div class="lg:hidden space-y-3">
                            @php $hasApprovedUsers = false; @endphp
                            @foreach($users as $user)
                                @if($user->subscription_status === 'active')
                                    @php $hasApprovedUsers = true; @endphp
                                    <div class="bg-white border border-green-200 rounded-lg p-4 shadow-sm transition-all duration-300 hover:shadow-md">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex-1">
                                                <h3 class="font-medium text-gray-900">{{ $user->name }}</h3>
                                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Active
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center text-sm text-gray-500 mb-3">
                                            <span>Approved: {{ $user->updated_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex justify-end">
                                            <form method="POST" action="{{ route('admin.subscriptions.toggle', $user->id) }}">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-all duration-200 transform hover:scale-105"
                                                onclick="return confirm('Are you sure you want to cancel this subscription?');">
                                                    <i class="fas fa-ban mr-2"></i>
                                                    Cancel Subscription
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            
                            @if(!$hasApprovedUsers)
                                <div class="text-center py-8">
                                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-crown text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500">No approved subscriptions found.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Desktop Table View -->
                        <div class="hidden lg:block shadow overflow-hidden border border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approval Date</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $hasApprovedUsers = false; @endphp
                                    @foreach($users as $user)
                                        @if($user->subscription_status === 'active')
                                            @php $hasApprovedUsers = true; @endphp
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                            <i class="fas fa-user text-green-600"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        <i class="fas fa-crown mr-1"></i>
                                                        Premium Active
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $user->updated_at->format('M d, Y') }}
                                                    <div class="text-xs text-gray-400">{{ $user->updated_at->diffForHumans() }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <form method="POST" action="{{ route('admin.subscriptions.toggle', $user->id) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-red-300 rounded-lg text-xs font-medium text-red-700 bg-white hover:bg-red-50 transition-all duration-200 transform hover:scale-105"
                                                        onclick="return confirm('Are you sure you want to cancel this subscription?');">
                                                            <i class="fas fa-ban mr-1"></i>
                                                            Cancel
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    
                                    @if(!$hasApprovedUsers)
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-crown text-4xl text-gray-300 mb-3"></i>
                                                    <p>No approved subscriptions found.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pending Approvals Section -->
                    <div class="mb-8 animate-fade-in" style="animation-delay: 0.2s">
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-4 sm:p-6 rounded-lg border-l-4 border-yellow-400 mb-4">
                            <h4 class="text-lg font-semibold text-yellow-900 mb-2 flex items-center">
                                <i class="fas fa-hourglass-half mr-2 animate-pulse"></i>
                                Pending Subscription Approvals
                                <span class="ml-3 text-sm bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full animate-pulse">
                                    {{ $users->where('subscription_status', 'pending')->count() }} Pending
                                </span>
                            </h4>
                            <p class="text-sm text-yellow-700">Subscription requests awaiting admin approval</p>
                        </div>
                        
                        <!-- Mobile Cards View -->
                        <div class="lg:hidden space-y-3">
                            @php $hasPendingUsers = false; @endphp
                            @foreach($users as $user)
                                @if($user->subscription_status === 'pending')
                                    @php $hasPendingUsers = true; @endphp
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 shadow-sm transition-all duration-300 hover:shadow-md">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex-1">
                                                <h3 class="font-medium text-gray-900">{{ $user->name }}</h3>
                                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 animate-pulse">
                                                <i class="fas fa-clock mr-1"></i>
                                                Pending
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center text-sm text-gray-500 mb-3">
                                            <span>Requested: {{ $user->updated_at->format('M d, Y') }}</span>
                                            <span class="text-xs">{{ $user->updated_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <form method="POST" action="{{ route('admin.subscriptions.approve', $user->id) }}" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 border border-green-300 rounded-lg text-sm font-medium text-green-700 bg-white hover:bg-green-50 transition-all duration-200 transform hover:scale-105">
                                                    <i class="fas fa-check mr-2"></i>
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.subscriptions.reject', $user->id) }}" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-all duration-200 transform hover:scale-105"
                                                onclick="return confirm('Are you sure you want to reject this subscription request?');">
                                                    <i class="fas fa-times mr-2"></i>
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            
                            @if(!$hasPendingUsers)
                                <div class="text-center py-8">
                                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-check-circle text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500">No pending subscription requests.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Desktop Table View -->
                        <div class="hidden lg:block shadow overflow-hidden border border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested On</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $hasPendingUsers = false; @endphp
                                    @foreach($users as $user)
                                        @if($user->subscription_status === 'pending')
                                            @php $hasPendingUsers = true; @endphp
                                            <tr class="bg-yellow-50 hover:bg-yellow-100 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                                            <i class="fas fa-user text-yellow-600"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 animate-pulse">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Awaiting Approval
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $user->updated_at->format('M d, Y') }}
                                                    <div class="text-xs text-gray-400">{{ $user->updated_at->diffForHumans() }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex justify-end gap-2">
                                                        <form method="POST" action="{{ route('admin.subscriptions.approve', $user->id) }}">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-green-300 rounded-lg text-xs font-medium text-green-700 bg-white hover:bg-green-50 transition-all duration-200 transform hover:scale-105">
                                                                <i class="fas fa-check mr-1"></i>
                                                                Approve
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.subscriptions.reject', $user->id) }}">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-red-300 rounded-lg text-xs font-medium text-red-700 bg-white hover:bg-red-50 transition-all duration-200 transform hover:scale-105"
                                                            onclick="return confirm('Are you sure you want to reject this subscription request?');">
                                                                <i class="fas fa-times mr-1"></i>
                                                                Reject
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    
                                    @if(!$hasPendingUsers)
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-check-circle text-4xl text-gray-300 mb-3"></i>
                                                    <p>No pending subscription requests.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Not Subscribed Section -->
                    <div class="animate-fade-in" style="animation-delay: 0.3s">
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-4 sm:p-6 rounded-lg border-l-4 border-gray-400 mb-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                                <i class="fas fa-users mr-2"></i>
                                Free Account Users
                                <span class="ml-3 text-sm bg-gray-200 text-gray-800 px-3 py-1 rounded-full">
                                    {{ $users->filter(function($user) { 
                                            return $user->subscription_status === 'expired' || $user->subscription_status === null; 
                                        })->count() }} Users
                                </span>
                            </h4>
                            <p class="text-sm text-gray-700">Users with basic free accounts</p>
                        </div>
                        
                        <!-- Mobile Cards View -->
                        <div class="lg:hidden space-y-3">
                            @php $hasNotSubscribedUsers = false; @endphp
                            @foreach($users as $user)
                                @if($user->subscription_status === 'expired' || $user->subscription_status === null)
                                    @php $hasNotSubscribedUsers = true; @endphp
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm transition-all duration-300 hover:shadow-md">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex-1">
                                                <h3 class="font-medium text-gray-900">{{ $user->name }}</h3>
                                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                <i class="fas fa-user mr-1"></i>
                                                Free
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center text-sm text-gray-500 mb-3">
                                            <span>Last Active: {{ $user->updated_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex justify-end">
                                            <form method="POST" action="{{ route('admin.subscriptions.toggle', $user->id) }}">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-indigo-300 rounded-lg text-sm font-medium text-indigo-700 bg-white hover:bg-indigo-50 transition-all duration-200 transform hover:scale-105">
                                                    <i class="fas fa-crown mr-2"></i>
                                                    Grant Premium Access
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            
                            @if(!$hasNotSubscribedUsers)
                                <div class="text-center py-8">
                                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-users text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500">All users have active or pending subscriptions.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Desktop Table View -->
                        <div class="hidden lg:block shadow overflow-hidden border border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $hasNotSubscribedUsers = false; @endphp
                                    @foreach($users as $user)
                                        @if($user->subscription_status === 'expired' || $user->subscription_status === null)
                                            @php $hasNotSubscribedUsers = true; @endphp
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                            <i class="fas fa-user text-gray-600"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        <i class="fas fa-user mr-1"></i>
                                                        Free Account
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $user->updated_at->format('M d, Y') }}
                                                    <div class="text-xs text-gray-400">{{ $user->updated_at->diffForHumans() }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <form method="POST" action="{{ route('admin.subscriptions.toggle', $user->id) }}">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-indigo-300 rounded-lg text-xs font-medium text-indigo-700 bg-white hover:bg-indigo-50 transition-all duration-200 transform hover:scale-105">
                                                            <i class="fas fa-crown mr-1"></i>
                                                            Activate Premium
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    
                                    @if(!$hasNotSubscribedUsers)
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-crown text-4xl text-gray-300 mb-3"></i>
                                                    <p>All users have active or pending subscriptions.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 animate-fade-in" style="animation-delay: 0.4s">
                        {{ $users->links() }}
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
            .text-2xl {
                font-size: 1.5rem;
            }
        }

        /* Enhanced card styling */
        .hover\:shadow-md:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Transition enhancements */
        .transition-colors {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</x-app-layout>