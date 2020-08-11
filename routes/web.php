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
Route::get('/orders', 'API\OrderController@index')->name("orderIndex");

Route::group(['middleware' => ['auth']], function () {
    Route::get('orders/list', 'API\OrderController@list')->name("orderList");
    Route::post('orders', 'API\OrderController@store');
    Route::delete('orders/{id}', 'API\OrderController@destroy');
    Route::put('orders/{id}', 'API\OrderController@update');
    Route::get('orders/{id}', 'API\OrderController@show')->name("oneOrder");
    Route::get('orders/page/{id}', 'API\OrderController@showPage')->name("oneOrderPage");

    Route::post('send-question', 'API\QuestionController@sendMessage');
});
