const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    // presets: [
    //     require('./vendor/wireui/wireui/tailwind.config.js')
    // ],
    content: [
        './resources/views/components/layouts/modal.blade.php',
        './resources/views/translation-organizer.blade.php'
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
            'primary': '#6EBAE7',
            'primary-dark': '#538CAD',
            'primary-light': '#F3F8FC',
        },
    },

    plugins: [
        require('@tailwindcss/forms')
    ],
};
