<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function rules()
    {

        // Charge with stripe and save order to db
        Stripe::setApiKey('sk_test_51HHWrwKg112ZJOxi1vzTpYho8tHcffJotwlTGz0RYxu55fpD6bxPCdwq7uTJs0cOlqp913sl7Nq47UpM66M5SWAM00nTsiIIF3');

        return [
            'name' => 'required|min:5|max:30|string',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits_between:9,30'
            'address' => 'required|min:5|max:30|string',
            'city' => 'required|min:3|max:30|string',
            'zip' => 'required|numeric|digits_between:1,5',
            'country' => 'required|min:2|max:30|string',
            'total' => 'required'
        ];
    }
}
