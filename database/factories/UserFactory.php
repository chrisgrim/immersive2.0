<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Event;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Event::class, function (Faker $faker) {
    $title = $faker->company;
    File::makeDirectory('public/storage/event-images/' . str_slug($title) . '/');
    $imagesave = $faker->image('public/storage/event-images/' . str_slug($title) . '/',1200,800, null, false);

    return [
        'user_id' => factory('App\User'),
        'organizer_id' => factory('App\Organizer'),
        'category_id' => $faker->randomElement(['1','2']),
        'advisories_id' => $faker->randomElement(['1']),
        'name' => $title,
        'slug' => str_slug($title),
        'description' => $faker->paragraph,
        'largeImagePath' => 'event-images/' . str_slug($title) .'/'. $imagesave,
        'thumbImagePath' => 'event-images/' . str_slug($title) .'/'. $imagesave,
        'closingDate' => '2020-05-05 00:00:00',
        'websiteUrl' => $faker->url,
        'status' => 'p',
        'showtype' => $faker->randomElement(['o','s','a']),
        'price_range' => $faker->biasedNumberBetween($min = 10, $max = 20, $function = 'sqrt'),
        'show_times' => $faker->sentence,
        'ticketUrl' => $faker->url,
        'hasLocation' => $faker->randomElement([1,0]),
        'location_latlon' => [
            'lat' => 38.2675796,
            'lon' => -122.6351578,
        ],
    ];
});

$factory->define(User::class, function (Faker $faker) {
    $name = $faker->name;
    File::makeDirectory('public/storage/user-images/' . str_slug($name) . '/');
    $imagesave = $faker->image('public/storage/user-images/' . str_slug($name) . '/',200,200, null, false);
    
    return [
        'name' => $name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'largeImagePath' => 'user-images/' . str_slug($name) .'/'. $imagesave,
        'thumbImagePath' => 'user-images/' . str_slug($name) .'/'. $imagesave,
        'email_verified_at' => $faker->dateTimeBetween('now', 'tomorrow'),
        'type' => 'g',

    ];
});

$factory->define(App\Organizer::class, function (Faker $faker) {
    $name = $faker->name;
    File::makeDirectory('public/storage/organizer-images/' . str_slug($name) . '/');
    $imagesave = $faker->image('public/storage/organizer-images/' . str_slug($name) . '/',600,600, null, false);
    return [
        'user_id' => factory('App\User'),
        'name' => $name,
        'slug' => str_slug($name),
        'description' => $faker->paragraph,
        'rating' => $faker->randomElement(['0']),
        'instagramHandle' => '@'. $name,
        'facebookHandle' => '@'. $name,
        'twitterHandle' => '@'. $name,
        'largeImagePath' => 'organizer-images/' . str_slug($name) .'/'. $imagesave,
        'thumbImagePath' => 'organizer-images/' . str_slug($name) .'/'. $imagesave,
        'website' => $faker->url,
        'email' => $faker->unique()->safeEmail,
    ];
});

$factory->define(App\Category::class, function (Faker $faker) {
    $name = $faker->name;
    File::makeDirectory('public/storage/category-images/' . str_slug($name) . '/');
    $imagesave = $faker->image('public/storage/category-images/' . str_slug($name) . '/',600,600, null, false);
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'description' => $faker->paragraph,
        'largeImagePath' => 'category-images/' . str_slug($name) .'/'. $imagesave,
        'thumbImagePath' => 'category-images/' . str_slug($name) .'/'. $imagesave,
    ];
});

$factory->define(App\Location::class, function (Faker $faker) {
    return [
        'hiddenLocationToggle' => $faker->randomElement(['0']),
        'hiddenLocation' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'home' => $faker->buildingNumber,
        'street' => $faker->streetName,
        'city' => $faker->city,
        'region' => $faker->state,
        'country' => 'US',
        'postal_code' => '94952',
        'longitude' => $faker->longitude($min = -120, $max = -130),
        'latitude' => $faker->latitude($min = 30, $max = 50),
    ];
});

$factory->define(App\Show::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTimeBetween('now', 'tomorrow'),
    ];
});

$factory->define(App\Advisory::class, function (Faker $faker) {
    return [
        'wheelchairReady' => $faker->randomElement(['0','1']),
        'sexualViolence' => '0',
        'ageRestriction' => 'All Ages',
        'contactAdvisories' => $faker->sentence,
    ];
});

$factory->define(App\ContactLevel::class, function (Faker $faker) {
    return [
        'level' => $faker->name,
    ];
});

$factory->define(App\ContentAdvisory::class, function (Faker $faker) {
    return [
        'advisories' => $faker->name,
        'user_id' => 1,
    ];
});


