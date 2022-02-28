<?php

namespace App\Http\Controllers\Create;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $event->load('shows');
        $tags = Genre::where('admin', true)->orWhere('user_id', auth()->user()->id)->get();
        $categories = Category::all();
        return view('create.category', compact('event','categories', 'tags'));
    }

    /**
    * Fetches the categories list
    *
    * @return \Illuminate\Http\Response
    */
    public function fetch(Event $event)
    {
        return [
            'category' => $event->category()->first(),
            'genres' => $event->genres()->get(),
        ];
    }

    /**
    * Updated the Event with the selected category ID
    *
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Event $event)
    {
        $event->storeGenres($request, $event);
        $event->update([ 'category_id' => $request->category['id'] ]);
        $event->updateEventStatus(3, $request);
    }
}
