<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Search;
use App\Models\Genre;
use App\Models\Category;
use App\Models\EventRemoteSearchRule;
use Illuminate\Http\Request;
use Elastic\ScoutDriverPlus\Support\Query;
use Illuminate\Support\Arr;

class OnlineSearchController extends Controller
{
    public function index(Request $request)
    {
        $request = Search::convertUrl($request);
        if ($request->dates) {
            $datesQuery = Query::bool()
                ->should(Query::range()->field('shows.date')->gte($request->dates[0])->lte($request->dates[1]))
                ->should(Query::term()->field('showtype')->value('a'))
                ->minimumShouldMatch(1);
        } else {
            $datesQuery = [];
        }
        if ($request->price) {
            $priceQuery = Query::range()->field('priceranges.price')->gte($request->price[0])->lte($request->price[1]);
        } else {
            $priceQuery = [];
        }
        if ($request->tag) {
            $tagQuery = Query::terms()->field('genres.name')->values($request->tag);
        } else {
            $tagQuery = [];
        }
        if ($request->category) {
            $categoryQuery = Query::terms()->field('category_id')->values($request->category);
        } else {
            $categoryQuery = null;
        }
        $categories = Category::where('remote', 1)->get();
        $tags = Genre::where('admin', 1)->orderBy('rank', 'desc')->get();

        $query = Query::bool()
            ->must( Query::range()->field('closingDate')->gte('now/d'))
            ->must( Query::term()->field('hasLocation')->value(false))
            ->when($request->price, function ($builder) use ($priceQuery) { return $builder->must($priceQuery); })
            ->when($request->category, function ($builder) use ($categoryQuery) { return $builder->filter($categoryQuery); })
            ->when($request->tag, function ($builder) use ($tagQuery) { return $builder->filter($tagQuery); })
            ->when($request->dates, function ($builder) use ($datesQuery) { return $builder->filter($datesQuery); });

        $builder = Event::searchQuery($query)
            ->load(['genres', 'category'])
            ->sortRaw(['published_at' => 'desc'])
            ->paginate(12);

        $content = tap($builder->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        $onlineevents = json_encode($content);

        return view('events.searchonline',compact('onlineevents', 'categories', 'tags'));
    }

    public function fetch(Request $request)
    {
        $request = Search::convertUrl($request);
         if ($request->price) {
            $priceQuery = Query::range()->field('priceranges.price')->gte($request->price[0])->lte($request->price[1]);
        } else {
            $priceQuery = [];
        }
        if ($request->tag) {
            $tagQuery = Query::terms()->field('genres.name')->values($request->tag);
        } else {
            $tagQuery = [];
        }
        if ($request->category) {
            $categoryQuery = Query::terms()->field('category_id')->values($request->category);
        } else {
            $categoryQuery = null;
        }
        if ($request->dates) {
            $datesQuery = Query::bool()
                ->should(Query::range()->field('shows.date')->gte($request->dates[0])->lte($request->dates[1]))
                ->should(Query::term()->field('showtype')->value('a'))
                ->minimumShouldMatch(1);
        } else {
            $datesQuery = [];
        }

        $query = Query::bool()
            ->must( Query::range()->field('closingDate')->gte('now/d'))
            ->must( Query::term()->field('hasLocation')->value(false))
            ->when($request->price, function ($builder) use ($priceQuery) { return $builder->must($priceQuery); })
            ->when($request->category, function ($builder) use ($categoryQuery) { return $builder->filter($categoryQuery); })
            ->when($request->tag, function ($builder) use ($tagQuery) { return $builder->filter($tagQuery); })
            ->when($request->dates, function ($builder) use ($datesQuery) { return $builder->filter($datesQuery); });

        $builder = Event::searchQuery($query)
            ->load(['genres', 'category'])
            ->sortRaw(['published_at' => 'desc'])
            ->paginate(12);

        $content = tap($builder->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return json_encode($content);
    }
}
