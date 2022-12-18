let mix = require('laravel-mix');
require('laravel-mix-tailwind');


mix.sass('resources/sass/app-create.scss', 'public/css')
    .sass('resources/sass/app-lite.scss', 'public/css')
    .postCss('resources/css/app.css', 'public/css')
    .js('resources/js/app.js', 'public/js')
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

