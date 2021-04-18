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

    // Get all categories with their subcategories
    public static function categories() {
        $parent_categories = Category::whereNull('parent_id')->get();
        $categories = [];

        foreach ($parent_categories as $parent) {
            $array['parent_id'] = $parent->id;
            $array['parent_name'] = $parent->category_name;
            $array['subcategories'] = $parent->subcategory->all();
            array_push($categories, $array);
        }

        return $categories;
    }

}
