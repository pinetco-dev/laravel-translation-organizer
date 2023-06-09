let mix = require('laravel-mix');

mix.setPublicPath('resources');

const tailwindcss = require('tailwindcss');

mix.js('resources/js/app.js', 'resources/dist')
    .postCss("resources/css/app.css", "resources/dist/translation.css",  [
        tailwindcss('./tailwind.config.js'),
        require('tailwindcss'),
    ])
    .options({
        terser: {
            extractComments: false,
        },
    })
    .disableNotifications();


