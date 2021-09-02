<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => "required|unique:categories,name",
            'description' => 'required|string|min:1|max:40000',
            'rank' => 'numeric',
            'image' => 'required|dimensions:min_width=800,min_height=800'
        ];
    }
}
