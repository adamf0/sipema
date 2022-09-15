const mix = require('laravel-mix');
mix.options({
    postCss: [require('autoprefixer')],
    clearConsole: true,
});
mix.disableSuccessNotifications();

mix.browserSync({
    proxy: "127.0.0.1:8000",
    open: false, //disable auto opening browser
    notify: false, //disable notification on top left of the browser
});

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
    .sass('resources/sass/app.scss', 'public/css');
