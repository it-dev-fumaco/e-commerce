<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Adrianorosa\GeoLocation\GeoLocation;

class CartController extends Controller
{
    public function productActions(Request $request) {
        $data = $request->all();
        $data['is_ajax'] = ($request->ajax());
        $data['ip'] = ($request->ip());

        $order_no = 'FUM-' . date('yd') . random_int(0, 9999);
        if(isset($data['reorder'])){
            session()->forget('fumOrderNo');
            session()->put('fumOrderNo', $order_no);
            $loc = GeoLocation::lookup($request->ip());
            $items = DB::table('fumaco_order_items')->where('order_number', $request->order_number)->get();
            $cart = [];
            foreach($items as $item){
                $cart[] = [
                    'transaction_id' => $order_no,
                    'user_type' => (Auth::check()) ? 'member' : 'guest',
                    'user_email' => (Auth::check()) ? Auth::user()->username : null,
                    'item_description' => $item->item_name,
                    'item_code' => $item->item_code,
                    'qty' => $item->item_qty,
                    'ip' => $request->ip(),
                    'city' => $loc->getCity(),
                    'region' => $loc->getRegion(),
                    'country' => $loc->getCountry(),
                    'latitude' => $loc->getLatitude(),
                    'longitude' => $loc->getLongitude(),
                ];
            }

            DB::table('fumaco_cart')->insert($cart);

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

            $loc = GeoLocation::lookup($data['ip']);
            $order_no = session()->get('fumOrderNo');
            // add data to fumaco cart table
            if (Auth::check()) {
                $existing_cart = DB::table('fumaco_cart')->where('user_email', Auth::user()->username)->where('user_type', 'member')->where('item_code', $product_details->f_idcode)->first();
            } else {
                $existing_cart = DB::table('fumaco_cart')->where('transaction_id', $order_no)->where('user_type', 'guest')->where('item_code', $product_details->f_idcode)->first();
            }
            
            if ($existing_cart) {
                DB::table('fumaco_cart')->where('id', $existing_cart->id)->update([
                    'user_type' => (Auth::check()) ? 'member' : 'guest',
                    'user_email' => (Auth::check()) ? Auth::user()->username : null,
                    'qty' => $existing_cart->qty + $data['quantity'],
                    'ip' => $data['ip'],
                    'city' => $loc->getCity(),
                    'region' => $loc->getRegion(),
                    'country' => $loc->getCountry(),
                    'latitude' => $loc->getLatitude(),
                    'longitude' => $loc->getLongitude(),
                ]);
            } else {
                DB::table('fumaco_cart')->insert([
                    'transaction_id' => $order_no,
                    'user_type' => (Auth::check()) ? 'member' : 'guest',
                    'user_email' => (Auth::check()) ? Auth::user()->username : null,
                    'item_description' => $product_details->f_name_name,
                    'item_code' => $product_details->f_idcode,
                    'qty' => $data['quantity'],
                    'ip' => $data['ip'],
                    'city' => $loc->getCity(),
                    'region' => $loc->getRegion(),
                    'country' => $loc->getCountry(),
                    'latitude' => $loc->getLatitude(),
                    'longitude' => $loc->getLongitude(),
                ]);
            }
            
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

        $order_no = 'FUM-' . date('yd') . random_int(0, 9999);
        if(!session()->get('fumOrderNo')){
            session()->put('fumOrderNo', $order_no);
        } else {
            $order_no = session()->get('fumOrderNo');
        }

        $loc = GeoLocation::lookup($data['ip']);
        // add data to fumaco cart table
        if (Auth::check()) {
            $existing_cart = DB::table('fumaco_cart')->where('user_email', Auth::user()->username)->where('user_type', 'member')->where('item_code', $product_details->f_idcode)->first();
        } else {
            $existing_cart = DB::table('fumaco_cart')->where('transaction_id', $order_no)->where('user_type', 'guest')->where('item_code', $product_details->f_idcode)->first();
        }
        
        if ($existing_cart) {
            DB::table('fumaco_cart')->where('id', $existing_cart->id)->update([
                'user_type' => (Auth::check()) ? 'member' : 'guest',
                'user_email' => (Auth::check()) ? Auth::user()->username : null,
                'qty' => $existing_cart->qty + $data['quantity'],
                'ip' => $data['ip'],
                'city' => $loc->getCity(),
                'region' => $loc->getRegion(),
                'country' => $loc->getCountry(),
                'latitude' => $loc->getLatitude(),
                'longitude' => $loc->getLongitude(),
            ]);
        } else {
            DB::table('fumaco_cart')->insert([
                'transaction_id' => $order_no,
                'user_type' => (Auth::check()) ? 'member' : 'guest',
                'user_email' => (Auth::check()) ? Auth::user()->username : null,
                'item_description' => $product_details->f_name_name,
                'item_code' => $product_details->f_idcode,
                'qty' => $data['quantity'],
                'ip' => $data['ip'],
                'city' => $loc->getCity(),
                'region' => $loc->getRegion(),
                'country' => $loc->getCountry(),
                'latitude' => $loc->getLatitude(),
                'longitude' => $loc->getLongitude(),
            ]);
        }

        return redirect()->back()->with('success', 'Product added to your cart!');
    }

    public function viewCart(Request $request) {
        $order_no = session()->get('fumOrderNo');
        if(Auth::check()) {
            $cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
                ->where('user_type', 'member')->where('user_email', Auth::user()->username)->get();
        } else {
            $cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
                ->where('user_type', 'guest')->where('transaction_id', $order_no)->get();
        }

        // set sale price
        $sale = DB::table('fumaco_on_sale')
            ->whereDate('start_date', '<=', Carbon::now()->toDateString())
            ->whereDate('end_date', '>=', Carbon::today()->toDateString())
            ->where('status', 1)->first();
        
        $cart_arr = [];
        foreach ($cart_items as $n => $item) {
            $discount = 0;
            $price = $item->f_original_price;
            $item_image = DB::table('fumaco_items_image_v1')
                ->where('idcode', $item->f_idcode)->first();

            if (!$item->f_onsale) {
                if ($sale) {
                    if ($sale->apply_discount_to == 'All Items') {
                        if ($sale->discount_type == 'By Percentage') {
                            $discount = ($item->f_original_price * ($sale->discount_rate/100));
                            $discount = ($discount > $sale->capped_amount) ? $sale->capped_amount : $discount;
                        } else {
                            $discount = $sale->discount_rate;
                        }
                    } else {
                        $sale_per_category = DB::table('fumaco_on_sale as sale')->join('fumaco_on_sale_categories as cat_sale', 'sale.id', 'cat_sale.sale_id')
                            ->whereDate('sale.start_date', '<=', Carbon::now())->whereDate('sale.end_date', '>=', Carbon::now())
                            ->where('status', 1)->where('cat_sale.category_id', $item->f_cat_id)
                            ->select('cat_sale.*')->first();
                        if ($sale_per_category) {
                            if ($sale_per_category->discount_type == 'By Percentage') {
                                $discount = ($item->f_original_price * ($sale_per_category->discount_rate/100));
                                $discount = ($discount > $sale->capped_amount) ? $sale_per_category->capped_amount : $discount;
                            } else {
                                $discount = $sale->discount_rate;
                            }
                        }
                    }
                }
            } else {
                $price = $item->f_price;
            }

            $price = $price - $discount;
            $cart_arr[] = [
                'item_code' => $item->f_idcode,
                'item_description' => $item->f_name_name,
                'price' => $price,
                'amount' => ($price * $item->qty),
                'quantity' => $item->qty,
                'stock_qty' => $item->f_qty - $item->f_reserved_qty,
                'stock_uom' => $item->f_stock_uom,
                'item_image' => ($item_image) ? $item_image->imgprimayx : null,
                'insufficient_stock' => ($item->qty > $item->f_qty) ? 1 : 0
            ];
        }

        $bill_address = "";
		$ship_address = "";
		if(Auth::check()){
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
            $loc = GeoLocation::lookup($request->ip());
            $product_details = DB::table('fumaco_items')->where('f_idcode', $id)->first();
            if ($product_details) {
                $order_no = 'FUM-' . date('yd') . random_int(0, 9999);
                if(!session()->get('fumOrderNo')){
                    session()->put('fumOrderNo', $order_no);
                } else {
                    $order_no = session()->get('fumOrderNo');
                }
                // add qty to fumaco cart table
                $existing_cart = DB::table('fumaco_cart')->where('transaction_id', $order_no)
                    ->where('item_code', $product_details->f_idcode)->first();
                if ($existing_cart) {
                    $plus_qty = $existing_cart->qty + 1;
                    $minus_qty = $existing_cart->qty - 1;
                    DB::table('fumaco_cart')->where('id', $existing_cart->id)->update([
                        'user_type' => (Auth::check()) ? 'member' : 'guest',
                        'user_email' => (Auth::check()) ? Auth::user()->username : null,
                        'qty' => ($request->type == 'increment') ? $plus_qty : $minus_qty,
                        'ip' => $request->ip(),
                        'city' => $loc->getCity(),
                        'region' => $loc->getRegion(),
                        'country' => $loc->getCountry(),
                        'latitude' => $loc->getLatitude(),
                        'longitude' => $loc->getLongitude(),
                    ]);
                }
            }

            return response()->json(['status' => 1, 'message' => 'Cart updated!']);
        }
    }

    public function removeFromCart(Request $request) {
        $id = $request->id;
        if($id) {
            $product_details = DB::table('fumaco_items')->where('f_idcode', $id)->first();
            if ($product_details) {
                $order_no = 'FUM-' . date('yd') . random_int(0, 9999);
                if(!session()->get('fumOrderNo')){
                    session()->put('fumOrderNo', $order_no);
                } else {
                    $order_no = session()->get('fumOrderNo');
                }

                if (Auth::check()) {
                    // delete data from fumaco cart table
                    DB::table('fumaco_cart')->where('user_email', Auth::user()->username)
                        ->where('item_code', $product_details->f_idcode)->delete();
                } else {
                    // delete data from fumaco cart table
                    DB::table('fumaco_cart')->where('transaction_id', $order_no)
                        ->where('item_code', $product_details->f_idcode)->delete();
                }
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
        if (Auth::check()) {
            return DB::table('fumaco_cart')->where('user_type', 'member')->where('user_email', Auth::user()->username)->sum('qty');
        } else { 
            return DB::table('fumaco_cart')->where('user_type', 'guest')->where('transaction_id', session()->get('fumOrderNo'))->sum('qty');
        }
    }

    public function countWishlist(){
        if(Auth::check()){
            return DB::table('datawishlist')->where('userid', Auth::user()->id)->count();
        }
        
        return 0;
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
