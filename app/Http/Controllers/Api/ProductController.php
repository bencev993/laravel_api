<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(ProductResource::collection(Product::all()));
    }

    public function show($id)
    {
        $product = Product::find($id);
        $images = $product->images;

        return response(['product' => $product, 'images' => $images], 200);
    }

}
