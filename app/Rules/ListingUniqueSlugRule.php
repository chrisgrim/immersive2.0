<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
Use App\Models\Curated\Listing;

class ListingUniqueSlugRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($name, $id, $community)
    {
        $this->name = $name;
        $this->id = $id;
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
        if (Listing::where('slug', '=', Str::slug($this->name . '-' . $this->community->id))->exists()) {
            return false;
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
        return "You can't have multiple lists with the same name per community";
    }
}
