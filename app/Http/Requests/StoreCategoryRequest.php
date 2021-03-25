<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|min:3|string|unique:categories,category_name',
            'parent_id' => 'integer|nullable'
        ];
    }
}
