const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/sass/app-create.scss', 'public/assets')
    .sass('resources/sass/app-admin.scss', 'public/assets')
    .sass('resources/sass/app-lite.scss', 'public/assets')
    .sass('resources/sass/app.scss', 'public/assets')
    .js('resources/js/app.js', 'public/assets')
    .extract(['vue'])
    .webpackConfig({
        output: {
            chunkFilename: '[name].js?id=[chunkhash]',
        }
    })
    .vue();

if (mix.inProduction()) {
    mix.version();
}

// mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
//     require('postcss-import'),
//     require('tailwindcss'),
//     require('autoprefixer'),
// ]);
