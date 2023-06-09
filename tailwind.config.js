const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/views/*.blade.php',
        './resources/views/**/**/*.blade.php',
        './resources/views/**/**/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito Sans', ...defaultTheme.fontFamily.sans],
            },
            fluidTypography: {
                remSize: 16,
                minScreenSize: 320,
                maxScreenSize: 1200,
                minTypeScale: 1.125,
                maxTypeScale: 1.250,
                lineHeight: 1.5,
            }
        },

        colors: {
            transparent: 'transparent',
            white: '#ffffff',
            black: '#000000',
            'primary': '#119CED',
            'primary-dark': '#005183',
            'primary-light': '#F3F8FC',
            'gray': {
                100: '#F5F5F5',
                300: '#E6E8EA',
                400: '#9ca3af',
                500: '#6b7280',
                700: '#4b5563',
                900: '#111827',
            },
            'red': {
                300: '#FEE6E6',
                500: '#D44B4B',
                700: '#C60707',
            },
            'green': {
                300: '#E9F9F0',
                500: '#27AE60',
                700: '#1A944B',
            },
            'blue': {
                50: '#eff6ff',
                100: '#dbeafe',
                200: '#bfdbfe',
                300: '#93c5fd',
                400: '#60a5fa',
                500: '#3b82f6',
                600: '#2563eb',
                700: '#1d4ed8',
                800: '#1e40af',
                900: '#1e3a8a',
                950: '#172554',
            },
            'yellow': {
                300: '#FDF9EB',
                500: '#EEC637',
                700: '#BE9E2C',
            },
            'orange': {
                500: '#E9910B',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms')
    ],
};
