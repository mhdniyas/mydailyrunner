// Fix for the sidebar dropdown and calculator issues in admin.blade.php

// Get the current file content
const fs = require('fs');
const path = require('path');

// Path to the file
const filePath = '/var/www/ard5/resources/views/layouts/admin.blade.php';

// Read the file
let content = fs.readFileSync(filePath, 'utf8');

// 1. Fix for dropdown functionality
// First, look for the DOMContentLoaded section in the mobile navigation script
const domLoadedPattern = /document\.addEventListener\('DOMContentLoaded',\s*function\(\)\s*\{/;
const dropdownCode = `document.addEventListener('DOMContentLoaded', function() {
                // Initialize dropdown functionality
                const dropdownButtons = document.querySelectorAll('.dropdown-button');
                
                dropdownButtons.forEach(button => {
                    button.addEventListener('click', function() {
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
                });`;

// Replace the beginning of the script with our dropdown code
content = content.replace(domLoadedPattern, dropdownCode);

// 2. Fix for calculator functionality
// Find and replace the calculator initialization code
const calcInitPattern = /calcInput\s*=\s*document\.getElementById\('calc-input'\);[\s\S]*?showCalculator\(\);[\s\S]*?hideCalculator\(\);[\s\S]*?\}\);/;
const calcFixCode = `calcInput = document.getElementById('calc-input');
                
                // Make sure the calculator button doesn't move
                calcButton.style.position = 'fixed';
                calcButton.style.bottom = '20px';
                calcButton.style.right = '20px';
                calcButton.style.zIndex = '9999999';
                
                // Show calculator when button is clicked
                calcButton.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent event from bubbling up
                    if (calcPopup.style.display === 'none' || calcPopup.style.display === '') {
                        showCalculator();
                    } else {
                        hideCalculator();
                    }
                });`;

// Replace the calculator initialization code
content = content.replace(calcInitPattern, calcFixCode);

// Write the updated content back to the file
fs.writeFileSync(filePath, content, 'utf8');

console.log('Fixed both dropdown and calculator issues in admin.blade.php');
