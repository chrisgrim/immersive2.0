<?php

namespace App\Actions\Curated;

use Illuminate\Http\Request;
use App\Models\ImageFile;
use App\Models\Curated\Card;
use App\Models\Curated\Post;
use App\Models\Curated\Community;
use App\Models\Featured\Feature;
use Illuminate\Support\Str;

class PostActions
{

    /**
     * Create a newly registered post.
     *
     * @param  array  $input
     * @return \App\Models\Curated\post
     */
    public function create(Request $request, Community $community)
    {
        $post = $community->posts()->create([
            'blurb' => $request->blurb,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $community->id,
            'user_id' => auth()->user()->id,
            'shelf_id' => $request->shelf_id,
        ]);

        if ($request->image) { ImageFile::saveImage($request, $post, 900, 500, 'post'); }

        return $post;
    }

    /**
     * Updates an existing post
     *
     * @param  array  $input
     * @return \App\Models\Curated\Post
     */
    public function update(Request $request, Post $post)
    {
        $post->update($request->except(['image']));
        $post->update(['slug' => Str::slug($request->name) . '-' . $post->community->id]);

        if ($request->image) { ImageFile::replaceImage($request, $post, 900, 500, 'post'); }

        if ($request->deleteImage) { 
            if ($post->image_type === 'u') {
                if (!Str::contains($post->thumbImagePath, 'event-images')) { 
                    ImageFile::deletePreviousImages($post); 
                }
            }
            ImageFile::clearImagePaths($post); 
        }
        
        return $post->load('cards', 'user');
    }

    /**
     * Destroys an existing post
     *
     * @param  array  $input
     * @return \App\Models\Curated\Post
     */
    public function destroy(Post $post)
    {
        ImageFile::deletePreviousImages($post);
        $post->delete();
        return $post->shelf->load('postsWithCards');
    }

    /**
     * Re orders the posts
     *
     * @param  array  $input
     * @return \App\Models\Curated\Post
     */
    public function reorder(Request $request)
    {
        foreach ($request->all() as $list) {
            Post::find($list['id'])->update([
                'order' => $list['order'],
            ]);
        }
    }

}
