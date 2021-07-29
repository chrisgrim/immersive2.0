<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
Use App\Models\Curated\Listing;
use Illuminate\Support\Facades\Log;

class ListingUniqueSlugRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($name, $listing, $community)
    {
        $this->name = $name;
        $this->listing = $listing;
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
            if (!$this->listing) { return false; }
            if (Listing::where('slug', '=', Str::slug($this->name . '-' . $this->community->id))->first()->id === $this->listing->id) {
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
