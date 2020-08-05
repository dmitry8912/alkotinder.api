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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->prefix('settings')->group(function() {
   Route::get('/','PersonalSettingsController@getSettings');
   Route::put('/','PersonalSettingsController@setSettings');
});

Route::middleware('auth:sanctum')->prefix('avatar')->group(function() {
    Route::get('/','PersonalSettingsController@getAvatar');
    Route::post('/','PersonalSettingsController@setAvatar');
});

Route::middleware('auth:sanctum')->prefix('drinks')->group(function() {
    Route::get('/','DrinkController@all');
    Route::post('/','DrinkController@add');
});

Route::middleware('auth:sanctum')->prefix('progress')->group(function() {
    Route::get('/','ProgressController@getToday');
    Route::post('/','ProgressController@add');
    Route::delete('/{uid}','ProgressController@remove');
});

Route::get('/token/{state}','TokensController@get');
