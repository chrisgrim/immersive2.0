<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
Use App\Models\Curated\Post;
use Illuminate\Support\Facades\Log;

class PostUniqueSlugRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($name, $post, $community)
    {
        $this->name = $name;
        $this->post = $post;
        $this->community = $community;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (Post::where('slug', '=', Str::slug($this->name . '-' . $this->community->id))->exists()) {
            if (!$this->post) { return false; }
            if (Post::where('slug', '=', Str::slug($this->name . '-' . $this->community->id))->first()->id === $this->post->id) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Post names must be unique per community";
    }
}
