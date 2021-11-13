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

// mix.js('resources/js/app.js', 'public/js')
    // .sass('resources/sass/app.scss', 'public/css');

mix.combine(['resources/css/bootstrap.min.css', 'resources/css/app.css', 'resources/css/tree.css', 'resources/css/main.css'], 'public/css/all.css', true);
mix.minify('public/css/all.css');
mix.js('resources/js/queryloader2.min.js', 'public/js/')
mix.combine(['resources/js/jquery-3.5.1.min.js', 'resources/js/bootstrap.min.js', 'resources/js/bootstrap.min.js', 'resources/js/btnUp.js'], 'public/js/all.js', true);
mix.minify('public/js/all.js');
mix.version();
