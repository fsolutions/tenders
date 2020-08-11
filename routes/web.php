<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/platforms', 'API\PlatformController@index')->name("platformIndex");

Route::group(['middleware' => ['auth']], function () {
    Route::get('platforms/list', 'API\PlatformController@list')->name("platformList");
    Route::post('platforms', 'API\PlatformController@store');
    Route::delete('platforms/{id}', 'API\PlatformController@destroy');
    Route::put('platforms/{id}', 'API\PlatformController@update');
    Route::get('platforms/{id}', 'API\PlatformController@show')->name("onePlatform");
    Route::get('platforms/page/{id}', 'API\PlatformController@showPage')->name("onePlatformPage");

    Route::get('platforms/boxes/create-default/{id}', 'API\PlatformController@createDefaultBoxes');
    Route::resource('platforms/boxes', 'API\BoxesController');

    Route::get('platforms/boxes/kupons/list', 'API\KuponsController@list');
    Route::post('platforms/boxes/kupons', 'API\KuponsController@store');
    Route::delete('platforms/boxes/kupons/{id}', 'API\KuponsController@destroy');
    Route::put('platforms/boxes/kupons/{id}', 'API\KuponsController@update');
    Route::post('platforms/boxes/delete-all-kupons/{id}', 'API\KuponsController@deleteAllKupons');
    Route::post('platforms/boxes/kupons-delete', 'API\KuponsController@deleteSelectedKupons');

    Route::get('platforms/statistics/all-numbers/{platform_id}', 'API\StatisticsController@allNumbers');

    Route::post('send-question', 'API\QuestionController@sendMessage');
});
