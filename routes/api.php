<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\UsersController;
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

Route::prefix('auth')->group(function () {
    Route::middleware('throttle:20,1')->group(function () {
        Route::post('send-code', [AuthController::class, 'sendCode']);
    });
    Route::post('register', [AuthController::class, 'signUp']);
    Route::post('login', [AuthController::class, 'signIn']);
    Route::post('refresh-tokens', [AuthController::class, 'refreshTokens']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('jwt-auth')->group(function () {
    Route::get('/', [ChatsController::class, 'index']);
//    Route::get('/limits', 'LimitsController@index');

    Route::prefix('chats')->group(function () {

        Route::apiResources([
            'direct-chats' => 'DirectChatsController',
            'channels' => 'ChannelsController',
            'groups' => 'GroupsController',
        ]);
    });

    Route::apiResources([
        'messages' => 'MessagesController',
    ]);

    Route::prefix('users')->group(function () {
        Route::get('/{username}/', [UsersController::class, 'show']);
        Route::put('/{user_id}', [UsersController::class, 'update']);
    });
});
