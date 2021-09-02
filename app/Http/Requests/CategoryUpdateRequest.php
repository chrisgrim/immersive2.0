<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
        if ($this->category && !$this->image) {
            return [
                'name' => "required|unique:categories,name,{$this->category->id}",
                'description' => 'required|string|min:1|max:40000',
                'rank' => 'numeric'
            ];
        }
        if ($this->image && $this->name) {
            return [
                'name' => "required|unique:categories,name",
                'description' => 'required|string|min:1|max:40000',
                'rank' => 'numeric',
                'image' => 'required|dimensions:min_width=800,min_height=800'
            ];
        }
        return [
        ];
    }
}
