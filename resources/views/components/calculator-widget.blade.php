<!-- Floating Calculator Widget -->
<div id="calculator-widget" class="fixed bottom-4 right-4" style="z-index: 9999;">
    <!-- Calculator Toggle Button -->
    <button id="calculator-toggle" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all duration-300 hover:scale-110" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-calculator text-xl"></i>
    </button>ating Calculator Widget -->
<div id="calculator-widget" class="fixed bottom-4 right-4 z-50">
    <!-- Calculator Toggle Button -->
    <button id="calculator-toggle" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 shadow-lg transition-all duration-300 hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
    </button>

    <!-- Calculator Popup -->
    <div id="calculator-popup" class="hidden absolute bottom-20 right-0 bg-white rounded-lg shadow-xl border p-4" style="width: 280px; z-index: 10000;">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-lg font-semibold text-gray-800">Calculator</h3>
            <button id="calculator-close" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Calculator Display -->
        <div class="mb-3">
            <input type="text" id="calculator-display" class="w-full p-3 text-right text-xl bg-gray-100 border rounded-md" readonly value="0">
        </div>

        <!-- Calculator Buttons -->
        <div class="grid grid-cols-4 gap-2">
            <!-- Row 1 -->
            <button class="calculator-btn bg-red-500 hover:bg-red-600 text-white p-3 rounded" onclick="clearCalculator()">C</button>
            <button class="calculator-btn bg-yellow-500 hover:bg-yellow-600 text-white p-3 rounded" onclick="deleteLast()">⌫</button>
            <button class="calculator-btn bg-blue-500 hover:bg-blue-600 text-white p-3 rounded" onclick="appendToDisplay('/')">/</button>
            <button class="calculator-btn bg-blue-500 hover:bg-blue-600 text-white p-3 rounded" onclick="appendToDisplay('*')">×</button>

            <!-- Row 2 -->
            <button class="calculator-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded" onclick="appendToDisplay('7')">7</button>
            <button class="calculator-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded" onclick="appendToDisplay('8')">8</button>
            <button class="calculator-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded" onclick="appendToDisplay('9')">9</button>
            <button class="calculator-btn bg-blue-500 hover:bg-blue-600 text-white p-3 rounded" onclick="appendToDisplay('-')">-</button>

            <!-- Row 3 -->
            <button class="calculator-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded" onclick="appendToDisplay('4')">4</button>
            <button class="calculator-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded" onclick="appendToDisplay('5')">5</button>
            <button class="calculator-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded" onclick="appendToDisplay('6')">6</button>
            <button class="calculator-btn bg-blue-500 hover:bg-blue-600 text-white p-3 rounded" onclick="appendToDisplay('+')">+</button>

            <!-- Row 4 -->
            <button class="calculator-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded" onclick="appendToDisplay('1')">1</button>
            <button class="calculator-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded" onclick="appendToDisplay('2')">2</button>
            <button class="calculator-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded" onclick="appendToDisplay('3')">3</button>
            <button class="calculator-btn bg-green-500 hover:bg-green-600 text-white p-3 rounded row-span-2" onclick="calculateResult()" rowspan="2">=</button>

            <!-- Row 5 -->
            <button class="calculator-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded col-span-2" onclick="appendToDisplay('0')">0</button>
            <button class="calculator-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded" onclick="appendToDisplay('.')">.</button>
        </div>
    </div>
</div>

<style>
    #calculator-widget {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
        z-index: 99999 !important;
    }
    
    #calculator-toggle {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
        border: none !important;
    }
    
    .calculator-btn {
        transition: all 0.2s ease;
        font-weight: 600;
        min-height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .calculator-btn:active {
        transform: scale(0.95);
    }
    
    #calculator-popup {
        animation: slideUp 0.3s ease-out;
        z-index: 99999 !important;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    let calculatorDisplay = document.getElementById('calculator-display');
    let currentInput = '0';
    let shouldResetDisplay = false;

    // Toggle calculator popup
    document.getElementById('calculator-toggle').addEventListener('click', function() {
        const popup = document.getElementById('calculator-popup');
        popup.classList.toggle('hidden');
    });

    // Close calculator
    document.getElementById('calculator-close').addEventListener('click', function() {
        document.getElementById('calculator-popup').classList.add('hidden');
    });

    // Close calculator when clicking outside
    document.addEventListener('click', function(event) {
        const widget = document.getElementById('calculator-widget');
        const popup = document.getElementById('calculator-popup');
        
        if (!widget.contains(event.target)) {
            popup.classList.add('hidden');
        }
    });

    // Calculator functions
    function updateDisplay() {
        calculatorDisplay.value = currentInput;
    }

    function appendToDisplay(value) {
        if (shouldResetDisplay) {
            currentInput = '0';
            shouldResetDisplay = false;
        }

        if (currentInput === '0' && value !== '.') {
            currentInput = value;
        } else {
            currentInput += value;
        }
        updateDisplay();
    }

    function clearCalculator() {
        currentInput = '0';
        shouldResetDisplay = false;
        updateDisplay();
    }

    function deleteLast() {
        if (currentInput.length > 1) {
            currentInput = currentInput.slice(0, -1);
        } else {
            currentInput = '0';
        }
        updateDisplay();
    }

    function calculateResult() {
        try {
            // Replace × with * for evaluation
            const expression = currentInput.replace(/×/g, '*');
            
            // Basic security check - only allow numbers, operators, and decimal points
            if (!/^[0-9+\-*/.() ]+$/.test(expression)) {
                throw new Error('Invalid expression');
            }
            
            const result = eval(expression);
            
            if (isNaN(result) || !isFinite(result)) {
                throw new Error('Invalid calculation');
            }
            
            currentInput = result.toString();
            shouldResetDisplay = true;
            updateDisplay();
        } catch (error) {
            currentInput = 'Error';
            shouldResetDisplay = true;
            updateDisplay();
            
            // Reset to 0 after a short delay
            setTimeout(() => {
                currentInput = '0';
                shouldResetDisplay = false;
                updateDisplay();
            }, 1500);
        }
    }

    // Keyboard support
    document.addEventListener('keydown', function(event) {
        const popup = document.getElementById('calculator-popup');
        
        // Only handle keyboard input if calculator is open
        if (popup.classList.contains('hidden')) {
            return;
        }
        
        const key = event.key;
        
        // Prevent default behavior for calculator keys
        if ('0123456789+-*/.='.includes(key) || key === 'Enter' || key === 'Backspace' || key === 'Escape') {
            event.preventDefault();
        }
        
        if ('0123456789'.includes(key)) {
            appendToDisplay(key);
        } else if ('+-*/'.includes(key)) {
            appendToDisplay(key === '*' ? '×' : key);
        } else if (key === '.') {
            appendToDisplay('.');
        } else if (key === 'Enter' || key === '=') {
            calculateResult();
        } else if (key === 'Backspace') {
            deleteLast();
        } else if (key === 'Escape') {
            clearCalculator();
        }
    });
</script>
