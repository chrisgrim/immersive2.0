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
        $hasLocationFilter = $datesFilter = $pricesFilter = $categoriesFilter = $tagsFilter = $latFilter = $boundaryFilter = [];
        $searchedCategories = $searchedTags = [];
        $searchedEvents = '';
        $maxprice = ceil(Event::getMostExpensive());
        $categories = Category::all();
        $tags = Genre::where('admin', 1)->orderBy('rank', 'desc')->get();

        if ($request->searchType === 'inPerson') { 
            $inPersonCategories = Category::where('remote', false)->get();
            $hasLocationFilter = Query::term()->field('hasLocation')->value(true);
        }
        if ($request->searchType === 'atHome') { 
            $atHomeCategories = Category::where('remote', true)->get();
            $hasLocationFilter = Query::term()->field('hasLocation')->value(false);
        }
        if ($request->lat) {
            $latFilter = Query::geoDistance()->field('location_latlon')->distance('40km')->lat($request->lat)->lon($request->lng);
        }
        if ($request->category) { 
            $request->request->add(['category' => explode(",",$request->category)]); 
            $searchedCategories = Category::find($request->category);
            $categoriesFilter = Query::terms()->field('category_id')->values($request->category);
        }
        if ($request->tag) { 
            $request->request->add(['tag' => explode(",",$request->tag)]); 
            $searchedTags = Genre::find($request->tag);
            $tagsFilter = Query::terms()->field('genres.genre_id')->values($request->tag);
        }
        if ($request->price0) { 
            $request->request->add(['price' => [$request->price0,$request->price1 ]]); 
            if ($request->price[1] === 100) { $top=9999 ;} else { $top=$request->price[1];}
            $pricesFilter = Query::range()->field('priceranges.price')->gte($request->price[0])->lte($top);
        }
        if ($request->start) { 
            $request->request->add(['dates' => [$request->start, $request->end]]); 
            $datesFilter = Query::bool()
                ->should(Query::range()->field('shows.date')->gte($request->dates[0])->lte($request->dates[1]))
                ->should(Query::term()->field('showtype')->value('a'))
                ->minimumShouldMatch(1);
        }
         if ($request->live) {
            $boundaryFilter = Query::bool()->filterRaw([
                'geo_bounding_box' => [
                    'location_latlon' => [
                        'top_right' => [
                            'lat' => $request->NElat,
                            'lon' => $request->NElng,
                        ],
                        'bottom_left' => [
                            'lat' => $request->SWlat,
                            'lon' => $request->SWlng,
                        ]
                    ]
                ]
            ]);
        }
        

        $query = Query::bool()
            ->filter( Query::range()->field('closingDate')->gte('now/d'))
            ->when( $request->searchType === 'atHome', function ($builder) use ($hasLocationFilter) { return $builder->filter($hasLocationFilter); })
            ->when( $request->searchType === 'inPerson', function ($builder) use ($hasLocationFilter) { return $builder->filter($hasLocationFilter); })
            ->when($request->price, function ($builder) use ($pricesFilter) { return $builder->filter($pricesFilter); })
            ->when($request->category, function ($builder) use ($categoriesFilter) { return $builder->filter($categoriesFilter); })
            ->when($request->start, function ($builder) use ($datesFilter) { return $builder->filter($datesFilter); })
            ->when($request->tag, function ($builder) use ($tagsFilter) { return $builder->filter($tagsFilter); })
            ->when($request->searchType === 'inPerson' && isset($request->live) && $request->live === 'false', function ($builder) use ($latFilter) { return $builder->filter($latFilter); })
            ->when($request->searchType === 'inPerson' && isset($request->live) && $request->live === 'true', function ($builder) use ($boundaryFilter) { return $builder->filter($boundaryFilter); });

        $builder = Event::searchQuery($query)
            ->load(['genres', 'category'])
            ->sortRaw(['published_at' => 'desc'])
            ->paginate(20);

        $searchedEvents = tap($builder->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        $docks = Dock::where('location', 'search')
            ->with(['posts.limitedCards', 'shelves.publishedPosts.limitedCards', 'communities'])
            ->orderBy('order', 'ASC')
            ->get();

        if ($request->searchType === 'inPerson' && isset($request->live)) {
            return view('events.search',compact('searchedEvents', 'docks', 'categories', 'maxprice', 'tags', 'inPersonCategories', 'searchedCategories', 'searchedTags'));
        }
        if ($request->searchType === 'atHome') {
            return view('events.searchonline',compact('searchedEvents', 'categories', 'tags', 'atHomeCategories', 'searchedCategories', 'searchedTags'));
        }
        return view('events.searchall',compact('searchedEvents', 'docks', 'categories', 'maxprice', 'tags', 'searchedCategories', 'searchedTags'));
    }

    public function fetch(Request $request)
    {
        $hasLocationFilter = [];
        $datesFilter = [];
        $pricesFilter = [];
        $categoriesFilter = [];
        $tagsFilter = [];
        $latFilter = [];
        $boundaryFilter = [];

        if ($request->searchType === 'inPerson') { 
            $hasLocationFilter = Query::term()->field('hasLocation')->value(true);
        }
        if ($request->searchType === 'atHome') { 
            $hasLocationFilter = Query::term()->field('hasLocation')->value(false);
        }
        if ($request->price) {
            if ($request->price[1] === 100) { $top=9999 ;} else { $top=$request->price[1];}
            $pricesFilter = Query::range()->field('priceranges.price')->gte($request->price[0])->lte($top);
        }
        if ($request->category) { 
            $categoriesFilter = Query::terms()->field('category_id')->values(array_column($request->category, 'id'));
        }
        if ($request->tag) { 
            $tagsFilter = Query::terms()->field('genres.genre_id')->values(array_column($request->tag, 'id'));
        }
        if ($request->location && $request->location['center']['lat']) {
            $latFilter = Query::geoDistance()->field('location_latlon')->distance('40km')->lat($request->location['center']['lat'])->lon($request->location['center']['lng']);
        }
        if ($request->location && $request->location['mapboundary']) {
            $boundaryFilter = Query::bool()->filterRaw([
                'geo_bounding_box' => [
                    'location_latlon' => [
                        'top_right' => [
                            'lat' => $request->location['mapboundary']['_northEast']['lat'],
                            'lon' => $request->location['mapboundary']['_northEast']['lng'],
                        ],
                        'bottom_left' => [
                            'lat' => $request->location['mapboundary']['_southWest']['lat'],
                            'lon' => $request->location['mapboundary']['_southWest']['lng'],
                        ]
                    ]
                ]
            ]);
        }
        if ($request->searchDates) {
            $datesFilter = Query::bool()
                ->should(Query::range()->field('shows.date')->gte($request->searchDates[0])->lte($request->searchDates[1]))
                ->should(Query::term()->field('showtype')->value('a'))
                ->minimumShouldMatch(1);
        } 

        $query = Query::bool()
            ->filter( Query::range()->field('closingDate')->gte('now/d'))
            ->when( $request->searchType === 'atHome', function ($builder) use ($hasLocationFilter) { return $builder->filter($hasLocationFilter); })
            ->when( $request->searchType === 'inPerson', function ($builder) use ($hasLocationFilter) { return $builder->filter($hasLocationFilter); })
            ->when($request->price, function ($builder) use ($pricesFilter) { return $builder->filter($pricesFilter); })
            ->when($request->category, function ($builder) use ($categoriesFilter) { return $builder->filter($categoriesFilter); })
            ->when($request->tag, function ($builder) use ($tagsFilter) { return $builder->filter($tagsFilter); })
            ->when($request->searchDates, function ($builder) use ($datesFilter) { return $builder->filter($datesFilter); })
            ->when($request->location && isset($request->location['live']) && $request->location['live'], function ($builder) use ($boundaryFilter) { return $builder->filter($boundaryFilter); })
            ->when($request->location && isset($request->location['live']) && !$request->location['live'], function ($builder) use ($latFilter) { return $builder->filter($latFilter); });

        $builder = Event::searchQuery($query)
            ->load(['genres', 'category'])
            ->sortRaw(['published_at' => 'desc'])
            ->paginate(20);

        $eventContent = tap($builder->toArray(), function (array &$content) {
            $content['data'] = Arr::pluck($content['data'], 'model');
        });

        return json_encode($eventContent);
    }

    public function fetchAll(Request $request)
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
