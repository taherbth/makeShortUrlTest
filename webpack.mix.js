const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .scripts(['public/assets/js/select2.min.js',
            'public/assets/js/script.js'
            ], 'public/js/script.js')
    .styles(['resources/css/app.css',
            'public/assets/css/select2.min.css'
            ], 'public/css/style.css')
    .sourceMaps();
