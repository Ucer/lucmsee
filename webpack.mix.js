let mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.scripts([
    'resources/js/main.js',
    'resources/js/vendor/highlight/jquery.highlight.js',
], 'public/js/jquery.js');

if (mix.inProduction()) {
    mix.version();
}

