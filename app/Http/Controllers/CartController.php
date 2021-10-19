<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class CartController extends Controller
{
    public function productActions(Request $request) {
        $data = $request->all();
        $order_no = 'FUM-' . date('ymd') . random_int(9999, 100000);
        if (isset($data['addtocart']) && $data['addtocart']) {
            if (!session()->get('fumOrderNo')) {
                session()->put('fumOrderNo', $order_no);
            }

            return $this->addToCart($data);
        }

        if (isset($data['addtowishlist']) && $data['addtowishlist']) {
            return $this->addToWishlist($data);
        }

        if (isset($data['buynow']) && $data['buynow']) {
            if (!session()->get('fumOrderNo')) {
                session()->put('fumOrderNo', $order_no);
            }
            
            if(Auth::check()){
				$user_id = DB::table('fumaco_users')->where('username', Auth::user()->username)->first();
                $bill_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Billing')->count();
				$ship_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Delivery')->count();

                $product_details = DB::table('fumaco_items')->where('f_idcode', $data['item_code'])->first();
                if (!$product_details) {
                    return redirect()->back()->with('error', 'Product not found.');
                }
                $cart = [
                    $data['item_code'] => [
                        "item_code" => $product_details->f_idcode,
                        "quantity" => $data['quantity'],
                        "price" => ($product_details->f_price > 0) ? $product_details->f_price : $product_details->f_original_price,
                    ]
                ];
     
                session()->put('fumCart', $cart);

                if($bill_address > 0 and $ship_address > 0){
                   
                    $action = '/setdetails';
                }else if($ship_address < 1){
                    $action = '/checkout/billing';
                }else if($bill_address < 1){
                    $action = '/checkout/set_billing_form';
                }else{
                    $action = '/checkout/billing';
                }

                return redirect($action);
            }
            return redirect('/checkout/billing');
        }

        return redirect('/');
    }

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

    public function setShippingBillingDetails(Request $request) {

        if(Auth::check()) {
            $user_id = Auth::user()->id;
            $user = DB::table('fumaco_users')->where('id', $user_id)->first();

            $shipping_address = DB::table('fumaco_user_add')->where('xdefault', 1)
                ->where('user_idx', $user_id)->where('address_class', 'Delivery')->first();
            
            $shipping_details = [
                'fname' => $shipping_address->xcontactname1,
                'lname' => $shipping_address->xcontactlastname1,
                'address_line1' => $shipping_address->xadd1,
                'address_line2' => $shipping_address->xadd2,
                'province' => $shipping_address->xprov,
                'city' => $shipping_address->xcity,
                'brgy' => $shipping_address->xbrgy,
                'postal_code' => $shipping_address->xpostal,
                'country' => $shipping_address->xcountry,
                'address_type' => $shipping_address->add_type,
                'email_address' => $shipping_address->xcontactemail1,
                'mobile_no' => $shipping_address->xmobile_number,
                'contact_no' => $shipping_address->xcontactnumber1,
                'same_as_billing' => 0
            ];

            $billing_address = DB::table('fumaco_user_add')->where('xdefault', 1)
                ->where('user_idx', $user_id)->where('address_class', 'Billing')->first();
            if ($billing_address) {
                $billing_details = [
                    'fname' => $shipping_address->xcontactname1,
                    'lname' => $shipping_address->xcontactlastname1,
                    'address_line1' => $shipping_address->xadd1,
                    'address_line2' => $shipping_address->xadd2,
                    'province' => $shipping_address->xprov,
                    'city' => $shipping_address->xcity,
                    'brgy' => $shipping_address->xbrgy,
                    'postal_code' => $shipping_address->xpostal,
                    'country' => $shipping_address->xcountry,
                    'address_type' => $shipping_address->add_type,
                    'email_address' => $shipping_address->xcontactemail1,
                    'mobile_no' => $shipping_address->xmobile_number,
                    'contact_no' => $shipping_address->xcontactnumber1,
                    'same_as_billing' => 0
                ];

                session()->put('fumBillDet', $billing_details);
            } else {
                session()->forget('fumBillDet');
            }
            
            session()->put('fumShipDet', $shipping_details);
        }
        
        if($request->isMethod('POST')) {
            $shipping_details = [
                'fname' => $request->fname,
                'lname' => $request->lname,
                'address_line1' => $request->ship_Address1_1,
                'address_line2' => $request->ship_Address2_1,
                'province' => $request->ship_province1_1,
                'city' => $request->ship_City_Municipality1_1,
                'brgy' => $request->ship_Barangay1_1,
                'postal_code' => $request->ship_postal1_1,
                'country' => $request->ship_country_region1_1,
                'address_type' => $request->ship_Address_type1_1,
                'email_address' => $request->ship_email,
                'mobile_no' => $request->ship_mobilenumber1_1,
                'contact_no' => $request->contactnumber1_1,
                'same_as_billing' => ($request->same_as_billing) ? 1 : 0
            ];
    
            if(!$request->same_as_billing) {
                $billing_details = [
                    'fname' => $request->bill_fname,
                    'lname' => $request->bill_lname,
                    'address_line1' => $request->Address1_1,
                    'address_line2' => $request->Address2_1,
                    'province' => $request->province1_1,
                    'city' => $request->City_Municipality1_1,
                    'brgy' => $request->Barangay1_1,
                    'postal_code' => $request->postal1_1,
                    'country' => $request->country_region1_1,
                    'address_type' => $request->Address_type1_1,
                    'email_address' => $request->email,
                    'mobile_no' => $request->mobilenumber1_1,
                ];
    
                session()->put('fumBillDet', $billing_details);
            } else {
                session()->forget('fumBillDet');
            }

            session()->put('fumShipDet', $shipping_details);
        }
    
        return redirect('/checkout/summary');
    }
}
