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
        Route::post('send-code', 'AuthController@sendCode');
    });
    Route::post('register', 'AuthController@signUp');
    Route::post('login', 'AuthController@signIn');
    Route::post('refresh-tokens', 'AuthController@refreshTokens');
    Route::post('logout', 'AuthController@logout');
});

Route::middleware('jwt-auth')->group(function () {
    Route::prefix('chats')->group(function () {
        Route::post('/direct/', 'DirectChatsController@store');
        Route::post('/channel/', 'ChannelsController@store');
        Route::post('/group/', 'GroupsController@store');
        Route::get('/', 'ChatsController@index');
    });

    Route::apiResources([
        'messages' => 'MessagesController',
    ]);

    Route::prefix('users')->group(function () {
        Route::get('/{username}/', 'UsersController@show');
    });
});
