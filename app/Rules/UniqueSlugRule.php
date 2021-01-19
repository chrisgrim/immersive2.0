<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;
Use App\Models\Genre;

class UniqueSlugRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
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
        $genre = Genre::where('slug', '=', Str::slug($value))->first();
        if (Genre::where('slug', '=', Str::slug($value))->exists()) {
            if ($this->id === $genre->id) return true;
           return false;
        }
        return true;
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
