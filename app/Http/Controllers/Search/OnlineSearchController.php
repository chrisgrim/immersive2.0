<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Search;
use App\Models\Genre;
use App\Models\Category;
use App\Models\EventRemoteSearchRule;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OnlineSearchController extends Controller
{
    public function index(Request $request)
    {
        $request = Search::convertUrl($request);

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

        return view('events.searchonline',compact('onlineevents', 'categories', 'tags'));
    }

    public function fetch(Request $request)
    {
        $request = Search::convertUrl($request);

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
        ->paginate(12);

        $content = tap($events->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return json_encode($content);
    }
}
