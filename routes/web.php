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
use Illuminate\Support\Facades\Input;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//go to create page
Route::get('event/create', 'EventController@create')->name('event_create');
//validate event form information and store in database
Route::post('event/create', 'EventController@store')->name('store');

//go to modify page
Route::get('event/modify/{id}', 'EventController@getModifyPage')->name('event_modify_page');
//modify existing event
Route::post('event/modify/{id}', 'EventController@modify')->name('event_modify');
//delete existing event
Route::get('event/delete/{id}', 'EventController@delete')->name('event_delete');

//list events
Route::get('event/list_events', 'EventController@listAll')->name('list_all_events');
Route::get('event/list_my_events', 'EventController@listOrganisersEvents')->name('list_my_events');
//validate filter of event list and return new list page with filter results
Route::post('event/list_events', 'EventController@validateFilter')->name('filter_all_events');
Route::post('event/list_my_events', 'EventController@validateFilter')->name('filter_my_events');

//go to event information page
Route::get('event/show/{id}', 'EventController@show')->name('show');

//like event
Route::post('event/show/{id}', 'EventController@likeEvent')->name('like_event');
