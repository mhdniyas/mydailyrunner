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
    </head>
    <body class="font-sans antialiased bg-primary-50">
        <div class="min-h-screen flex">
            <!-- Mobile menu overlay -->
            <div id="mobile-menu-overlay" 
                 class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden transition-opacity duration-300 ease-in-out touch-none"></div>
            
            <!-- Sidebar -->
            <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 w-64 bg-primary-900 shadow-luxury-lg transition-transform duration-300 ease-in-out z-50 lg:z-auto overflow-y-auto overscroll-contain">
                <div class="flex flex-col h-full">
                    <!-- Logo with close button -->
                    <div class="flex items-center justify-between h-20 bg-primary-800 border-b border-primary-700 px-4">
                        <h1 class="font-serif text-xl lg:text-2xl font-bold text-white">
                            {{ session('current_shop_name') ?? 'Shop Manager' }}
                        </h1>
                        <!-- Mobile close button inside sidebar -->
                        <button id="sidebar-close-button" 
                                class="lg:hidden p-2 rounded-md text-primary-200 hover:text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-accent-500 transition-colors duration-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-2">
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-chart-line mr-3"></i>
                            Dashboard
                        </a>
                        
                        <a href="{{ route('stock-ins.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('stock-ins.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-boxes-stacked mr-3"></i>
                            Stock In
                        </a>
                        
                        <a href="{{ route('daily-stock-checks.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('daily-stock-checks.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-clipboard-check mr-3"></i>
                            Daily Stock Check
                        </a>
                        
                        <a href="{{ route('sales.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('sales.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-receipt mr-3"></i>
                            Sales
                        </a>
                        
                        <a href="{{ route('customer-payments.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('customer-payments.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-users mr-3"></i>
                            Customer Payments
                        </a>
                        
                        <a href="{{ route('customers.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('customers.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-address-card mr-3"></i>
                            Customers
                        </a>
                        
                        <a href="{{ route('financial-entries.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('financial-entries.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-money-bill-wave mr-3"></i>
                            Finance
                        </a>
                        
                        <div class="px-4 py-3">
                            <h5 class="text-xs uppercase font-semibold text-primary-400 tracking-wider">Reports</h5>
                        </div>
                        
                        <a href="{{ route('reports.stock') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('reports.stock') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-boxes mr-3"></i>
                            Stock Report
                        </a>
                        
                        <a href="{{ route('reports.discrepancy') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('reports.discrepancy') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-exclamation-triangle mr-3"></i>
                            Discrepancy Report
                        </a>
                        
                        <a href="{{ route('reports.financial') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('reports.financial') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-chart-bar mr-3"></i>
                            Financial Report
                        </a>
                        
                        <a href="{{ route('reports.customer-dues') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('reports.customer-dues') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-hand-holding-usd mr-3"></i>
                            Customer Dues
                        </a>
                        
                        <a href="{{ route('reports.bag-weights') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('reports.bag-weights') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-weight-hanging mr-3"></i>
                            Bag Weights
                        </a>
                        
                        <div class="px-4 py-3">
                            <h5 class="text-xs uppercase font-semibold text-primary-400 tracking-wider">Tools</h5>
                        </div>
                        
                        <a href="{{ route('discrepancy-calculator.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('discrepancy-calculator.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-calculator mr-3"></i>
                            Discrepancy Calculator
                        </a>
                        
                        <div class="px-4 py-3">
                            <h5 class="text-xs uppercase font-semibold text-primary-400 tracking-wider">Setup</h5>
                        </div>
                        
                        <a href="{{ route('products.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('products.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-box mr-3"></i>
                            Products
                        </a>

                        @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.subscriptions.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.subscriptions.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-id-card mr-3"></i>
                            Subscription Management
                            @php
                                $pendingCount = \App\Models\User::where('is_subscribed', true)->where('is_admin_approved', false)->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-yellow-500 text-white">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        @endif

                        @if(Auth::user()->hasRole('owner'))
                        <a href="{{ route('users.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('users.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-user-shield mr-3"></i>
                            User Management
                        </a>
                        
                        <a href="{{ route('shops.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('shops.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-store mr-3"></i>
                            Shop Management
                        </a>
                        @endif

                        <div class="border-t border-primary-700 my-4"></div>

                        @if(Auth::user()->shops->count() > 1)
                        <a href="{{ route('shops.select') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200">
                            <i class="fas fa-store mr-3"></i> 
                            Switch Shop
                        </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-4 py-3 text-primary-200 rounded-lg hover:bg-red-600 hover:text-white transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                Logout
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
                                    class="mobile-menu-button lg:hidden mr-4">
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
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-accent-600 rounded-full flex items-center justify-center">
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
                <div style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; background-color: #3B82F6; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; z-index: 9999999; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.3);" id="calc-button">
                    <i class="fas fa-calculator" style="font-size: 20px;"></i>
                </div>

                <!-- Calculator Popup -->
                <div id="calc-popup" style="display: none; position: fixed; bottom: 90px; right: 20px; z-index: 9999999; background: white; border-radius: 10px; padding: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.2); width: 280px;">
                    <div style="margin-bottom: 12px;">
                        <input type="text" id="calc-input" value="0" readonly style="width: 100%; padding: 10px; text-align: right; font-size: 18px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                        <button onclick="calcClear()" style="background: #EF4444; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">C</button>
                        <button onclick="calcDelete()" style="background: #F59E0B; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">⌫</button>
                        <button onclick="calcAppend('/')" style="background: #3B82F6; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">/</button>
                        <button onclick="calcAppend('*')" style="background: #3B82F6; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">×</button>
                        
                        <button onclick="calcAppend('7')" style="background: #6B7280; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">7</button>
                        <button onclick="calcAppend('8')" style="background: #6B7280; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">8</button>
                        <button onclick="calcAppend('9')" style="background: #6B7280; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">9</button>
                        <button onclick="calcAppend('-')" style="background: #3B82F6; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">-</button>
                        
                        <button onclick="calcAppend('4')" style="background: #6B7280; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">4</button>
                        <button onclick="calcAppend('5')" style="background: #6B7280; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">5</button>
                        <button onclick="calcAppend('6')" style="background: #6B7280; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">6</button>
                        <button onclick="calcAppend('+')" style="background: #3B82F6; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">+</button>
                        
                        <button onclick="calcAppend('1')" style="background: #6B7280; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">1</button>
                        <button onclick="calcAppend('2')" style="background: #6B7280; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">2</button>
                        <button onclick="calcAppend('3')" style="background: #6B7280; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">3</button>
                        <button onclick="calcCalculate()" style="background: #10B981; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; grid-row: span 2;">=</button>
                        
                        <button onclick="calcAppend('0')" style="background: #6B7280; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; grid-column: span 2;">0</button>
                        <button onclick="calcAppend('.')" style="background: #6B7280; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">.</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Calculator JavaScript -->
        <script>
            // Calculator functionality
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('calc-button').addEventListener('click', function() {
                    const popup = document.getElementById('calc-popup');
                    popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
                });
            });

            let calcInput = document.getElementById('calc-input');
            let calcValue = '0';
            let calcReset = false;

            function updateCalcDisplay() {
                calcInput.value = calcValue;
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
                    calcValue = eval(calcValue).toString();
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

            // Close when clicking outside
            document.addEventListener('click', function(event) {
                const popup = document.getElementById('calc-popup');
                const button = document.getElementById('calc-button');
                
                if (!popup.contains(event.target) && event.target !== button && !button.contains(event.target)) {
                    popup.style.display = 'none';
                }
            });
        </script>
        
        <!-- Mobile Navigation JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobile-menu-overlay');
                const menuIcon = document.getElementById('menu-icon');
                const closeIcon = document.getElementById('close-icon');
                const sidebarCloseButton = document.getElementById('sidebar-close-button');
                
                let isMenuOpen = false;
                
                function openMobileMenu() {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                    mobileMenuButton.classList.add('bg-primary-100', 'text-primary-900');
                    isMenuOpen = true;
                }
                
                function closeMobileMenu() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
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
                
                // Touch gesture support
                let touchStartX = 0;
                let touchCurrentX = 0;
                let isDragging = false;
                
                sidebar.addEventListener('touchstart', function(e) {
                    touchStartX = e.touches[0].clientX;
                    isDragging = true;
                });
                
                sidebar.addEventListener('touchmove', function(e) {
                    if (!isDragging) return;
                    
                    touchCurrentX = e.touches[0].clientX;
                    const diffX = touchStartX - touchCurrentX;
                    
                    // Only close if we're swiping left by a significant amount
                    if (diffX > 70 && isMenuOpen) {
                        closeMobileMenu();
                        isDragging = false;
                    }
                });
                
                sidebar.addEventListener('touchend', function() {
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
            });
        </script>
    </body>
</html>
