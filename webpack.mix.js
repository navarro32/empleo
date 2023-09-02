const mix = require('laravel-mix');
/**
 * Import laravel-mix-graphql
 */
require("@pp-spaces/laravel-mix-graphql");
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
    .js('resources/js/empresas.js', 'public/js/empresas')
    .js('resources/js/usuarios.js', 'public/js/usuarios')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/empresas.scss', 'public/css/empresas')
    .sass('resources/sass/usuarios.scss', 'public/css/usuarios');

mix.copy('node_modules/sweetalert/dist', 'public/sweetalert/')


