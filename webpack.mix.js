const mix = require('laravel-mix');

// mix.postCss("resources/css/app.css", "public/assets", [
//     require("tailwindcss"),
// ]);
require('laravel-mix-tailwind');

mix.sass('resources/sass/app-create.scss', 'public/assets')
    .sass('resources/sass/app-admin.scss', 'public/assets')
    .sass('resources/sass/app-lite.scss', 'public/assets')
    .postCss('resources/css/app.css', 'public/assets', [
        require('tailwindcss'),
    ])
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

// mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
//     require('postcss-import'),
//     require('tailwindcss'),
//     require('autoprefixer'),
// ]);
