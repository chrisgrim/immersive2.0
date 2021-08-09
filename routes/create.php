<?php

use Illuminate\Support\Facades\Route;

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