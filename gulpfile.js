var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    mix.sass([
        './node_modules/sweetalert/dist/sweetalert.css',
        'app.scss'
    ]).scripts([
        './node_modules/jquery/dist/jquery.min.js',
        './node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        './node_modules/socket.io/node_modules/socket.io-client/socket.io.js',
        './node_modules/sweetalert/dist/sweetalert.min.js',
        'app.js'
    ])
        .copy('./node_modules/bootstrap-sass/assets/fonts/bootstrap/**', "./public/fonts/bootstrap")
        .version([
            './public/css/app.css',
            './public/js/all.js',
        ]);
});
