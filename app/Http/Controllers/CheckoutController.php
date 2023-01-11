<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderSuccess;

use App\Models\ShippingService;
use App\Models\ShippingZoneRate;
use App\Models\ShippingCondition;

use App\Http\Traits\ProductTrait;
use App\Http\Traits\GeneralTrait;

class CheckoutController extends Controller
{
	use ProductTrait;
	use GeneralTrait;

	private function saveTempOrder($order_no, $ip) {
		if ($order_no) {
			$existing_temp = DB::table('fumaco_temp')->where('xlogs', $order_no)->first();
			if(!$existing_temp) {
				DB::table('fumaco_temp')->insert([
					'xtempcode' => uniqid(),
					'xlogs' => $order_no,
					'order_tracker_code' => $order_no,
					'order_ip' => $ip,
					'xusertype' => Auth::check() ? 'Member' : 'Guest',
					'xusernamex' => Auth::check() ? Auth::user()->username : null,
					'xuser_id' => Auth::check() ? Auth::user()->id : null,
				]);
			}
		}
	}

	public function billingForm(Request $request) {
		$order_no = session()->get('fumOrderNo');

		$this->saveTempOrder($order_no, $request->ip());
	
        if(Auth::check()) {
            $cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
				->where('user_type', 'member')->where('user_email', Auth::user()->username)->select('qty', 'f_qty')->get();
        } else {
            $cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
				->where('user_type', 'guest')->where('transaction_id', $order_no)->select('qty', 'f_qty')->get();
        }

        $cart_arr = [];
        foreach ($cart_items as $n => $item) {
			if ($item->qty > $item->f_qty) {
				return redirect()->back()->with('error', 'Insufficient stock for <b>' . $item->f_name_name . '</b>');
			}
        }

		$shipping_zones = DB::table('fumaco_shipping_zone_rate')->distinct()->pluck('province_name')->toArray();

		$has_shipping_address = true;
		if (Auth::check()) {
			$has_shipping_address = DB::table('fumaco_user_add')
				->where('xdefault', 1)->where('user_idx', Auth::user()->id)
				->where('address_class', 'Delivery')->exists();
		}

		$is_on_payment_page = DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->whereIn('last_transaction_page', ['Payment Page', 'Checkout Page'])->exists();
		if (!$is_on_payment_page) {
			DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->update(['last_transaction_page' => 'Billing & Shipping Form']);
		}

		return view('frontend.checkout.billing_address_form', compact('has_shipping_address', 'shipping_zones'));
	}

	public function setBillingForm($item_code_buy = null, $qty_buy = null){
		$shipping_zones = DB::table('fumaco_shipping_zone_rate')->distinct()->pluck('province_name')->toArray();

		$order_no = session()->get('fumOrderNo');

		$is_on_payment_page = DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->whereIn('last_transaction_page', ['Payment Page', 'Checkout Page'])->exists();
		if (!$is_on_payment_page) {
			DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->update(['last_transaction_page' => 'Billing & Shipping Form']);
		}

		return view('frontend.checkout.set_billing', compact('item_code_buy', 'qty_buy', 'shipping_zones'));
	}

	public function setBilling(Request $request){
		DB::beginTransaction();
		try{
			$o_email = Auth::user()->username;

			$user = DB::table('fumaco_users')->where('username', $o_email)->first();
			$user_id = $user->id;

			$bill_address_arr = [
				'address_class' => 'Billing',
				'user_idx' => $user_id,
				'add_type' => $request->Address_type1_1,
				'xadd1' => $request->Address1_1,
				'xadd2' => ($request->Address2_1) ? $request->Address2_1 : " ",
				'xprov' => $request->province1_1,
				'xcity' => $request->City_Municipality1_1,
				'xbrgy' => $request->Barangay1_1,
				'xpostal' => $request->postal1_1,
				'xcountry' => $request->country_region1_1,
				'xcontactname1' => $request->fname,
				'xcontactlastname1' => $request->lname,
				'xcontactnumber1' => ($request->contactnumber1_1) ? $request->contactnumber1_1 : 0,
				'xmobile_number' => $request->mobilenumber1_1,
				'xcontactemail1' => $request->email,
				'xdefault' => 1
			];

			DB::table('fumaco_user_add')->insert($bill_address_arr);

			DB::commit();

			$bill_address = "";
			$ship_address = "";
			if(Auth::check()){
				$user_id = DB::table('fumaco_users')->where('username', Auth::user()->username)->first();

				$bill_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Billing')->get();
				$ship_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id->id)->where('address_class', 'Delivery')->get();
			}

			$item_code_buy = "";
			$qty_buy = "";
			$summary = '/checkout/summary/';

			if(isset($request->buy_now)){
				$item_code_buy = $request->buy_now_item_code;
				$qty_buy = $request->buy_now_qty;
				$summary = '/checkout/summary/'.$item_code_buy."/".$qty_buy;
			}

			return redirect($summary)->with('add_success', 'Record Updated');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}	
		
	}

	public function setAddress(Request $request){
		DB::beginTransaction();
		try{
			if ($request->ajax()) {
				$user_id = Auth::user()->id;
	
				$bill_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id)->where('address_class', 'Billing')->get();
				$ship_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id)->where('address_class', 'Delivery')->get();
				if(count($bill_address) > 0)
				{
					DB::table('fumaco_user_add')->where('user_idx', $user_id)->where('address_class', 'Billing')->update(['xdefault' => 0]);
				}
	
				if(count($ship_address) > 0){
					DB::table('fumaco_user_add')->where('user_idx', $user_id)->where('address_class', 'Delivery')->update(['xdefault' => 0]);
				}
	
				$ship_address_arr = [
					'address_class' => 'Delivery',
					'user_idx' => $user_id,
					'add_type' => $request->ship_Address_type1_1,
					'xbusiness_name' => $request->ship_business_name,
					'xtin_no' => $request->ship_tin,
					'add_type' => $request->ship_Address_type1_1,
					'xadd1' => $request->ship_Address1_1,
					'xadd2' => ($request->ship_Address2_1) ? $request->ship_Address2_1 : " ",
					'xprov' => $request->ship_province1_1,
					'xcity' => $request->ship_City_Municipality1_1,
					'xbrgy' => $request->ship_Barangay1_1,
					'xpostal' => $request->ship_postal1_1,
					'xcountry' => $request->ship_country_region1_1,
					'xcontactname1' => $request->fname,
					'xcontactlastname1' => $request->lname,
					'xcontactnumber1' => ($request->contactnumber1_1) ? $request->contactnumber1_1 : 0,
					'xmobile_number' => $request->ship_mobilenumber1_1,
					'xcontactemail1' => $request->ship_email,
					'xdefault' => 1
				];
	
				if (isset($request->same_as_billing)){
					$bill_address_arr = [
						'address_class' => 'Billing',
						'user_idx' => $user_id,
						'add_type' => $request->ship_Address_type1_1,
						'xbusiness_name' => $request->ship_business_name,
						'xtin_no' => $request->ship_tin,
						'xadd1' => $request->ship_Address1_1,
						'xadd2' => ($request->ship_Address2_1) ? $request->ship_Address2_1 : " ",
						'xprov' => $request->ship_province1_1,
						'xcity' => $request->ship_City_Municipality1_1,
						'xbrgy' => $request->ship_Barangay1_1,
						'xpostal' => $request->ship_postal1_1,
						'xcountry' => $request->ship_country_region1_1,
						'xcontactname1' => $request->fname,
						'xcontactlastname1' => $request->lname,
						'xcontactnumber1' => ($request->contactnumber1_1) ? $request->contactnumber1_1 : 0,
						'xmobile_number' => $request->ship_mobilenumber1_1,
						'xcontactemail1' => $request->ship_email,
						'xdefault' => 1
					];
				}else{
					$bill_address_arr = [
						'address_class' => 'Billing',
						'user_idx' => $user_id,
						'add_type' => $request->Address_type1_1,
						'xbusiness_name' => $request->bill_business_name,
						'xtin_no' => $request->bill_tin,
						'xadd1' => $request->Address1_1,
						'xadd2' => ($request->Address2_1) ? $request->Address2_1 : " ",
						'xprov' => $request->province1_1,
						'xcity' => $request->City_Municipality1_1,
						'xbrgy' => $request->Barangay1_1,
						'xpostal' => $request->postal1_1,
						'xcountry' => $request->country_region1_1,
						'xcontactname1' => $request->fname,
						'xcontactlastname1' => $request->lname,
						'xcontactnumber1' => ($request->contactnumber1_1) ? $request->contactnumber1_1 : 0,
						'xmobile_number' => $request->mobilenumber1_1,
						'xcontactemail1' => $request->email,
						'xdefault' => 1
					];
					
				}
	
				$bill_insert = DB::table('fumaco_user_add')->insert($bill_address_arr);
				$ship_insert = DB::table('fumaco_user_add')->insert($ship_address_arr);
	
				DB::commit();

				return response()->json(['status' => 1, 'message' => '/setdetails']);
			}
			
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}	
	}

	public function updateShipping(Request $request){
		DB::beginTransaction();
		try{
			$insert = [
				'address_class' => 'Delivery',
				'add_type' => $request->ship_Address_type1_1,
				'xbusiness_name' => $request->ship_business_name,
				'xtin_no' => $request->ship_tin,
				'xadd1' => $request->ship_Address1_1,
				'xadd2' => $request->ship_Address2_1,
				'xprov' => $request->ship_province1_1,
				'xcity' => $request->ship_City_Municipality1_1,
				'xbrgy' => $request->ship_Barangay1_1,
				'xpostal' => $request->ship_postal1_1,
				'xcountry' => $request->ship_country_region1_1,
				'xmobile_number' => $request->ship_mobilenumber1_1,
				'xcontactname1' => $request->fname,
				'xcontactlastname1' => $request->lname,
				'xcontactnumber1' => $request->contactnumber1_1,
				'xcontactemail1' => $request->ship_email,
				'user_idx' => Auth::user()->id,
				'xdefault' => 1
			];

			DB::table('fumaco_user_add')->where('user_idx', Auth::user()->id)->where('address_class', 'Delivery')->update(['xdefault' => 0]);

			DB::table('fumaco_user_add')->insert($insert);

			DB::commit();
			return redirect()->back()->with('success', 'Address Updated.');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
	}

	public function updateBilling(Request $request){
		DB::beginTransaction();
		try{
			$insert = [
				'address_class' => 'Billing',
				'add_type' => $request->Address_type1_1,
				'xbusiness_name' => $request->bill_business_name,
				'xtin_no' => $request->bill_tin,
				'xadd1' => $request->Address1_1,
				'xadd2' => $request->Address2_1,
				'xprov' => $request->province1_1,
				'xcity' => $request->City_Municipality1_1,
				'xbrgy' => $request->Barangay1_1,
				'xpostal' => $request->postal1_1,
				'xcountry' => $request->country_region1_1,
				'xmobile_number' => $request->mobilenumber1_1,
				'xcontactname1' => $request->bill_fname,
				'xcontactlastname1' => $request->bill_lname,
				'xcontactemail1' => $request->email,
				'user_idx' => Auth::user()->id,
				'xdefault' => 1
			];

			DB::table('fumaco_user_add')->where('user_idx', Auth::user()->id)->where('address_class', 'Billing')->update(['xdefault' => 0]);

			DB::table('fumaco_user_add')->insert($insert);

			DB::commit();
			return redirect()->back()->with('success', 'Address Updated.');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
	}

	public function checkoutSummary(Request $request){
        DB::beginTransaction();
		try{
			session()->forget('fumVoucher');

			$order_no = 'FUM-' . date('yd') . random_int(0, 9999);
			if(!session()->get('fumOrderNo')){
				session()->put('fumOrderNo', $order_no);
			} else {
				$order_no = session()->get('fumOrderNo');
			}

			if(Auth::check()) {
				$cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
					->where('user_type', 'member')->where('user_email', Auth::user()->username)
					->select('f_idcode', 'f_default_price', 'b.qty', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'slug', 'f_name_name', 'f_item_name', 'f_qty', 'f_reserved_qty', 'f_item_type')->get();
			}else{
				$cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
					->where('user_type', 'guest')->where('transaction_id', $order_no)
					->select('f_idcode', 'f_default_price', 'b.qty', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'slug', 'f_name_name', 'f_item_name', 'f_qty', 'f_reserved_qty', 'f_item_type')->get();
			}

			if(count($cart_items) <= 0) {
				return redirect('/cart');
			}

			foreach ($cart_items as $n => $item) {
				if ($item->qty > $item->f_qty) {
					return redirect()->back()->with('error', 'Insufficient stock for <b>' . $item->f_name_name . '</b>');
				}
			}

			 // get sitewide sale
			$sale = DB::table('fumaco_on_sale')
				->whereDate('start_date', '<=', Carbon::now()->toDateString())
				->whereDate('end_date', '>=', Carbon::today()->toDateString())
				->where('status', 1)->where('apply_discount_to', 'All Items')
				->select('discount_rate', 'discount_type')->first();

			$item_codes = array_column($cart_items->toArray(), 'f_idcode');
			
			$clearance_sale_items = $on_sale_items = [];
			if (count($item_codes) > 0) {
				$item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $item_codes)
					->select('imgprimayx', 'idcode')->get();
				$item_images = collect($item_images)->groupBy('idcode')->toArray();

				$clearance_sale_items = $this->isIncludedInClearanceSale($item_codes);

				$on_sale_items = $this->onSaleItems($item_codes);
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

			$cart_arr = collect($cart_items)->map(function ($q){
				return [
					'item_code' => $q->f_idcode,
					'category_id' => $q->f_cat_id,
					'quantity' => $q->qty,
				];
			});

			$price_rule = $this->getPriceRules($cart_arr);
        	$price_rule = isset($price_rule['price_rule']) ? $price_rule['price_rule'] : [];

			$cart_arr = [];
			$order_cart = [];
			foreach ($cart_items as $n => $item) {
				$image = null;
				if (array_key_exists($item->f_idcode, $item_images)) {
					$image = $item_images[$item->f_idcode][0]->imgprimayx;
				}

				$is_new_item = 0;
				if($item->f_new_item == 1){
					if($item->f_new_item_start <= Carbon::now() and $item->f_new_item_end >= Carbon::now()){
						$is_new_item = 1;
					}
				}

				$on_sale = false;
				$discount_type = $discount_rate = null;
				if (array_key_exists($item->f_idcode, $on_sale_items)) {
					$on_sale = $on_sale_items[$item->f_idcode]['on_sale'];
					$discount_type = $on_sale_items[$item->f_idcode]['discount_type'];
					$discount_rate = $on_sale_items[$item->f_idcode]['discount_rate'];
				}

				$item_detail = [
					'default_price' => $item->f_default_price,
					'category_id' => $item->f_cat_id,
					'item_code' => $item->f_idcode,
					'discount_type' => $discount_type,
					'discount_rate' => $discount_rate,
					'stock_uom' => $item->f_stock_uom,
					'on_sale' => $on_sale
				];

				$is_on_clearance_sale = false;
				if (array_key_exists($item->f_idcode, $clearance_sale_items)) {
					$item_detail['discount_type'] = $clearance_sale_items[$item->f_idcode][0]->discount_type;
					$item_detail['discount_rate'] = $clearance_sale_items[$item->f_idcode][0]->discount_rate;
					$is_on_clearance_sale = true;
				}

				// get item price, discounted price and discount rate
				$item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);

				$price = $item_price_data['discounted_price'];
				$total_amount = $price * $item->qty;
				$item_discount = $item_price_data['discount_rate'] ? $item_price_data['discount_rate'] : 0;

				$discount_type = isset($item_price_data['discount_type']) ? $item_price_data['discount_type'] : null;
				
				if(isset($price_rule[$item->f_idcode]) && !isset($price_rule['Transaction'])){
					$rule = $price_rule[$item->f_idcode];
					$item_discount = $rule['discount_rate'];
					switch ($rule['discount_type']) {
						case 'Percentage':
							$discount_type = 'percentage';
							$discount_rate = $total_amount * ($rule['discount_rate'] / 100);
							$total_amount = $total_amount - $discount_rate;
							break;
						default:
							$discount_type = 'Fixed Amount';
							$total_amount = $total_amount - $rule['discount_rate'];
							break;
					}
				}

				$cart_arr[] = [
					'item_code' => $item->f_idcode,
					'item_description' => $item->f_name_name,
					'alt' => $item->f_item_name,
					'price' => $price,
					'subtotal' => ($total_amount),
					'original_price' => $item_price_data['item_price'],
					'discount' => ($item_discount > 0) ? $item_price_data['is_on_sale'] : 0,
					'quantity' => $item->qty,
					'stock_qty' => $item->f_qty,
					'item_image' => $image,
					'uom' => $item->f_stock_uom,
					'category_id' => $item->f_cat_id
				];

				$existing_order_item = DB::table('fumaco_order_items')->where('order_number', $order_no)
					->where('item_code', $item->f_idcode)->exists();
			
				if($existing_order_item){
					// update item qty
					DB::table('fumaco_order_items')->where('order_number', $order_no)
						->where('item_code', $item->f_idcode)->update([
							'item_name' => $item->f_name_name,
							'item_discount' => $item_discount,
							'item_discount_type' => $discount_type, 
							'item_original_price' => $item->f_default_price,
							'item_qty' => $item->qty,
							'item_price' => $price,	
							'item_total_price' => $total_amount,
						]);
				} else {
					$order_cart[] = [
						'order_number' => $order_no,
						'item_code' => $item->f_idcode,
						'item_name' => $item->f_name_name,
						'item_discount' => $item_discount,
						'item_discount_type' => $discount_type, 
						'item_original_price' => $item->f_default_price,
						'item_qty' => $item->qty,
						'item_price' => $price,
						'item_status' => 2,
						'date_update' => Carbon::now()->toDateTimeString(),
						'ip_address' => $request->ip(),
						'item_total_price' => $total_amount,
						'item_type' => $item->f_item_type,
					];
				}
			}
			
			DB::table('fumaco_order_items')->insert($order_cart);

			$shipping_rates = $this->getShippingRates();
			
			$shipping_add = $billing_add = [];
			if (Auth::check()) {
				$billing_add = DB::table('fumaco_user_add')->where('user_idx', Auth::user()->id)->where('address_class','Billing')
					->select('id', 'xdefault', 'xadd1', 'xadd2', 'xprov', 'xcontactlastname1', 'xcontactname1', 'add_type', 'xcontactnumber1', 'xmobile_number', 'xcontactemail1', 'xpostal', 'xcountry', 'xbusiness_name', 'xtin_no', 'xcity', 'xpostal', 'xbrgy')->get();

				$billing_address = collect($billing_add)->where('xdefault', 1)->first();
				$billing_address = collect($billing_address)->toArray();

				$billing_details = [
					'contact_person' => $billing_address['xcontactname1'] . ' ' . $billing_address['xcontactlastname1'],
					'address_line1' => $billing_address['xadd1'],
					'address_line2' => $billing_address['xadd2'],
					'province' => $billing_address['xprov'],
					'city' => $billing_address['xcity'],
					'brgy' => $billing_address['xbrgy'],
					'postal_code' => $billing_address['xpostal'],
					'country' => $billing_address['xcountry'],
					'address_type' => $billing_address['add_type'],
					'business_name' => $billing_address['xbusiness_name'],
					'tin' => $billing_address['xtin_no'],
					'email_address' => $billing_address['xcontactemail1'],
					'mobile_no' => $billing_address['xmobile_number'],
				];

				$shipping_add = DB::table('fumaco_user_add')->where('user_idx', Auth::user()->id)->where('address_class','Delivery')
					->select('id', 'xdefault', 'xadd1', 'xadd2', 'xprov', 'xcontactlastname1', 'xcontactname1', 'add_type', 'xcontactnumber1', 'xmobile_number', 'xcontactemail1', 'xpostal', 'xcountry', 'xbusiness_name', 'xtin_no', 'xcity', 'xpostal', 'xbrgy')->get();
				
				$shipping_address = collect($shipping_add)->where('xdefault', 1)->first();
				$shipping_address = collect($shipping_address)->toArray();

				$shipping_details = [
					'contact_person' => $shipping_address['xcontactname1'] . ' ' . $shipping_address['xcontactlastname1'],
					'address_line1' => $shipping_address['xadd1'],
					'address_line2' => $shipping_address['xadd2'],
					'province' => $shipping_address['xprov'],
					'city' => $shipping_address['xcity'],
					'brgy' => $shipping_address['xbrgy'],
					'postal_code' => $shipping_address['xpostal'],
					'country' => $shipping_address['xcountry'],
					'address_type' => $shipping_address['add_type'],
					'business_name' => $shipping_address['xbusiness_name'],
					'tin' => $shipping_address['xtin_no'],
					'email_address' => $shipping_address['xcontactemail1'],
					'mobile_no' => $shipping_address['xmobile_number'],
					'same_as_billing' => 0
				];

				$ship = collect($shipping_details)->except('same_as_billing');
				$address_check = $ship->diff($billing_details);

				$same_as_billing = 0;
				if($shipping_details['same_as_billing'] == 0){
					$same_as_billing = $address_check->isEmpty() ? 1 : 0;
					$shipping_details['same_as_billing'] = $same_as_billing;	
				}
			} else {
				$temp_data = DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->first();
				$shipping_details = [
					'contact_person' => $temp_data->xshipcontact_person,
					'address_line1' => $temp_data->xshippadd1,
					'address_line2' => $temp_data->xshippadd2,
					'province' => $temp_data->xshiprov,
					'city' => $temp_data->xshipcity,
					'brgy' => $temp_data->xshipbrgy,
					'postal_code' => $temp_data->xshippostalcode,
					'country' => $temp_data->xshipcountry,
					'address_type' => $temp_data->xshiptype,
					'business_name' => $temp_data->xship_business_name,
					'tin' => $temp_data->xship_tin,
					'email_address' => $temp_data->xemail_shipping,
					'mobile_no' => $temp_data->xcontact,
					'same_as_billing' => $temp_data->shipping_same_as_billing
				];

				$billing_details = [
					'contact_person' => $temp_data->xcontact_person,
					'address_line1' => $temp_data->xadd1,
					'address_line2' => $temp_data->xadd2,
					'province' => $temp_data->xprov,
					'city' => $temp_data->xcity,
					'brgy' => $temp_data->xbrgy,
					'postal_code' => $temp_data->xpostal,
					'country' => $temp_data->xcountry,
					'address_type' => $temp_data->xaddresstype,
					'business_name' => $temp_data->xbusiness_name,
					'tin' => $temp_data->xtin_no,
					'email_address' => $temp_data->xemail,
					'mobile_no' => $temp_data->xmobile,
				];
			}

			$is_on_payment_page = DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->where('last_transaction_page', 'Payment Page')->exists();
			if (!$is_on_payment_page) {
				DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->update(['last_transaction_page' => 'Checkout Page']);
			}

			$shipping_zones = DB::table('fumaco_shipping_zone_rate')->distinct()->pluck('province_name')->toArray();
			$free_shipping_remarks = null;
			if ($shipping_rates['free_delivery_zones']) {
				$free_shipping_remarks = $shipping_rates['free_delivery_zones'];
			}

			$free_shipping_remarks = null;
			if ($shipping_rates['free_delivery_zones']) {
				$free_shipping_remarks = $shipping_rates['free_delivery_zones'];
			}

			$shipping_rates = $shipping_rates['shipping_offer_rates'];

			$check_shipping_service_discount = DB::table('fumaco_on_sale as p')
				->join('fumaco_on_sale_shipping_service as c', 'p.id', 'c.sale_id')
				->where('status', 1)->where('p.apply_discount_to', 'Per Shipping Service')->whereDate('p.start_date', '<=', Carbon::now())->whereDate('p.end_date', '>=', Carbon::now())
				->get();

			$shipping_service_discount = collect($check_shipping_service_discount)->groupBy('shipping_service');

			$payment_methods = DB::table('fumaco_payment_method')->where('is_enabled', 1)
				->select('payment_method_name', 'payment_type', 'issuing_bank', 'show_image', 'image')->get();

			$available_voucher = DB::table('fumaco_voucher')
				->when(!Auth::check(), function ($q){
					return $q->where('require_signin', 0);
				})
				->where('minimum_spend', '<=', collect($cart_arr)->sum('subtotal'))->where('auto_apply', 1)
				->orderByRaw('LENGTH(order_no)', 'ASC')->orderBy('order_no', 'ASC')->orderBy('created_at', 'ASC')
				->get();

			$applicable_voucher = null;
			foreach ($available_voucher as $voucher) {
				$available = 1;
				if(!$voucher->unlimited){
					if($voucher->total_allotment < $voucher->total_consumed){
						$available = 0;
					}
				}

				if(Carbon::now() < Carbon::parse($voucher->validity_date_start)->startOfDay() || Carbon::now() > Carbon::parse($voucher->validity_date_end)->endOfDay()){
					$available = 0;
				}

				if($voucher->discount_type == 'Fixed Amount' && $voucher->discount_rate > collect($cart_arr)->sum('subtotal')){
					$available = 0;
				}

				if($available){
					$applicable_voucher = $voucher->code;

					break; // get only the first applicable coupon
				}
			}

			DB::commit();

			return view('frontend.checkout.check_out_summary', compact('shipping_details', 'billing_details', 'shipping_rates', 'order_no', 'cart_arr', 'shipping_add', 'billing_add', 'shipping_zones', 'payment_methods', 'free_shipping_remarks', 'shipping_service_discount', 'applicable_voucher', 'price_rule'));
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}		
	}

	public function saveOrder(Request $request) {
		DB::beginTransaction();
		try {
			$order_no = session()->get('fumOrderNo');
			$voucher_code = session()->get('fumVoucher');

			$existing_order_temp = DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->first();
			if(!$existing_order_temp){
				return response()->json(['status' => 2, 'message' => 'An error occured. Please try again.']);
			} else {
				$attempt = $existing_order_temp->payment_attempt + 1;
				DB::table('fumaco_temp')->where('id', $existing_order_temp->id)->update([
					'shipping_name' => $request->s_name,
					'shipping_amount' => $request->s_amount,
					'payment_method' => $request->pay_name,
					'issuing_bank' => $request->ib,
					'estimated_delivery_date' => $request->estimated_del,
					'xstore_location' => ($request->s_name == 'Store Pickup') ? $request->storeloc : null,
					'xpickup_date' => ($request->s_name == 'Store Pickup') ? Carbon::parse($request->picktime)->format('Y-m-d') : null,
					'voucher_code' => ($voucher_code) ? strtoupper($voucher_code) : null,
					'payment_attempt' => $attempt,
					'last_transaction_page' => 'Payment Page'
				]);
			}

			DB::table('fumaco_order_items')->where('order_number', $order_no)->delete();

			if(Auth::check()) {
				$cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
					->select('f_idcode', 'f_default_price', 'b.qty', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'slug', 'f_name_name', 'f_qty', 'f_reserved_qty', 'f_item_type')
					->where('user_type', 'member')->where('user_email', Auth::user()->username)->get();
			} else {
				$cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
					->select('f_idcode', 'f_default_price', 'b.qty', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'slug', 'f_name_name', 'f_qty', 'f_reserved_qty', 'f_item_type')
					->where('user_type', 'guest')->where('transaction_id', $order_no)->get();
			}

			 // get sitewide sale
			$sale = DB::table('fumaco_on_sale')
				->whereDate('start_date', '<=', Carbon::now()->toDateString())
				->whereDate('end_date', '>=', Carbon::today()->toDateString())
				->where('status', 1)->where('apply_discount_to', 'All Items')->first();

			$clearance_sale_items = $this->isIncludedInClearanceSale(array_column($cart_items->toArray(), 'f_idcode'));
			$on_sale_items = $this->onSaleItems(array_column($cart_items->toArray(), 'f_idcode'));

			$sale_per_category = [];
			if (!$sale && !Auth::check()) {
				$item_categories = array_column($cart_items->toArray(), 'f_cat_id');
				$sale_per_category = $this->getSalePerItemCategory($item_categories);
			}

			if (Auth::check()) {
				$customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

				$sale = $customer_group_sale ? $customer_group_sale : $sale;
			}

			$cart_arr = collect($cart_items)->map(function ($q){
				return [
					'item_code' => $q->f_idcode,
					'category_id' => $q->f_cat_id,
					'quantity' => $q->qty,
				];
			});

			$price_rule = $this->getPriceRules($cart_arr);
        	$price_rule = isset($price_rule['price_rule']) ? $price_rule['price_rule'] : [];
 
			$cart_arr = [];
			foreach ($cart_items as $n => $item) {
				$on_sale = false;
				$discount_type = null;
				$discount_rate = 0;
				if (array_key_exists($item->f_idcode, $on_sale_items)) {
					$on_sale = $on_sale_items[$item->f_idcode]['on_sale'];
					$discount_type = $on_sale_items[$item->f_idcode]['discount_type'];
					$discount_rate = $on_sale_items[$item->f_idcode]['discount_rate'];
				}

				$item_detail = [
					'default_price' => $item->f_default_price,
					'category_id' => $item->f_cat_id,
					'item_code' => $item->f_idcode,
					'discount_type' => $discount_type,
					'discount_rate' => $discount_rate,
					'stock_uom' => $item->f_stock_uom,
					'on_sale' => $on_sale
				];

				$is_on_clearance_sale = false;
				if (array_key_exists($item->f_idcode, $clearance_sale_items)) {
					$item_detail['discount_type'] = $clearance_sale_items[$item->f_idcode][0]->discount_type;
					$item_detail['discount_rate'] = $clearance_sale_items[$item->f_idcode][0]->discount_rate;
					$is_on_clearance_sale = true;
				}

				// get item price, discounted price and discount rate
				$item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);
			
				$price = $item_price_data['discounted_price'];
				$item_image = DB::table('fumaco_items_image_v1')
					->where('idcode', $item->f_idcode)->first();

				$total_amount = $price * $item->qty;
				$item_discount = $item_price_data['discount_rate'];

				$discount_type = isset($item_price_data['discount_type']) ? $item_price_data['discount_type'] : null;
				
				if(isset($price_rule[$item->f_idcode]) && !isset($price_rule['Transaction'])){
					$rule = $price_rule[$item->f_idcode];
					$item_discount = $rule['discount_rate'];
					switch ($rule['discount_type']) {
						case 'Percentage':
							$discount_type = 'percentage';
							$discount_rate = $total_amount * ($rule['discount_rate'] / 100);
							$total_amount = $total_amount - $discount_rate;
							break;
						default:
							$discount_type = 'Fixed Amount';
							$total_amount = $total_amount - $rule['discount_rate'];
							break;
					}
				}

				$existing_order_item = DB::table('fumaco_order_items')->where('order_number', $order_no)
						->where('item_code', $item->f_idcode)->exists();
			
				if($existing_order_item){
					// update item qty
					DB::table('fumaco_order_items')->where('order_number', $order_no)
						->where('item_code', $item->f_idcode)->update([
							'item_name' => $item->f_name_name,
							'item_discount' => $item_discount,
							'item_discount_type' => $discount_type,
							'item_original_price' => $item->f_default_price,
							'item_qty' => $item->qty,
							'item_price' => $price,	
							'item_total_price' => $total_amount,
						]);
				} else {
					$cart_arr[] = [
						'order_number' => $order_no,
						'item_code' => $item->f_idcode,
						'item_name' => $item->f_name_name,
						'item_discount' => $item_discount,
						'item_discount_type' => $discount_type,
						'item_original_price' => $item->f_default_price,
						'item_qty' => $item->qty,
						'item_price' => $price,
						'item_status' => 2,
						'date_update' => Carbon::now()->toDateTimeString(),
						'ip_address' => $request->ip(),
						'item_total_price' => $total_amount,
						'item_type' => $item->f_item_type,
					];
				}
			}

			DB::table('fumaco_order_items')->insert($cart_arr);

			DB::commit();
			session()->forget('fumVoucher');

			return response()->json(['status' => 1, 'id' => $order_no, 'code' => $existing_order_temp->xtempcode]);
		} catch (Exception $e) {
			DB::rollback();

			return response()->json(['status' => 2, 'message' => 'An error occured. Please try again.']);
		}
	}

	// EGHL payment form
	public function viewPaymentForm($order_no, Request $request) {
		if($request->ajax()) {
			$api = DB::table('api_setup')->where('type', 'payment_api')->first();

			$temp = DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->first();

			$items = DB::table('fumaco_order_items as order')
				->join('fumaco_items as item', 'item.f_idcode', 'order.item_code')
				->where('order.order_number', $order_no)
				->select('order.*', 'item.f_cat_id')
				->get();

			$items_arr = collect($items)->map(function ($q){
				return [
					'item_code' => $q->item_code,
					'quantity' => $q->item_qty,
					'category_id' => $q->f_cat_id,
					'subtotal' => $q->item_total_price
				];
			});

			$price_rule = $this->getPriceRules($items_arr);
			$price_rule = $price_rule['price_rule'];
	
			$amount = collect($items)->sum('item_total_price');

			if(isset($price_rule['Transaction'])){
				$rule = $price_rule['Transaction'];
				switch ($rule['discount_type']) {
					case 'Percentage':
						$discount_amount = $amount * ($rule['discount_rate'] / 100);
						break;
					default:
						$discount_amount = $amount > $rule['discount_rate'] ? $rule['discount_rate'] : 0;
						break;
				}

				$amount = $amount - $discount_amount;
			}

			$discount = 0;
			if ($temp) {
				if($temp->voucher_code) {
					$voucher_details = DB::table('fumaco_voucher')
						->where('code', strtoupper($temp->voucher_code))->first();
					
					$is_voucher_valid = false;
					if ($voucher_details) {
						$is_voucher_valid = true;
						if($voucher_details->validity_date_start && $voucher_details->validity_date_end) {
							if ($voucher_details->validity_date_start && $voucher_details->validity_date_end) {
								$startDate = Carbon::parse($voucher_details->validity_date_start)->startOfDay();
								$endDate = Carbon::parse($voucher_details->validity_date_end)->endOfDay();
								$checkDate = Carbon::now()->between($startDate, $endDate);
								if (!$checkDate) {
									$is_voucher_valid = false;
								}
							}
						}
	
						if($voucher_details->minimum_spend > 0) {
							if($amount < $voucher_details->minimum_spend) {
								$is_voucher_valid = false;
							}
						}

						if(!$voucher_details->unlimited) {
							if ($voucher_details->total_allotment <= $voucher_details->total_consumed) {
								$is_voucher_valid = false;
							}
						}

						if($voucher_details->coupon_type == 'Promotional') {
							if($voucher_details->require_signin) {
								if (!Auth::check()) {
									$is_voucher_valid = false;
								}
			
								// count consumed voucher for loggedin user
								$consumed_voucher = DB::table('fumaco_order')->where('user_email', Auth::user()->username)
									->where('voucher_code', $voucher_details->code)->count();
								if ($consumed_voucher >= $voucher_details->allowed_usage) {
									$is_voucher_valid = false;
								}
							}
						}
			
						if($voucher_details->coupon_type == 'Exclusive Voucher') {
							if(Auth::check()) {
								// check if voucher is applicable for loggedin user
								$not_existing_voucher_customer = DB::table('fumaco_voucher_customers as a')
									->join('fumaco_users as b', 'a.customer_id', 'b.id')->where('a.voucher_id', $voucher_details->id)
									->where('b.username', Auth::user()->username)->doesntExist();
								if ($not_existing_voucher_customer) {
									$is_voucher_valid = false;
								}
								// count consumed voucher for loggedin user
								$consumed_voucher = DB::table('fumaco_order')->where('user_email', Auth::user()->username)
									->where('voucher_code', $voucher_details->code)->count();
								if ($consumed_voucher >= $voucher_details->allowed_usage) {
									$is_voucher_valid = false;
								}
							} else {
								$is_voucher_valid = false;
							}
						}
						
						if ($is_voucher_valid) {
							if($voucher_details->discount_type == 'By Percentage') {
								$discount = ($voucher_details->discount_rate/100) * $amount;
								if($voucher_details->capped_amount > 0) {
									if ($discount > $voucher_details->capped_amount) {
										$discount = $voucher_details->capped_amount;
									}
								}
							}
				
							if($voucher_details->discount_type == 'Fixed Amount') {
								$discount = $voucher_details->discount_rate;
							}
				
							if($voucher_details->discount_type == 'Free Delivery') {
								$discount = 0;
							}
						}
					}
				}
			}

			// Store Pickup Discount
			$shipping_discount = [];
			$shipping_discount_amount = 0;
			if($temp->shipping_name == 'Store Pickup'){
				$shipping_discount = $this->getSalePerShippingService($temp->shipping_name);

				// return collect($shipping_discount);
				if($shipping_discount){
					switch ($shipping_discount->discount_type) {
						case 'Fixed Amount':
							$shipping_discount_amount = $shipping_discount->discount_rate;
							break;
						case 'By Percentage':
							$shipping_discount_amount = ($shipping_discount->discount_rate / 100) * $amount;
							$shipping_discount_amount = $shipping_discount_amount > $shipping_discount->capped_amount ? $shipping_discount->capped_amount : $shipping_discount_amount;
							break;
						default:
							break;
					}
				}
			}

			$shipping_discount_amount = $shipping_discount_amount > $amount ? 0 : $shipping_discount_amount;
	
			$grand_total = $amount - ($discount + $shipping_discount_amount);
			$grand_total = $grand_total + $temp->shipping_amount;

			return view('frontend.checkout.eghl_form', compact('temp', 'api', 'grand_total'));
		}
	}

	public function orderSuccess($id, Request $request) {
		DB::beginTransaction();
		try {
			$temp = DB::table('fumaco_temp')->where('xtempcode', $id)->first();
			if(!$temp) {
				return redirect('/');
			}

			$now = Carbon::now();

			$order_items = DB::table('fumaco_order_items as order_items')
				->join('fumaco_items as item', 'order_items.item_code', 'item.f_idcode')
				->where('order_items.order_number', $temp->order_tracker_code)
				->select('order_items.*', 'item.f_cat_id')
				->get();

			$loggedin = ($temp->xusernamex) ? $temp->xusernamex : $temp->xemail_shipping;

			$phone = $temp->xmobile;

			$items = [];
			foreach($order_items as $row) {
				$image = DB::table('fumaco_items_image_v1')->where('idcode', $row->item_code)->first();

				$items[] = [
					'item_code' => $row->item_code,
					'item_name' => $row->item_name,
					'price' => $row->item_price,
					'discount' => $row->item_discount,
					'discount_type' => $row->item_discount_type,
					'qty' => $row->item_qty,
					'amount' => $row->item_total_price,
					'image' => ($image) ? $image->imgprimayx : null,
					// for price rules
					'quantity' => $row->item_qty,
					'category_id' => $row->f_cat_id,
					'subtotal' => $row->item_total_price
				];

				// update reserved qty for items
				$item_details = DB::table('fumaco_items')->where('f_idcode', $row->item_code)->first();
				if($item_details) {
					DB::table('fumaco_items')->where('f_idcode', $row->item_code)->update([
						'f_reserved_qty' => $item_details->f_reserved_qty + $row->item_qty,
					]);
				}
			}

			$subtotal = collect($order_items)->sum('item_total_price');

			$price_rule = $this->getPriceRules($items);
			$price_rule = isset($price_rule['price_rule']) ? $price_rule['price_rule'] : [];

			$pr_discount_rate = 0;
			if(isset($price_rule['Transaction'])){
				$rule = $price_rule['Transaction'];
				switch ($rule['discount_type']) {
					case 'Percentage':
						$pr_discount_rate = $subtotal * ($rule['discount_rate'] / 100);
						break;
					default:
						$pr_discount_rate = $subtotal > $rule['discount_rate'] ? $rule['discount_rate'] : 0;
						break;
				}

				$price_rule = collect($price_rule)->merge(['discount_amount' => $pr_discount_rate]);

				$subtotal = $subtotal - $pr_discount_rate;
			}

			$discount = 0;
			$is_voucher_valid = false;
			if($temp->voucher_code) {
				$voucher_details = DB::table('fumaco_voucher')
					->where('code', strtoupper($temp->voucher_code))->first();
				
				if ($voucher_details) {
					$is_voucher_valid = true;
					if($voucher_details->validity_date_start && $voucher_details->validity_date_end) {
						if ($voucher_details->validity_date_start && $voucher_details->validity_date_end) {
							$startDate = Carbon::parse($voucher_details->validity_date_start)->startOfDay();
							$endDate = Carbon::parse($voucher_details->validity_date_end)->endOfDay();
							$checkDate = Carbon::now()->between($startDate, $endDate);
							if (!$checkDate) {
								$is_voucher_valid = false;
							}
						}
					}

					if($voucher_details->minimum_spend > 0) {
						if($subtotal < $voucher_details->minimum_spend) {
							$is_voucher_valid = false;
						}
					}

					if(!$voucher_details->unlimited) {
						if ($voucher_details->total_allotment <= $voucher_details->total_consumed) {
							$is_voucher_valid = false;
						}
					}

					if($voucher_details->coupon_type == 'Promotional') {
						if($voucher_details->require_signin) {
							if (!$loggedin) {
								$is_voucher_valid = false;
							}
		
							// count consumed voucher for loggedin user
							$consumed_voucher = DB::table('fumaco_order')->where('user_email', $loggedin)
								->where('voucher_code', $voucher_details->code)->count();
							if ($consumed_voucher >= $voucher_details->allowed_usage) {
								$is_voucher_valid = false;
							}
						}
					}
		
					if($voucher_details->coupon_type == 'Exclusive Voucher') {
						if($loggedin) {
							// check if voucher is applicable for loggedin user
							$not_existing_voucher_customer = DB::table('fumaco_voucher_customers as a')
								->join('fumaco_users as b', 'a.customer_id', 'b.id')->where('a.voucher_id', $voucher_details->id)
								->where('b.username', $loggedin)->doesntExist();
							if ($not_existing_voucher_customer) {
								$is_voucher_valid = false;
							}
							// count consumed voucher for loggedin user
							$consumed_voucher = DB::table('fumaco_order')->where('user_email', $loggedin)
								->where('voucher_code', $voucher_details->code)->count();
							if ($consumed_voucher >= $voucher_details->allowed_usage) {
								$is_voucher_valid = false;
							}
						} else {
							$is_voucher_valid = false;
						}
					}
					
					if ($is_voucher_valid) {
						if($voucher_details->discount_type == 'By Percentage') {
							$discount = ($voucher_details->discount_rate/100) * $subtotal;
							if($voucher_details->capped_amount > 0) {
								if ($discount > $voucher_details->capped_amount) {
									$discount = $voucher_details->capped_amount;
								}
							}
						}
			
						if($voucher_details->discount_type == 'Fixed Amount') {
							$discount = $voucher_details->discount_rate;
						}
			
						if($voucher_details->discount_type == 'Free Delivery') {
							$discount = 0;
						}

						DB::table('fumaco_voucher')->where('code', $temp->voucher_code)->update(['total_consumed' => $voucher_details->total_consumed + 1]);
					}
				}
			}

			// insert orders if not existing
			$existing_order = DB::table('fumaco_order')
				->where('order_number', $temp->order_tracker_code)->exists();
			if (!$existing_order) {
				switch ($request->PymtMethod) {
					case 'CC':
						$payment_method = 'Credit Card';
						break;
					case 'MO':
						$payment_method = 'Credit Card (MOTO)';
						break;
					case 'DD':
						$payment_method = 'Direct Debit';
						break;
					case 'WA':
						$payment_method = 'e-Wallet';
						break;
					default:
						$payment_method = $request->PymtMethod;
						break;
				}

				if(!$payment_method) {
					$payment_method = $temp->payment_method;
				}

				$default_payment_status = 'Payment Received';
				if($payment_method == 'Bank Deposit'){
					$default_payment_status = DB::table('fumaco_payment_status')->where('status_sequence', 1)->pluck('status')->first();
				}

				// Store Pickup Discount
				$shipping_discount = [];
				$shipping_discount_amount = 0;
				if($temp->shipping_name == 'Store Pickup'){
					$shipping_discount = $this->getSalePerShippingService($temp->shipping_name);
					if($shipping_discount){
						switch ($shipping_discount->discount_type) {
							case 'Fixed Amount':
								$shipping_discount_amount = $shipping_discount->discount_rate;
								break;
							case 'By Percentage':
								$shipping_discount_amount = ($shipping_discount->discount_rate / 100) * $subtotal;
								$shipping_discount_amount = $shipping_discount_amount > $shipping_discount->capped_amount ? $shipping_discount->capped_amount : $shipping_discount_amount;
								break;
							default:
								break;
						}
					}
				}

				$shipping_discount_amount = $subtotal > $shipping_discount_amount ? $shipping_discount_amount : 0;
				
				$grand_total = (collect($order_items)->sum('item_total_price') + $temp->shipping_amount) - ($discount + $shipping_discount_amount + $pr_discount_rate);

				DB::table('fumaco_order')->insert([
					'order_number' => $temp->xlogs,
					'order_account' => $temp->xuser_id, // account number of logged user
					'order_name' => $temp->xfname,
					'order_lastname' => $temp->xlname,
					'order_bill_address1' => $temp->xadd1,
					'order_bill_address2' => $temp->xadd2,
					'order_bill_prov' => $temp->xprov,
					'order_bill_city' => $temp->xcity,
					'order_bill_brgy' => $temp->xbrgy,
					'order_bill_postal' => $temp->xpostal,
					'order_bill_country' => $temp->xcountry,
					'order_bill_type' => $temp->xaddresstype,
					'order_bill_contact' => $phone,
					'order_bill_email' => $temp->xemail,
					'order_contactperson' => $temp->xcontact_person,
					'order_ship_contactperson' => $temp->xshipcontact_person,
					'order_ship_address1' => $temp->xshippadd1,
					'order_ship_address2' => $temp->xshippadd2,
					'order_ship_prov' => $temp->xshiprov,
					'order_ship_city' => $temp->xshipcity,
					'order_ship_brgy' => $temp->xshipbrgy,
					'order_ship_postal' => $temp->xshippostalcode,
					'order_ship_country' => $temp->xshipcountry,
					'order_ship_type' => $temp->xshiptype,
					'order_email' => $temp->xemail_shipping,
					'order_contact' => $temp->xcontact,
					'order_subtotal' => $subtotal,
					'order_shipping' => $temp->shipping_name,
					'order_shipping_amount' => $temp->shipping_amount,
					'order_ip' => $temp->order_ip,
				  	'order_date' => $now,
					'order_status' => "Order Placed",
					'payment_status' => $default_payment_status,
					'order_payment_method' => $payment_method,
					'tracker_code' => $temp->order_tracker_code,
					'estimated_delivery_date' => $temp->estimated_delivery_date,
					'payment_id' => $request->PaymentID,
					'bank_ref_no' => $request->BankRefNo,
					'issuing_bank' => $request->IssuingBank,
					'payment_transaction_time' => $request->RespTime,
					'amount_paid' => ($request->Amount) ? $request->Amount : 0,
					'grand_total' => $grand_total,
					'order_type' => $temp->xusertype,
					'user_email' => $loggedin,
					'shipping_business_name' => $temp->xship_business_name,
					'shipping_tin' => $temp->xship_tin,
					'billing_business_name' => $temp->xbusiness_name,
					'billing_tin' => $temp->xtin_no,
					'store_location' => $temp->xstore_location,
					'pickup_date' => $temp->xpickup_date,
					'pickup_time' => $temp->xpickup_time,
					'voucher_code' => ($is_voucher_valid) ? $temp->voucher_code : null,
					'discount_amount' => $discount + $shipping_discount_amount + $pr_discount_rate,
					'deposit_slip_token' => $payment_method == 'Bank Deposit' ? hash('sha256', Carbon::now()->toDateTimeString()) : null,
					'deposit_slip_token_date_created' => $payment_method == 'Bank Deposit' ? Carbon::now()->toDateTimeString() : null,
				]);

				// insert order in tracking order table
				DB::table('track_order')->insert([
					'track_code' => $temp->order_tracker_code,
					'track_date' => $now,
					'track_item' => 'Item Purchase',
					'track_description' => 'Your order is on processing',
					'track_status' => 'Order Placed',
					'track_payment_status' => $default_payment_status,
					'track_ip' => $temp->order_ip,
					'track_active' => 1,
					'transaction_member' => $temp->xusertype
				]);
			}

			DB::table('fumaco_temp')->where('xtempcode', $id)->delete();

			// delete item from cart
			if ($temp->xusertype != 'Guest') {
				DB::table('fumaco_cart')->where('user_type', 'member')->where('user_email', $loggedin)->delete();
			} else {
				DB::table('fumaco_cart')->where('user_type', 'guest')->where('transaction_id', $temp->xlogs)->delete();
			}
			
			$order_details = DB::table('fumaco_order')->where('order_number', $temp->xlogs)->first();

			$store_address = null;
			if($order_details->order_shipping == 'Store Pickup') {
				$store = DB::table('fumaco_store')->where('store_name', $order_details->store_location)->first();
				$store_address = ($store) ? $store->address : null;
			}
			$voucher_details = DB::table('fumaco_voucher')->where('code', $order_details->voucher_code)->first();

			$order = [
				'order_details' => $order_details,
				'items' => $items,
				'store_address' => $store_address,
				'new_token' => null,
				'voucher_details' => $voucher_details,
				'shipping_discount' => $shipping_discount,
				'price_rule' => $price_rule
			];

			// // send email to customer / client
			$emails = array_filter(array_unique([trim($order_details->order_bill_email), trim($order_details->order_email), trim($temp->xusernamex)]));

			$ordered_items = DB::table('fumaco_order_items as ordered')->where('order_number', $temp->xlogs)->pluck('item_code');
        
			$leadtime_arr = [];
			foreach($ordered_items as $item){
				$category_id = DB::table('fumaco_items')->where('f_idcode', $item)->pluck('f_cat_id')->first();
				if($temp->shipping_name != 'Store Pickup') {
					$shipping = DB::table('fumaco_shipping_service as shipping_service')
						->join('fumaco_shipping_zone_rate as zone_rate', 'shipping_service.shipping_service_id', 'zone_rate.shipping_service_id')
						->where('shipping_service.shipping_service_name', $temp->shipping_name)
						->where('zone_rate.province_name', $temp->xshiprov)
						->first();
				} else {
					$shipping = DB::table('fumaco_shipping_service')->where('shipping_service_name', $temp->shipping_name)->first();
				}
				
				$lead_time_per_category = DB::table('fumaco_shipping_product_category')->where('shipping_service_id', $shipping->shipping_service_id)
					->where('category_id', $category_id)->select('min_leadtime', 'max_leadtime')->first();

				$leadtime_arr[] = [
					'min_leadtime' => $lead_time_per_category ? $lead_time_per_category->min_leadtime : $shipping->min_leadtime,
					'max_leadtime' => $lead_time_per_category ? $lead_time_per_category->max_leadtime : $shipping->max_leadtime
				];
			}

			$min_leadtime = collect($leadtime_arr)->pluck('min_leadtime')->max();
			$max_leadtime = collect($leadtime_arr)->pluck('max_leadtime')->max();

			$bank_accounts = [];
			$view = 'frontend.checkout.success';

			$url = null;
			$deposit_slip_url = null;
			$sms_message = null;
			if($order_details->order_payment_method == 'Bank Deposit'){
				$bank_accounts = DB::table('fumaco_bank_account')->where('is_active', 1)->get();
				$view = 'frontend.checkout.order_success_page';
				
				$order['bank_accounts'] = $bank_accounts;
				
				try {
					Mail::send('emails.order_success_bank_deposit', $order, function($message) use ($emails) {
						$message->to($emails);
						$message->subject('Order Placed - Bank Deposit - FUMACO');
					});
				} catch (\Swift_TransportException  $e) {

				}

				$deposit_slip_url = $request->root().'/upload_deposit_slip/'.$order_details->deposit_slip_token;
				$shortened_deposit_slip_url = $this->generateShortUrl($request->root(), $deposit_slip_url);
				
				$sms_message = 'Hi '.$temp->xfname.' '.$temp->xlname.'!, to process your order please settle your payment thru bank deposit. Click '.$shortened_deposit_slip_url.' to upload your bank deposit slip.';
			}else{
				$tracking_url = $request->root().'/track_order/'.$temp->xlogs;
				$shortened_tracking_url = $this->generateShortUrl($request->root(), $tracking_url);
				
				$tracking_url_text = $shortened_tracking_url ? 'Click ' . $shortened_tracking_url . ' to track your order.' : null;

				$sms_message = 'Hi '.$temp->xfname.' '.$temp->xlname.'!, your order '.$temp->xlogs.' with an amount of P '.number_format($request->Amount, 2).' has been received, please allow '.$min_leadtime.' to '.$max_leadtime.' business days to process your order. ' . $tracking_url_text;

				try {
					Mail::to($emails)->queue(new OrderSuccess($order));
				} catch (\Swift_TransportException $e) {
		
				}
			}

			if ($url || $deposit_slip_url) {
				$sms_api = DB::table('api_setup')->where('type', 'sms_gateway_api')->first();
				if ($sms_api) {
					$sms = Http::asForm()->withHeaders([
						'Accept' => 'application/json',
						'Content-Type' => 'application/x-www-form-urlencoded',
					])->post($sms_api->base_url, [
						'api_key' => $sms_api->api_key,
						'api_secret' => $sms_api->api_secret_key,
						'from' => 'FUMACO',
						'to' => $phone,
						'text' => $sms_message
					]);
				}
			}
			
			// send email to fumaco staff
			$email_recipient = DB::table('email_config')->first();
			$email_recipient = ($email_recipient) ? explode(",", $email_recipient->email_recipients) : [];
			if (count(array_filter($email_recipient)) > 0) {
				try {
					Mail::send('emails.new_order', $order, function($message) use ($email_recipient) {
						$message->to($email_recipient);
						$message->subject('New Order - FUMACO');
					});
				} catch (\Swift_TransportException $e) {
					
				}
			}

			session()->forget('fumOrderNo');
			DB::commit();

			return view($view, compact('order_details', 'items', 'loggedin', 'store_address', 'bank_accounts', 'shipping_discount', 'voucher_details', 'price_rule'));
		} catch (Exception $e) {
			DB::rollback();

			return view('error');
		}
	}
	// get address detail from google maps api
	private function getAddressDetails($address){
        if($address){
            $response = \GoogleMaps::load('geocoding')
                ->setParam (['address' => $address])
                ->get();

            $components = [
                'political' => "long_name",
                'locality' => "long_name",
                'administrative_area_level_1' => "long_name",
                'administrative_area_level_2' => "long_name"
            ];

            $output= json_decode($response, true);
            $arr= [];
			if ($output['status'] != "ZERO_RESULTS") {
				for ($i = 0; $i < count($output['results'][0]['address_components']); $i++) {
					$address_type = $output['results'][0]['address_components'][$i]['types'][0];
					if(isset($components[$address_type])){
						array_push($arr, strtolower($output['results'][0]['address_components'][$i][$components[$address_type]]));
					}
				}
			}
            
            return $arr;
        }
    }

	private function delivery_leadtime($min, $max){
        $min_leadtime = Carbon::parse(now()->addDays($min));
        $max_leadtime = Carbon::parse(now()->addDays($max));

		$holidays = DB::table('fumaco_holiday')->where('holiday_date', '>=', $min_leadtime->format('Y-m-d'))->where('holiday_date', '<=', $max_leadtime->format('Y-m-d'))->select('holiday_date')->get();

		foreach($holidays as $holiday){
			$min_leadtime_d = (Carbon::parse($holiday->holiday_date)->format('m-d') == $min_leadtime->format('m-d')) ? $min_leadtime->addDays(1)->format('d') : $min_leadtime->format('d');
		}

		// $min_leadtime = $min_leadtime->addDays(count($holidays));
		$min_leadtime_y = $min_leadtime->format('Y');
        $min_leadtime_m = $min_leadtime->format('M');
        $min_leadtime_d = $min_leadtime->format('d');

		$max_leadtime = $max_leadtime->addDays(count($holidays));
        $max_leadtime_m = $max_leadtime->format('M');
        $max_leadtime_d = $max_leadtime->format('d');
        $max_leadtime_y = $max_leadtime->format('Y');

        if($min_leadtime->format('M d, Y') == $max_leadtime->format('M d, Y')){
            return $min_leadtime->format('M d, Y');
        }

        if($min_leadtime_y == $max_leadtime_y){
            if($min_leadtime_m == $max_leadtime_m){
                return $min_leadtime_m . ' ' . $min_leadtime_d . ' - ' . $max_leadtime_d . ', ' . $min_leadtime_y;
            }else{
                return $min_leadtime_m . ' ' . $min_leadtime_d . ' - ' . $max_leadtime_m . ' ' . $max_leadtime_d . ', ' . $min_leadtime_y;
            }
        } else {
            return $min_leadtime->format('M d, Y') . ' - ' . $max_leadtime->format('M d, Y');
        }
    }

	private function get_shipping_cost_per_calculation($operator, $op1, $op2, $cost){
        if($operator == '>') {
            if($op1 > $op2){
                return $cost;
            }
        }elseif($operator == '>=') {
            if($op1 >= $op2){
                return $cost;
            }
        }elseif($operator == '==') {
            if($op1 == $op2){
                return $cost;
            }
        }elseif($operator == '<=') {
            if($op1 <= $op2){
                return $cost;
            }
        }elseif($operator == '<') {
            if($op1 < $op2){
                return $cost;
            }
        }

        return false;
    }

	private function getShippingRates(){
		$shipping_details = [];
		if (Auth::check()) {
			$shipping_address = DB::table('fumaco_user_add')->where('xdefault', 1)
            	->where('user_idx', Auth::user()->id)->where('address_class', 'Delivery')->first();
            
            $shipping_details = [
                'address_line1' => $shipping_address->xadd1,
                'address_line2' => $shipping_address->xadd2,
                'province' => $shipping_address->xprov,
                'city' => $shipping_address->xcity,
                'brgy' => $shipping_address->xbrgy,
                'country' => $shipping_address->xcountry,
            ];
		} else {
			$order_no = session()->get('fumOrderNo');
			$shipping_address = DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->first();
			$shipping_details = [
                'address_line1' => $shipping_address->xshippadd1,
                'address_line2' => $shipping_address->xshippadd2,
                'province' => $shipping_address->xshiprov,
                'city' => $shipping_address->xshipcity,
                'brgy' => $shipping_address->xshipbrgy,
                'country' => $shipping_address->xshipcountry,
            ];
		}

		$address = strtolower($shipping_details['address_line1'] . ' ' . $shipping_details['address_line2'] . ' ' . $shipping_details['brgy']. ' ' . $shipping_details['city'] . ' ' . $shipping_details['province'] . ' ' .	$shipping_details['country']);
		$region = strtolower($shipping_details['province']);
		$city = strtolower($shipping_details['city']);

		$order_no = session()->get('fumOrderNo');
		if(Auth::check()) {
			$order_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
				->where('user_type', 'member')->where('user_email', Auth::user()->username)
				->select('f_idcode', 'f_default_price', 'b.qty', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_package_height', 'f_package_weight', 'f_package_length', 'f_package_width')->get();
		} else {
			$order_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
				->where('user_type', 'guest')->where('transaction_id', $order_no)
				->select('f_idcode', 'f_default_price', 'b.qty', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_package_height', 'f_package_weight', 'f_package_length', 'f_package_width')->get();
		}

		$total_amount = 0;
		$total_weight_of_items = 0;
		$total_cubic_cm = 0;

		// get sitewide sale
		$sale = DB::table('fumaco_on_sale')
			->whereDate('start_date', '<=', Carbon::now()->toDateString())
			->whereDate('end_date', '>=', Carbon::today()->toDateString())
			->where('status', 1)->where('apply_discount_to', 'All Items')
			->select('discount_type', 'discount_rate')->first();

		$clearance_sale_items = $this->isIncludedInClearanceSale(array_column($order_items->toArray(), 'f_idcode'));
		$on_sale_items = $this->onSaleItems(array_column($order_items->toArray(), 'f_idcode'));
		
		$sale_per_category = [];
		if (!$sale && !Auth::check()) {
			$item_categories = array_column($order_items->toArray(), 'f_cat_id');
			$sale_per_category = $this->getSalePerItemCategory($item_categories);
		}

		if (Auth::check()) {
            $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

			$sale = $customer_group_sale ? $customer_group_sale : $sale;
        }
			
        foreach ($order_items as $row) {
			$is_new_item = 0;
			if($row->f_new_item == 1){
				if($row->f_new_item_start <= Carbon::now() and $row->f_new_item_end >= Carbon::now()){
					$is_new_item = 1;
				}
			}

			$on_sale = false;
            $discount_type = $discount_rate = null;
            if (array_key_exists($row->f_idcode, $on_sale_items)) {
                $on_sale = $on_sale_items[$row->f_idcode]['on_sale'];
                $discount_type = $on_sale_items[$row->f_idcode]['discount_type'];
                $discount_rate = $on_sale_items[$row->f_idcode]['discount_rate'];
            }

			$item_detail = [
				'default_price' => $row->f_default_price,
				'category_id' => $row->f_cat_id,
				'item_code' => $row->f_idcode,
				'discount_type' => $discount_type,
				'discount_rate' => $discount_rate,
				'stock_uom' => $row->f_stock_uom,
				'on_sale' => $on_sale
			];

			$is_on_clearance_sale = false;
			if (array_key_exists($row->f_idcode, $clearance_sale_items)) {
				$item_detail['discount_type'] = $clearance_sale_items[$row->f_idcode][0]->discount_type;
				$item_detail['discount_rate'] = $clearance_sale_items[$row->f_idcode][0]->discount_rate;
				$is_on_clearance_sale = true;
			}

			// get item price, discounted price and discount rate
			$item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);

			$item_qty = $row->qty;
			$price = $item_price_data['discounted_price'];
			$total_amount += ($price * $item_qty);
            $cubic_cm = ($row->f_package_length * $row->f_package_width * $row->f_package_height);
            $cubic_cm = $cubic_cm * $item_qty;

            $total_cubic_cm += $cubic_cm;
            $total_weight_of_items += $row->f_package_weight * $item_qty;

            $packs[] = [
                "dimensions" => [(float)$row->f_package_length, (float)$row->f_package_width, (float)$row->f_package_height],
                "weight" => (float)$row->f_package_weight,
                "quantity" =>  (int)$item_qty
            ];
        }

		$intersect_array_counts = [];
        $shipping_address_arr = $this->getAddressDetails($address);

        // get shipping zone based on selected address
        $shipping_zones = ShippingZoneRate::whereIn('province_name', $shipping_address_arr)
			->select('city_code', 'city_name', 'province_name', 'shipping_service_id')
			->orderBy('shipping_service_id', 'desc')->get();

        $shipping_services_arr = [];
        foreach($shipping_zones as $row){
            $address = ($row->city_code > -1) ? $row->city_name . ' ' . $row->province_name : $row->province_name;
            $address_details = $this->getAddressDetails($address);

            $address_arr_intersect = array_intersect($shipping_address_arr, $address_details);

            if(!in_array($row->shipping_service_id, $shipping_services_arr)){
                if(isset($address_details) && in_array($city, $address_details) && in_array($region, $address_details)){
                    array_push($shipping_services_arr, $row->shipping_service_id);
           
                    $intersect_array_counts[] = [
                        'count_intersect' =>count($address_arr_intersect),
                        'shipping_service_id' => $row->shipping_service_id,
                    ];
                }
            }

            if(!in_array($row->shipping_service_id, $shipping_services_arr)){
                if(isset($address_details) && ($row->city_code == -1) && in_array($region, $address_details)){
                    array_push($shipping_services_arr, $row->shipping_service_id);

                    $intersect_array_counts[] = [
                        'count_intersect' => count($address_arr_intersect),
                        'shipping_service_id' => $row->shipping_service_id,
                    ];
                }
            }
        }

        $max_intersect = collect($intersect_array_counts)->max('count_intersect');
        $shipping_services_arr = collect($intersect_array_counts)->filter(function ($value, $key) use ($max_intersect){
            return $value['count_intersect'] == $max_intersect;
        })->toArray();

        $shipping_services_arr = array_column($shipping_services_arr, 'shipping_service_id');
        $shipping_services_without_conditions = ShippingService::where('shipping_calculation', 'Flat Rate')->whereIn('shipping_service_id', $shipping_services_arr)
			->select('shipping_service_id', 'min_leadtime', 'max_leadtime', 'shipping_service_name', 'amount')->get();

		$free_delivery_zones = [];
        $shipping_offer_rates = [];
        foreach($shipping_services_without_conditions as $row){
			$not_appicable_categories = DB::table('fumaco_shipping_product_category')
				->where('shipping_service_id', $row->shipping_service_id)
				->whereIn('category_id', array_column($order_items->toArray(), 'f_cat_id'))
				->count();
			if ($not_appicable_categories <= 0) {
				$max_leadtime = DB::table('fumaco_shipping_product_category')
					->whereIn('category_id', array_column($order_items->toArray(), 'f_cat_id'))
					->where('shipping_service_id', $row->shipping_service_id)->max('max_leadtime');

				$min_leadtime = DB::table('fumaco_shipping_product_category')
					->whereIn('category_id', array_column($order_items->toArray(), 'f_cat_id'))
					->where('shipping_service_id', $row->shipping_service_id)->max('min_leadtime');

				$min = ($min_leadtime > 0) ? $min_leadtime : $row->min_leadtime;
				$max = ($max_leadtime > 0) ? $max_leadtime : $row->max_leadtime;

				$expected_delivery_date = $this->delivery_leadtime($min, $max);

				$shipping_offer_rates[] = [
					'shipping_service_name' => $row->shipping_service_name,
					'expected_delivery_date' => $expected_delivery_date,
					'min_lead_time' => $min,
					'max_lead_time' => $max,
					'shipping_cost' => (float)$row->amount,
					'external_carrier' => false,
					'allow_delivery_after' => 0,
					'pickup' => false,
					'stores' => [],
				];
			}
        }

       $shipping_services = ShippingService::join('fumaco_shipping_condition as a', 'fumaco_shipping_service.shipping_service_id', 'a.shipping_service_id')
            ->whereIn('a.shipping_service_id', $shipping_services_arr)
			->select('shipping_calculation', 'shipping_amount', 'conditional_operator', 'value', 'min_charge_amount', 'max_charge_amount', 'a.shipping_service_id', 'min_leadtime', 'max_leadtime', 'shipping_service_name')->get();

        foreach($shipping_services as $row){
			if($row->shipping_calculation == 'Per Cubic cm') {
				$shipping_cost = $row->shipping_amount * $total_cubic_cm;
			}

			if($row->shipping_calculation == 'Per Weight') {
				$shipping_cost = $this->get_shipping_cost_per_calculation($row->conditional_operator, $total_weight_of_items, $row->value, $row->shipping_amount);
			}

			if($row->shipping_calculation == 'Per Amount') {
				$shipping_cost = $this->get_shipping_cost_per_calculation($row->conditional_operator, $total_amount, $row->value, $row->shipping_amount);
			}

			if($row->shipping_calculation == 'Per Quantity') {
				$shipping_cost = $this->get_shipping_cost_per_calculation($row->conditional_operator, $total_quantity_of_items, $row->value, $row->shipping_amount);
			}

			if($row->shipping_calculation != 'Flat Rate'){
				if($shipping_cost > 0 && $shipping_cost < $row->min_charge_amount){
					$shipping_cost = $row->min_charge_amount;
				}

				if($shipping_cost > 0 && $shipping_cost > $row->max_charge_amount){
					$shipping_cost = $row->max_charge_amount;
				}
			}

			if($shipping_cost !== false) {
				$max_leadtime = DB::table('fumaco_shipping_product_category')
					->whereIn('category_id', array_column($order_items->toArray(), 'f_cat_id'))
					->where('shipping_service_id', $row->shipping_service_id)->max('max_leadtime');

				$min_leadtime = DB::table('fumaco_shipping_product_category')
					->whereIn('category_id', array_column($order_items->toArray(), 'f_cat_id'))
					->where('shipping_service_id', $row->shipping_service_id)->max('min_leadtime');

				$min = ($min_leadtime > 0) ? $min_leadtime : $row->min_leadtime;
				$max = ($max_leadtime > 0) ? $max_leadtime : $row->max_leadtime;

				$expected_delivery_date = $this->delivery_leadtime($min, $max);

				if($shipping_cost <= 0) {
					$delivery_zones = DB::table('fumaco_shipping_zone_rate')->where('shipping_service_id', $row->shipping_service_id)->pluck('province_name');
					$free_delivery_zones = collect($free_delivery_zones)->merge($delivery_zones);
				}

				$shipping_offer_rates[] = [
					'shipping_service_name' => $row->shipping_service_name,
					'expected_delivery_date' => $expected_delivery_date,
					'min_lead_time' => $min, //
					'max_lead_time' => $max, //
					'shipping_cost' => $shipping_cost,
					'external_carrier' => false,
					'allow_delivery_after' => 0,
					'pickup' => false,
					'stores' => [],
				];
            }
        }

		$store_pickup_query = ShippingService::where('shipping_service_name', 'Store Pickup')
			->select('shipping_service_id', 'shipping_service_name', 'max_leadtime')->get();
		foreach($store_pickup_query as $row){
			$stores = DB::table('fumaco_store')
				->join('fumaco_shipping_service_store', 'fumaco_shipping_service_store.store_location_id', 'fumaco_store.store_id')
				->where('shipping_service_id', $row->shipping_service_id)->select('store_name', 'available_from', 'available_to', 'address', 'allowance_in_hours')->get();

			$max_leadtime = DB::table('fumaco_shipping_product_category')
				->whereIn('category_id', array_column($order_items->toArray(), 'f_cat_id'))
				->where('shipping_service_id', $row->shipping_service_id)->max('max_leadtime');

			$shipping_offer_rates[] = [
				'shipping_service_name' => $row->shipping_service_name,
				'expected_delivery_date' => null,
				'min_lead_time' => null, //
				'max_lead_time' => ($max_leadtime > 0) ? $max_leadtime : $row->max_leadtime, //
				'shipping_cost' => 0,
				'external_carrier' => false,
				'allow_delivery_after' => 0,
				'pickup' => true,
				'stores' => $stores,
				'remarks' => null
			];
		}

		$free_delivery_zones = collect($free_delivery_zones)->unique()->toArray();
		$free_delivery_zone_remarks = null;
		if(count($free_delivery_zones) > 0) {
			$free_delivery_zone_remarks = 'Free shipping within ';
			foreach($free_delivery_zones as $zone) {
				if (end($free_delivery_zones) == $zone) {
					$free_delivery_zone_remarks = rtrim($free_delivery_zone_remarks,", ");
					$free_delivery_zone_remarks .= (count($free_delivery_zones) > 1 ? ' and ' : ' ') . ucwords(strtolower($zone) . '.');
				} else {
					$free_delivery_zone_remarks .= ucwords(strtolower($zone)) . ', ';
				}
			}
		}

		return [
			'shipping_offer_rates' => $shipping_offer_rates,
			'free_delivery_zones' => $free_delivery_zone_remarks
		];
	}

	public function orderFailed() {
		return view('frontend.checkout.failed');
	}

	public function paymentCallback(Request $request) {
		return ($request->urlType == 'return') ? 'Retry' : 'OK';
	}

	public function applyVoucher($code, Request $request) {
		if ($request->ajax()) {
			session()->forget('fumVoucher');

			$voucher_details = DB::table('fumaco_voucher')->where('code', strtoupper($code))->first();
			
			if (!$voucher_details) {
				return response()->json(['status' => 0, 'message' => 'Please enter a valid coupon code.']);
			}

			if($voucher_details->validity_date_start && $voucher_details->validity_date_end) {
				if ($voucher_details->validity_date_start && $voucher_details->validity_date_end) {
					$startDate = Carbon::parse($voucher_details->validity_date_start)->startOfDay();
					$endDate = Carbon::parse($voucher_details->validity_date_end)->endOfDay();
					$checkDate = Carbon::now()->between($startDate, $endDate);
					if (!$checkDate) {
						return response()->json(['status' => 0, 'message' => 'Coupon is already expired.']);
					}
				}
			}

			if(!$voucher_details->unlimited) {
				if ($voucher_details->total_allotment <= $voucher_details->total_consumed) {
					return response()->json(['status' => 0, 'message' => 'Coupon is already expired.']);
				}
			}

			if(in_array($voucher_details->coupon_type, ['Promotional', 'Per Customer Group'])) {
				if($voucher_details->require_signin) {
					if (!Auth::check()) {
						return response()->json(['status' => 0, 'message' => 'Please sign in to avail this coupon code.']);
					}

					// count consumed voucher for loggedin user
					$consumed_voucher = DB::table('fumaco_order')->where('user_email', Auth::user()->username)
						->where('voucher_code', $voucher_details->code)->count();
					if ($consumed_voucher >= $voucher_details->allowed_usage) {
						return response()->json(['status' => 0, 'message' => 'Coupon is already expired.']);
					}
				}
			}

			$voucher_items = [];
			if($voucher_details->coupon_type == 'Per Item') {
				$voucher_items = DB::table('fumaco_voucher_exclusive_to')->where('voucher_id', $voucher_details->id)
					->where('voucher_type', 'Per Item')->distinct()->pluck('exclusive_to')->toArray();
			}

			$order_no = session()->get('fumOrderNo');
			if(Auth::check()) {
				$cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
					->select('f_idcode', 'f_default_price', 'b.qty', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_package_height', 'f_package_weight', 'f_package_length', 'f_package_width')
					->where('user_type', 'member')->where('user_email', Auth::user()->username)->get();
			} else {
				$cart_items = DB::table('fumaco_items as a')->join('fumaco_cart as b', 'a.f_idcode', 'b.item_code')
					->select('f_idcode', 'f_default_price', 'b.qty', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_package_height', 'f_package_weight', 'f_package_length', 'f_package_width')
					->where('user_type', 'guest')->where('transaction_id', $order_no)->get();
			}

			$order_items = DB::table('fumaco_order_items')->where('order_number', $order_no)->get();
			$order_items = collect($order_items)->groupBy('item_code');

			$voucher_category = [];
			$vouched_item_category_price = 0;
			if($voucher_details->coupon_type == 'Per Category') {
				$voucher_category = DB::table('fumaco_voucher_exclusive_to')->where('voucher_id', $voucher_details->id)
					->where('voucher_type', 'Per Category')->distinct()->pluck('exclusive_to')->toArray();
			}

			$subtotal = 0;
			$discount = 0;
			$item_total_amount = 0;
			$item_applied_discount = [];
			$below_min_spend_category = [];
			foreach ($cart_items as $item) {
				$item_total = isset($order_items[$item->f_idcode]) ? $order_items[$item->f_idcode][0]->item_total_price : $item->f_default_price * $item->qty;
				if (in_array($item->f_idcode, $voucher_items)) {
					$discount_per_item = 0;
					if($voucher_details->minimum_spend >= 0) {
						if($item_total > $voucher_details->minimum_spend) {
							array_push($item_applied_discount, $item->f_idcode);
							if($voucher_details->discount_type == 'By Percentage') {
								$discount_per_item = ($voucher_details->discount_rate/100) * $item_total;
								if($voucher_details->capped_amount > 0) {
									if ($discount_per_item > $voucher_details->capped_amount) {
										$discount_per_item = $voucher_details->capped_amount;
									}
								}
							}
				
							if($voucher_details->discount_type == 'Fixed Amount') {
								$discount_per_item = $voucher_details->discount_rate;
							}
						}
					}

					$discount += $discount_per_item;
				
					$item_total -= $discount_per_item;
				}
			
				if (in_array($item->f_cat_id, $voucher_category)) {
					$vouched_item_category_price += $item_total;
					if (!array_key_exists($item->f_cat_id, $below_min_spend_category)) {
						$below_min_spend_category[$item->f_cat_id] = 0;
					} 
					
					$below_min_spend_category[$item->f_cat_id] += $item_total;
				}

				$subtotal += $item_total;
			}

			$items_arr = collect($cart_items)->map(function ($q) use ($order_items){
				return [
					'item_code' => $q->f_idcode,
					'quantity' => $q->qty,
					'subtotal' => isset($order_items[$q->f_idcode]) ? $order_items[$q->f_idcode][0]->item_total_price : $q->f_default_price * $q->qty
				];
			});

			$applicable_price_rule = $this->getPriceRules($items_arr);
			$price_rule = isset($applicable_price_rule['price_rule']) ? $applicable_price_rule['price_rule'] : [];

			$cart_item_codes = collect($cart_items)->pluck('f_idcode');

			if($voucher_details->coupon_type == 'Per Category') {
				$discount_per_category = 0;
				if($voucher_details->minimum_spend > 0) {
					if($vouched_item_category_price > $voucher_details->minimum_spend) {
						$voucher_category_item_code = collect($cart_items)->map(function($k) use ($voucher_category) {
							return (in_array($k->f_cat_id, $voucher_category)) ? $k->f_idcode : null;
						})->toArray();
						
						$item_applied_discount = array_merge($item_applied_discount, $voucher_category_item_code);
						if($voucher_details->discount_type == 'By Percentage') {
							$discount_per_category = ($voucher_details->discount_rate/100) * $vouched_item_category_price;
							if($voucher_details->capped_amount > 0) {
								if ($discount_per_category > $voucher_details->capped_amount) {
									$discount_per_category = $voucher_details->capped_amount;
								}
							}
						}
			
						if($voucher_details->discount_type == 'Fixed Amount') {
							$discount_per_category = $voucher_details->discount_rate;
						}
					}
				}

				$discount += $discount_per_category;
				if($vouched_item_category_price < $voucher_details->minimum_spend && count($item_applied_discount) > 0) {
					$below_min_spend_category = array_filter($below_min_spend_category, function ($var) use ($voucher_details) {
						return ($var < $voucher_details->minimum_spend);
					});
		
					$below_min_spend_category = array_keys($below_min_spend_category);

					$item_categories = DB::table('fumaco_categories')
						->whereIn('id', $voucher_category)->pluck('name', 'id')->toArray();
					return response()->json(['status' => 0, 'message' => 'Required total amount ₱ ' . number_format(str_replace(",","",$voucher_details->minimum_spend), 2) . ' for ' . $item_categories[$below_min_spend_category[0]]]);
				}
			}

			if(isset($price_rule['Transaction'])){
				$pr = $price_rule['Transaction'];
				switch($pr['discount_type']){
					case 'Percentage':
						$pr_discount = $subtotal * ($pr['discount_rate'] / 100);
						break;
					default:
						$pr_discount = $pr['discount_rate'] < $subtotal ? $pr['discount_rate'] : 0;
						break;
				}

				$subtotal = $subtotal - $pr_discount;
			}

			if(in_array($voucher_details->coupon_type, ['Promotional', 'Per Customer Group'])) {
				if($voucher_details->minimum_spend > 0) {
					if($subtotal < $voucher_details->minimum_spend) {
						return response()->json(['status' => 0, 'message' => 'Required total amount ₱ ' . number_format(str_replace(",","",$voucher_details->minimum_spend), 2)]);
					}
				}

				if($voucher_details->discount_type == 'By Percentage') {
					$discount = ($voucher_details->discount_rate/100) * $subtotal;
					if($voucher_details->capped_amount > 0) {
						if ($discount > $voucher_details->capped_amount) {
							$discount = $voucher_details->capped_amount;
						}
					}
				}
	
				if($voucher_details->discount_type == 'Fixed Amount') {
					$discount = $voucher_details->discount_rate;
				}	

				if ($voucher_details->coupon_type == 'Per Customer Group') {
					$customer_group = DB::table('fumaco_voucher_exclusive_to as a')
						->join('fumaco_customer_group as b', 'a.exclusive_to', 'b.id')
						->where('voucher_id', $voucher_details->id)->pluck('b.id')->toArray();

					if (Auth::check() && !in_array(Auth::user()->customer_group, $customer_group)) {
						$discount = 0;
					}
				}
			}

			$free_delivery = [];
			if($voucher_details->discount_type == 'Free Delivery') {
				$free_shipping = DB::table('fumaco_shipping_service')->where('shipping_service_name', 'Free Delivery')->first();
				$expected_delivery_date = null;
				if($free_shipping) {
					$min = $free_shipping->min_leadtime;
					$max = $free_shipping->max_leadtime;
	
					$expected_delivery_date = $this->delivery_leadtime($min, $max);

					$free_delivery = [
						'shipping_service_name' => 'Free Delivery',
						'expected_delivery_date' => $expected_delivery_date,
						'min_lead_time' => $min,
						'max_lead_time' => $max,
						'shipping_cost' => 0,
						'external_carrier' => false,
						'allow_delivery_after' => 0,
					];
				}

				$discount = 0;
			}

			$discounted_subtotal = $subtotal - $discount;

			if($voucher_details) {
				session()->put('fumVoucher', $code);
			}
			
			return response()->json([
				'voucher_code' => strtoupper($code),
				'discount' => $discount,
				'total' => $discounted_subtotal,
				'shipping' => $free_delivery,
				'item_applied_discount' => $item_applied_discount
			]);
		}
	}
}
