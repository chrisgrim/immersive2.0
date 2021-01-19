<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Genre;
use App\Models\Category;
use App\Models\EventRemoteSearchRule;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OnlineSearchController extends Controller
{
    public function index(Request $request)
    {
        if ($request->category) { $request->request->add(['category' => explode(",",$request->category)]); }
        if ($request->tag) { $request->request->add(['tag' => explode(",",$request->tag)]); }
        if ($request->price0) {
            if ($request->price1 >= 100) {
                $request->request->add(['price' => [$request->price0, 9999]]);
            } else {
                $request->request->add(['price' => [$request->price0,$request->price1 ]]);
            }
        }
        if ($request->start) {$request->request->add(['dates' => [$request->start, $request->end]]);}

        $maxprice = ceil(Event::getMostExpensive());
        $categories = Category::all();
        $tags = Genre::where('admin', 1)->orderBy('rank', 'desc')->get();

        $searchRequest = Event::boolSearch()
        ->must('range', ['closingDate' => [ 'gte' => 'now/d']])
        ->must('match', ['hasLocation' => false])
        ->when($request->price, function ($builder) use ($request) {
            return $builder->must('range', ['priceranges.price' => [ 'gte' => $request->price[0],'lte' => $request->price[1]]]);
        })
        ->when($request->category, function ($builder) use ($request) {
            return $builder->filter('terms', ['category_id' => $request->category]);
        })
        ->when($request->tag, function ($builder) use ($request) {
            return $builder->filter('terms', ['genres.name' => $request->tag]);
        })
        ->when($request->dates, function ($builder) use ($request) {
            return $builder->should('range', ['shows.date' => [ 'gte' => $request->dates[0],'lte' => $request->dates[1]]])
                ->should('term', ['showtype' => 'a'])
                ->minimumShouldMatch(1);
        })
        ->sortRaw(['published_at' => 'desc'])
        ->load(['genres', 'category'])
        ->paginate(12);

        $content = tap($searchRequest->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        $onlineevents = json_encode($content);

        return view('events.searchonline',compact('onlineevents', 'categories', 'maxprice', 'tags'));
    }

    public function fetch(Request $request)
    {
        if ($request->price && $request->price[1] >= 100) {
            $low = $request->price[0];
            $request->request->add(['price' => [$low, 9999]]);
        }
        if ($request->lat) { $showNumber = 6; } else {$showNumber = 12;}

        $events = Event::boolSearch()
        ->must('range', ['closingDate' => [ 'gte' => 'now/d']])
        ->must('match', ['hasLocation' => false])
        ->when($request->price, function ($builder) use ($request) {
            return $builder->must('range', ['priceranges.price' => [ 'gte' => $request->price[0],'lte' => $request->price[1]]]);
        })
        ->when($request->category, function ($builder) use ($request) {
            return $builder->filter('terms', ['category_id' => $request->category]);
        })
        ->when($request->tag, function ($builder) use ($request) {
            return $builder->filter('terms', ['genres.name' => $request->tag]);
        })
        ->when($request->dates, function ($builder) use ($request) {
            return $builder->should('range', ['shows.date' => [ 'gte' => $request->dates[0],'lte' => $request->dates[1]]])
                ->should('term', ['showtype' => 'a'])
                ->minimumShouldMatch(1);
        })
        ->sortRaw(['published_at' => 'desc'])
        ->load(['genres', 'category'])
        ->paginate($showNumber);

        $content = tap($events->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return json_encode($content);


    }
}
