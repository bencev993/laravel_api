<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{

    public function index() {
        $parent_categories = Category::whereNull('parent_id')->get();
        $categories = [];

        foreach ($parent_categories as $parent) {
            $array['parent_id'] = $parent->id;
            $array['parent_name'] = $parent->category_name;
            $array['subcategories'] = $parent->subcategory->all();
            array_push($categories, $array);
        }

        return response()->json(['categories' => $categories], 200);
    }
}
