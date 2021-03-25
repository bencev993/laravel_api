<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'city',
        'zipcode',
        'country',
        'email',
        'phone',
        'cart',
        'payment_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

}
