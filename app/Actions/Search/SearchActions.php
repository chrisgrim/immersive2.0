<?php

namespace App\Actions\Search;

use Illuminate\Http\Request;
use App\Models\Curated\Shelf;
use App\Models\Curated\Community;
use Illuminate\Support\Str;
use Elastic\ScoutDriverPlus\Support\Query;

class SearchActions
{

    /**
     * Create helper for name search.
     */
    public function nameSearch(Request $request)
    {
        if ($request->keywords) {
            return Query::multiMatch()
                ->fields(['name', 'name._2gram','name._3gram'])
                ->type('bool_prefix')
                ->query($request->keywords);
        } else {
            return Query::matchAll();
        }
    }

    public function eventSearch(Request $request)
    {

    }
    
}