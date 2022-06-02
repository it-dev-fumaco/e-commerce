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
        $order_no = 'FUM-' . date('yd') . random_int(0, 9999);
        if(!session()->get('fumOrderNo')){
            session()->put('fumOrderNo', $order_no);
        } else {
            $order_no = session()->get('fumOrderNo');
        }

        if(Auth::check()) {
            $cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
                ->where('user_type', 'member')->where('user_email', Auth::user()->username)
                ->select('f_idcode', 'f_default_price', 'f_onsale', 'b.qty', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_discount_type', 'f_discount_rate', 'f_stock_uom', 'slug', 'f_name_name', 'f_qty', 'f_reserved_qty')->get();
        } else {
            $cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
                ->where('user_type', 'guest')->where('transaction_id', $order_no)
                ->select('f_idcode', 'f_default_price', 'f_onsale', 'b.qty', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_discount_type', 'f_discount_rate', 'f_stock_uom', 'slug', 'f_name_name', 'f_qty', 'f_reserved_qty')->get();
        }

        if (count($cart_items) > 0) {
            if ($order_no) {
                $existing_temp = DB::table('fumaco_temp')->where('xlogs', $order_no)->first();
                if(!$existing_temp) {
                    $loc = GeoLocation::lookup($request->ip());
                    DB::table('fumaco_temp')->insert([
                        'xtempcode' => uniqid(),
                        'xlogs' => $order_no,
                        'order_tracker_code' => $order_no,
                        'order_ip' => $request->ip(),
                        'ip_city' => $loc->getCity(),
                        'ip_region' => $loc->getRegion(),
                        'ip_country' => $loc->getCountry(),
                        'xusertype' => Auth::check() ? 'Member' : 'Guest',
                        'xusernamex' => Auth::check() ? Auth::user()->username : null,
                        'xuser_id' => Auth::check() ? Auth::user()->id : null,
                        'last_transaction_page' => 'Shopping Cart Page'
                    ]);
                }
            }
        }

        // get sitewide sale
        $sale = DB::table('fumaco_on_sale')
            ->whereDate('start_date', '<=', Carbon::now()->toDateString())
            ->whereDate('end_date', '>=', Carbon::today()->toDateString())
            ->where('status', 1)->where('apply_discount_to', 'All Items')
            ->select('discount_type', 'discount_rate')->first();

        $item_codes = array_column($cart_items->toArray(), 'f_idcode');

        if (count($item_codes) > 0) {
            $item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $item_codes)
                ->select('imgprimayx', 'idcode')->get();
            $item_images = collect($item_images)->groupBy('idcode')->toArray();
        }
        $sale_per_category = [];
        if (!$sale && !Auth::check()) {
            $item_categories = array_column($cart_items->toArray(), 'f_cat_id');
            $sale_per_category = $this->getSalePerItemCategory($item_categories);
        }

        if (Auth::check()) {
            $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

            $sale = $customer_group_sale ? $customer_group_sale : $sale;
        }

        $cart_arr = [];
        foreach ($cart_items as $n => $item) {
            $image = null;
            if (array_key_exists($item->f_idcode, $item_images)) {
                $image = $item_images[$item->f_idcode][0]->imgprimayx;
            }

            $item_price = $item->f_default_price;
            $item_on_sale = $item->f_onsale;
            
            $is_new_item = 0;
            if($item->f_new_item == 1){
                if($item->f_new_item_start <= Carbon::now() and $item->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }
            // get item price, discounted price and discount rate
            $item_price_data = $this->getItemPriceAndDiscount($item_on_sale, $item->f_cat_id, $sale, $item_price, $item->f_idcode, $item->f_discount_type, $item->f_discount_rate, $item->f_stock_uom, $sale_per_category);

            $cart_arr[] = [
                'item_code' => $item->f_idcode,
                'slug' => $item->slug,
                'item_description' => $item->f_name_name,
                'price' => $item_price_data['discounted_price'],
                'amount' => ($item_price_data['discounted_price'] * $item->qty),
                'quantity' => $item->qty,
                'stock_qty' => $item->f_qty - $item->f_reserved_qty,
                'stock_uom' => $item->f_stock_uom,
                'item_image' => $image,
                'insufficient_stock' => ($item->qty > $item->f_qty) ? 1 : 0
            ];
        }

        $cart_item_codes = collect($cart_items)->pluck('f_idcode');

        $cross_sell_products = DB::table('fumaco_items_cross_sell as cs')->join('fumaco_items as i', 'cs.item_code_cross_sell', 'i.f_idcode')
            ->whereIn('cs.item_code', $cart_item_codes)->whereNotIn('cs.item_code_cross_sell', $cart_item_codes)
            ->select('f_idcode', 'f_default_price', 'f_onsale', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_discount_type', 'f_discount_rate', 'f_stock_uom', 'slug', 'f_name_name', 'f_qty', 'f_reserved_qty')->get();

        $cross_selling_item_codes = array_column($cross_sell_products->toArray(), 'f_idcode');

        if (count($cross_selling_item_codes) > 0) {
            $cross_selling_item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $cross_selling_item_codes)
                ->select('imgprimayx', 'idcode')->get();
            $cross_selling_item_images = collect($cross_selling_item_images)->groupBy('idcode')->toArray();

            $product_reviews = $this->getProductRating($cross_selling_item_codes);
        }
        $sale_per_category = [];
        if (!$sale && !Auth::check()) {
            $item_categories = array_column($cross_sell_products->toArray(), 'f_cat_id');
            $sale_per_category = $this->getSalePerItemCategory($item_categories);
        }

        if (Auth::check()) {
            $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

            $sale = $customer_group_sale ? $customer_group_sale : $sale;
        }
    
        $cross_sell_arr = [];
        foreach($cross_sell_products as $cs){
            $item_price = $cs->f_default_price;
            $item_on_sale = $cs->f_onsale;
            $image = null;
            if (array_key_exists($cs->f_idcode, $cross_selling_item_images)) {
                $image = $cross_selling_item_images[$cs->f_idcode][0]->imgprimayx;
            }

            $is_new_item = 0;
            if($cs->f_new_item == 1){
                if($cs->f_new_item_start <= Carbon::now() and $cs->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }
            // get item price, discounted price and discount rate
            $item_price_data = $this->getItemPriceAndDiscount($item_on_sale, $cs->f_cat_id, $sale, $item_price, $cs->f_idcode, $cs->f_discount_type, $cs->f_discount_rate, $cs->f_stock_uom, $sale_per_category);
            // get product reviews
            $total_reviews = array_key_exists($cs->f_idcode, $product_reviews) ? $product_reviews[$cs->f_idcode]['total_reviews'] : 0;
            $overall_rating = array_key_exists($cs->f_idcode, $product_reviews) ? $product_reviews[$cs->f_idcode]['overall_rating'] : 0;
            
            $cross_sell_arr[] = [
                'item_code' => $cs->f_idcode,
                'item_name' => $cs->f_name_name,
                'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                'on_stock' => ($cs->f_qty - $cs->f_reserved_qty) > 0 ? 1 : 0,
                'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                'discount_display' => $item_price_data['discount_display'],
                'image' => $image,
                'slug' => $cs->slug,
                'is_new_item' => $is_new_item,
                'overall_rating' => $overall_rating,
                'total_reviews' => $total_reviews
            ];
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
        $order_no = session()->get('fumOrderNo');
        $existing_order_temp = DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->first();
        if(!$existing_order_temp){
            if ($order_no) {
                $existing_temp = DB::table('fumaco_temp')->where('xlogs', $order_no)->first();
                if(!$existing_temp) {
                    $loc = GeoLocation::lookup($request->ip());
                    DB::table('fumaco_temp')->insert([
                        'xtempcode' => uniqid(),
                        'xlogs' => $order_no,
                        'order_tracker_code' => $order_no,
                        'order_ip' => $request->ip(),
                        'ip_city' => $loc->getCity(),
                        'ip_region' => $loc->getRegion(),
                        'ip_country' => $loc->getCountry(), 
                        'xusertype' => Auth::check() ? 'Member' : 'Guest',
                        'xusernamex' => Auth::check() ? Auth::user()->username : null,
                        'xuser_id' => Auth::check() ? Auth::user()->id : null,
                    ]);
                }
            }
        }

        if(Auth::check()) {
            $user_id = Auth::user()->id;
            $user = DB::table('fumaco_users')->where('id', $user_id)->first();

            $shipping_address = DB::table('fumaco_user_add')->where('xdefault', 1)
                ->where('user_idx', $user_id)->where('address_class', 'Delivery')->first();

            $mobile = null;
            if($shipping_address->xmobile_number){
                $mobile = preg_replace("/[^0-9]/", "", $shipping_address->xmobile_number);
                if($mobile[0] == 0){
                    $mobile = '63'.substr($mobile, 1);
                }else if(substr($mobile, 0, 2) != '63' || $mobile[0] == '9'){
                    $mobile = '63'.$mobile;
                }
                // $mobile = $mobile[0] == 0 ? '63'.substr($mobile, 1) : '63'.$mobile;
            }

            $contact = null;
            if($shipping_address->xcontactnumber1){
                $contact = preg_replace("/[^0-9]/", "", $shipping_address->xcontactnumber1);
                // $contact = $contact[0] == 0 ? '63'.substr($contact, 1) : '63'.$contact;
                if($contact[0] == 0){
                    $contact = '63'.substr($contact, 1);
                }else if(substr($contact, 0, 2) != '63' || $contact[0] == '9'){
                    $contact = '63'.$contact;
                }
            }

            $bill_mobile = $mobile;
            $bill_contact = $contact;
            
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
                'mobile_no' => $mobile,
                'contact_no' => $contact,
                'same_as_billing' => 0
            ];

            $billing_address = DB::table('fumaco_user_add')->where('xdefault', 1)
                ->where('user_idx', $user_id)->where('address_class', 'Billing')->first();
            if ($billing_address) {
                $bill_mobile = null;
                if($billing_address->xmobile_number){
                    $bill_mobile = preg_replace("/[^0-9]/", "", $billing_address->xmobile_number);
                    // $bill_mobile = $bill_mobile[0] == 0 ? '63'.substr($bill_mobile, 1) : '63'.$bill_mobile;
                    if($bill_mobile[0] == 0){
                        $bill_mobile = '63'.substr($bill_mobile, 1);
                    }else if(substr($bill_mobile, 0, 2) != '63' || $bill_mobile[0] == '9'){
                        $bill_mobile = '63'.$bill_mobile;
                    }
                }

                $bill_contact = null;
                if($billing_address->xcontactnumber1){
                    $bill_contact = preg_replace("/[^0-9]/", "", $billing_address->xcontactnumber1);
                    // $bill_contact = $bill_contact[0] == 0 ? '63'.substr($bill_contact, 1) : '63'.$bill_contact;
                    if($bill_contact[0] == 0){
                        $bill_contact = '63'.substr($bill_contact, 1);
                    }else if(substr($bill_contact, 0, 2) != '63' || $bill_contact[0] == '9'){
                        $bill_contact = '63'.$bill_contact;
                    }
                }

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
                    'mobile_no' => $bill_mobile,
                    'contact_no' => $bill_contact,
                ];
            }
        }
        
        if($request->isMethod('POST')) {
            if ($request->ajax()) {
                if (!Auth::check()) {
                    $existing_account = DB::table('fumaco_users')->where('username', $request->ship_email)->exists();
                    if ($existing_account) {
                        return response()->json(['status' => 'error', 'message' => 'Email already exists, please <a href="'. route('login') .'">login</a>.']);
                    }
                }

                $ship_mobile = null;
                if($request->ship_mobilenumber1_1){
                    $ship_mobile = preg_replace("/[^0-9]/", "", $request->ship_mobilenumber1_1);
                    // $ship_mobile = $ship_mobile[0] == 0 ? '63'.substr($ship_mobile, 1) : '63'.$ship_mobile;
                    if($ship_mobile[0] == 0){
                        $ship_mobile = '63'.substr($ship_mobile, 1);
                    }else if(substr($ship_mobile, 0, 2) != '63' || $ship_mobile[0] == '9'){
                        $ship_mobile = '63'.$ship_mobile;
                    }
                }

                $ship_contact = null;
                if($request->contactnumber1_1){
                    $ship_contact = preg_replace("/[^0-9]/", "", $request->contactnumber1_1);
                    // $ship_contact = $ship_contact[0] == 0 ? '63'.substr($ship_contact, 1) : '63'.$ship_contact;
                    if($ship_contact[0] == 0){
                        $ship_contact = '63'.substr($ship_contact, 1);
                    }else if(substr($ship_contact, 0, 2) != '63' || $ship_contact[0] == '9'){
                        $ship_contact = '63'.$ship_contact;
                    }
                }

                $bill_mobile = $ship_mobile;
               
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
                    'mobile_no' => $ship_mobile,
                    'contact_no' => $ship_contact,
                    'same_as_billing' => ($request->same_as_billing) ? 1 : 0
                ];
                
                $billing_details = [];
                if(!$request->same_as_billing) {
                    $bill_mobile = null;
                    if($request->mobilenumber1_1){
                        $bill_mobile = preg_replace("/[^0-9]/", "", $request->mobilenumber1_1);
                        // $bill_mobile = $bill_mobile[0] == 0 ? '63'.substr($bill_mobile, 1) : '63'.$bill_mobile;
                        if($bill_mobile[0] == 0){
                            $bill_mobile = '63'.substr($bill_mobile, 1);
                        }else if(substr($bill_mobile, 0, 2) != '63' || $bill_mobile[0] == '9'){
                            $bill_mobile = '63'.$bill_mobile;
                        }
                    }
                    
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
                        'mobile_no' => $bill_mobile,
                    ];
                }
            }
        }

        if ($order_no) {
            $temp_data = [
                'xfname' => (Auth::check()) ? Auth::user()->f_name : $shipping_details['fname'],
                'xlname' => (Auth::check()) ? Auth::user()->f_lname : $shipping_details['lname'],
                'xcontact_person' => ($billing_details) ? $billing_details['fname']. " " . $billing_details['lname'] : $shipping_details['fname']. " " . $shipping_details['lname'],
                'xshipcontact_person' => $shipping_details['fname']. " " . $shipping_details['lname'],
                'xadd1' => ($billing_details) ? $billing_details['address_line1'] : $shipping_details['address_line1'],
                'xadd2' => ($billing_details) ? $billing_details['address_line2'] : $shipping_details['address_line2'],
                'xprov' => ($billing_details) ? $billing_details['province'] : $shipping_details['province'],
                'xcity' => ($billing_details) ? $billing_details['city'] : $shipping_details['city'],
                'xbrgy' => ($billing_details) ? $billing_details['brgy'] : $shipping_details['brgy'],
                'xpostal' => ($billing_details) ? $billing_details['postal_code'] : $shipping_details['postal_code'],
                'xcountry' => ($billing_details) ? $billing_details['country'] : $shipping_details['country'],
                'xaddresstype' => ($billing_details) ? $billing_details['address_type'] : $shipping_details['address_type'],
                'xbusiness_name' => ($billing_details) ? $billing_details['business_name'] : $shipping_details['business_name'],
                'xtin_no' => ($billing_details) ? $billing_details['tin'] : $shipping_details['tin'],
                'xemail' => ($billing_details) ? $billing_details['email_address'] : $shipping_details['email_address'],
                'xemail_shipping' => $shipping_details['email_address'],
                'xmobile' => ($billing_details) ? $billing_details['mobile_no'] : null,
                'xcontact' => $shipping_details['mobile_no'],
                'xshippadd1' => $shipping_details['address_line1'],
                'xshippadd2' => $shipping_details['address_line2'],
                'xshiprov' => $shipping_details['province'],
                'xshipcity' => $shipping_details['city'],
                'xshipbrgy' => $shipping_details['brgy'],
                'xshippostalcode' => $shipping_details['postal_code'],
                'xshipcountry' => $shipping_details['country'],
                'xshiptype' => $shipping_details['address_type'],
                'xship_business_name' => $shipping_details['business_name'],
                'xship_tin' => $shipping_details['tin'],
                'order_status' => 'Order Pending',
                'order_shipping_type' => null, 
                'xusertype' => (Auth::check()) ? 'Member' : 'Guest',
                'xusernamex' => (Auth::check()) ? Auth::user()->username : null,
                'xstatus' => 2,
                'xuser_id' => (Auth::check()) ? Auth::user()->id : null,
                'shipping_same_as_billing' => ($request->same_as_billing) ? 1 : 0,
            ];

            DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->update($temp_data);
        }

        if($request->isMethod('POST')) {
            return response()->json(['status' => 'success', 'message' => '/checkout/summary']);
        }

        return redirect('/checkout/summary');
    }
}
