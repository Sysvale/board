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

module.exports = {
	rules: [
		{
			test: /\.s(c|a)ss$/,
			use: [
				'vue-style-loader',
				'css-loader',
				{
					loader: 'sass-loader',
					// Requires sass-loader@^7.0.0
					options: {
						implementation: require('sass'),
						fiber: require('fibers'),
						indentedSyntax: true, // optional
					},
					// Requires sass-loader@^8.0.0
					options: {
						implementation: require('sass'),
						sassOptions: {
							fiber: require('fibers'),
							indentedSyntax: true, // optional
						},
					},
				},
			],
		},
	],
}

mix.babelConfig({
	plugins: ['@babel/plugin-syntax-dynamic-import'],
});

require('laravel-mix-transpile-node-modules');
mix.transpileNodeModules(['v-calendar']);

mix.js('resources/js/modules/board/app.js', 'public/js/app.min.js')
	.sass('resources/sass/app.scss', 'public/css')
	.sass('resources/sass/home.scss', 'public/css');

mix.version();
