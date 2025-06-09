<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopSync - Ultimate Shop Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #6B7280 0%, #1F2937 100%);
        }
        .feature-card {
            transition: transform 0.3s ease-in-out;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .cta-button {
            transition: all 0.3s ease-in-out;
        }
        .cta-button:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-gray-800">ShopSync</div>
            <div class="space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">Dashboard</a>
                    <a href="{{ route('shops.index') }}" class="text-gray-600 hover:text-gray-800">My Shops</a>
                    <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-gray-800">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-800 bg-transparent">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cta-button">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl font-bold mb-4">Manage Your Shop with Ease</h1>
            <p class="text-xl mb-8">Streamline inventory, track sales, manage customers, and generate insightful reports with ShopSync.</p>
            <a href="{{ route('register') }}" class="bg-yellow-400 text-gray-900 px-6 py-3 rounded-full font-semibold cta-button">Start Free Trial</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose ShopSync?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="text-xl font-semibold mb-2">Inventory Management</h3>
                    <p class="text-gray-600 mb-4">Track products, manage stock-ins, and perform daily stock checks effortlessly.</p>
                    @auth
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Products</a>
                            <a href="{{ route('stock-ins.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Stock-Ins</a>
                        </div>
                    @endauth
                </div>
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-xl font-semibold mb-2">Sales & Payments</h3>
                    <p class="text-gray-600 mb-4">Record sales, manage customer payments, and track dues with ease.</p>
                    @auth
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('sales.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Sales</a>
                            <a href="{{ route('customer-payments.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Payments</a>
                        </div>
                    @endauth
                </div>
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <h3 class="text-xl font-semibold mb-2">Powerful Reports</h3>
                    <p class="text-gray-600 mb-4">Generate stock, financial, and discrepancy reports to make informed decisions.</p>
                    @auth
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('reports.stock') }}" class="text-blue-600 hover:text-blue-800 font-medium">Stock</a>
                            <a href="{{ route('reports.financial') }}" class="text-blue-600 hover:text-blue-800 font-medium">Financial</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="bg-blue-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Transform Your Shop Management?</h2>
            <p class="text-lg mb-8">Join thousands of shop owners who trust ShopSync for seamless operations.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-6 py-3 rounded-full font-semibold cta-button">Go to Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-6 py-3 rounded-full font-semibold cta-button">Sign Up Now</a>
            @endauth
        </div>
    </section>
    
    @auth
    <!-- Quick Links Section for Authenticated Users -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold text-center mb-10">Quick Access to App Features</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Inventory Management Column -->
                <div class="feature-card bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4 text-blue-600 border-b border-gray-200 pb-2">Inventory</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>Products</a></li>
                        <li><a href="{{ route('stock-ins.index') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>Stock-Ins</a></li>
                        <li><a href="{{ route('daily-stock-checks.index') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>Daily Stock Checks</a></li>
                    </ul>
                </div>
                
                <!-- Sales & Payments Column -->
                <div class="feature-card bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4 text-blue-600 border-b border-gray-200 pb-2">Sales & Payments</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('sales.index') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>Sales</a></li>
                        <li><a href="{{ route('customer-payments.index') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>Customer Payments</a></li>
                        <li><a href="{{ route('sales.create') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>New Sale</a></li>
                    </ul>
                </div>
                
                <!-- Financial Management Column -->
                <div class="feature-card bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4 text-blue-600 border-b border-gray-200 pb-2">Financial</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('financial-entries.index') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>Financial Entries</a></li>
                        <li><a href="{{ route('financial-entries.create') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>New Entry</a></li>
                    </ul>
                </div>
                
                <!-- Reports Column -->
                <div class="feature-card bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4 text-blue-600 border-b border-gray-200 pb-2">Reports</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('reports.stock') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>Stock Report</a></li>
                        <li><a href="{{ route('reports.financial') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>Financial Report</a></li>
                        <li><a href="{{ route('reports.discrepancy') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>Discrepancy Report</a></li>
                        <li><a href="{{ route('reports.customer-dues') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>Customer Dues</a></li>
                        <li><a href="{{ route('reports.bag-weights') }}" class="text-gray-700 hover:text-blue-600 flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>Bag Weights</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    @endauth

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <h3 class="text-xl font-bold mb-2">ShopSync</h3>
                    <p class="text-gray-400 text-sm">The ultimate shop management solution</p>
                </div>
                
                <div class="flex flex-wrap justify-center gap-x-8 gap-y-4 mb-6 md:mb-0">
                    <div>
                        <h4 class="font-semibold mb-2 text-sm">Main Menu</h4>
                        <ul class="text-gray-400 text-sm space-y-1">
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white">Dashboard</a></li>
                            <li><a href="{{ route('shops.index') }}" class="hover:text-white">Shops</a></li>
                            <li><a href="{{ route('products.index') }}" class="hover:text-white">Products</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-2 text-sm">Resources</h4>
                        <ul class="text-gray-400 text-sm space-y-1">
                            <li><a href="{{ route('reports.stock') }}" class="hover:text-white">Reports</a></li>
                            <li><a href="#" class="hover:text-white">Documentation</a></li>
                            <li><a href="#" class="hover:text-white">Support</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-6 pt-6 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} ShopSync. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>