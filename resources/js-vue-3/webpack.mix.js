/* eslint-disable no-undef */
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

module.exports = {
	resolve: {
		extensions: ['.tsx', '.ts', '.js', '.vue'],
	},
	rules: [
		{
			test: [
				/\.s(c|a)ss$/,
				/\.vue$/,
			],
		},
	],
};

mix
  .setPublicPath('../../public')
  .js('./app.js', 'js/app-vue-3.min.js')
	.sass('../sass/app.scss', 'css').vue();