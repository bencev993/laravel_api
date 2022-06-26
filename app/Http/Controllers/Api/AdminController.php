<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Image;
use App\Models\Category;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateUserRequest;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::where('sold', '>', 0)->orderBy('sold', 'desc')->limit(5)->get();
        if(count($products) < 5) {
            $products = Product::inRandomOrder()->limit(5)->get();
        }
        return response()->json(['products' => $products], 200);
    }

    public function storeProduct(StoreProductRequest $request)
    {

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category  ? $request->category : null,
            'description' => $request->description,
            'discount' => $request->discount
        ]);

        if($request->images) {
            try {
                foreach($request->images as $image) {
                    $name = $image->getClientOriginalName();
                    $image->move('images', $name);
                    Image::create([
                    'image' => $name,
                    'product_id' => $product->id
                    ]);
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                return response()->json(['error' => $error], 500);
            }
        }

        return response()->json(['product' => $product], 200);
    }

    public function updateProduct(UpdateProductRequest $request)
    {
        try {
            if($request->images) {

                $i = 0;
                if($request->ids) {
                    $ids = $request->ids;

                    foreach($request->images as $image) {
                        $name = $image->getClientOriginalName();
                        $id = $ids[$i];

                        Image::where('id', $id)->update(['image' => $name]);
                        $image->move('images', $name);

                        $i ++;
                    }
                } else {
                    foreach($request->images as $image) {
                        $name = $image->getClientOriginalName();

                        Image::create([
                            'image' => $name,
                            'product_id' => $request->id
                        ]);

                        $image->move('images', $name);
                        $i ++;
                    }
                }

            }

            $input = [];

            if($request->name) {
                $input['name'] = $request->name;
            }

            if($request->price) {
                $input['price'] = $request->price;
            }

            if($request->stock) {
                $input['stock'] = $request->stock;
            }

            if($request->category) {
                $input['category_id'] = $request->category;
            }

            if($request->description) {
                $input['description'] = $request->description;
            }

            if($request->discount) {
                $input['discount'] = $request->discount;
            }

            $id = $request->id;
            $product = Product::where('id', $id);
            $product->update($input);


        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->json([null], 500);
        }

        return response()->json(['product' => $product], 200);
    }

    public function activateProduct(Request $request)
    {
        if($request->id) {
            $request = $this->validate($request, [
                'id' => 'required|integer'
            ]);

            $product = Product::find($request['id']);
            if($product->is_active == 0) {
                $product->update(['is_active' => 1]);
            } else {
                $product->update(['is_active' => 0]);
            }

            return response()->json(['product' => $product], 200);
        }

        return response()->json([], 500);
    }

    public function deleteProduct(Request $request)
    {
        if($request->id) {
            $request = $this->validate($request, [
                'id' => 'required|integer'
            ]);

            $product = Product::find($request['id']);
            if($product) {
                $product->delete();
            }

            return response()->json(['product' => $product], 200);
        }

        return response()->json([], 500);
    }

    public function storeCategory(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create([
                'category_name' => $request->name,
                'parent_id' => $request->parent_id
            ]);
        } catch (Exception $e) {
            return response()->json([], 500);
        }

        return response()->json(['category' => $category], 200);
    }

    public function destroyCategory(Request $request)
    {
        $request = $this->validate($request, [
            'id' => 'required|integer'
        ]);

        try {
            $category = Category::find($request['id']);

            if($category->parent_id == null) {
                $categories = Category::where('parent_id', '=', $category->id)->get();
                foreach($categories as $item) {
                    $item->delete();
                }
            }

            $category->delete();

            $categories = Category::categories();

            return response()->json(['categories' => $categories], 200);
        } catch (Exception $e) {
            return response()->json([], 500);
        }

    }

    public function updateUser(UpdateUserRequest $request)
    {
        try {
            User::where('is_admin', 1)->update(['password' => bcrypt($request->password)]);
            return response()->json(null, 200);
        } catch(Exception $e) {
            return response()->json([], 500);
        }
        
    }

    public function getOrder($id) {
        $order = Order::find($id);
        $cartItems = unserialize($order->cart);
        return response()->json(['order' => $order, 'cartItems' => $cartItems], 200);
    }

    public function getOrders() {
        $orders = OrderResource::collection(Order::paginate(20));

        if($orders) {
           return response($orders); 
        }

        return response()->json([], 500);
    }

    public function updateOrderStatus(Request $request) {
        try {
            $request = $this->validate($request, [
                'id' => 'required|integer',
                'status' => 'nullable|string|min:5|max:10'
            ]);

            if($request['status'] == null) {
                Order::find($request['id'])->delete();
            } else {
                Order::where('id', $request['id'])->update(['status' => $request['status']]);
            }

            return response()->json(['Success'], 200);

        } catch(Exception $e) {
            return response()->json([], 500);
        }
    }
}
