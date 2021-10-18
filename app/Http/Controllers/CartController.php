<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class CartController extends Controller
{
    public function productActions(Request $request) {
        $data = $request->all();

        if (isset($data['addtocart']) && $data['addtocart']) {
            return $this->addToCart($data);
        }

        if (isset($data['addtowishlist']) && $data['addtowishlist']) {
            return $this->addToWishlist($data);
        }

        if (isset($data['buynow']) && $data['buynow']) {
            // return $this->buyNow($data);
            if(Auth::check()){
				$user_id = DB::table('fumaco_users')->where('username', Auth::user()->username)->first();
                $bill_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Billing')->count();
				$ship_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Delivery')->count();

                if($bill_address > 0 and $ship_address > 0){
                    $action = '/checkout/summary/'.$data['item_code']."/".$data['quantity'];
                }else if($ship_address < 1){
                    $action = '/checkout/billing/'.$data['item_code']."/".$data['quantity'];
                }else if($bill_address < 1){
                    $action = '/checkout/set_billing_form/'.$data['item_code']."/".$data['quantity'];
                }else{
                    $action = '/checkout/billing/'.$data['item_code']."/".$data['quantity'];
                }

                return redirect($action);
            }
            return redirect('/checkout/billing/'.$data['item_code']."/".$data['quantity']);
            // return redirect('/buy_now');
        }

        return redirect('/');
    }

    // public function buyNow($data){
    //     dd($data);
    // }

    public function addToCart($data) {
        $id = $data['item_code'];

        $product_details = DB::table('fumaco_items')->where('f_idcode', $id)->first();
        if (!$product_details) {
            return redirect()->back()->with('error', 'Product not found.');
        }
        // if cart is empty then this the first product
        $cart = session()->get('fumCart');
        if(!$cart) {
            $cart = [
                $id => [
                    "item_code" => $product_details->f_idcode,
                    "quantity" => $data['quantity'],
                    "price" => ($product_details->f_price > 0) ? $product_details->f_price : $product_details->f_original_price,
                ]
            ];
 
            session()->put('fumCart', $cart);

            if (isset($data['buynow']) && $data['buynow']) {
                return redirect('/checkout/summary');
            }

            return redirect()->back()->with('success', 'Product added to your cart!');
        }
        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;

            session()->put('fumCart', $cart);

            if (isset($data['buynow']) && $data['buynow']) {
                return redirect('/cart');
            }

            return redirect()->back()->with('success', 'Product added to your cart!');
        }
        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "item_code" => $product_details->f_idcode,
            "quantity" => 1,
            "price" => ($product_details->f_price > 0) ? $product_details->f_price : $product_details->f_original_price,
        ];

        session()->put('fumCart', $cart);

        if (isset($data['buynow']) && $data['buynow']) {
            return redirect('/cart');
        }

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

            $price = ($item->f_price > 0) ? $item->f_price : $item->f_original_price;

            $cart_arr[] = [
                'item_code' => $item->f_idcode,
                'item_description' => $item->f_name_name,
                'price' => $price,
                'amount' => ($price * $cart[$item->f_idcode]['quantity']),
                'quantity' => $cart[$item->f_idcode]['quantity'],
                'stock_qty' => $item->f_qty,
                'item_image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg'
            ];
        }
        // dd($cart);
        $bill_address = "";
		$ship_address = "";
		if(Auth::check()){
            request()->session()->put('order_no', 'FUM-'.random_int(10000000, 99999999));
			$user_id = DB::table('fumaco_users')->where('username', Auth::user()->username)->first();

			$bill_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Billing')->count();

			$ship_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Delivery')->count();
		}

        return view('frontend.cart', compact('cart_arr', 'bill_address', 'ship_address'));
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

    // add shipping details in cart sessions
    public function addShippingDetails(Request $request) {
        $cart = session()->get('fumCart');

        $cart['shipping'] = [
            "shipping_name" => $request->shipping_name,
            "shipping_fee" => $request->shipping_fee,
        ];

        session()->put('fumCart', $cart);

        return response()->json(['status' => 1, 'message' => 'Cart updated!']);
    }

    public function addToWishlist($data) {
        DB::beginTransaction();
        try {
            if (!Auth::check()) {
                return redirect('/login');
            }

            $id = $data['item_code'];

            $product_details = DB::table('fumaco_items')->where('f_idcode', $id)->first();
            if (!$product_details) {
                return redirect()->back()->with('error', 'Product not found.');
            }

            // check if item is in the list of existing wishlist
            $existing_wishlist = DB::table('datawishlist')->where('userid', Auth::user()->id)
                ->where('item_code', $id)->exists();
            // add item to wishlist if npt existing
            if(!$existing_wishlist) {
                DB::table('datawishlist')->insert(
                    [
                        'userid' => Auth::user()->id,
                        'item_code' => $id,
                        'item_name' => $product_details->f_name_name,
                        'item_price' => ($product_details->f_price > 0) ? $product_details->f_price : $product_details->f_original_price
                    ]
                );
    
                DB::commit();
            }

            return redirect()->back()->with('success', 'Product added to your wishlist!');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.'); 
        }
    }

    public function countCartItems() {
        $session_cart = session()->get('fumCart');

        $count = 0;
        if (isset($session_cart)) {
            $count = count($session_cart);
        }

        unset($session_cart['shipping']);

        if (Auth::check()) {
            $count += DB::table('fumaco_cart')->where('f_account_id', Auth::user()->id)->count();
        }

        return $count;
    }

    public function countWishlist(){
        if(Auth::check()){
            $wishlist = DB::table('datawishlist')->where('userid', Auth::user()->id)->count();

            return $wishlist;
        }else{
            $wishlist = 0;
            return $wishlist;
        }
    }
}
