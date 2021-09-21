<?php

use Illuminate\Support\Facades\Route;


Route::RESOURCE('communities', 'Curated\CommunityController');
Route::RESOURCE('posts', 'Curated\PostController');
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


// ----------   Shelves -------
Route::POST('/shelves/{community}', 'Curated\ShelfController@store')->middleware('can:update,community');
Route::PUT('/shelves/{shelf}', 'Curated\ShelfController@update')->middleware('can:update,shelf');
Route::PUT('/shelves/{community}/order', 'Curated\ShelfController@order')->middleware('can:update,community');
Route::DELETE('/shelves/{shelf}', 'Curated\ShelfController@destroy')->middleware('can:destroy,shelf');
Route::GET('/shelves/{shelf}/paginate', 'Curated\ShelfController@paginate');


// ----------   Posts -------
Route::GET('/posts/{community}/create', 'Curated\PostController@create')->middleware('can:update,community');
Route::POST('/posts/{community}/store', 'Curated\PostController@store')->middleware('can:update,community');
Route::GET('/communities/{community}/{post}', 'Curated\PostController@show')->middleware('can:preview,post');
Route::GET('/communities/{community}/{post}/edit', 'Curated\PostController@edit')->middleware('can:update,post');
Route::PUT('/communities/{community}/{post}/update', 'Curated\PostController@update')->middleware('can:update,post');
Route::DELETE('/posts/{post}', 'Curated\PostController@destroy')->middleware('can:destroy,post');
Route::PUT('/posts/{community}/order', 'Curated\PostController@order')->middleware('can:update,community');

// ----------   Cards  -------
Route::POST('/cards/{card}', 'Curated\CardController@update')->middleware('can:destroy,card');
Route::POST('/cards/{post}/create', 'Curated\CardController@store')->middleware('can:update,post');
Route::PUT('/cards/{post}/order', 'Curated\CardController@order')->middleware('can:update,post');
Route::DELETE('/cards/{card}', 'Curated\CardController@destroy')->middleware('can:destroy,card');