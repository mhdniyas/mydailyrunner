<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Shop Manager' }} - {{ config('app.name', 'Shop Stock & Financial Manager') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Chart.js for analytics -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!-- Custom Styles for Enhanced Sidebar -->
        <style>
            /* Enhanced Sidebar Transitions */
            .sidebar-transition {
                transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.4s ease;
            }
            
            /* Overlay transition */
            .overlay-transition {
                transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                backdrop-filter: blur(2px);
            }
            
            /* Nav item hover effects */
            .nav-item {
                position: relative;
                overflow: hidden;
            }
            
            .nav-item::after {
                content: '';
                position: absolute;
                left: 0;
                bottom: 0;
                width: 0;
                height: 2px;
                background-color: currentColor;
                transition: width 0.3s ease;
            }
            
            .nav-item:hover::after {
                width: 100%;
            }
            
            /* Button effects */
            .menu-button-effect {
                transition: transform 0.2s ease, background-color 0.2s ease;
            }
            
            .menu-button-effect:active {
                transform: scale(0.95);
            }
            
            /* Active link indicator */
            .nav-active {
                position: relative;
            }
            
            .nav-active::before {
                content: '';
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 4px;
                height: 60%;
                background-color: #fff;
                border-radius: 0 2px 2px 0;
            }
            
            /* Sidebar scroll styling */
            .sidebar-scroll {
                scrollbar-width: thin;
                scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
            }
            
            .sidebar-scroll::-webkit-scrollbar {
                width: 4px;
            }
            
            .sidebar-scroll::-webkit-scrollbar-track {
                background: transparent;
            }
            
            .sidebar-scroll::-webkit-scrollbar-thumb {
                background-color: rgba(255, 255, 255, 0.2);
                border-radius: 20px;
            }
            
            /* Menu item animation */
            @keyframes slideIn {
                0% {
                    opacity: 0;
                    transform: translateX(-10px);
                }
                100% {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            .menu-appear {
                animation: slideIn 0.3s ease forwards;
            }
            
            /* Ripple effect for buttons */
            .ripple {
                position: relative;
                overflow: hidden;
            }
            
            .ripple::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 5px;
                height: 5px;
                background: rgba(255, 255, 255, 0.5);
                opacity: 0;
                border-radius: 100%;
                transform: scale(1, 1) translate(-50%, -50%);
                transform-origin: 50% 50%;
            }
            
            .ripple:focus:not(:active)::after {
                animation: ripple 0.5s ease-out;
            }
            
            @keyframes ripple {
                0% {
                    transform: scale(0, 0) translate(-50%, -50%);
                    opacity: 0.5;
                }
                100% {
                    transform: scale(20, 20) translate(-50%, -50%);
                    opacity: 0;
                }
            }
            
            /* Dropdown menu styles */
            .dropdown-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease-out, opacity 0.3s ease;
                opacity: 0;
                pointer-events: none;
            }
            
            .dropdown-content.show {
                max-height: 500px;
                opacity: 1;
                transition: max-height 0.5s ease-in, opacity 0.3s ease;
                pointer-events: auto;
            }
            
            .dropdown-chevron {
                transition: transform 0.3s ease;
            }
            
            .dropdown-chevron.rotate {
                transform: rotate(180deg);
            }
            
            /* Fixed Calculator Popup for Mobile */
            .calc-popup {
                position: fixed;
                bottom: 75px; 
                right: 20px;
                z-index: 9999999;
                width: min(90vw, 280px);
                background: white;
                border-radius: 12px;
                padding: 15px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.25);
                display: none;
                transform: translateY(10px);
                opacity: 0;
                transition: transform 0.3s ease, opacity 0.3s ease;
                max-width: 300px;
            }
            
            @media (max-width: 320px) {
                .calc-popup {
                    right: 10px;
                    left: 10px;
                    width: auto;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-primary-50">
        <div class="min-h-screen flex">
            <!-- Mobile menu overlay -->
            <div id="mobile-menu-overlay" 
                 class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden overlay-transition touch-none"></div>
            
            <!-- Sidebar -->
            <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 w-64 bg-primary-900 shadow-luxury-lg sidebar-transition z-50 lg:z-auto overflow-y-auto overscroll-contain sidebar-scroll">
                <div class="flex flex-col h-full">
                    <!-- Logo with close button -->
                    <div class="flex items-center justify-between h-20 bg-primary-800 border-b border-primary-700 px-4 sticky top-0 z-10">
                        <h1 class="font-serif text-xl lg:text-2xl font-bold text-white">
                            {{ session('current_shop_name') ?? 'Shop Manager' }}
                        </h1>
                        <!-- Mobile close button inside sidebar -->
                        <button id="sidebar-close-button" 
                                class="lg:hidden p-2 rounded-md text-primary-200 hover:text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-accent-500 transition-colors duration-200 menu-button-effect ripple">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation with Dropdowns -->
                    <nav class="flex-1 px-4 py-3 space-y-1">
                        <!-- Dashboard - Single Item -->
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 nav-item ripple {{ request()->routeIs('dashboard') ? 'bg-accent-600 text-white nav-active' : '' }}">
                            <i class="fas fa-chart-line mr-3"></i>
                            <span class="menu-appear" style="animation-delay: 0.05s">Dashboard</span>
                        </a>
                        
                        <!-- Stock Management Dropdown -->
                        <div class="dropdown-section">
                            <button class="dropdown-button flex items-center justify-between w-full px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 nav-item ripple {{ request()->routeIs('stock-ins.*') || request()->routeIs('daily-stock-checks.*') ? 'bg-accent-600 text-white nav-active' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-boxes-stacked mr-3"></i>
                                    <span class="menu-appear" style="animation-delay: 0.1s">Stock Management</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-chevron text-xs"></i>
                            </button>
                            <div class="dropdown-content pl-8 pr-2">
                                <a href="{{ route('stock-ins.index') }}" 
                                   class="flex items-center px-4 py-2 mt-1 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('stock-ins.*') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-arrow-right-to-bracket mr-3 text-xs"></i>
                                    <span>Stock In</span>
                                </a>
                                <a href="{{ route('daily-stock-checks.index') }}" 
                                   class="flex items-center px-4 py-2 mb-1 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('daily-stock-checks.*') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-clipboard-check mr-3 text-xs"></i>
                                    <span>Daily Stock Check</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Sales & Customers Dropdown -->
                        <div class="dropdown-section">
                            <button class="dropdown-button flex items-center justify-between w-full px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 nav-item ripple {{ request()->routeIs('sales.*') || request()->routeIs('customer-payments.*') || request()->routeIs('customers.*') ? 'bg-accent-600 text-white nav-active' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-cash-register mr-3"></i>
                                    <span class="menu-appear" style="animation-delay: 0.15s">Sales & Customers</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-chevron text-xs"></i>
                            </button>
                            <div class="dropdown-content pl-8 pr-2">
                                <a href="{{ route('sales.index') }}" 
                                   class="flex items-center px-4 py-2 mt-1 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('sales.*') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-receipt mr-3 text-xs"></i>
                                    <span>Sales</span>
                                </a>
                                <a href="{{ route('customer-payments.index') }}" 
                                   class="flex items-center px-4 py-2 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('customer-payments.*') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-money-bill-wave mr-3 text-xs"></i>
                                    <span>Customer Payments</span>
                                </a>
                                <a href="{{ route('customers.index') }}" 
                                   class="flex items-center px-4 py-2 mb-1 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('customers.*') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-address-card mr-3 text-xs"></i>
                                    <span>Customers</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Finance - Single Item -->
                        <a href="{{ route('financial-entries.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 nav-item ripple {{ request()->routeIs('financial-entries.*') ? 'bg-accent-600 text-white nav-active' : '' }}">
                            <i class="fas fa-money-bill-wave mr-3"></i>
                            <span class="menu-appear" style="animation-delay: 0.2s">Finance</span>
                        </a>
                        
                        <!-- Reports Dropdown -->
                        <div class="dropdown-section">
                            <button class="dropdown-button flex items-center justify-between w-full px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 nav-item ripple {{ request()->routeIs('reports.*') ? 'bg-accent-600 text-white nav-active' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-chart-bar mr-3"></i>
                                    <span class="menu-appear" style="animation-delay: 0.25s">Reports</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-chevron text-xs"></i>
                            </button>
                            <div class="dropdown-content pl-8 pr-2">
                                <a href="{{ route('reports.stock') }}" 
                                   class="flex items-center px-4 py-2 mt-1 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('reports.stock') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-boxes mr-3 text-xs"></i>
                                    <span>Stock Report</span>
                                </a>
                                <a href="{{ route('reports.discrepancy') }}" 
                                   class="flex items-center px-4 py-2 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('reports.discrepancy') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-exclamation-triangle mr-3 text-xs"></i>
                                    <span>Discrepancy Report</span>
                                </a>
                                <a href="{{ route('reports.financial') }}" 
                                   class="flex items-center px-4 py-2 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('reports.financial') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-chart-pie mr-3 text-xs"></i>
                                    <span>Financial Report</span>
                                </a>
                                <a href="{{ route('reports.customer-dues') }}" 
                                   class="flex items-center px-4 py-2 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('reports.customer-dues') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-hand-holding-usd mr-3 text-xs"></i>
                                    <span>Customer Dues</span>
                                </a>
                                <a href="{{ route('reports.bag-weights') }}" 
                                   class="flex items-center px-4 py-2 mb-1 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('reports.bag-weights') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-weight-hanging mr-3 text-xs"></i>
                                    <span>Bag Weights</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Tools Dropdown -->
                        <div class="dropdown-section">
                            <button class="dropdown-button flex items-center justify-between w-full px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 nav-item ripple {{ request()->routeIs('discrepancy-calculator.*') ? 'bg-accent-600 text-white nav-active' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-tools mr-3"></i>
                                    <span class="menu-appear" style="animation-delay: 0.3s">Tools</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-chevron text-xs"></i>
                            </button>
                            <div class="dropdown-content pl-8 pr-2">
                                <a href="{{ route('discrepancy-calculator.index') }}" 
                                   class="flex items-center px-4 py-2 mt-1 mb-1 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('discrepancy-calculator.*') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-calculator mr-3 text-xs"></i>
                                    <span>Discrepancy Calculator</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Setup Dropdown -->
                        <div class="dropdown-section">
                            <button class="dropdown-button flex items-center justify-between w-full px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 nav-item ripple {{ request()->routeIs('products.*') || request()->routeIs('admin.subscriptions.*') || request()->routeIs('users.*') || request()->routeIs('shops.*') ? 'bg-accent-600 text-white nav-active' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-cogs mr-3"></i>
                                    <span class="menu-appear" style="animation-delay: 0.35s">Setup</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-chevron text-xs"></i>
                            </button>
                            <div class="dropdown-content pl-8 pr-2">
                                <a href="{{ route('products.index') }}" 
                                   class="flex items-center px-4 py-2 mt-1 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('products.*') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-box mr-3 text-xs"></i>
                                    <span>Products</span>
                                </a>

                                @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.subscriptions.index') }}" 
                                   class="flex items-center px-4 py-2 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.subscriptions.*') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-id-card mr-3 text-xs"></i>
                                    <span>Subscription Management</span>
                                    @php
                                        $pendingCount = \App\Models\User::where('is_subscribed', true)->where('is_admin_approved', false)->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-yellow-500 text-white animate-pulse">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                                @endif

                                @if(Auth::user()->hasRole('owner'))
                                <a href="{{ route('users.index') }}" 
                                   class="flex items-center px-4 py-2 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('users.*') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-user-shield mr-3 text-xs"></i>
                                    <span>User Management</span>
                                </a>
                                
                                <a href="{{ route('shops.index') }}" 
                                   class="flex items-center px-4 py-2 mb-1 text-primary-300 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('shops.*') && !request()->routeIs('shops.select') ? 'bg-primary-700 text-white' : '' }}">
                                    <i class="fas fa-store mr-3 text-xs"></i>
                                    <span>Shop Management</span>
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="border-t border-primary-700 my-4"></div>

                        @if(Auth::user()->shops->count() > 1)
                        <a href="{{ route('shops.select') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 nav-item ripple {{ request()->routeIs('shops.select') ? 'bg-accent-600 text-white nav-active' : '' }}">
                            <i class="fas fa-exchange-alt mr-3"></i> 
                            <span class="menu-appear" style="animation-delay: 0.4s">Switch Shop</span>
                        </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-4 py-3 text-primary-200 rounded-lg hover:bg-red-600 hover:text-white transition-colors duration-200 nav-item ripple">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                <span class="menu-appear" style="animation-delay: 0.45s">Logout</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0 lg:ml-0">
                <!-- Top Bar -->
                <header class="bg-white shadow-sm border-b border-primary-100">
                    <div class="flex items-center justify-between px-4 lg:px-6 py-4">
                        <div class="flex items-center">
                            <!-- Mobile menu button -->
                            <button id="mobile-menu-button" 
                                    class="mobile-menu-button lg:hidden mr-4 p-2 rounded-md text-primary-800 hover:bg-primary-100 hover:text-primary-900 menu-button-effect ripple">
                                <!-- Hamburger icon -->
                                <svg id="menu-icon" class="h-6 w-6 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <!-- Close icon (hidden by default) -->
                                <svg id="close-icon" class="h-6 w-6 hidden transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            
                            <div>
                                <h2 class="font-serif text-xl lg:text-2xl font-semibold text-primary-900">
                                    {{ $title ?? 'Dashboard' }}
                                </h2>
                                @if(isset($subtitle))
                                    <p class="text-primary-600 mt-1 text-sm lg:text-base hidden sm:block">{{ $subtitle }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 lg:space-x-4">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-medium text-primary-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-primary-500">{{ ucfirst(session('current_shop_role') ?? 'User') }}</p>
                            </div>
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-accent-600 rounded-full flex items-center justify-center transition-transform hover:scale-105">
                                <i class="fas fa-user text-white text-sm lg:text-base"></i>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 lg:p-6 overflow-x-auto">
                    {{ $slot }}
                </main>
                
                <!-- Calculator Widget -->
                <!-- Direct Calculator Button -->
                <div style="position: fixed !important; bottom: 20px !important; right: 20px !important; width: 60px !important; height: 60px !important; background-color: #3B82F6 !important; color: white !important; border-radius: 50% !important; display: flex !important; justify-content: center !important; align-items: center !important; z-index: 9999999 !important; cursor: pointer !important; box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important; transition: transform 0.2s !important;" id="calc-button" class="ripple">
                    <i class="fas fa-calculator" style="font-size: 20px !important;"></i>
                </div>

                <!-- Calculator Popup -->
                <div id="calc-popup" style="display: none; position: fixed !important; bottom: 90px !important; right: 20px !important; z-index: 9999999 !important; background: white !important; border-radius: 10px !important; padding: 15px !important; box-shadow: 0 8px 25px rgba(0,0,0,0.2) !important; width: 280px !important; transform: translateY(10px); opacity: 0; transition: transform 0.3s ease, opacity 0.3s ease;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <span style="font-weight: bold; color: #333;">Calculator</span>
                        <button id="calc-close-btn" style="background: none; border: none; color: #777; cursor: pointer; font-size: 16px;">×</button>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <input type="text" id="calc-input" value="0" readonly style="width: 100% !important; padding: 10px !important; text-align: right !important; font-size: 18px !important; border: 1px solid #ddd !important; border-radius: 5px !important; background: #f9f9f9 !important;">
                    </div>
                    
                    <div style="display: grid !important; grid-template-columns: repeat(4, 1fr) !important; gap: 8px !important;">
                        <button onclick="calcClear()" class="calc-btn" style="background: #EF4444 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">C</button>
                        <button onclick="calcDelete()" class="calc-btn" style="background: #F59E0B !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">⌫</button>
                        <button onclick="calcAppend('/')" class="calc-btn" style="background: #3B82F6 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">÷</button>
                        <button onclick="calcAppend('*')" class="calc-btn" style="background: #3B82F6 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">×</button>
                        
                        <button onclick="calcAppend('7')" class="calc-btn" style="background: #6B7280 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">7</button>
                        <button onclick="calcAppend('8')" class="calc-btn" style="background: #6B7280 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">8</button>
                        <button onclick="calcAppend('9')" class="calc-btn" style="background: #6B7280 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">9</button>
                        <button onclick="calcAppend('-')" class="calc-btn" style="background: #3B82F6 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">-</button>
                        
                        <button onclick="calcAppend('4')" class="calc-btn" style="background: #6B7280 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">4</button>
                        <button onclick="calcAppend('5')" class="calc-btn" style="background: #6B7280 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">5</button>
                        <button onclick="calcAppend('6')" class="calc-btn" style="background: #6B7280 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">6</button>
                        <button onclick="calcAppend('+')" class="calc-btn" style="background: #3B82F6 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">+</button>
                        
                        <button onclick="calcAppend('1')" class="calc-btn" style="background: #6B7280 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">1</button>
                        <button onclick="calcAppend('2')" class="calc-btn" style="background: #6B7280 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">2</button>
                        <button onclick="calcAppend('3')" class="calc-btn" style="background: #6B7280 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">3</button>
                        <button onclick="calcCalculate()" class="calc-btn" style="background: #10B981 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important; grid-row: span 2 !important;">=</button>
                        
                        <button onclick="calcAppend('0')" class="calc-btn" style="background: #6B7280 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important; grid-column: span 2 !important;">0</button>
                        <button onclick="calcAppend('.')" class="calc-btn" style="background: #6B7280 !important; color: white !important; border: none !important; padding: 12px !important; border-radius: 5px !important; cursor: pointer !important; transition: transform 0.1s !important;">.</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Calculator JavaScript -->
        <script>
            // Global variables and functions
            let calcInput;
            let calcValue = '0';
            let calcReset = false;
            let hideCalculator; // Will be defined in the DOMContentLoaded
            
            // Calculator functionality
            document.addEventListener('DOMContentLoaded', function() {
                const calcButton = document.getElementById('calc-button');
                const calcPopup = document.getElementById('calc-popup');
                const calcCloseBtn = document.getElementById('calc-close-btn');
                
                calcInput = document.getElementById('calc-input');
                
                // Position the calculator absolutely to prevent it from moving
                calcButton.style.position = 'fixed';
                calcButton.style.bottom = '20px';
                calcButton.style.right = '20px';
                calcButton.style.zIndex = '9999';
                
                // Show calculator when button is clicked
                calcButton.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent event from bubbling up
                    if (calcPopup.style.display === 'none' || calcPopup.style.display === '') {
                        showCalculator();
                    } else {
                        hideCalculator();
                    }
                });
                
                // Hover effect for calculator button
                calcButton.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.1)';
                });
                
                calcButton.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
                
                // Close calculator when close button is clicked
                calcCloseBtn.addEventListener('click', function() {
                    hideCalculator();
                });
                
                function showCalculator() {
                    // Show calculator with animation
                    calcPopup.style.display = 'block';
                    // Trigger reflow
                    void calcPopup.offsetWidth;
                    calcPopup.style.opacity = '1';
                    calcPopup.style.transform = 'translateY(0)';
                }
                
                // Define the hideCalculator function
                hideCalculator = function() {
                    // Hide calculator with animation
                    calcPopup.style.opacity = '0';
                    calcPopup.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        calcPopup.style.display = 'none';
                    }, 300);
                };
                
                // Button press effect with ripple
                const calcButtons = document.querySelectorAll('.calc-btn');
                calcButtons.forEach(button => {
                    // Mouse events
                    button.addEventListener('mousedown', function(e) {
                        // Scale effect
                        this.style.transform = 'scale(0.95)';
                        
                        // Ripple effect
                        createRipple(button, e.clientX, e.clientY);
                    });
                    
                    // Touch events
                    button.addEventListener('touchstart', function(e) {
                        // Scale effect
                        this.style.transform = 'scale(0.95)';
                        
                        // Get touch position
                        const touch = e.touches[0];
                        const rect = button.getBoundingClientRect();
                        const x = touch.clientX - rect.left;
                        const y = touch.clientY - rect.top;
                        
                        // Create ripple at touch position
                        createRipple(button, touch.clientX, touch.clientY);
                    });
                    
                    button.addEventListener('mouseup', function() {
                        this.style.transform = 'scale(1)';
                    });
                    
                    button.addEventListener('touchend', function() {
                        this.style.transform = 'scale(1)';
                    });
                    
                    button.addEventListener('mouseleave', function() {
                        this.style.transform = 'scale(1)';
                    });
                    
                    button.addEventListener('touchcancel', function() {
                        this.style.transform = 'scale(1)';
                    });
                });
                
                // Helper function to create ripple effect
                function createRipple(element, clientX, clientY) {
                    const rect = element.getBoundingClientRect();
                    const x = clientX - rect.left;
                    const y = clientY - rect.top;
                    
                    const ripple = document.createElement('span');
                    ripple.style.position = 'absolute';
                    ripple.style.width = '5px';
                    ripple.style.height = '5px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
                    ripple.style.borderRadius = '50%';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s linear';
                    ripple.style.pointerEvents = 'none';
                    
                    element.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                }
            });

            // Calculator functions
            function updateCalcDisplay() {
                document.getElementById('calc-input').value = calcValue;
            }

            function calcAppend(val) {
                if (calcReset) {
                    calcValue = '0';
                    calcReset = false;
                }

                if (calcValue === '0' && val !== '.') {
                    calcValue = val;
                } else {
                    calcValue += val;
                }
                updateCalcDisplay();
            }

            function calcClear() {
                calcValue = '0';
                updateCalcDisplay();
            }

            function calcDelete() {
                if (calcValue.length > 1) {
                    calcValue = calcValue.slice(0, -1);
                } else {
                    calcValue = '0';
                }
                updateCalcDisplay();
            }

            function calcCalculate() {
                try {
                    // Replace display symbols with actual JavaScript operators
                    let expression = calcValue.replace(/×/g, '*').replace(/÷/g, '/');
                    calcValue = eval(expression).toString();
                    calcReset = true;
                    updateCalcDisplay();
                } catch (e) {
                    calcValue = 'Error';
                    calcReset = true;
                    updateCalcDisplay();
                    
                    setTimeout(() => {
                        calcValue = '0';
                        calcReset = false;
                        updateCalcDisplay();
                    }, 1500);
                }
            }

            // Close calculator when clicking outside
            document.addEventListener('click', function(event) {
                const popup = document.getElementById('calc-popup');
                const button = document.getElementById('calc-button');
                
                if (popup.style.display !== 'none' && 
                    !popup.contains(event.target) && 
                    event.target !== button && 
                    !button.contains(event.target)) {
                    // Call the hide calculator function
                    hideCalculator();
                }
            });
            
            // Keyboard support for calculator
            document.addEventListener('keydown', function(event) {
                const popup = document.getElementById('calc-popup');
                if (popup.style.display !== 'block') return;
                
                const key = event.key;
                if (/[0-9]/.test(key)) calcAppend(key);
                else if (key === '.') calcAppend('.');
                else if (key === '+') calcAppend('+');
                else if (key === '-') calcAppend('-');
                else if (key === '*') calcAppend('*');
                else if (key === '/') calcAppend('/');
                else if (key === 'Enter') calcCalculate();
                else if (key === 'Escape') {
                    hideCalculator();
                }
                else if (key === 'Backspace') calcDelete();
            });
        </script>
        
        <!-- Mobile Navigation JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize dropdown functionality
                const dropdownButtons = document.querySelectorAll('.dropdown-button');
                
                dropdownButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault(); // Prevent default behavior
                        e.stopPropagation(); // Prevent event bubbling
                        
                        // Get the dropdown content
                        const content = this.nextElementSibling;
                        const chevron = this.querySelector('.dropdown-chevron');
                        
                        // Toggle show class
                        content.classList.toggle('show');
                        chevron.classList.toggle('rotate');
                        
                        // Close other dropdowns
                        dropdownButtons.forEach(otherButton => {
                            if (otherButton !== button) {
                                const otherContent = otherButton.nextElementSibling;
                                const otherChevron = otherButton.querySelector('.dropdown-chevron');
                                otherContent.classList.remove('show');
                                otherChevron.classList.remove('rotate');
                            }
                        });
                    });
                });
                
                // Check for active dropdowns and expand them on page load
                dropdownButtons.forEach(button => {
                    if (button.classList.contains('nav-active') || button.querySelector('.nav-active')) {
                        const content = button.nextElementSibling;
                        const chevron = button.querySelector('.dropdown-chevron');
                        content.classList.add('show');
                        chevron.classList.add('rotate');
                    }
                });
                
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobile-menu-overlay');
                const menuIcon = document.getElementById('menu-icon');
                const closeIcon = document.getElementById('close-icon');
                const sidebarCloseButton = document.getElementById('sidebar-close-button');
                
                let isMenuOpen = false;
                
                function openMobileMenu() {
                    // Show overlay first with fade in
                    overlay.classList.remove('hidden');
                    setTimeout(() => {
                        overlay.style.opacity = '1';
                    }, 10);
                    
                    // Then slide in sidebar
                    setTimeout(() => {
                        sidebar.classList.remove('-translate-x-full');
                        sidebar.classList.add('shadow-2xl');
                    }, 100);
                    
                    document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                    mobileMenuButton.classList.add('bg-primary-100', 'text-primary-900');
                    
                    // Animate menu items sequentially
                    const menuItems = sidebar.querySelectorAll('.menu-appear');
                    menuItems.forEach((item, index) => {
                        item.style.opacity = '0';
                        setTimeout(() => {
                            item.style.opacity = '1';
                        }, 150 + (index * 50));
                    });
                    
                    isMenuOpen = true;
                }
                
                function closeMobileMenu() {
                    // First animate sidebar out
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('shadow-2xl');
                    
                    // Then fade out overlay
                    setTimeout(() => {
                        overlay.style.opacity = '0';
                        setTimeout(() => {
                            overlay.classList.add('hidden');
                        }, 300);
                    }, 200);
                    
                    document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    mobileMenuButton.classList.remove('bg-primary-100', 'text-primary-900');
                    isMenuOpen = false;
                }
                
                function toggleMobileMenu() {
                    if (isMenuOpen) {
                        closeMobileMenu();
                    } else {
                        openMobileMenu();
                    }
                }
                
                // Event listeners for menu toggle
                mobileMenuButton.addEventListener('click', toggleMobileMenu);
                overlay.addEventListener('click', closeMobileMenu);
                sidebarCloseButton.addEventListener('click', closeMobileMenu);
                
                // Add button press effect
                mobileMenuButton.addEventListener('mousedown', function() {
                    this.style.transform = 'scale(0.95)';
                });
                
                mobileMenuButton.addEventListener('mouseup', function() {
                    this.style.transform = 'scale(1)';
                });
                
                mobileMenuButton.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
                
                // Close menu on navigation
                const navLinks = sidebar.querySelectorAll('nav a, nav button[type="submit"]');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 1024 && isMenuOpen) {
                            setTimeout(closeMobileMenu, 150);
                        }
                    });
                });
                
                // Window resize handler
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1024 && isMenuOpen) {
                        closeMobileMenu();
                    }
                });
                
                // Keyboard support
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && isMenuOpen) {
                        closeMobileMenu();
                    }
                });
                
                // Enhanced Touch gesture support
                let touchStartX = 0;
                let touchCurrentX = 0;
                let touchStartY = 0;
                let touchCurrentY = 0;
                let isDragging = false;
                let isVerticalScroll = false;
                
                sidebar.addEventListener('touchstart', function(e) {
                    touchStartX = e.touches[0].clientX;
                    touchStartY = e.touches[0].clientY;
                    isDragging = true;
                    isVerticalScroll = false;
                });
                
                sidebar.addEventListener('touchmove', function(e) {
                    if (!isDragging) return;
                    
                    touchCurrentX = e.touches[0].clientX;
                    touchCurrentY = e.touches[0].clientY;
                    
                    // Determine if the user is trying to scroll vertically
                    const diffX = Math.abs(touchStartX - touchCurrentX);
                    const diffY = Math.abs(touchStartY - touchCurrentY);
                    
                    // If vertical scrolling is dominant, let the browser handle it
                    if (diffY > diffX && !isVerticalScroll) {
                        isVerticalScroll = true;
                        return;
                    }
                    
                    if (isVerticalScroll) return;
                    
                    const swipeDistance = touchStartX - touchCurrentX;
                    
                    // Only close if we're swiping left by a significant amount
                    if (swipeDistance > 70 && isMenuOpen) {
                        closeMobileMenu();
                        isDragging = false;
                    }
                    
                    // Optional: Animate the sidebar as the user drags
                    if (isMenuOpen && swipeDistance > 0) {
                        sidebar.style.transform = `translateX(-${swipeDistance}px)`;
                        e.preventDefault(); // Prevent browser handling when doing horizontal swipe
                    }
                });
                
                sidebar.addEventListener('touchend', function() {
                    if (isDragging && isMenuOpen && !isVerticalScroll) {
                        // If dragged less than threshold, snap back to open position
                        sidebar.style.transform = ''; // Reset any inline transform
                    }
                    isDragging = false;
                });
                
                document.addEventListener('touchstart', function(e) {
                    // Handle swipe from left edge to open menu
                    if (!isMenuOpen && e.touches[0].clientX < 30) {
                        touchStartX = e.touches[0].clientX;
                        isDragging = true;
                    }
                });
                
                document.addEventListener('touchmove', function(e) {
                    if (!isMenuOpen && isDragging) {
                        touchCurrentX = e.touches[0].clientX;
                        const swipeDistance = touchCurrentX - touchStartX;
                        
                        // If swiping right from the edge more than 70px, open the menu
                        if (swipeDistance > 70) {
                            openMobileMenu();
                            isDragging = false;
                        }
                    }
                });
                
                document.addEventListener('touchend', function() {
                    isDragging = false;
                });
                
                // Prevent scrolling issues when sidebar is open
                document.addEventListener('touchmove', function(e) {
                    if (isMenuOpen && window.innerWidth < 1024) {
                        // Only prevent default if touch is on the overlay, not the sidebar content
                        if (e.target.closest('#sidebar') === null) {
                            e.preventDefault();
                        }
                    }
                }, { passive: false });
                
                // Initialize buttons with ripple effect
                const rippleButtons = document.querySelectorAll('.ripple');
                rippleButtons.forEach(button => {
                    button.addEventListener('mousedown', function(e) {
                        const rect = button.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;
                        
                        const ripple = document.createElement('span');
                        ripple.style.position = 'absolute';
                        ripple.style.width = '5px';
                        ripple.style.height = '5px';
                        ripple.style.left = x + 'px';
                        ripple.style.top = y + 'px';
                        ripple.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
                        ripple.style.borderRadius = '50%';
                        ripple.style.transform = 'scale(0)';
                        ripple.style.animation = 'ripple 0.6s linear';
                        ripple.style.pointerEvents = 'none';
                        
                        button.style.position = 'relative';
                        button.style.overflow = 'hidden';
                        button.appendChild(ripple);
                        
                        setTimeout(() => {
                            ripple.remove();
                        }, 600);
                    });
                });
                
                // Add animation keyframes
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes ripple {
                        to {
                            transform: scale(20);
                            opacity: 0;
                        }
                    }
                `;
                document.head.appendChild(style);
            });
        </script>
    </body>
</html>