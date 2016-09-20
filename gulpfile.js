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

elixir(function(mix) {
    mix.sass('style.scss');

    mix.version(["css/style.css"]);

    //Copy css from node_modules to my css directory
    mix.copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'resources/assets/css/bootstrap.min.css');
    // mix.copy('node_modules/tooltipster/dist/css/tooltipster.bundle.min.css', 'resources/assets/css/tooltipster.min.css');
    mix.copy('node_modules/animate.css/animate.min.css', 'resources/assets/css/animate.min.css');
    mix.copy('node_modules/sweetalert2/dist/sweetalert2.css', 'resources/assets/css/sweetalert2.css');

    mix.stylesIn('resources/assets/css', 'public/css/plugins.css');
});
