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

const options = {};
if (process.env.NODE_ENV === 'production') {
  options.terser = {
    terserOptions: {
      compress: {
        drop_console: true,
      },
    },
  };
}

mix.options(options)
  .js('resources/js/app.js', 'public/js')
  .react()
  .sass('resources/sass/app.scss', 'public/css')
  .copy('resources/images', 'public/images')
  .version();
