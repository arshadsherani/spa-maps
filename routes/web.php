<?php

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

Route::resource('maps','MapsController');
Route::post('changestatus', 'MapsController@changeStatus');
Route::get('show-all-adddress', 'MapsController@show_all_adddress'); 

Route::get('get-all-adddress', 'MapsController@get_all_adddress'); 

Route::get('autocomplete-ajax',array('as'=>'autocomplete.ajax','uses'=>'MapsController@ajaxData'));


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
