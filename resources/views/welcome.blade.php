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
        @media (max-width: 640px) {
            .discrepancy-section {
                padding: 1rem;
            }
            .discrepancy-section input {
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center flex-wrap">
            <div class="text-2xl font-bold text-gray-800">ShopSync</div>
            <div class="space-x-4 mt-4 sm:mt-0">
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cta-button">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4">Manage Your Shop with Ease</h1>
            <p class="text-lg sm:text-xl mb-8">Streamline inventory, track sales, manage customers, and generate insightful reports with ShopSync.</p>
            <a href="{{ route('register') }}" class="bg-yellow-400 text-gray-900 px-6 py-3 rounded-full font-semibold cta-button">Start Free Trial</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose ShopSync?</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="text-xl font-semibold mb-2">Inventory Management</h3>
                    <p class="text-gray-600 mb-4">Track products, manage stock-ins, and perform daily stock checks effortlessly.</p>
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">Try Now</a>
                </div>
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-xl font-semibold mb-2">Sales & Payments</h3>
                    <p class="text-gray-600 mb-4">Record sales, manage customer payments, and track dues with ease.</p>
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">Try Now</a>
                </div>
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <h3 class="text-xl font-semibold mb-2">Powerful Reports</h3>
                    <p class="text-gray-600 mb-4">Generate stock, financial, and discrepancy reports to make informed decisions.</p>
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">Try Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Discrepancy Calculator Section -->
    <section class="py-20 bg-gray-50 discrepancy-section">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-8">Try Our Discrepancy Calculator</h2>
            <div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1 text-sm">Product Name</label>
                    <input type="text" id="productName" class="w-full p-2 border rounded" placeholder="Enter product name">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1 text-sm">Recorded Quantity</label>
                    <input type="number" id="recordedQuantity" class="w-full p-2 border rounded" placeholder="Enter recorded quantity" min="0">
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Physical Count</h3>
                    <div id="rowContainer" class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:space-x-2 items-end row">
                            <div class="flex-1 mb-2 sm:mb-0">
                                <label class="block text-gray-700 mb-1 text-sm">Bags per Row</label>
                                <input type="number" class="bags-per-row w-full p-2 border rounded" placeholder="Bags per row" min="0">
                            </div>
                            <div class="flex-1 mb-2 sm:mb-0">
                                <label class="block text-gray-700 mb-1 text-sm">Number of Rows</label>
                                <input type="number" class="num-rows w-full p-2 border rounded" placeholder="Number of rows" min="0">
                            </div>
                            <button onclick="removeRow(this)" class="text-red-600 hover:text-red-800 mb-2">×</button>
                        </div>
                    </div>
                    <button onclick="addRow()" class="mt-2 text-blue-600 hover:text-blue-800 font-medium">Add Row</button>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700"><strong>Total Physical Count:</strong> <span id="totalPhysical">0</span></p>
                    <p class="text-gray-700"><strong>Discrepancy:</strong> <span id="discrepancy" class="font-semibold">0</span></p>
                </div>
                <button onclick="calculateDiscrepancy()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full mb-4">Calculate</button>
                <div class="text-center">
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">Sign Up for Full Features</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="bg-blue-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Transform Your Shop Management?</h2>
            <p class="text-lg mb-8">Join thousands of shop owners who trust ShopSync for seamless operations.</p>
            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-6 py-3 rounded-full font-semibold cta-button">Sign Up Now</a>
        </div>
    </section>

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
                            <li><a href="{{ route('register') }}" class="hover:text-white">Get Started</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-2 text-sm">Resources</h4>
                        <ul class="text-gray-400 text-sm space-y-1">
                            <li><a href="#" class="hover:text-white">Documentation</a></li>
                            <li><a href="#" class="hover:text-white">Support</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-6 pt-6 text-center text-sm text-gray-400">
                <p>© {{ date('Y') }} ShopSync. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function addRow() {
            const rowContainer = document.getElementById('rowContainer');
            const newRow = document.createElement('div');
            newRow.className = 'flex flex-col sm:flex-row sm:space-x-2 items-end row';
            newRow.innerHTML = `
                <div class="flex-1 mb-2 sm:mb-0">
                    <label class="block text-gray-700 mb-1 text-sm">Bags per Row</label>
                    <input type="number" class="bags-per-row w-full p-2 border rounded" placeholder="Bags per row" min="0">
                </div>
                <div class="flex-1 mb-2 sm:mb-0">
                    <label class="block text-gray-700 mb-1 text-sm">Number of Rows</label>
                    <input type="number" class="num-rows w-full p-2 border rounded" placeholder="Number of rows" min="0">
                </div>
                <button onclick="removeRow(this)" class="text-red-600 hover:text-red-800 mb-2">×</button>
            `;
            rowContainer.appendChild(newRow);
        }

        function removeRow(button) {
            if (document.querySelectorAll('.row').length > 1) {
                button.parentElement.remove();
                calculateDiscrepancy();
            }
        }

        function calculateDiscrepancy() {
            const recordedQuantity = parseInt(document.getElementById('recordedQuantity').value) || 0;
            const rows = document.querySelectorAll('.row');
            let totalPhysical = 0;

            rows.forEach(row => {
                const bagsPerRow = parseInt(row.querySelector('.bags-per-row').value) || 0;
                const numRows = parseInt(row.querySelector('.num-rows').value) || 0;
                totalPhysical += bagsPerRow * numRows;
            });

            const discrepancy = recordedQuantity - totalPhysical;

            document.getElementById('totalPhysical').textContent = totalPhysical;
            document.getElementById('discrepancy').textContent = discrepancy;
            document.getElementById('discrepancy').className = discrepancy < 0 ? 'font-semibold text-red-600' : discrepancy > 0 ? 'font-semibold text-green-600' : 'font-semibold';
        }

        // Add event listeners to recalculate on input change
        document.getElementById('rowContainer').addEventListener('input', calculateDiscrepancy);
        document.getElementById('recordedQuantity').addEventListener('input', calculateDiscrepancy);

        // Ensure calculator is visible on page load
        window.onload = function() {
            document.querySelector('.discrepancy-section').style.display = 'block';
        };
    </script>
</body>
</html>