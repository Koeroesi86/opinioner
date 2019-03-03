const mix = require('laravel-mix');

if (!mix.inProduction()) {
  mix
    .webpackConfig({
      devtool: 'source-map'
    })
    .sourceMaps()
}

mix.sass('public/templates/2014newbrand/style.sass', 'public/templates/2014newbrand/style.css');
