<?php

namespace App\Http\Controllers\Create;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Checks for admin before allowing controller access
     *
     * @return \Illuminate\Http\__construct
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('can:update,event');
    }

    /**
     * Displays the User Event Creation Category Page
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        if ($event->checkEventStatus(2)) return back();
        $categories = Category::all();
        $event->load('category');
        return view('create.category', compact('event','categories'));
    }

    /**
    * Fetches the categories list
    *
    * @return \Illuminate\Http\Response
    */
    public function fetch(Event $event)
    {
        return $event->category()->first();
    }

    /**
    * Updated the Event with the selected category ID
    *
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Event $event)
    {
        $event->update([ 'category_id' => request('id') ]);
        $event->updateEventStatus(3, $request);
    }
}
