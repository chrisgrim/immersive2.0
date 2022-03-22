let mix = require('laravel-mix');
require('laravel-mix-tailwind');

mix.sass('resources/sass/app-create.scss', 'public/assets')
    .sass('resources/sass/app-lite.scss', 'public/assets')
    .postCss('resources/css/app.css', 'public/assets')
    .js('resources/js/app.js', 'public/assets')
    .extract(['vue'])
    .webpackConfig({
        output: {
            chunkFilename: '[name].js?id=[chunkhash]',
        }
    })
    .tailwind()
    .vue();


if (mix.inProduction()) {
    mix.version();
}

