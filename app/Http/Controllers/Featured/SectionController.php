<?php

namespace App\Http\Controllers\Featured;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Curated\Community;
use App\Models\Featured\Section;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
    }

    /**
     * update an existing model
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        $section->update(['name' => $request->name]);
        return $section;
    }

    /**
     * Destroys an existing section
     *
     * @param  array  $input
     * @return \App\Models\Curated\Listing
     */
    public function destroy(Section $section)
    {
        if (count($section->featured)) { return; }
        $section->delete();
    }

    /**
     * Update the order for sections
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request, Community $community)
    {
        foreach ($request->all() as $list) {
            Section::find($list['id'])->update([
                'order' => $list['order'],
            ]);
        }
    }

    /**
     * Store a section for a community
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCommunity(Request $request, Community $community)
    {
        $community->sections()->create(['user_id' => auth()->id()]);
        return $community->sections()->with('featured')->get();;
    }

}
