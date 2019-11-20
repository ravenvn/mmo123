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

Route::get('/accounts', 'AccountController@getAccounts');
Route::post('/accounts/store', 'AccountController@store');
Route::post('/accounts/update', 'AccountController@update');
Route::post('/accounts/delete', 'AccountController@delete');
Route::post('/accounts/update-status', 'AccountController@updateStatus');
Route::post('/accounts/mass-delete', 'AccountController@massDelete');
Route::get('/accounts/kho-video-1s', 'Video1sController@index')->name('video1s');