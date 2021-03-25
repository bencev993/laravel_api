<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'category_name',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id'); // get parent category
    }

    public function subcategory()
    {
        return $this->hasMany('App\Models\Category', 'parent_id'); //get all subs. NOT RECURSIVE
    }

}
