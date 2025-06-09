import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', 'Georgia', 'serif'],
            },
            colors: {
                primary: {
                    50: '#f8f6f3',
                    100: '#f1ede7',
                    200: '#e3dbd1',
                    300: '#d0c2b3',
                    400: '#b8a091',
                    500: '#a88776',
                    600: '#9b7868',
                    700: '#816158',
                    800: '#69524a',
                    900: '#56443d',
                },
                accent: {
                    50: '#fdf7f0',
                    100: '#fbeee0',
                    200: '#f6ddc1',
                    300: '#efc496',
                    400: '#e6a469',
                    500: '#df8c47',
                    600: '#d1753c',
                    700: '#ae5d34',
                    800: '#8b4a32',
                    900: '#713e2d',
                },
                luxury: {
                    gold: '#d4af37',
                    cream: '#f8f6f3',
                    dark: '#2c2c2c',
                },
            },
            backgroundImage: {
                'gradient-luxury': 'linear-gradient(135deg, #56443d 0%, #816158 100%)',
                'gradient-accent': 'linear-gradient(135deg, #df8c47 0%, #d1753c 100%)',
            },
            boxShadow: {
                'luxury': '0 10px 30px -5px rgba(86, 68, 61, 0.3)',
                'luxury-lg': '0 20px 40px -10px rgba(86, 68, 61, 0.4)',
            },
        },
    },

    plugins: [forms],
};
