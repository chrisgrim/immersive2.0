<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\MakeImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

class CategoryController extends Controller
{
    /**
     * Checks for admin before allowing controller access
     *
     * @return \Illuminate\Http\__construct
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
        $this->middleware('moderator')->except('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            'remote' => Category::all()->where('remote', 1),
            'location' => Category::all()->where('remote', 0),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('adminArea.categories');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = Category::create($request->except(['image']));
        $category->update(['slug'=> Str::slug($request->name)]);
        MakeImage::saveImage($request, $category, 600, 600, 'category');
        return [
            'remote' => Category::all()->where('remote', 1),
            'location' => Category::all()->where('remote', 0),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category->load(['events' => function ($query) {
            $query->where('status', 'p')->with('organizer');
        }]);
        return view('categories.show',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->updateElements($request, $category);
        return [
            'remote' => Category::all()->where('remote', 1),
            'location' => Category::all()->where('remote', 0),
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->deleteCategory($category);
        return [
            'remote' => Category::all()->where('remote', 1),
            'location' => Category::all()->where('remote', 0),
        ];
    }
}
