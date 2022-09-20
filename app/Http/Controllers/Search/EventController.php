<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Genre;
use App\Models\Category;
use App\Models\Date;
use App\Models\User;
use App\Models\Organizer;
use App\Models\CityList;
use App\Models\SearchData;
use App\Models\Search;
use App\Models\RemoteLocation;
use App\Models\Featured\Dock;
use DB;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Arr;
use Elastic\ScoutDriverPlus\Support\Query;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $datesFilter = [];
        $pricesFilter = [];
        $categoriesFilter = [];
        $tagsFilter = [];
        $latFilter = [];
        if ($request->category) { 
            $request->request->add(['category' => explode(",",$request->category)]); 
            $categoriesFilter = Query::terms()->field('category_id')->values($request->category);
        }
        if ($request->tag) { 
            $request->request->add(['tag' => explode(",",$request->tag)]); 
            $tagsFilter = Query::terms()->field('genres.name')->values($request->tag);
        }
        if ($request->price0) { 
            $request->request->add(['price' => [$request->price0,$request->price1 ]]); 
            $pricesFilter = Query::range()->field('priceranges.price')->gte($request->price[0])->lte($request->price[1]);
        }
        if ($request->start) { 
            $request->request->add(['dates' => [$request->start, $request->end]]); 
            $datesFilter = Query::bool()
                ->should(Query::range()->field('shows.date')->gte($request->dates[0])->lte($request->dates[1]))
                ->should(Query::term()->field('showtype')->value('a'))
                ->minimumShouldMatch(1);
        }
        if ($request->live) {
            $request->request->add([
                'mapboundary' => [
                    '_northEast' => [ 'lat' => $request->NElat,'lng' => $request->NElng ],
                    '_southWest' => [ 'lat' => $request->SWlat,'lng' => $request->SWlng ],
                    ]
                ]);
        }

        $maxprice = ceil(Event::getMostExpensive());
        $categories = Category::all();
        $tags = Genre::where('admin', 1)->orderBy('rank', 'desc')->get();
        $latFilter = Query::geoDistance()->field('location_latlon')->distance('40km')->lat($request->lat)->lon($request->lng);
        
        $query = Query::bool()
            ->must( Query::range()->field('closingDate')->gte('now/d'))
            ->must( Query::term()->field('hasLocation')->value(true))
            ->when($request->price, function ($builder) use ($pricesFilter) { return $builder->must($pricesFilter); })
            ->when($request->category, function ($builder) use ($categoriesFilter) { return $builder->filter($categoriesFilter); })
            ->when($request->tag, function ($builder) use ($tagsFilter) { return $builder->filter($tagsFilter); })
            ->when($request->lat, function ($builder) use ($latFilter) { return $builder->filter($latFilter); });

        $builder = Event::searchQuery($query)
            ->load(['genres', 'category'])
            ->sortRaw(['published_at' => 'desc'])
            ->paginate(20);

        $eventContent = tap($builder->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        $searchedevents = json_encode($eventContent);

        $docks = Dock::where('location', 'search')->with(['posts.limitedCards', 'shelves.publishedPosts.limitedCards', 'communities'])->orderBy('order', 'ASC')->get();

        return view('events.search',compact('searchedevents', 'docks', 'categories', 'maxprice', 'tags'));
    }

    public function mapBoundary(Request $request)
    {
        $prices = Query::range()->field('priceranges.price')->gte($request->price[0])->lte($request->price[1]);
        $categories = Query::terms()->field('category_id')->values($request->category);
        $lat = Query::geoDistance()->field('location_latlon')->distance('40km')->lat($request->lat)->lon($request->lng);
        $tags = Query::terms()->field('genres.name')->values($request->tag);
        if ($request->mapboundary) {
            $boundary = Query::bool()->filterRaw([
                'geo_bounding_box' => [
                    'location_latlon' => [
                        'top_right' => [
                            'lat' => $request->mapboundary['_northEast']['lat'],
                            'lon' => $request->mapboundary['_northEast']['lng'],
                        ],
                        'bottom_left' => [
                            'lat' => $request->mapboundary['_southWest']['lat'],
                            'lon' => $request->mapboundary['_southWest']['lng'],
                        ]
                    ]
                ]
            ]);
        } else {
            $boundary = [];
        }
        if ($request->dates) {
            $dates = Query::bool()
                ->should(Query::range()->field('shows.date')->gte($request->dates[0])->lte($request->dates[1]))
                ->should(Query::term()->field('showtype')->value('a'))
                ->minimumShouldMatch(1);
        } else {
            $dates = [];
        }

        $query = Query::bool()
            ->must( Query::range()->field('closingDate')->gte('now/d'))
            ->must( Query::term()->field('hasLocation')->value(true))
            ->when($request->price, function ($builder) use ($prices) { return $builder->must($prices); })
            ->when($request->category, function ($builder) use ($categories) { return $builder->filter($categories); })
            ->when($request->tag, function ($builder) use ($tags) { return $builder->filter($tags); })
            ->when($request->dates, function ($builder) use ($dates) { return $builder->filter($dates); })
            ->when($request->live, function ($builder) use ($boundary) { return $builder->filter($boundary); })
            ->when(!$request->live, function ($builder) use ($lat) { return $builder->filter($lat); });

        $builder = Event::searchQuery($query)
            ->load(['genres', 'category'])
            ->sortRaw(['published_at' => 'desc'])
            ->paginate(20);

        $eventContent = tap($builder->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return json_encode($eventContent);
    }

    public function allSearch(Request $request)
    {
        $request = Search::convertUrl($request);
        $categories = Category::all();
        $tags = Genre::where('admin', 1)->orderBy('rank', 'desc')->get();
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

        $allevents = json_encode($content);
        
        return view('events.searchall',compact('allevents', 'categories', 'tags'));
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

    public function online(Request $request)
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
