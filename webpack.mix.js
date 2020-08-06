const mix = require('laravel-mix');
// const autoprefixer = require('autoprefixer');
//
//
// //CONFIG
//
// mix.options({
// 	postCss: [
// 		autoprefixer({
// 			overrideBrowserslist: ['> 1%', 'last 2 versions'],
// 		}),
// 	],
// });


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

mix.js('resources/js/modules/home/app.js', 'public/js/app.min.js')
	.sass('resources/sass/app.scss', 'public/css');
