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
            },
            colors: {
                'coconuthusk': '#2A2323',
                'goldennut': '#F7C758',
                'coastalfern': '#92B598',
                'sandyshore': '#EEE4CA',
                'tamanuleaf' : '#1F3A25',
            },
        },
    },

    plugins: [forms],
};
