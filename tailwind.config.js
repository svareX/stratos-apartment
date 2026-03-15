import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                'purple': '#4B2EA2',
                'purpleMid': '#6B47C8',
                'purplePale': '#F0ECFF',
                'purpleGhost': '#F8F6FF',

                'teal': '#00C9A7',
                'tealD': '#00A88C',
                'tealL': '#E0FAF5',

                'red': '#E53935',

                'navy': '#1A0A3B',
                'text': '#1C1530',
                'muted': '#7A7090',

                'border': '#E2DCF5',
                'white': '#FFFFFF',
                'cream': '#FDFCFF',
                'gray': '#F5F3FA',
            }
        },
    },

    plugins: [forms, typography],
};
