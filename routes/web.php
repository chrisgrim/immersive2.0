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
Route::RESOURCE('communities', 'Curated\CommunityController');
Route::RESOURCE('listings', 'Curated\ListingController');
Route::RESOURCE('cards', 'Curated\CardController');

// ----------   Communities Page -------
Route::GET('/communities/{community}/fetch', 'Curated\CommunityController@fetch');
Route::POST('/communities/{community}/curators/remove', 'Curated\CommunityController@removeCurator');
Route::POST('/communities/{community}/curators/owner', 'Curated\CommunityController@updateOwner');
Route::POST('/communities/{community}/curators/add', 'Curated\CommunityController@addCurator');
Route::GET('/create/communities/thanks', 'Curated\CommunityController@submitted');
Route::PATCH('/index/{community}/order', 'Curated\CommunityController@order');
Route::GET('/create/{community}/listing', 'Curated\ListingController@create');
Route::GET('/index/{community}/listing', 'Curated\ListingController@index');
Route::GET('/index/{community}/paginate', 'Curated\ListingController@paginate');
Route::POST('/create/{community}/listing', 'Curated\ListingController@store');
Route::PATCH('/listings/{listing}/order', 'Curated\ListingController@order');
Route::GET('/communities/{community}/{listing}', 'Curated\ListingController@show');
Route::GET('/communities/{community}/{listing}/edit', 'Curated\ListingController@edit');
Route::GET('/listings/{listing}/fetch', 'Curated\ListingController@fetch');
Route::POST('/create/{listing}/card', 'Curated\CardController@store');

// ----------   Admin Communities Page -------
Route::GET('admin/communities/finalize', 'Admin\CommunityController@index');
Route::GET('admin/communities/{community}/show', 'Admin\CommunityController@show');
Route::POST('admin/communities/{community}/approve', 'Admin\CommunityController@approve');
Route::POST('admin/communities/{community}/reject', 'Admin\CommunityController@reject');


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

//Admin Area
Route::GET('/admin/dashboard', 'Admin\AdminAreaController@index');

//Admin Events
Route::GET('/admin/events', 'Admin\EventController@index');
Route::GET('/admin/events/fetch', 'Admin\EventController@fetch');
Route::GET('/admin/event-requests', function () { return view('adminArea.eventrequests'); });
Route::GET('/admin/event-requests/fetch', 'Admin\EventController@eventRequests');
Route::POST('/admin/event-requests/edit/{EventRequest}', 'Admin\EventController@editTitle');
Route::POST('/admin/event-requests/respond/{EventRequest}', 'Admin\EventController@respondRequest');
Route::GET('/admin/events/show/{event}', 'Admin\EventController@show');
Route::POST('/admin/event/{event}/approve', 'Admin\EventController@approve');
Route::POST('/admin/event/{event}/fail', 'Admin\EventController@fail');
Route::POST('/admin/event/{event}/reject', 'Admin\EventController@reject');
Route::GET('/admin/events/finalize', 'Admin\EventController@queues');
Route::GET('/admin/events/finalize/fetch', 'Admin\EventController@fetchQue');
Route::GET('/admin/events/{event}/finalize', 'Admin\EventController@finalize');
Route::POST('/admin/event/{event}/change-organizer', 'Admin\EventController@changeOrganizer');
Route::POST('/admin/event/boneyard/fetch', 'Admin\BoneyardController@fetch');
Route::POST('/admin/event/boneyard/{event}/resurrect', 'Admin\BoneyardController@resurrect');
Route::GET('/admin/boneyard', 'Admin\BoneyardController@index');
Route::GET('/admin/events/purgatory', 'Admin\PurgatoryController@index');
Route::POST('/admin/events/purgatory/fetch', 'Admin\PurgatoryController@fetch');
Route::GET('/admin/nav/fetch', 'Admin\AdminAreaController@nav');

//Admin Users
Route::GET('/admin/users', 'Admin\UsersController@index');
Route::PATCH('/admin/users/{user}', 'Admin\UsersController@update');
Route::DELETE('/admin/users/{user}/delete', 'Admin\UsersController@delete');
Route::POST('/admin/users/fetch', 'Admin\UsersController@fetch');

//Admin Staff Picks
Route::POST('/admin/staffpicks/fetch', 'Admin\StaffPicksController@fetch');
Route::GET('/staffpicks/current', 'Admin\StaffPicksController@show');

//Admin Organizers
Route::POST('/admin/organizer/{organizer}/approve', 'Admin\OrganizerController@approve');
Route::POST('/admin/organizer/{organizer}/delete', 'Admin\OrganizerController@delete');
Route::POST('/admin/organizer/{organizer}/changeUser', 'Admin\OrganizerController@user');
Route::GET('/admin/organizers/finalize', 'Admin\OrganizerController@queues');
Route::GET('/admin/organizers/{organizer}/finalize', 'Admin\OrganizerController@finalize');
Route::GET('/admin/organizer', 'Admin\OrganizerController@index');
Route::PATCH('/admin/organizer/{organizer}', 'Admin\OrganizerController@update');
Route::GET('/admin/organizer/fetch', 'Admin\OrganizerController@fetch');
Route::POST('/admin/organizer/{organizer}/addTeamMember', 'Admin\OrganizerController@addTeamMember');


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

//Event Creation Process
Route::GET('/create/events/edit', 'Create\EventController@show');
Route::GET('/create/organizers/fetch', 'Create\OrganizerController@fetch');
Route::POST('/create/{organizer}/events/fetch', 'Create\EventController@fetch');
//Create Title
Route::GET('/create/{event}/title', 'Create\TitleController@create');
Route::GET('/create/{event}/title/fetch', 'Create\TitleController@fetch');
Route::PATCH('/create/{event}/title', 'Create\TitleController@update');
Route::PATCH('/create/{event}/change-title', 'Create\TitleController@changeTitle');
//Create Location
Route::GET('/create/{event}/location', 'Create\LocationController@create');
Route::GET('/create/{event}/location/fetch', 'Create\LocationController@fetch');
Route::PATCH('/create/{event}/location', 'Create\LocationController@update');
//Select Category
Route::GET('/create/{event}/category', 'Create\CategoryController@create');
Route::GET('/create/{event}/category/fetch', 'Create\CategoryController@fetch');
Route::PATCH('/create/{event}/category', 'Create\CategoryController@update');
//Create Show and Ticket Times
Route::GET('/create/{event}/shows', 'Create\ShowsController@create');
Route::GET('/create/{event}/shows/fetch', 'Create\ShowsController@fetch');
Route::POST('/create/{event}/shows', 'Create\ShowsController@update');
//Create Tickets
Route::GET('/create/{event}/tickets', 'Create\TicketsController@create');
Route::GET('/create/{event}/tickets/fetch', 'Create\TicketsController@fetch');
Route::POST('/create/{event}/tickets', 'Create\TicketsController@update');
//Create Description
Route::GET('/create/{event}/description', 'Create\DescriptionController@create');
Route::GET('/create/{event}/description/fetch', 'Create\DescriptionController@fetch');
Route::PATCH('/create/{event}/description ', 'Create\DescriptionController@update');
//Create Advisories
Route::GET('/create/{event}/advisories', 'Create\AdvisoriesController@create');
Route::GET('/create/{event}/advisories/fetch', 'Create\AdvisoriesController@fetch');
Route::PATCH('/create/{event}/advisories ', 'Create\AdvisoriesController@update');
//Create Image
Route::GET('/create/{event}/images', 'Create\ImageController@create');
Route::POST('/create/{event}/add-images', 'Create\ImageController@addImage');
Route::POST('/create/{event}/images', 'Create\ImageController@update');
//Create Review
Route::GET('/create/{event}/review', 'Create\EventController@review');
//Submit Event
Route::GET('/create/{event}/submit', 'Create\EventController@submit');
Route::GET('/create/{event}/thankyou', 'Create\EventController@completed');

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