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
                sans: ['Montserrat', ...defaultTheme.fontFamily.sans],
                serif: ['"Cormorant Garamond"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                helena: {
                    verde: '#6B7451',
                    'verde-oscuro': '#565E40',
                    crema: '#F6ECE4',
                    arena: '#EFE5DB',
                    rosa: '#D9A69C',
                    oscuro: '#2E2C2B',
                },
            },
        },
    },

    plugins: [forms],
};
