import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gray: {
                    750: '#2d3748',
                },
                primary: {
                    50: '#f0f9f6',
                    100: '#dcf0ea',
                    200: '#bae1d4',
                    300: '#87c9b3',
                    400: '#4fad8d',
                    500: '#1c5247',
                    600: '#17463c',
                    700: '#143a32',
                    800: '#122f29',
                    900: '#0f2621',
                },
            },
        },
    },

    plugins: [forms],
};
