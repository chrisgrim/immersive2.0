<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::GET('/', 'EventController@index')->name('home');

// Route::GET('/login/{provider}', 'Auth\LoginController@redirectToProvider');
// Route::GET('/login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::RESOURCE('events', 'EventController');
Route::RESOURCE('categories', 'Admin\CategoryController');
Route::RESOURCE('contactlevels', 'Admin\ContactLevelsController');
Route::RESOURCE('contentadvisories', 'Admin\ContentAdvisoriesController');
Route::RESOURCE('genres', 'Admin\GenresController');
Route::RESOURCE('remotelocations', 'Admin\RemoteLocationsController');
Route::RESOURCE('mobilities', 'Admin\MobilitiesController');
Route::RESOURCE('staffpicks', 'Admin\StaffPicksController');
Route::RESOURCE('reviewevents', 'Admin\ReviewEventsController');
Route::RESOURCE('interactivelevels', 'Admin\InteractiveLevelController');

// ----------   Docks -------
Route::GET('/admin/docks', 'Featured\DockController@index');
Route::POST('/admin/docks/{dock}', 'Featured\DockController@store');

// ----------   Search Page -------
Route::POST('/search/storedata', 'Search\SearchDataController@store');
Route::GET('/index/search', 'Search\EventController@index');
Route::GET('/index/search-online', 'Search\OnlineSearchController@index');
Route::GET('/index/search-all', 'Search\EventController@allSearch');
Route::POST('/admin/search/data', 'Search\SearchDataController@create');

// This is the search for vuex
Route::POST('/vuex/search', 'SearchController@filterIndex');

//Get Messages from database
Route::GET('/messages', 'ConversationsController@index');
Route::GET('/conversations/fetch/messages', 'ConversationsController@fetchmessages');
Route::GET('/conversations/fetch/eventmessages', 'ConversationsController@fetcheventmessages');
Route::GET('/conversations/{conversation}', 'ConversationsController@show');
Route::POST('/conversations/{conversation}', 'ConversationsController@update');

//Organizer 
Route::RESOURCE('organizer', 'OrganizerController');
Route::GET('/organizer/{organizer}/events', 'OrganizerController@fetchEvents');
Route::POST('/organizer/{organizer}/patch', 'OrganizerController@update');
Route::POST('message/organizer/{organizer}/{user}', 'OrganizerController@message');
Route::POST('assign/organizer/{organizer}', 'OrganizerController@assign');

//User Profile Pages
Route::RESOURCE('users', 'ProfilesController');
Route::GET('account-settings', 'ProfilesController@account');
Route::GET('account-settings/notifications', 'ProfilesController@notifications');
Route::GET('account-settings/favorited', 'ProfilesController@favorited');

// favorites Button
Route::GET('myFavorites/{user}', 'FavoritesController@index');
Route::POST('favorite/{event}/favorites', 'FavoritesController@store');
Route::DELETE('favorite/{event}/favorites', 'FavoritesController@destroy');
Route::GET('favorite/{event}/login', 'FavoritesController@loginToFavorite');

// ratings 
Route::POST('rate/{event}/rating', 'RatingController@store');
Route::POST('flag/{rating}/', 'FlagController@rating')->middleware('verified');

//Data Mining
Route::POST('track/event/click', 'Data\UserBehaviorController@trackClicks');

//Website Info
Route::GET('/privacy-policy', 'LegalController@privacy');
Route::GET('/terms', 'LegalController@terms');
Route::GET('/disclaimer', 'LegalController@disclaimer');
Route::GET('/event-sitemap.xml', 'SitemapController@event_sitemap');
Route::GET('/organizer-sitemap.xml', 'SitemapController@organizer_sitemap');

// RSS feed
Route::get('feed', 'RssFeedController@feed');

//Generate City List
Route::GET('/citylist/generate', 'CityListsController@generate');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/create.php';
require __DIR__.'/curated.php';