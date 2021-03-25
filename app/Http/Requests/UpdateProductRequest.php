<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:30',
            'price' => 'nullable|min:1|between:1,9999.99',
            'discount' => 'nullable|integer',
            'stock' => 'nullable|integer',
            'category' => 'nullable|integer',
            'description' => 'nullable|string|min:10|max:2000',
            'images' => 'nullable'
        ];
    }
}
