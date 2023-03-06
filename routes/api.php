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

Route::GET('search/community', 'Search\SearchController@community');
Route::GET('search/shelf', 'Search\SearchController@shelf');
Route::GET('search/navbar/location', 'Search\SearchController@location');
Route::GET('search/navbar/eventorganizer', 'Search\SearchController@eventorganizer');
Route::GET('search/events', 'Search\SearchController@events');
Route::GET('search/navbar/tags', 'Search\SearchController@tags');
Route::GET('search/navbar/categories', 'Search\SearchController@categories');
Route::POST('search/fetch', 'Search\EventController@fetch');
Route::POST('search/online', 'Search\EventController@online');
Route::POST('search/all', 'Search\EventController@fetchAll');


// IS USED Admin get list of events
Route::GET('admin/event/deleted/search', 'Admin\SearchController@deletedEvents');
Route::GET('admin/organizer/search', 'Admin\SearchController@organizers');
Route::GET('admin/genres/search', 'Admin\SearchController@genres');
Route::GET('admin/events/search', 'Admin\SearchController@events');
Route::GET('admin/purgatory/search', 'Admin\SearchController@purgatory');
Route::GET('admin/users/search', 'Admin\SearchController@users');
Route::GET('admin/communities/search', 'Admin\SearchController@communities');


