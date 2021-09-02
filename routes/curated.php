<?php

use Illuminate\Support\Facades\Route;


Route::RESOURCE('communities', 'Curated\CommunityController');
Route::RESOURCE('listings', 'Curated\ListingController');
Route::RESOURCE('cards', 'Curated\CardController');


// ----------   Communities -------
Route::GET('/communities/{community}', 'Curated\CommunityController@show')->middleware('can:preview,community');
Route::GET('/communities/{community}/edit', 'Curated\CommunityController@edit')->middleware('can:locked,community');
Route::PUT('/communities/{community}', 'Curated\CommunityController@update')->middleware('can:update,community');
Route::DELETE('/communities/{community}', 'Curated\CommunityController@destroy')->middleware('can:destroy,community');
Route::POST('/communities/{community}/curators/add', 'Curated\CommunityController@addCurator')->middleware('can:owner,community');
Route::POST('/communities/{community}/curators/remove', 'Curated\CommunityController@removeCurator')->middleware('can:update,community');
Route::POST('/communities/{community}/curators/owner', 'Curated\CommunityController@updateOwner')->middleware('can:owner,community');
Route::GET('/create/communities/thanks', 'Curated\CommunityController@submitted');


// ----------   Listings -------
Route::GET('/listings/{community}/create', 'Curated\ListingController@create')->middleware('can:update,community');
Route::POST('/listings/{community}/store', 'Curated\ListingController@store')->middleware('can:update,community');
Route::GET('/communities/{community}/{listing}', 'Curated\ListingController@show')->middleware('can:preview,listing');
Route::GET('/communities/{community}/{listing}/edit', 'Curated\ListingController@edit')->middleware('can:update,listing');
Route::PUT('/communities/{community}/{listing}/update', 'Curated\ListingController@update')->middleware('can:update,listing');
Route::DELETE('/listings/{listing}', 'Curated\ListingController@destroy')->middleware('can:destroy,listing');
Route::PUT('/listings/{community}/order', 'Curated\ListingController@order')->middleware('can:update,community');

// ----------   Cards  -------
Route::POST('/cards/{card}', 'Curated\CardController@update')->middleware('can:destroy,card');
Route::POST('/cards/{listing}/create', 'Curated\CardController@store')->middleware('can:update,listing');
Route::PUT('/cards/{listing}/order', 'Curated\CardController@order')->middleware('can:update,listing');
Route::DELETE('/cards/{card}', 'Curated\CardController@destroy')->middleware('can:destroy,card');