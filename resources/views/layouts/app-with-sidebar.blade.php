<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- FontAwesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-primary-50">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-luxury">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content with Sidebar -->
            <div class="flex">
                <!-- Sidebar -->
                <aside class="hidden lg:flex flex-col w-64 bg-white border-r border-gray-200 shadow-sm min-h-screen">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">{{ $sidebarTitle ?? 'Navigation' }}</h2>
                        <p class="text-sm text-gray-500">{{ $sidebarSubtitle ?? '' }}</p>
                    </div>
                    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                        {{ $sidebar }}
                    </nav>
                </aside>

                <!-- Main Content -->
                <main class="flex-1 p-4">
                    {{ $slot }}
                </main>

                <!-- Mobile Sidebar Toggle -->
                <div x-data="{ open: false }" class="lg:hidden">
                    <button @click="open = !open" class="fixed bottom-4 right-4 bg-primary-600 text-white p-3 rounded-full shadow-lg">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Mobile Sidebar -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-full"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform translate-x-full"
                         class="fixed inset-y-0 right-0 w-64 bg-white shadow-2xl z-50">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-900">{{ $sidebarTitle ?? 'Navigation' }}</h2>
                            <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <nav class="p-4 space-y-2">
                            {{ $sidebar }}
                        </nav>
                    </div>

                    <!-- Backdrop -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="open = false"
                         class="fixed inset-0 bg-black bg-opacity-25 z-40">
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Enhanced scrollbar styling */
            ::-webkit-scrollbar {
                width: 4px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #555;
            }

            /* Enhanced transitions */
            .transition-all {
                transition-property: all;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                transition-duration: 300ms;
            }

            /* Enhanced shadows */
            .shadow-luxury {
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            }
        </style>
    </body>
</html>
