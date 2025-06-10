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

            <!-- Page Content -->
            <main>
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
        </div>
    </body>
</html>
