<aside class="bg-white shadow-lg rounded-xl p-5 mb-6 border border-gray-100 hover:border-indigo-100 transition-colors duration-300">
    <!-- Quick Access Header -->
    <div class="flex items-center mb-4">
        <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-md flex items-center justify-center">
            <i class="fas fa-bolt text-white"></i>
        </div>
        <h3 class="ml-3 text-lg font-medium text-gray-900">Quick Access</h3>
    </div>
    
    <div class="space-y-1">
        <a href="{{ route('daily-workflow.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-indigo-700 hover:text-white hover:bg-gradient-to-r hover:from-indigo-600 hover:to-purple-600 transition-all duration-200">
            <div class="mr-3 h-8 w-8 rounded-lg bg-indigo-100 group-hover:bg-indigo-200 flex items-center justify-center text-indigo-600 group-hover:text-white transition-colors duration-200">
                <i class="fas fa-tasks"></i>
            </div>
            <span>Daily Workflow</span>
            <span class="ml-auto inline-flex items-center rounded-full bg-indigo-100 group-hover:bg-white px-2 py-1 text-xs font-medium text-indigo-700 group-hover:text-indigo-700 ring-1 ring-inset ring-indigo-600/20 group-hover:ring-indigo-700/40">
                New
            </span>
        </a>
        
        <a href="{{ route('stock-ins.create') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-indigo-700 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-cyan-600 transition-all duration-200">
            <div class="mr-3 h-8 w-8 rounded-lg bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center text-blue-600 group-hover:text-white transition-colors duration-200">
                <i class="fas fa-boxes"></i>
            </div>
            <span>Add Stock</span>
            <span class="ml-auto inline-flex items-center rounded-full bg-blue-100 group-hover:bg-white px-2 py-1 text-xs font-medium text-blue-700 group-hover:text-blue-700 ring-1 ring-inset ring-blue-600/20 group-hover:ring-blue-700/40">
                Batches
            </span>
        </a>
        
        <a href="{{ route('sales.create') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-indigo-700 hover:text-white hover:bg-gradient-to-r hover:from-emerald-600 hover:to-green-600 transition-all duration-200">
            <div class="mr-3 h-8 w-8 rounded-lg bg-emerald-100 group-hover:bg-emerald-200 flex items-center justify-center text-emerald-600 group-hover:text-white transition-colors duration-200">
                <i class="fas fa-cash-register"></i>
            </div>
            <span>New Sale</span>
        </a>
        
        <a href="{{ route('daily-stock-checks.create') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-indigo-700 hover:text-white hover:bg-gradient-to-r hover:from-amber-600 hover:to-yellow-600 transition-all duration-200">
            <div class="mr-3 h-8 w-8 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center text-amber-600 group-hover:text-white transition-colors duration-200">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <span>Stock Check</span>
        </a>
        
        <a href="{{ route('reports.stock') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-indigo-700 hover:text-white hover:bg-gradient-to-r hover:from-purple-600 hover:to-fuchsia-600 transition-all duration-200">
            <div class="mr-3 h-8 w-8 rounded-lg bg-purple-100 group-hover:bg-purple-200 flex items-center justify-center text-purple-600 group-hover:text-white transition-colors duration-200">
                <i class="fas fa-chart-bar"></i>
            </div>
            <span>Reports</span>
        </a>
    </div>
    
    <div class="mt-6 pt-6 border-t border-gray-200">
        <h4 class="text-sm font-medium text-gray-600 mb-2">Subscription Status</h4>
        
        @if(Auth::user()->subscription_status === 'active')
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-md p-3 shadow-sm hover:shadow transition-shadow duration-300">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 flex items-center">
                            Active
                            <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Premium
                            </span>
                        </p>
                        @if(Auth::user()->subscription_expires_at)
                            <p class="mt-1 text-xs text-green-700 flex items-center">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Expires: {{ Auth::user()->subscription_expires_at->format('M d, Y') }}
                                
                                @if(Auth::user()->needsExpirationWarning())
                                    <span class="ml-1 text-yellow-600">
                                        <i class="fas fa-exclamation-circle" title="Expiring soon"></i>
                                    </span>
                                @endif
                            </p>
                        @else
                            <p class="mt-1 text-xs text-green-700">
                                <i class="fas fa-infinity mr-1"></i>
                                No expiration date
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->subscription_status === 'grace_period')
            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-md p-3 shadow-sm hover:shadow transition-shadow duration-300">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center animate-pulse">
                            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800 flex items-center">
                            Grace Period
                            <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Action Required
                            </span>
                        </p>
                        <p class="mt-1 text-xs text-yellow-700">
                            <i class="fas fa-clock mr-1"></i>
                            Limited access - Please renew soon
                        </p>
                        <a href="{{ route('subscription.status') }}" class="mt-2 inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-600 hover:to-amber-600 shadow-sm">
                            <i class="fas fa-sync-alt mr-1"></i>
                            Renew Subscription
                        </a>
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->subscription_status === 'pending')
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-md p-3 shadow-sm hover:shadow transition-shadow duration-300">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-blue-600 animate-pulse"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800 flex items-center">
                            Approval Pending
                            <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                In Review
                            </span>
                        </p>
                        <p class="mt-1 text-xs text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            Your request is being reviewed
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-red-50 to-rose-50 rounded-md p-3 shadow-sm hover:shadow transition-shadow duration-300">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 flex items-center">
                            Subscription Expired
                            <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Limited Access
                            </span>
                        </p>
                        <p class="mt-1 text-xs text-red-700">
                            <i class="fas fa-lock mr-1"></i>
                            Some features are restricted
                        </p>
                        <a href="{{ route('subscription.status') }}" class="mt-2 inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 shadow-sm">
                            <i class="fas fa-rocket mr-1"></i>
                            Upgrade Now
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Helpful Tips Section -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center mb-3">
            <div class="w-6 h-6 bg-gradient-to-r from-amber-500 to-yellow-500 rounded-full shadow-sm flex items-center justify-center">
                <i class="fas fa-lightbulb text-white text-xs"></i>
            </div>
            <h4 class="ml-2 text-sm font-medium text-gray-700">Tips & Shortcuts</h4>
        </div>
        
        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-lg p-3 border border-amber-100 shadow-sm">
            <p class="text-xs text-amber-800 mb-2">
                <i class="fas fa-star text-amber-500 mr-1"></i>
                <strong>Pro Tip:</strong> Use the new Daily Workflow for guided step-by-step operations.
            </p>
            <p class="text-xs text-amber-700">
                <i class="fas fa-keyboard text-amber-500 mr-1"></i>
                <strong>Shortcut:</strong> Press <kbd class="px-1.5 py-0.5 text-xs bg-white rounded border border-gray-300 shadow-sm">Alt+D</kbd> to quickly access the Daily Workflow from anywhere.
            </p>
        </div>
        
        <!-- Version Info -->
        <div class="mt-4 text-center">
            <p class="text-xs text-gray-500">
                MorningCricket v2.5.0
                <span class="ml-1 inline-flex items-center rounded-full bg-indigo-50 px-1.5 py-0.5 text-xs font-medium text-indigo-700">
                    <i class="fas fa-arrow-up text-[10px] mr-0.5"></i>
                    Updated
                </span>
            </p>
        </div>
    </div>
</aside>
