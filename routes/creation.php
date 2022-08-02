<?php

use Illuminate\Support\Facades\Route;

Route::GET('/experience-hosting/listing/{event}/experience-type', 'Creation\IdeaController@experienceType');


Route::PATCH('/experience-hosting/listing/{event}/experience-update', 'Creation\ExperienceController@update');

Route::PATCH('/experience-hosting/listing/{event}', 'Creation\EventController@update');

Route::PATCH('/experience-hosting/listing/{event}/host', 'Creation\HostController@update');