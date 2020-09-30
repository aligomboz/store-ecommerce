<?php

namespace App\Http\Requests;

use App\Http\Enumeration\CategoryType;
use Illuminate\Foundation\Http\FormRequest;

class MainCategoryRequest extends FormRequest
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
        /*
        $rules = [
        'name' => 'required',
        'slug' => 'required|unique:categories,slug,'.$this->id,
        ];
        foreach(CategoryType::getAll() as $key => $val)
        {
        $rules[$key] = 'required|in:'.$val;
        }
        return $rules;
        */
        return [
            'name' => 'required',
            'type' => 'required|in:'.CategoryType::MainCategory.','.CategoryType::SupCategory,
            'slug' => 'required|unique:categories,slug,'.$this->id,
        ];
    }
}
