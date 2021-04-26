<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PaymentRequest;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    public function postPayment(PaymentRequest $request)
    {

        // Charge with stripe and save order to db
        Stripe::setApiKey('sk_test_51HHWrwKg112ZJOxi1vzTpYho8tHcffJotwlTGz0RYxu55fpD6bxPCdwq7uTJs0cOlqp913sl7Nq47UpM66M5SWAM00nTsiIIF3');

        $input = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'zipcode' => $request->zip,
            'country' => $request->country
        ];

        try {
            $charge = Charge::create([
                'amount' => $request->total * 100,
                'currency' => 'usd',
                'description' => 'Example charge',
                'source' => $request->stripeToken,
            ]);

            $order = Order::create([
                'user_id' => Auth::id() || null,
                'name' => $input['name'],
                'address' => $input['address'],
                'city' => $input['city'],
                'zipcode' => $input['zipcode'],
                'country' => $input['country'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'cart' => serialize($request->items),
                'payment_id' => $charge->id
            ]);

            !Auth::user() ? $order->save() : Auth::user()->orders()->save($order);

        } catch(Exception $e) {
            $error = $e->getMessage();
            return response()->json(['error' => $error, 'request' => $request], 500);
        }

        // Mail::to($input['email'])->send(new OrderUnderProcess($input['name'], $cart));

        // Count how many times product sold
        // while(count($request->items) != 0) {
        //     $soldqty = array_values($cart->items)[0]['quantity'];
        //     $soldid = array_values($cart->items)[0]['item']->id;
        //     $product = Product::find($soldid);
        //     $product->sold = $product->sold + $soldqty;
        //     $product->save();
        //
        //     \array_splice($cart->items, 0, 1);
        // }


        // return redirect()->route('product.index')->with('success', 'Your order created successfully. Thank you for choosing us!');
        return response()->json(null, 200);
    }
}
