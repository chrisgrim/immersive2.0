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

require('laravel-mix-merge-manifest');

mix.js('resources/js/app.js', 'public/assets')
    .extract(['vue','leaflet','vue-cookies'])
    .mergeManifest()
    .webpackConfig({
    output: {
        chunkFilename: '[name].js?id=[chunkhash]',
    }
});

if (mix.inProduction()) {
    mix.version();
}



