<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CartController extends Controller
{
    public function addToCart(Request $request) {

        $id = $request->item_code;

        $product_details = DB::table('fumaco_items')->where('f_idcode', $id)->first();
        if (!$product_details) {
            return 'Item not found.';
        }
        // if cart is empty then this the first product
        $cart = session()->get('fumCart');
        if(!$cart) {
 
            $cart = [
                    $id => [
                        "item_code" => $product_details->f_idcode,
                        "quantity" => $request->quantity,
                        "price" => $product_details->f_price,
                    ]
            ];
 
            session()->put('fumCart', $cart);

            return redirect()->back()->with('success', 'Product added to your cart!');
        }
        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;

            session()->put('fumCart', $cart);

            return redirect()->back()->with('success', 'Product added to your cart!');
        }
        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "item_code" => $product_details->f_idcode,
            "quantity" => 1,
            "price" => $product_details->f_price,
        ];

        session()->put('fumCart', $cart);

        return redirect()->back()->with('success', 'Product added to your cart!');
    }

    public function viewCart() {
        $cart = session()->get('fumCart');
        $cart = (!$cart) ? [] : $cart;

        $cart_items = DB::table('fumaco_items')
            ->whereIn('f_idcode', array_column($cart, 'item_code'))->get();
        
        $cart_arr = [];
        foreach ($cart_items as $n => $item) {
            $item_image = DB::table('fumaco_items_image_v1')
                ->where('idcode', $item->f_idcode)->first();

            $cart_arr[] = [
                'item_code' => $item->f_idcode,
                'item_description' => $item->f_name_name,
                'price' => $item->f_price,
                'amount' => ($item->f_price * $cart[$item->f_idcode]['quantity']),
                'quantity' => $cart[$item->f_idcode]['quantity'],
                'item_image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg'
            ];
        }

        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();

        return view('frontend.cart', compact('website_settings', 'item_categories', 'cart_arr'));
    }

    public function updateCart(Request $request) {
        $id = $request->id;
        if($id && $request->quantity) {
            $cart = session()->get('fumCart');

            if($request->type == 'increment') {
                $cart[$id]["quantity"]++;
            } else {
                $cart[$id]["quantity"]--;
            }

            session()->put('fumCart', $cart);

            return response()->json(['status' => 1, 'message' => 'Cart updated!']);
        }
    }

    public function removeFromCart(Request $request) {
        if($request->id) {
            $cart = session()->get('fumCart');
            if(isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('fumCart', $cart);
            }

            return response()->json(['status' => 1, 'message' => 'Cart updated!']);
        }
    }
}
