<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\SearchData;
use Illuminate\Http\Request;

class SearchDataController extends Controller
{
    public function create(Request $request)
    {
        return $categorySearches = SearchData::where('search_type', $request->type)
            ->selectRaw('count(*) as count, search_term as name') 
            ->groupBy('search_term') 
            ->orderByRaw('count(*) desc')
            ->get();

        return SearchData::where('search_type', $request->type)->limit(40)->get();
    }

    public function store(Request $request)
    {
        SearchData::store($request);
    }

}
