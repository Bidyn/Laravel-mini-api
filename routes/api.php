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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'middleware' => 'apiLimit'], function(){

    Route::get('/json/ip', 'GeolocationController@getJson');
    Route::get('/jsonp/ip', 'GeolocationController@getJsonp');
    Route::get('/soap/ip', 'GeolocationController@getSoap');

});