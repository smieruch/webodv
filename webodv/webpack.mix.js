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

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css')
//     .sourceMaps();

mix.styles(['node_modules/bootstrap/dist/css/bootstrap.min.css',
            'public/font-awesome-4.7.0/css/font-awesome.min.css',
	    'node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
	    'node_modules/datepicker-bootstrap/css/core.min.css',
	    'node_modules/datepicker-bootstrap/css/datepicker.min.css',
	    'node_modules/cropperjs/dist/cropper.min.css',
	   ],
	   'public/css/webodv/webodv_vendor.css').version();

mix.scripts(['node_modules/jquery/dist/jquery.min.js',
	     'node_modules/blockui/jquery.blockui.min.js',
	     'node_modules/popper.js/dist/umd/popper.min.js',
	     'node_modules/bootstrap/dist/js/bootstrap.min.js',
	     'node_modules/jquery-touchswipe/jquery.touchSwipe.min.js',
	     'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
	     'node_modules/datepicker-bootstrap/js/core.min.js',
	     'node_modules/datepicker-bootstrap/js/datepicker.min.js',
	     'node_modules/cropperjs/dist/cropper.min.js',
	     'node_modules/moment/min/moment.min.js',
	    ],
	    'public/js/webodv/webodv_vendor.js').version();

mix.copy('node_modules/plotly.js/dist/plotly.js', 'public/js/webodv/plotly.js');

mix.copy('node_modules/hummingbird-treeview/hummingbird-treeview.js', 'public/js/webodv/hummingbird-treeview.js');
mix.copy('node_modules/hummingbird-treeview/hummingbird-treeview.css', 'public/css/webodv/hummingbird-treeview.css');

