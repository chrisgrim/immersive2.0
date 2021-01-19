<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
Use App\Models\Organizer;

class OrganizerUniqueSlugRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($name, $id)
    {
        $this->name = $name;
        $this->id = $id;
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

        $organizer = Organizer::where('slug', '=', Str::slug($this->name))->first();

        if (Organizer::where('slug', '=', Str::slug($this->name))->exists()) {
            if ( (int)$this->id === (int)$organizer->id ) {
                return true;
            }
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
        return 'name must be unique';
    }
}
