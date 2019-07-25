let mix = require('laravel-mix');
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');

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

mix.js(['resources/assets/js/app.js'], 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css', { implementation: require('node-sass') })
   .version();


mix.webpackConfig(webpack => {
   return {
      plugins: [
        // Strip all locales except “en”, “es-us” and “ru”
        // (“en” is built into Moment and can’t be removed)
        new MomentLocalesPlugin({
            localesToKeep: ['es-us', 'en-au'],
        }),
      ]
   };
});