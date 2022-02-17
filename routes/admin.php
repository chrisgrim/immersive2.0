<?php

use Illuminate\Support\Facades\Route;

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

// ----------   Admin Curated Pages -------
Route::GET('admin/communities/index', 'Admin\CommunityController@index');
Route::GET('admin/communities/finalize', 'Admin\CommunityController@queue');
Route::GET('admin/communities/{community}/show', 'Admin\CommunityController@show');
Route::POST('admin/communities/{community}/approve', 'Admin\CommunityController@approve');
Route::POST('admin/communities/{community}/reject', 'Admin\CommunityController@reject');


// ----------   Docks -------
Route::GET('/admin/docks', 'Admin\Featured\DockController@index');
Route::POST('/admin/docks', 'Admin\Featured\DockController@store');
Route::POST('/admin/docks/{dock}', 'Admin\Featured\DockController@update');
Route::POST('/admin/docks/{dock}/shelf/{shelf}', 'Admin\Featured\DockController@addShelf');
Route::POST('/admin/docks/{dock}/community/{community}', 'Admin\Featured\DockController@addCommunity');
Route::POST('/admin/docks/{dock}/post/{post}', 'Admin\Featured\DockController@addPost');
Route::DELETE('/admin/docks/{dock}', 'Admin\Featured\DockController@destroy');

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