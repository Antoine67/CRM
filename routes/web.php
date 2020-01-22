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

Route::get('/signin', 'AuthController@signin');
Route::get('/authorize', 'AuthController@gettoken');

Route::get('/login', 'LoginController@get');

Route::group(['middleware' => 'use.ssl'], function () {
    //SSL pages there - certs have to be setup
});

//Routes which need to be logged in with a microsoft account
Route::group(['middleware' => 'need.microsoft'], function () {
    
    Route::get('/sharepoint', 'SharepointController@get');

    Route::get('/customer/{id}', 'CustomerController@getById');
    Route::get('/customer', 'CustomerController@getAll');



    Route::get('/profile', 'ProfileController@get');
    Route::post('/logout', 'ProfileController@logout');
});

