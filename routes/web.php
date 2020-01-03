<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/accounts', 'AccountController@getAccounts');
    Route::post('/accounts/store', 'AccountController@store');
    Route::post('/accounts/update', 'AccountController@update');
    Route::post('/accounts/delete', 'AccountController@delete');
    Route::post('/accounts/update-status', 'AccountController@updateStatus');
    Route::post('/accounts/mass-delete', 'AccountController@massDelete');
    Route::get('/warehouses/video-1s', 'WarehouseController@video1s')->name('video1s');
    Route::get('/warehouses/get-warehouse-video-1s', 'WarehouseController@getWarehousesVideo1s')->name('getWarehousesVideo1s');
    Route::post('/warehouses/store-warehouse-video-1s', 'WarehouseController@storeWarehouseVideo1s');
    Route::post('/warehouses/update-warehouse-video-1s', 'WarehouseController@updateWarehouseVideo1s');
    Route::post('/warehouses/delete-warehouse-video-1s', 'WarehouseController@deleteWarehouseVideo1s');
    Route::post('/warehouses/mass-delete-warehouse-video-1s', 'WarehouseController@massDeleteWarehouseVideo1s');
});