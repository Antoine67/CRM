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

Auth::routes();

Route::get('/home', 'WelcomeController@get');

Route::group(['middleware' => 'use.ssl'], function () {
    //SSL pages there - certs have to be setup
});

//Routes which need to be logged in and have an editor, or admin account
Route::group(['middleware' => ['need.login','user.level'] ], function () {

    Route::get('/sharepoint', 'SharepointController@get');
    Route::post('/sharepoint', 'SharepointController@post');

    Route::get('/customer/{id}', 'CustomerController@getById');
    Route::post('/customer/{id}', 'CustomerController@updateById');
    Route::get('/customer', 'CustomerController@getAll');
    Route::post('/editor-mode', 'EditorController@post');



   
});

//Routes which need to be logged in without any particular permissions
Route::group(['middleware' => 'need.login' ], function () {
    Route::get('/', 'WelcomeController@get');
    Route::get('/profile', 'ProfileController@get');
});



