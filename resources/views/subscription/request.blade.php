<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Request Premium Subscription') }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                    <i class="fas fa-user mr-1"></i>
                    mhdniyas
                </span>
                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                    <i class="fas fa-clock mr-1"></i>
                    June 11, 11:03 AM
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg transition-all duration-300 hover:shadow-xl">
                <div class="p-4 sm:p-6">
                    <!-- Enhanced Header Section -->
                    <div class="mb-8 text-center animate-fade-in">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-full mb-4 shadow-lg">
                            <i class="fas fa-crown text-3xl text-indigo-600"></i>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Upgrade to Premium</h1>
                        <p class="text-gray-600 text-sm sm:text-base max-w-2xl mx-auto">Transform your business with our comprehensive shop management system. Get access to all premium features and take your inventory management to the next level.</p>
                        
                        <!-- Request Info -->
                        <div class="mt-4 inline-flex items-center bg-indigo-50 px-4 py-2 rounded-full border border-indigo-200">
                            <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                            <span class="text-sm font-medium text-indigo-800">Your request will be reviewed within 24 hours</span>
                        </div>
                    </div>

                    <!-- Subscription Information -->
                    <div class="mb-8 animate-fade-in" style="animation-delay: 0.1s">
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-4 sm:p-6 rounded-lg border-l-4 border-indigo-400">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-indigo-600"></i>
                                How It Works
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                <div class="flex items-start">
                                    <div class="bg-indigo-100 p-2 rounded-full mr-3 flex-shrink-0">
                                        <span class="text-indigo-600 font-bold text-sm">1</span>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Submit Request</h4>
                                        <p class="text-gray-600">Complete the form below to request premium access</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-yellow-100 p-2 rounded-full mr-3 flex-shrink-0">
                                        <span class="text-yellow-600 font-bold text-sm">2</span>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Admin Review</h4>
                                        <p class="text-gray-600">Our team reviews your request within 24 hours</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-green-100 p-2 rounded-full mr-3 flex-shrink-0">
                                        <span class="text-green-600 font-bold text-sm">3</span>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Get Access</h4>
                                        <p class="text-gray-600">Instant access to all premium features upon approval</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Premium Benefits -->
                    <div class="mb-8 animate-fade-in" style="animation-delay: 0.2s">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 sm:p-6 rounded-lg border-l-4 border-green-400">
                            <h4 class="text-lg font-semibold text-green-900 mb-6 flex items-center">
                                <i class="fas fa-star mr-2"></i>
                                Premium Features & Benefits
                            </h4>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Core Features -->
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-rocket mr-2 text-green-600"></i>
                                        Core Features
                                    </h5>
                                    <ul class="space-y-3">
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mt-0.5 mr-3">
                                                <i class="fas fa-check text-green-600 text-xs"></i>
                                            </div>
                                            <span class="text-gray-700"><strong>Unlimited Products:</strong> Add and manage unlimited products across all categories</span>
                                        </li>
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mt-0.5 mr-3">
                                                <i class="fas fa-check text-green-600 text-xs"></i>
                                            </div>
                                            <span class="text-gray-700"><strong>Advanced Inventory:</strong> Real-time stock tracking, low stock alerts, and automated reorder points</span>
                                        </li>
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mt-0.5 mr-3">
                                                <i class="fas fa-check text-green-600 text-xs"></i>
                                            </div>
                                            <span class="text-gray-700"><strong>Financial Tracking:</strong> Comprehensive financial reports, profit/loss statements, and expense tracking</span>
                                        </li>
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0 w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mt-0.5 mr-3">
                                                <i class="fas fa-check text-green-600 text-xs"></i>
                                            </div>
                                            <span class="text-gray-700"><strong>Customer Management:</strong> Complete customer database with purchase history and payment tracking</span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Advanced Features -->
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                                        Advanced Analytics
                                    </h5>
                                    <ul class="space-y-3">
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0 w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mt-0.5 mr-3">
                                                <i class="fas fa-check text-blue-600 text-xs"></i>
                                            </div>
                                            <span class="text-gray-700"><strong>Sales Analytics:</strong> Detailed sales reports with trends and performance insights</span>
                                        </li>
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0 w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mt-0.5 mr-3">
                                                <i class="fas fa-check text-blue-600 text-xs"></i>
                                            </div>
                                            <span class="text-gray-700"><strong>Custom Reports:</strong> Generate custom reports for any date range or category</span>
                                        </li>
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0 w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mt-0.5 mr-3">
                                                <i class="fas fa-check text-blue-600 text-xs"></i>
                                            </div>
                                            <span class="text-gray-700"><strong>Data Export:</strong> Export all your data in various formats (CSV, PDF, Excel)</span>
                                        </li>
                                        <li class="flex items-start">
                                            <div class="flex-shrink-0 w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mt-0.5 mr-3">
                                                <i class="fas fa-check text-blue-600 text-xs"></i>
                                            </div>
                                            <span class="text-gray-700"><strong>Priority Support:</strong> 24/7 priority customer support with dedicated assistance</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="mb-8 animate-fade-in" style="animation-delay: 0.3s">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 sm:p-6 rounded-lg border-l-4 border-purple-400">
                            <h4 class="text-lg font-semibold text-purple-900 mb-4 flex items-center">
                                <i class="fas fa-tag mr-2"></i>
                                Premium Access
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-2">What You Get:</h5>
                                    <ul class="text-sm text-gray-700 space-y-1">
                                        <li>• Full access to all features</li>
                                        <li>• Unlimited product management</li>
                                        <li>• Advanced reporting & analytics</li>
                                        <li>• Priority customer support</li>
                                        <li>• Regular feature updates</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900 mb-2">Perfect For:</h5>
                                    <ul class="text-sm text-gray-700 space-y-1">
                                        <li>• Small to medium businesses</li>
                                        <li>• Retail shops & stores</li>
                                        <li>• Wholesale distributors</li>
                                        <li>• E-commerce businesses</li>
                                        <li>• Multi-location stores</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Request Form -->
                    <div class="border-t border-gray-200 pt-6 animate-fade-in" style="animation-delay: 0.4s">
                        <div class="bg-white p-6 rounded-lg border-2 border-indigo-200 shadow-sm">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-paper-plane mr-2 text-indigo-600"></i>
                                Submit Your Request
                            </h4>
                            
                            <form method="POST" action="{{ route('subscription.submit') }}" class="space-y-6">
                                @csrf
                                
                                <!-- User Information Display -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h5 class="font-medium text-gray-900 mb-3">Request Details</h5>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <label class="block text-gray-600">Requesting User:</label>
                                            <p class="font-medium text-gray-900">mhdniyas</p>
                                        </div>
                                        <div>
                                            <label class="block text-gray-600">Request Date:</label>
                                            <p class="font-medium text-gray-900">June 11, 2025 at 11:03 AM</p>
                                        </div>
                                        <div>
                                            <label class="block text-gray-600">Account Type:</label>
                                            <p class="font-medium text-gray-900">Standard → Premium Upgrade</p>
                                        </div>
                                        <div>
                                            <label class="block text-gray-600">Processing Time:</label>
                                            <p class="font-medium text-green-600">Usually within 24 hours</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                    <div class="flex items-start">
                                        <input id="terms" name="terms" type="checkbox" required 
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded mt-1 mr-3">
                                        <div class="flex-1">
                                            <label for="terms" class="block text-sm text-gray-900">
                                                <span class="font-medium">I agree to the Terms and Conditions</span>
                                            </label>
                                            <p class="text-xs text-gray-600 mt-1">
                                                By checking this box, you agree to our 
                                                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 underline">Terms of Service</a> 
                                                and 
                                                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 underline">Privacy Policy</a>.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-200">
                                    <a href="{{ route('subscription.status') }}" class="w-full sm:w-auto px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 transform hover:scale-105 text-center">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Back to Status
                                    </a>
                                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                                        <i class="fas fa-crown mr-2"></i>
                                        Submit Premium Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="mt-8 bg-gray-50 rounded-lg p-6 animate-fade-in" style="animation-delay: 0.5s">
                        <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-question-circle mr-2 text-gray-600"></i>
                            Frequently Asked Questions
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <div>
                                <h5 class="font-medium text-gray-900 mb-2">How long does approval take?</h5>
                                <p class="text-gray-600">Most requests are processed within 24 hours during business days.</p>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900 mb-2">Can I cancel anytime?</h5>
                                <p class="text-gray-600">Yes, you can cancel your subscription at any time from your account settings.</p>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900 mb-2">What happens to my data?</h5>
                                <p class="text-gray-600">All your existing data remains safe and you'll retain access even if you cancel.</p>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900 mb-2">Need help deciding?</h5>
                                <p class="text-gray-600">Contact our support team for a personalized consultation about premium features.</p>
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

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .text-3xl {
                font-size: 2rem;
            }
            
            .text-2xl {
                font-size: 1.5rem;
            }
        }

        /* Enhanced form styling */
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Checkbox styling enhancement */
        input[type="checkbox"]:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        /* Enhanced shadow styling */
        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form enhancement
            const form = document.querySelector('form');
            const submitButton = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function(e) {
                // Show loading state
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting Request...';
                submitButton.disabled = true;
            });

            // Terms checkbox validation
            const termsCheckbox = document.getElementById('terms');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            function validateForm() {
                if (termsCheckbox.checked) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
            
            termsCheckbox.addEventListener('change', validateForm);
            
            // Initial validation
            validateForm();
        });
    </script>
</x-app-layout>