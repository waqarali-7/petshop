<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'admin'], function () {
        Route::post('/login', 'App\Http\Controllers\AuthUserController@login');
        Route::group(['middleware' => ['auth:api', 'admin:api']], function () {
            Route::post('/logout', 'App\Http\Controllers\AuthUserController@logout');
            Route::post('/create', 'App\Http\Controllers\UserController@createAdminUser');
            Route::GET('/user-listing', 'App\Http\Controllers\UserController@getAllAdminUsers');
        });
    });

    Route::group(['prefix' => 'user'], function () {
        Route::post('/login', 'App\Http\Controllers\AuthUserController@login');
        Route::group(['middleware' => ['auth:api']], function () {
            Route::get('/orders', 'App\Http\Controllers\OrderController@getUserOrders');
            Route::post('/create', 'App\Http\Controllers\UserController@createUser');
            Route::get('/{user}', 'App\Http\Controllers\UserController@getUser');
            Route::delete('/{user}', 'App\Http\Controllers\UserController@remove');
        });
    });

});
