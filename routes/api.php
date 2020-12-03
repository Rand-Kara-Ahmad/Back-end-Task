<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {

    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);

});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user/info', [\App\Http\Controllers\Api\AuthController::class, 'details']);

    Route::group(['prefix' => 'tweet'], function () {
        Route::post('/store', [\App\Http\Controllers\Tweet\TweetController::class, 'store']);
        Route::get('/all', [\App\Http\Controllers\Tweet\TweetController::class, 'getUserTweets']);
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::post('/follow', [\App\Http\Controllers\Follow\FollowController::class, 'follow']);
        Route::post('/unfollow', [\App\Http\Controllers\Follow\FollowController::class, 'unFollow']);
        Route::get('/followers', [\App\Http\Controllers\Follow\FollowController::class, 'getFollowers']);
    });
});
