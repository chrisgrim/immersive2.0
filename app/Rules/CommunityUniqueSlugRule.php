<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
Use App\Models\Curated\Community;

class CommunityUniqueSlugRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($name, $community)
    {
        $this->name = $name;
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
        if (Community::where('slug', '=', Str::slug($this->name))->exists()) {
            if (!$this->community) { return false; }
            if (Community::where('slug', '=', Str::slug($this->name))->first()->id === $this->community->id) {
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
        return 'Every Community must have a unique name';
    }
}
