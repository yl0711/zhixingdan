<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

$httpHost = config('global.HTTP_HOST');

$domain = config('global.DOMAIN');

switch ($httpHost) {
    case $domain['ADMIN']:
        require app_path('Http/adminroutes.php');
        break;
    case $domain['api']:
        require app_path('Http/apiroutes.php');
        break;
    default:
        # code...
        break;
}
