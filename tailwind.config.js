import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                'gold': {
                    '50': '#ffffe7',
                    '100': '#feffc1',
                    '200': '#fffd86',
                    '300': '#fff441',
                    '400': '#ffe60d',
                    '500': '#ffd700',
                    '600': '#d19e00',
                    '700': '#a67102',
                    '800': '#89580a',
                    '900': '#74480f',
                    '950': '#442604',
                },
                'silver': {
                    '50': '#f7f7f7',
                    '100': '#ededed',
                    '200': '#dfdfdf',
                    '300': '#d3d3d3',
                    '400': '#adadad',
                    '500': '#999999',
                    '600': '#888888',
                    '700': '#7b7b7b',
                    '800': '#676767',
                    '900': '#545454',
                    '950': '#363636',
                },
                'bronze': {
                    '50': '#fcf7ee',
                    '100': '#f5e8d0',
                    '200': '#e9d09e',
                    '300': '#deb46b',
                    '400': '#d69c49',
                    '500': '#cd7f32',
                    '600': '#b5622a',
                    '700': '#974826',
                    '800': '#7c3924',
                    '900': '#663121',
                    '950': '#3a170e',
                }
            }
        },
    },

    plugins: [forms],
};
