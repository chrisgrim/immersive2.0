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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::GET('search/navbar', 'Search\SearchController@nav');
Route::GET('search/navbar/location', 'Search\SearchController@location');
Route::POST('search/mapboundary', 'Search\EventController@mapBoundary');
Route::POST('search/online', 'Search\OnlineSearchController@fetch');



// IS USED Admin get list of events
Route::GET('admin/event/deleted/search', 'Admin\SearchController@deletedEvents');
Route::GET('admin/organizer/search', 'Admin\SearchController@organizers');
Route::GET('admin/genres/search', 'Admin\SearchController@genres');
Route::GET('admin/events/search', 'Admin\SearchController@events');
Route::GET('admin/purgatory/search', 'Admin\SearchController@purgatory');
Route::GET('admin/users/search', 'Admin\SearchController@users');


