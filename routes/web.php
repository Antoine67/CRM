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

$guest = env('GUEST_LEVEL', 0 );
$user = env('USER_LEVEL', 1 );
$editor = env('EDITOR_LEVEL', 2 );
$admin = env('ADMIN_LEVEL', 3 );

Auth::routes();

Route::get('/home', 'WelcomeController@get');

Route::group(['middleware' => 'use.ssl'], function () {
    //SSL pages there - certs have to be setup
});



//Routes which need to be logged in without any particular permissions
Route::group(['middleware' => 'need.login' ], function () {
    Route::get('/', 'WelcomeController@get');
    Route::get('/profile', 'ProfileController@get');
});



//Routes which need to be logged in and have at least a user account 
Route::group(['middleware' => ['need.login','user.level:'.$user]], function () {

    //Route::get('/sharepoint', 'SharepointController@get');
    //Route::post('/sharepoint', 'SharepointController@post');

    Route::get('/customer/{id}', 'CustomerController@getById');
    Route::post('/customer/{id}', 'CustomerController@updateById');
    Route::get('/customer', 'CustomerController@getAll');

    Route::get('/creation', 'CreationController@get');
    Route::post('/creation', 'CreationController@post');
    
});

//Routes which need to be logged in and have an editor, or admin account
Route::group(['middleware' => ['need.login','user.level:'.$editor]], function () {
    Route::post('/editor-mode', 'EditorController@post');
});

//Routes which need to be logged in and have an admin account
Route::group(['middleware' => ['need.login','user.level:'.$admin]], function () {
    Route::get('/datasources', 'AdminController@datasources');
    Route::post('/datasources', 'AdminController@postDatasources');
    Route::get('/data-default', 'AdminController@datadefault');
    Route::post('/data-default', 'AdminController@postDatadefault');
    Route::get('/admin', 'AdminController@get');
});





