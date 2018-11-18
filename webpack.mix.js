/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

const mix = require('laravel-mix')

mix.js('resources/js/app.js', 'public/js').sass(
    'resources/sass/app.scss',
    'public/css'
)

/*
 |--------------------------------------------------------------------------
 | Plugins
 |--------------------------------------------------------------------------
 */

const LiveReloadPlugin = require('webpack-livereload-plugin')

mix.webpackConfig({
    plugins: [new LiveReloadPlugin()],
})
