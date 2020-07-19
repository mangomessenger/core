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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::prefix('auth')->group(function () {
    Route::middleware('throttle:20,2')->group(function () {
        Route::post('sendCode', 'AuthController@sendCode');
    });
    Route::post('signUp', 'AuthController@signUp');
    Route::post('signIn', 'AuthController@signIn');
    Route::post('refresh-tokens', 'AuthController@refreshTokens');
    Route::post('logout', 'AuthController@logout');
});

Route::middleware('jwt-auth')->prefix('messages')->group(function (){
    Route::get('/test', function (Request $request) {
        return auth()->user();
    });

    Route::post('/', 'MessagesController@sendMessage');
});
