<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|min:3|string',
            'parent_id' => 'integer|nullable'
        ];
    }
}
