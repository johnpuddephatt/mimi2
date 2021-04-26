const path = require('path');
const mix = require('laravel-mix');
require('laravel-mix-clean');


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

mix
  .js('resources/js/app.js', 'public/js').vue()
  .options({
    extractVueStyles: 'css/vue.css'
  })
  .setPublicPath('public')
  .version()
  .sourceMaps()
  .sass('resources/sass/app.scss', 'public/css')
  .browserSync({
   proxy: 'public.mimi.localhost',
   files: [
      'app/**/*.php',
      'resources/views/**/*.php',
      'public/js/**/*.js',
      'public/css/**/*.css'
   ]
  })
  .copy('resources/images/**','public/images')
  .webpackConfig(webpack => {
    return {
      output: {
        chunkFilename: 'js/[name].[chunkhash].js',
      },
      resolve: {
          alias: {
              '@': path.resolve('resources/js'),
          },
      },
    };
  })
  .clean({
    cleanOnceBeforeBuildPatterns: ['./js/*'],
  });
