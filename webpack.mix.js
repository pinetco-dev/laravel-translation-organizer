let mix = require('laravel-mix');

mix.setPublicPath('resources');

mix.js('resources/js/app.js', 'resources/dist')
    .postCss("resources/css/app.css", "resources/dist", [
        require("tailwindcss"),
    ])
    .options({
        terser: {
            extractComments: false,
        },
    })
    .disableNotifications();


