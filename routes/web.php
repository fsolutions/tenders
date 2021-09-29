<?php

use App\Model\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

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
Route::get('/orders/client/act/{id}', 'API\OrderController@orderClientAct')->name("orderClientAct");
Route::get('/orders/client/{id}', 'API\OrderController@orderClientNaryad')->name("orderClientNaryad");
Route::get('orders/list', 'API\OrderController@list')->name("orderList");
Route::get('orders/page/{id}', 'API\OrderController@showPage')->name("oneOrderPage");
Route::put('orders/sign/{id}', 'API\OrderController@updatePart');

Route::group(['middleware' => ['auth']], function () {

    Route::post('orders', 'API\OrderController@store');
    Route::delete('orders/{id}', 'API\OrderController@destroy');
    Route::put('orders/{id}', 'API\OrderController@update');
    Route::get('orders/{id}', 'API\OrderController@show')->name("oneOrder");
    Route::get('orders/reaction/{id}', 'API\OrderController@sendReaction');
    Route::get('api/services', 'API\ListsController@servicesIndex');
    Route::get('api/cities', 'API\ListsController@citiesIndex');
    Route::get('api/graveyards/{city_id}', 'API\ListsController@graveyardsIndex');

    Route::post('send-question', 'API\QuestionController@sendMessage');

    Route::get('order-create', function () {
        return view('orders.create');
    });
    Route::get('order-edit/{id}', 'API\OrderController@orderEdit')->name("orderEdit");
});

Route::get('/logout', function () {
    Auth::logout();
    Session::flush();
    return Redirect::to('/');
});

Route::get('/terms', function () {
    return view('pages.terms');
});

Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
});

// Route::get('/notify', function () {
//     $order = \App\Model\Order::find(77);
//     $order->notify(new \App\Notifications\NewOrderPublished('111', '222'));

//     return true;
// });
