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

// Route::resource('platform', 'API\PlatformController');

Route::get('platform/web/{id}', 'API\PlatformController@webShow');
Route::get('refollowers/web/{id}/{platform_id}', 'API\RefollowersController@changeAccount');

Route::get('platform/web/openbox/{box_id}/{user_id}', 'API\BoxesController@openBox');

Route::post('statistics/web/platform/opened/{id}', 'API\PlatformController@openPlatform');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
