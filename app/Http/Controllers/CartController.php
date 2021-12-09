<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;

class CartController extends Controller
{
    public function productActions(Request $request) {
        $data = $request->all();
        $data['is_ajax'] = ($request->ajax());

        $order_no = 'FUM-' . date('yd') . random_int(0, 9999);

        if(isset($data['reorder'])){
            session()->forget('fumCart');
            session()->forget('fumOrderNo');
            session()->put('fumOrderNo', $order_no);
            
            $items = DB::table('fumaco_order_items')->where('order_number', $request->order_number)->get();
            $cart = [];
            foreach($items as $item){
                $cart[$item->item_code] = [
                    "item_code" => $item->item_code,
                    "quantity" => $item->item_qty,
                    "price" => $item->item_price
                ];
                session()->put('fumCart', $cart);
            }

            // return session()->get('fumCart');
            $user_id = DB::table('fumaco_users')->where('username', Auth::user()->username)->first();
            $bill_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Billing')->count();
            $ship_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Delivery')->count();

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
            
            if(Auth::check()){
				$user_id = DB::table('fumaco_users')->where('username', Auth::user()->username)->first();
                $bill_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Billing')->count();
				$ship_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Delivery')->count();

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

        $category = DB::table('fumaco_categories')->where('name', $product_details->f_category)->first();

        $category_discount = DB::table('fumaco_on_sale as sale')->join('fumaco_on_sale_categories as cat_sale', 'sale.id', 'cat_sale.sale_id')->whereDate('sale.start_date', '<=', Carbon::now())->whereDate('sale.end_date', '>=', Carbon::now())->where('status', 1)->where('cat_sale.category_id', $category->id)->first();

        $product_price = $product_details->f_original_price;
        $discounted_from_category = 0;
        if($category_discount){ // check if product category is discounted
            if($category_discount->discount_type == 'By Percentage'){
                $discounted_from_category = 1;
                $product_price = $product_details->f_original_price - ($product_details->f_original_price * ($category_discount->discount_rate/100));
            }else if($category_discount->discount_type == 'Fixed Amount' and $product_details->f_original_price > $category_discount->discount_rate){
                $discounted_from_category = 1;
                $product_price = $product_details->f_original_price - $category_discount->discount_rate;
            }
        }

        // if cart is empty then this the first product
        $cart = session()->get('fumCart');
        if(!$cart) {
            $cart = [
                $id => [
                    "item_code" => $product_details->f_idcode,
                    "quantity" => $data['quantity'],
                    "price" => ($product_details->f_onsale == 1) ? $product_details->f_price : $product_price,
                    'is_discounted_from_category' => $discounted_from_category
                ]
            ];
 
            session()->put('fumCart', $cart);

            if(!$data['is_ajax']) {
                if (isset($data['buynow']) && $data['buynow']) {
                    return redirect('/checkout/summary');
                }
    
                return redirect()->back()->with('success', 'Product added to your cart!');
            } else {
                return response()->json(['message' => $cart]);
            }
        }
        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] = $cart[$id]['quantity'] + $data['quantity'];
            
            session()->put('fumCart', $cart);

            if(!$data['is_ajax']) {
                if (isset($data['buynow']) && $data['buynow']) {
                    return redirect('/cart');
                }
    
                return redirect()->back()->with('success', 'Product added to your cart!');
            } else {
                return response()->json(['message' => $cart]);
            }
        }
        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "item_code" => $product_details->f_idcode,
            "quantity" => $data['quantity'],
            "price" => ($product_details->f_onsale == 1) ? $product_details->f_price : $product_price,
            'is_discounted_from_category' => $discounted_from_category
        ];

        session()->put('fumCart', $cart);

        if(!$data['is_ajax']) {
            if (isset($data['buynow']) && $data['buynow']) {
                return redirect('/cart');
            }
    
            return redirect()->back()->with('success', 'Product added to your cart!');
        } else {
            return response()->json(['message' => $cart]);
        }
    }

    public function viewCart(Request $request) {
        $cart = session()->get('fumCart');
        $cart = (!$cart) ? [] : $cart;

        $cart_items = DB::table('fumaco_items')
            ->whereIn('f_idcode', array_column($cart, 'item_code'))->get();
        
        $cart_arr = [];
        foreach ($cart_items as $n => $item) {
            $item_image = DB::table('fumaco_items_image_v1')
                ->where('idcode', $item->f_idcode)->first();

            // $price = ($item->f_onsale) ? $item->f_price : $item->f_original_price;
            $test = collect($cart)->where('item_code', $item->f_idcode)->pluck('price')->first();
           
            $price = ($item->f_onsale) ? $item->f_price : $test;

            $cart_arr[] = [
                'item_code' => $item->f_idcode,
                'item_description' => $item->f_name_name,
                'price' => $price,
                'amount' => ($price * $cart[$item->f_idcode]['quantity']),
                'quantity' => $cart[$item->f_idcode]['quantity'],
                'stock_qty' => $item->f_qty - $item->f_reserved_qty,
                'stock_uom' => $item->f_stock_uom,
                'item_image' => ($item_image) ? $item_image->imgprimayx : null,
                'insufficient_stock' => ($cart[$item->f_idcode]['quantity'] > $item->f_qty) ? 1 : 0
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

        if ($request->ajax()) {
            return view('frontend.cart_preview', compact('cart_arr', 'bill_address', 'ship_address'));
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
            $count = collect($session_cart)->sum('quantity');
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
                'business_name' => $shipping_address->xbusiness_name,
                'tin' => $shipping_address->xtin_no,
                'email_address' => $shipping_address->xcontactemail1,
                'mobile_no' => $shipping_address->xmobile_number,
                'contact_no' => $shipping_address->xcontactnumber1,
                'same_as_billing' => 0
            ];

            $billing_address = DB::table('fumaco_user_add')->where('xdefault', 1)
                ->where('user_idx', $user_id)->where('address_class', 'Billing')->first();
            if ($billing_address) {
                $billing_details = [
                    'fname' => $billing_address->xcontactname1,
                    'lname' => $billing_address->xcontactlastname1,
                    'address_line1' => $billing_address->xadd1,
                    'address_line2' => $billing_address->xadd2,
                    'province' => $billing_address->xprov,
                    'city' => $billing_address->xcity,
                    'brgy' => $billing_address->xbrgy,
                    'postal_code' => $billing_address->xpostal,
                    'country' => $billing_address->xcountry,
                    'address_type' => $billing_address->add_type,
                    'business_name' => $billing_address->xbusiness_name,
                    'tin' => $billing_address->xtin_no,
                    'email_address' => $billing_address->xcontactemail1,
                    'mobile_no' => $billing_address->xmobile_number,
                    'contact_no' => $billing_address->xcontactnumber1,
                ];

                session()->put('fumBillDet', $billing_details);
            } else {
                session()->forget('fumBillDet');
            }
            
            session()->put('fumShipDet', $shipping_details);
        }
        
        if($request->isMethod('POST')) {
            if ($request->ajax()) {
                if (!Auth::check()) {
                    $existing_account = DB::table('fumaco_users')->where('username', $request->ship_email)->exists();
                    if ($existing_account) {
                        return response()->json(['status' => 'error', 'message' => 'Email already exists, please <a href="'. route('login') .'">login</a>.']);
                    }
                }
               
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
                    'business_name' => $request->ship_business_name,
                    'tin' => $request->ship_tin,
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
                        'business_name' => $request->bill_business_name,
                        'tin' => $request->bill_tin,
                        'email_address' => $request->email,
                        'mobile_no' => $request->mobilenumber1_1,
                    ];
        
                    session()->put('fumBillDet', $billing_details);
                } else {
                    session()->forget('fumBillDet');
                }
    
                session()->put('fumShipDet', $shipping_details);
    
                return response()->json(['status' => 'success', 'message' => '/checkout/summary']);
            }
        }
    
        return redirect('/checkout/summary');
    }
}
