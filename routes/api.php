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
        Route::group(['middleware' => ['auth:api']], function () {
            Route::post('/logout', 'App\Http\Controllers\AuthUserController@logout');
            Route::GET('/user-listing', 'App\Http\Controllers\UserController@getAllUsers');
        });
    });

});
