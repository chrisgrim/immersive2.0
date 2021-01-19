<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Event;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            CategorySeeder::class,
            EventSeeder::class,
        ]);
        // $categories = factory(App\Category::class, 1)->create();
        // $categories->each(function($category) {
        //     $events = factory('App\Event')->create(['category_id' => $category->id]);
        //     $events->each(function($event) {
        //     factory('App\Location')->create(['event_id' => $event->id]);
        //     });
        //     $events->each(function($event) {
        //         factory('App\Show')->create(['event_id' => $event->id]);
        //     });
        //     $events->each(function($event) {
        //         factory('App\ContentAdvisory')->create();
        //     });
        //     $events->each(function($event) {
        //         factory('App\ContactLevel')->create();
        //     });
        //     $events->each(function($event) {
        //         factory('App\Advisory')->create(['event_id' => $event->id]);
        //     });
        // });

        // $events = factory(Event::class, 2)->create();
        // $events->each(function($event) {
        //     factory('App\Location')->create(['event_id' => $event->id]);
        // });
        // $events->each(function($event) {
        // 	factory('App\Show')->create(['event_id' => $event->id]);
        // });
        // $events->each(function($event) {
        //     factory('App\ContentAdvisory')->create();
        // });
        // $events->each(function($event) {
        //     factory('App\ContactLevel')->create();
        // });
        // $events->each(function($event) {
        //     factory('App\Advisory')->create(['event_id' => $event->id]);
        // });
    }
}
