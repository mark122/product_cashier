<?php

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
Route::resource('products','ProductController');
Route::get('/subscribe', 'SubscriptionController@showSubscription');
Route::post('/subscribe', 'SubscriptionController@processSubscription');
// welcome page only for subscribed users
Route::get('/welcome', 'SubscriptionController@showWelcome')->middleware('subscribed');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
