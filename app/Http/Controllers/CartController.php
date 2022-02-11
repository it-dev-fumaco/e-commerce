<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Adrianorosa\GeoLocation\GeoLocation;
use App\Http\Traits\ProductTrait;

class CartController extends Controller
{
    use ProductTrait;

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
                    'category_id' => $product_details->f_cat_id,
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
                'category_id' => $product_details->f_cat_id,
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

        // get sitewide sale
        $sale = DB::table('fumaco_on_sale')
            ->whereDate('start_date', '<=', Carbon::now()->toDateString())
            ->whereDate('end_date', '>=', Carbon::today()->toDateString())
            ->where('status', 1)->where('apply_discount_to', 'All Items')->first();

        $cart_arr = [];
        foreach ($cart_items as $n => $item) {
            $image = DB::table('fumaco_items_image_v1')->where('idcode', $item->f_idcode)->first();
            $item_price = $item->f_default_price;
            $item_on_sale = $item->f_onsale;
            
            $is_new_item = 0;
            if($item->f_new_item == 1){
                if($item->f_new_item_start <= Carbon::now() and $item->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }
            // get item price, discounted price and discount rate
            $item_price_data = $this->getItemPriceAndDiscount($item_on_sale, $item->f_cat_id, $sale, $item_price, $item->f_idcode, $item->f_discount_type, $item->f_discount_rate, $item->f_stock_uom);
            // get product reviews
            $product_reviews = $this->getProductRating($item->f_idcode);

            $cart_arr[] = [
                'item_code' => $item->f_idcode,
                'slug' => $item->slug,
                'item_description' => $item->f_name_name,
                'price' => $item_price_data['discounted_price'],
                'amount' => ($item_price_data['discounted_price'] * $item->qty),
                'quantity' => $item->qty,
                'stock_qty' => $item->f_qty - $item->f_reserved_qty,
                'stock_uom' => $item->f_stock_uom,
                'item_image' => ($image) ? $image->imgprimayx : null,
                'insufficient_stock' => ($item->qty > $item->f_qty) ? 1 : 0
            ];
        }

        $cross_sell_products = DB::table('fumaco_items_cross_sell')->whereIn('item_code', collect($cart_items)->pluck('f_idcode'))->whereNotIn('item_code_cross_sell', collect($cart_items)->pluck('f_idcode'))->get();
        
        $all_item_discount = DB::table('fumaco_on_sale')->whereDate('start_date', '<=', Carbon::now()->toDateString())->whereDate('end_date', '>=', Carbon::now()->toDateString())->where('status', 1)->where('apply_discount_to', 'All Items')->first();

        $cross_sell_arr = [];
        foreach($cross_sell_products as $cs){
            $item_details = DB::table('fumaco_items as item')->join('fumaco_items_image_v1 as img', 'item.f_idcode', 'img.idcode')->where('item.f_idcode', $cs->item_code_cross_sell)->first();
            if ($item_details) {
                $image = DB::table('fumaco_items_image_v1')->where('idcode', $item_details->f_idcode)->first();
                $item_price = $item_details->f_default_price;
                $item_on_sale = $item_details->f_onsale;
                
                $is_new_item = 0;
                if($item_details->f_new_item == 1){
                    if($item_details->f_new_item_start <= Carbon::now() and $item_details->f_new_item_end >= Carbon::now()){
                        $is_new_item = 1;
                    }
                }
                // get item price, discounted price and discount rate
                $item_price_data = $this->getItemPriceAndDiscount($item_on_sale, $item_details->f_cat_id, $sale, $item_price, $item_details->f_idcode, $item_details->f_discount_type, $item_details->f_discount_rate, $item_details->f_stock_uom);
                // get product reviews
                $product_reviews = $this->getProductRating($item_details->f_idcode);
               
                $cross_sell_arr[] = [
                    'item_code' => $item_details->f_idcode,
                    'item_name' => $item_details->f_name_name,
                    'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                    'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                    'on_stock' => ($item_details->f_qty - $item_details->f_reserved_qty) > 0 ? 1 : 0,
                    'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                    'discount_display' => $item_price_data['discount_display'],
                    'image' => ($image) ? $image->imgprimayx : null,
                    'slug' => $item_details->slug,
                    'is_new_item' => $is_new_item,
                    'overall_rating' => $product_reviews['overall_rating'],
                    'total_reviews' => $product_reviews['total_reviews']
                ];
            }

            $product_review_per_code = DB::table('fumaco_product_review')->where('status', '!=', 'pending')->where('item_code', $item_details->f_idcode)->get();
        }

        $bill_address = "";
		$ship_address = "";
		if(Auth::check()){
			$bill_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', Auth::user()->id)->where('address_class', 'Billing')->count();

			$ship_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', Auth::user()->id)->where('address_class', 'Delivery')->count();
		}

        if ($request->ajax()) {
            return view('frontend.cart_preview', compact('cart_arr', 'bill_address', 'ship_address'));
        }

        return view('frontend.cart', compact('cart_arr', 'bill_address', 'ship_address', 'cross_sell_arr'));
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
                    if($request->type == 'increment'){
                        $new_qty = $existing_cart->qty + 1;
                    }else if($request->type == 'decrement'){
                        $new_qty = $existing_cart->qty - 1;
                    }else{
                        $new_qty = $request->quantity;
                    }
                    DB::table('fumaco_cart')->where('id', $existing_cart->id)->update([
                        'user_type' => (Auth::check()) ? 'member' : 'guest',
                        'user_email' => (Auth::check()) ? Auth::user()->username : null,
                        'qty' => $new_qty,
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
                        'category_id' => $product_details->f_cat_id,
                        'item_name' => $product_details->f_name_name,
                        'item_price' => $product_details->f_default_price
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
