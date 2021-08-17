<?php

namespace App\Http\Controllers\Featured;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Featured\Section;
use App\Models\Featured\Feature;
use Illuminate\Support\Facades\Log;

class FeaturedController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
    }

    /**
     * Update the order for sections
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request, Section $section)
    {
        foreach ($request->all() as $list) {
            $feature = Feature::find($list['id']);
            $feature->sections()->updateExistingPivot($section->id, ['order' => $list['pivot']['order']]);
        }
    }

}
