<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Event;

class EventSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $events = factory(Event::class, 30)->create();
        $events->each(function($event) {
            factory('App\Location')->create(['event_id' => $event->id]);
        });
        $events->each(function($event) {
         factory('App\Show')->create(['event_id' => $event->id]);
        });
        $events->each(function($event) {
            factory('App\ContentAdvisory')->create();
        });
        $events->each(function($event) {
            factory('App\ContactLevel')->create();
        });
        $events->each(function($event) {
            factory('App\Advisory')->create(['event_id' => $event->id]);
        });
    }
}
