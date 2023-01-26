<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//auth
$router->group(['prefix' =>'auth'], function () use ($router){
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
});

//transaksi
$router->get('/TransaksiApotek', 'TransaksiApotekController@index');
Route::group(['middleware' => ['auth']], function ($router){
    $router->post('/TransaksiApotek', 'TransaksiApotekController@store');
    $router->get('/TransaksiApotek/{id}', 'TransaksiApotekController@show');
    $router->put('/TransaksiApotek/{id}', 'TransaksiApotekController@update');
    $router->delete('/TransaksiApotek/{id}', 'TransaksiApotekController@destroy');
});

//post public
$router->get('/public/ObatApotek', 'ObatApotekPublicController@index');
$router->get('/public/ObatApotek/{id}', 'ObatApotekPublicController@show');

//obat
Route::group(['middleware' => ['auth']], function ($router){
    $router->get('/ObatApotek', 'ObatApotekController@index');
    $router->post('/ObatApotek', 'ObatApotekController@store');
    $router->get('/ObatApotek/{id_obat}', 'ObatApotekController@show');
    $router->put('/ObatApotek/{id_obat}', 'ObatApotekController@update');
    $router->delete('/ObatApotek/{id_obat}', 'ObatApotekController@destroy');
});

//supplier
Route::group(['middleware' => ['auth']], function ($router){
    $router->get('/Supplier', 'SupplierController@index');
    $router->post('/Supplier', 'SupplierController@store');
    $router->get('/Supplier/{id}', 'SupplierController@show');
    $router->put('/Supplier/{id}', 'SupplierController@update');
    $router->delete('/Supplier/{id}', 'SupplierController@destroy');
});

//profiles
Route::group(['middleware' => ['auth']], function ($router){
    $router->get('/Profiles', 'ProfilesController@index');
    $router->post('/Profiles', 'ProfilesController@store');
    //$router->get('/Profiles/{id}', 'ProfilesController@show');
    //$router->put('/Profiles/{id}', 'ProfilesController@update');
    $router->get('/Profiles/image/{imageName}', 'ProfilesController@image');
    //$router->delete('/Profiles/{id}', 'ProfilesController@destroy');
});