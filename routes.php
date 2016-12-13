<?php
use Microit\LaravelAdminBaseStandalone\AuthController;
use Microit\LaravelAdminBaseStandalone\DashboardController;

// Load AdminLTE files
Route::get('/adminlte/{one?}/{two?}/{three?}/{four?}/{five?}/', function () {
    $file = join("/", func_get_args());
    $ext = explode(".", $file);
    switch(end($ext)) {
        case "css":
            header("Content-type: text/css");
            break;
        case "js":
            header("Content-type: text/javascript");
            break;
    }

    echo file_get_contents(app_path().'/../vendor/almasaeed2010/adminlte/'.$file);
    die();
});

\Route::group(
    ['middleware' => ['web', 'auth']],
    function () {
        \Route::get('admin/logout', ['as' => 'admin.logout', 'uses' => \App\Http\Controllers\Auth\LoginController::class.'@logout']);

        Route::get('/admin', ['as' => 'admin.dashboard', 'uses' =>function() {
            return view('laravel-admin-base-standalone::layouts.dashboard');
        }]);
    }
);
