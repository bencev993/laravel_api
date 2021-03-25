<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|min:5|max:30|string|unique:products,name',
            'price' => 'required|min:1|between:1,9999.99',
            'discount' => 'integer|nullable',
            'stock' => 'required|integer',
            'category' => 'integer|nullable',
            'description' => 'required|string|min:10|max:2000',
            'images' => 'nullable'
        ];
    }
}
