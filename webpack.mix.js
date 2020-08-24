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
            indentedSyntax: true // optional
          },
          // Requires sass-loader@^8.0.0
          options: {
            implementation: require('sass'),
            sassOptions: {
              fiber: require('fibers'),
              indentedSyntax: true // optional
            },
          },
        },
      ],
    },
  ],
}

mix.js('resources/js/modules/board/app.js', 'public/js/app.min.js')
	.sass('resources/sass/app.scss', 'public/css');
