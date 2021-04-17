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
        return response(ProductResource::collection(Product::paginate(20)));
    }

    public function search(Request $request)
    {
        if($request->q) {
            $request = $this->validate($request, [
                'q' => 'required|string|min:3|max:30'
            ]);

            $products = Product::query()
            ->where('name', 'like', '%' . $request->q . '%')
            ->orWhere('description', 'like', '%' . $request->q . '%')
            ->orderBy('id', 'desc')->paginate(20);

            if($products->count() > 0) {
                return response()->json(['products' => $products], 200);
            }
        }

        return response()->json([null], 500);
    }

    public function show($id)
    {
        $product = Product::find($id);
        $images = $product->images;

        return response()->json(['product' => $product, 'images' => $images], 200);
    }

}
