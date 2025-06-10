<!-- Simple Test Calculator -->
<div class="calc-toggle" onclick="toggleSimpleCalc()" style="position: fixed !important; bottom: 20px !important; right: 20px !important; z-index: 9999999 !important; background: red !important; color: white !important; width: 60px !important; height: 60px !important; border-radius: 50% !important; display: flex !important; align-items: center !important; justify-content: center !important; cursor: pointer !important;">
    <i class="fas fa-calculator" style="font-size: 20px !important;"></i>
</div>

<div id="simple-calc" class="calc-container">
    <div class="calc-display-container">
        <input type="text" id="calc-display" value="0" readonly aria-label="Calculator display">
    </div>
    
    <div class="calc-buttons">
        <button onclick="clearCalc()" class="calc-btn clear-btn" aria-label="Clear">C</button>
        <button onclick="deleteLast()" class="calc-btn delete-btn" aria-label="Delete">⌫</button>
        <button onclick="appendCalc('/')" class="calc-btn operator-btn" aria-label="Divide">÷</button>
        <button onclick="appendCalc('*')" class="calc-btn operator-btn" aria-label="Multiply">×</button>
        
        <button onclick="appendCalc('7')" class="calc-btn number-btn" aria-label="Seven">7</button>
        <button onclick="appendCalc('8')" class="calc-btn number-btn" aria-label="Eight">8</button>
        <button onclick="appendCalc('9')" class="calc-btn number-btn" aria-label="Nine">9</button>
        <button onclick="appendCalc('-')" class="calc-btn operator-btn" aria-label="Subtract">-</button>
        
        <button onclick="appendCalc('4')" class="calc-btn number-btn" aria-label="Four">4</button>
        <button onclick="appendCalc('5')" class="calc-btn number-btn" aria-label="Five">5</button>
        <button onclick="appendCalc('6')" class="calc-btn number-btn" aria-label="Six">6</button>
        <button onclick="appendCalc('+')" class="calc-btn operator-btn" aria-label="Add">+</button>
        
        <button onclick="appendCalc('1')" class="calc-btn number-btn" aria-label="One">1</button>
        <button onclick="appendCalc('2')" class="calc-btn number-btn" aria-label="Two">2</button>
        <button onclick="appendCalc('3')" class="calc-btn number-btn" aria-label="Three">3</button>
        <button onclick="calculateResult()" class="calc-btn equals-btn" aria-label="Calculate">=</button>
        
        <button onclick="appendCalc('0')" class="calc-btn number-btn zero-btn" aria-label="Zero">0</button>
        <button onclick="appendCalc('.')" class="calc-btn number-btn" aria-label="Decimal">.</button>
    </div>
</div>

<style>
/* Calculator Toggle Button */
.calc-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 999999;
    background: #3B82F6;
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    transition: transform 0.2s;
}
.calc-toggle:hover {
    transform: scale(1.1);
}
.calc-toggle i {
    font-size: 20px;
}

/* Calculator Container */
.calc-container {
    display: none;
    position: fixed;
    bottom: 90px;
    right: 20px;
    z-index: 999999;
    background: white;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    width: min(90vw, 280px);
    max-width: 300px;
}

/* Display */
.calc-display-container {
    margin-bottom: 12px;
}
#calc-display {
    width: 100%;
    padding: 10px;
    text-align: right;
    font-size: clamp(16px, 5vw, 20px);
    border: 1px solid #ddd;
    border-radius: 5px;
    background: #f9f9f9;
    color: #333;
}

/* Button Grid */
.calc-buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
}
.calc-btn {
    padding: clamp(10px, 3vw, 12px);
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: clamp(14px, 4vw, 16px);
    transition: background 0.2s, transform 0.1s;
}
.calc-btn:hover {
    filter: brightness(90%);
}
.calc-btn:active {
    transform: scale(0.95);
}
.number-btn { background: #6B7280; color: white; }
.operator-btn { background: #3B82F6; color: white; }
.clear-btn { background: #EF4444; color: white; }
.delete-btn { background: #F59E0B; color: white; }
.equals-btn { background: #10B981; color: white; grid-row: span 2; }
.zero-btn { grid-column: span 2; }

/* Mobile Adjustments */
@media (max-width: 600px) {
    .calc-toggle {
        width: 50px;
        height: 50px;
    }
    .calc-container {
        bottom: 80px;
        right: 10px;
        padding: 10px;
        width: min(95vw, 260px);
    }
    .calc-btn {
        padding: 8px;
        font-size: 14px;
    }
}
</style>

<script>
let calcDisplay = document.getElementById('calc-display');
let currentCalcInput = '0';
let shouldResetCalcDisplay = false;

function toggleSimpleCalc() {
    const calc = document.getElementById('simple-calc');
    calc.style.display = calc.style.display === 'none' ? 'block' : 'none';
}

function updateCalcDisplay() {
    calcDisplay.value = currentCalcInput;
}

function appendCalc(value) {
    if (shouldResetCalcDisplay) {
        currentCalcInput = '0';
        shouldResetCalcDisplay = false;
    }

    // Prevent multiple decimals in a number
    if (value === '.' && currentCalcInput.split(/[+\-*/]/).pop().includes('.')) {
        return;
    }

    // Prevent leading zeros unless followed by a decimal
    if (currentCalcInput === '0' && value === '0') {
        return;
    }
    if (currentCalcInput === '0' && value !== '.') {
        currentCalcInput = value;
    } else {
        currentCalcInput += value;
    }
    updateCalcDisplay();
}

function clearCalc() {
    currentCalcInput = '0';
    shouldResetCalcDisplay = false;
    updateCalcDisplay();
}

function deleteLast() {
    if (currentCalcInput.length > 1) {
        currentCalcInput = currentCalcInput.slice(0, -1);
    } else {
        currentCalcInput = '0';
    }
    updateCalcDisplay();
}

function calculateResult() {
    try {
        const expression = currentCalcInput.replace(/×/g, '*').replace(/÷/g, '/');
        
        if (!/^[0-9+\-*/.() ]+$/.test(expression)) {
            throw new Error('Invalid expression');
        }
        
        const result = eval(expression);
        
        if (isNaN(result) || !isFinite(result)) {
            throw new Error('Invalid calculation');
        }
        
        currentCalcInput = Number(result.toFixed(8)).toString();
        shouldResetCalcDisplay = true;
        updateCalcDisplay();
    } catch (error) {
        currentCalcInput = 'Error';
        shouldResetCalcDisplay = true;
        updateCalcDisplay();
        
        setTimeout(() => {
            currentCalcInput = '0';
            shouldResetCalcDisplay = false;
            updateCalcDisplay();
        }, 1500);
    }
}

// Keyboard Support
document.addEventListener('keydown', (event) => {
    const calc = document.getElementById('simple-calc');
    if (calc.style.display !== 'block') return;

    const key = event.key;
    if (/[0-9]/.test(key)) appendCalc(key);
    else if (key === '.') appendCalc('.');
    else if (key === '+') appendCalc('+');
    else if (key === '-') appendCalc('-');
    else if (key === '*') appendCalc('*');
    else if (key === '/') appendCalc('/');
    else if (key === 'Enter') calculateResult();
    else if (key === 'Escape') clearCalc();
    else if (key === 'Backspace') deleteLast();
});

// Close calculator when clicking outside
document.addEventListener('click', function(event) {
    const calc = document.getElementById('simple-calc');
    const button = event.target.closest('.calc-toggle');
    
    if (!calc.contains(event.target) && !button) {
        calc.style.display = 'none';
    }
});
</script>