<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Oauth 2.0
Route::post('oauth/login', 'API\UserController@login');
Route::post('oauth/register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('oauth/details', 'API\UserController@details');
});

// JWT
Route::post('jwt/login', 'Auth\UserController@login');
Route::post('jwt/register', 'Auth\UserController@register');
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('jwt/details', 'Auth\UserController@details');
});