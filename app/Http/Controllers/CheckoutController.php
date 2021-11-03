<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderSuccess;

use App\Models\ShippingService;
use App\Models\ShippingZoneRate;
use App\Models\ShippingCondition;

class CheckoutController extends Controller
{
	public function billingForm() {
		$cart = session()->get('fumCart');
        $cart = (!$cart) ? [] : $cart;

        $cart_items = DB::table('fumaco_items')
            ->whereIn('f_idcode', array_column($cart, 'item_code'))->get();
        
        $cart_arr = [];
        foreach ($cart_items as $n => $item) {
			if ($cart[$item->f_idcode]['quantity'] > $item->f_qty) {
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

		return view('frontend.checkout.billing_address_form', compact('has_shipping_address', 'shipping_zones'));
	}

	public function setBillingForm($item_code_buy = null, $qty_buy = null){
		$shipping_zones = DB::table('fumaco_shipping_zone_rate')->distinct()->pluck('province_name')->toArray();

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
				$o_email = Auth::user()->username;

				$user = DB::table('fumaco_users')->where('username', $o_email)->first();
				$user_id = $user->id;
	
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

			$billing_details = session()->get('fumBillDet');

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
                'same_as_billing' => 0
            ];

            session()->put('fumShipDet', $shipping_details);

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

			$shipping_details = session()->get('fumShipDet');

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
                'same_as_billing' => 0
            ];

            session()->put('fumBillDet', $billing_details);

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
			$cart = session()->get('fumCart');
			$cart = (!$cart) ? [] : $cart;

			$cart_items = DB::table('fumaco_items')
				->whereIn('f_idcode', array_column($cart, 'item_code'))->get();
			
			$cart_arr = [];
			foreach ($cart_items as $n => $item) {
				$available_qty = ($item->f_qty - $item->f_reserved_qty);
				if ($cart[$item->f_idcode]['quantity'] > $available_qty) {
					return redirect()->back()->with('error', 'Insufficient stock for <b>' . $item->f_name_name . '</b>');
				}
			}

			if (!session()->get('fumOrderNo')) {
				$order_no = 'FUM-' . date('ymd') . random_int(9999, 100000);
                session()->put('fumOrderNo', $order_no);
            }

			$order_no = session()->get('fumOrderNo');

			$shipping_details = session()->get('fumShipDet');
			$billing_details = session()->get('fumBillDet');

			$cart = session()->get('fumCart');
			$cart = (!$cart) ? [] : $cart;
			if(count($cart) <= 0) {
				return redirect('/cart');
			}
	
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
					'subtotal' => ($price * $cart[$item->f_idcode]['quantity']),
					'original_price' => $item->f_original_price,
					'discount' => $item->f_discount_percent,
					'quantity' => $cart[$item->f_idcode]['quantity'],
					'stock_qty' => $item->f_qty,
					'item_image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg'
				];
			}

			$shipping_rates = $this->getShippingRates();
			
			$shipping_add = $billing_add = [];
			if (Auth::check()) {
				$shipping_add = DB::table('fumaco_user_add')->where('user_idx', Auth::user()->id)->where('address_class','Delivery')->get();
				$billing_add = DB::table('fumaco_user_add')->where('user_idx', Auth::user()->id)->where('address_class','Billing')->get();
			}

			$shipping_zones = DB::table('fumaco_shipping_zone_rate')->distinct()->pluck('province_name')->toArray();

			return view('frontend.checkout.check_out_summary', compact('shipping_details', 'billing_details', 'shipping_rates', 'order_no', 'cart_arr', 'shipping_add', 'billing_add', 'shipping_zones'));
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}		
	}

	public function saveOrder(Request $request) {
		DB::beginTransaction();
		try {
			$order_no = session()->get('fumOrderNo');
			$shipping_details = session()->get('fumShipDet');
			$billing_details = session()->get('fumBillDet');
			
			$temp_data = [
				'xtempcode' => uniqid(),
				'xfname' => (Auth::check()) ? Auth::user()->f_name : $shipping_details['fname'],
				'xlname' => (Auth::check()) ? Auth::user()->f_lname : $shipping_details['lname'],
				'xcontact_person' => $billing_details['fname']. " " . $billing_details['lname'],
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
				'xmobile' => ($billing_details) ? $billing_details['mobile_no'] : $shipping_details['mobile_no'],
				'xcontact' => $shipping_details['contact_no'],
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
				'xlogs' => $order_no,
				'order_status' => 'Order Pending',
				'order_tracker_code' => $order_no,
				'order_shipping_type' => null, 
				'order_ip' => $request->ip(),
				'xusertype' => (Auth::check()) ? 'Member' : 'Guest',
				'xusernamex' => (Auth::check()) ? Auth::user()->username : null,
				'xstatus' => 2,
				'xuser_id' => (Auth::check()) ? Auth::user()->id : null,
				'shipping_name' => $request->s_name,
				'shipping_amount' => $request->s_amount,
				'estimated_delivery_date' => $request->estimated_del,
			];

			$existing_order_temp = DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->exists();
			if(!$existing_order_temp){
				DB::table('fumaco_temp')->insert($temp_data);
			}

			$cart = session()->get('fumCart');
			$cart = (!$cart) ? [] : $cart;
			if(count($cart) <= 0) {
				return redirect('/cart');
			}
	
			$cart_items = DB::table('fumaco_items')
				->whereIn('f_idcode', array_column($cart, 'item_code'))->get();
			
			$cart_arr = [];
			foreach ($cart_items as $n => $item) {
				$price = ($item->f_discount_trigger) ? $item->f_price : $item->f_original_price;

				$existing_order_item = DB::table('fumaco_order_items')->where('order_number', $order_no)
						->where('item_code', $item->f_idcode)->exists();
			
				if($existing_order_item){
					// update item qty
					DB::table('fumaco_order_items')->where('order_number', $order_no)
						->where('item_code', $item->f_idcode)->update([
							'item_name' => $item->f_name_name,
							'item_discount' => $item->f_discount_percent,
							'item_original_price' => $item->f_original_price,
							'item_qty' => $cart[$item->f_idcode]['quantity'],
							'item_price' => $price,	
							'item_total_price' => ($price * $cart[$item->f_idcode]['quantity'])
						]);
				} else {
					$cart_arr[] = [
						'order_number' => $order_no,
						'item_code' => $item->f_idcode,
						'item_name' => $item->f_name_name,
						'item_discount' => $item->f_discount_percent,
						'item_original_price' => $item->f_original_price,
						'item_qty' => $cart[$item->f_idcode]['quantity'],
						'item_price' => $price,
						'item_status' => 2,
						'date_update' => Carbon::now()->toDateTimeString(),
						'ip_address' => $request->ip(),
						'item_total_price' => ($price * $cart[$item->f_idcode]['quantity'])
					];
				}
			}

			DB::table('fumaco_order_items')->insert($cart_arr);

			DB::commit();

			return response()->json(['status' => 1, 'id' => $order_no]);
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
	
			$amount = DB::table('fumaco_order_items')->where('order_number', $order_no)->sum('item_total_price');
	
			$grand_total = $amount + $temp->shipping_amount;
	
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

			$order_items = DB::table('fumaco_order_items')
				->where('order_number', $temp->order_tracker_code)->get();

			// insert orders if not existing
			$existing_order = DB::table('fumaco_order')->where('order_number', $temp->order_tracker_code)->exists();
			if (!$existing_order) {
				$subtotal = collect($order_items)->sum('item_total_price');

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
				
				DB::table('fumaco_order')->insert([
					// 'order_number' => $temp->order_tracker_code,
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
					'order_bill_contact' => $temp->xmobile,
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
					'order_payment_method' => $payment_method,
					'tracker_code' => $temp->order_tracker_code,
					'estimated_delivery_date' => $temp->estimated_delivery_date,
					'payment_id' => $request->PaymentID,
					'bank_ref_no' => $request->BankRefNo,
					'issuing_bank' => $request->IssuingBank,
					'payment_transaction_time' => $request->RespTime,
					'amount_paid' => $request->Amount,
					'order_type' => $temp->xusertype,
					'user_email' => $temp->xusernamex,
					'shipping_business_name' => $temp->xship_business_name,
					'shipping_tin' => $temp->xship_tin,
					'billing_business_name' => $temp->xbusiness_name,
					'billing_tin' => $temp->xtin_no
				]);

				// insert order in tracking order table
				DB::table('track_order')->insert([
					'track_code' => $temp->order_tracker_code,
					'track_date' => $now,
					'track_item' => 'Item Purchase',
					'track_description' => 'Your order is on processing',
					'track_status' => 'Order Placed',
					'track_ip' => $temp->order_ip,
					'transaction_member' => $temp->xusertype
				]);
			}

			$loggedin = ($temp->xusernamex) ? $temp->xusernamex : $temp->xemail_shipping;

			$order_details = DB::table('fumaco_order')->where('order_number', $temp->xlogs)->first();

			$items = [];
			foreach($order_items as $row) {
				$image = DB::table('fumaco_items_image_v1')->where('idcode', $row->item_code)->first();

				$items[] = [
					'item_code' => $row->item_code,
					'item_name' => $row->item_name,
					'price' => $row->item_price,
					'discount' => $row->item_discount,
					'qty' => $row->item_qty,
					'amount' => $row->item_total_price,
					'image' => ($image) ? $image->imgprimayx : null
				];

				// update reserved qty for items
				$item_details = DB::table('fumaco_items')->where('f_idcode', $row->item_code)->first();
				if($item_details) {
					DB::table('fumaco_items')->where('f_idcode', $row->item_code)->update([
						'f_qty' => $item_details->f_qty - $row->item_qty,
						'f_reserved_qty' => $item_details->f_reserved_qty + $row->item_qty,
					]);
				}
			}

			DB::table('fumaco_temp')->where('xtempcode', $id)->delete();

			session()->forget('fumCart');
			
			DB::commit();

			$order = [
				'order_details' => $order_details,
				'items' => $items
			];

			$emails = array_filter(array_unique([trim($order_details->order_bill_email), trim($order_details->order_email), trim($temp->xusernamex)]));
			Mail::to($emails)
				->queue(new OrderSuccess($order));

			return view('frontend.checkout.success', compact('order_details', 'items', 'loggedin'));
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
        $min_leadtime_y = $min_leadtime->format('Y');
        $min_leadtime_m = $min_leadtime->format('M');
        $min_leadtime_d = $min_leadtime->format('d');

        $max_leadtime = Carbon::parse(now()->addDays($max));
        $max_leadtime_y = $max_leadtime->format('Y');
        $max_leadtime_m = $max_leadtime->format('M');

		$holidays = DB::table('fumaco_holiday')->where('holiday_date', '>=', $min_leadtime->format('Y-m-d'))->where('holiday_date', '<=', $max_leadtime->format('Y-m-d'))->get();

		foreach($holidays as $holiday){
			$min_leadtime_d = (Carbon::parse($holiday->holiday_date)->format('m-d') == $min_leadtime->format('m-d')) ? $min_leadtime->addDays(1)->format('d') : $min_leadtime->format('d');
		}

        $max_leadtime_d = $max_leadtime->addDays(count($holidays))->format('d');

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
		$shipping_details = session()->get('fumShipDet');
		if (!$shipping_details) {
			return [];
		}

		$address = strtolower($shipping_details['address_line1'] . ' ' . $shipping_details['address_line2'] . ' ' . $shipping_details['brgy']. ' ' . $shipping_details['city'] . ' ' . $shipping_details['province'] . ' ' .	$shipping_details['country']);
		$region = strtolower($shipping_details['province']);
		$city = strtolower($shipping_details['city']);

		$cart = session()->get('fumCart');
		$cart = (!$cart) ? [] : $cart;
		if(count($cart) <= 0) {
			return redirect('/cart');
		}

		$order_items = DB::table('fumaco_items')
			->whereIn('f_idcode', array_column($cart, 'item_code'))->get();

		$total_amount = 0;
		$total_weight_of_items = 0;
		$total_cubic_cm = 0;
        foreach ($order_items as $row) {
			$item_qty = $cart[$row->f_idcode]['quantity'];
			$price = ($row->f_price > 0) ? $row->f_price : $row->f_original_price;
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
        $shipping_services_without_conditions = ShippingService::where('shipping_calculation', 'Flat Rate')->whereIn('shipping_service_id', $shipping_services_arr)->get();
        
        $shipping_offer_rates = [];
        foreach($shipping_services_without_conditions as $row){
            $expected_delivery_date = $this->delivery_leadtime($row->min_leadtime, $row->max_leadtime);
            $shipping_offer_rates[] = [
                'shipping_service_name' => $row->shipping_service_name,
                'expected_delivery_date' => $expected_delivery_date,
				'min_lead_time' => $row->min_leadtime,
				'max_lead_time' => $row->max_leadtime,
                'shipping_cost' => (float)$row->amount,
                'external_carrier' => false,
                'allow_delivery_after' => 0,
                'pickup' => false,
                'stores' => [],
            ];
        }

       $shipping_services = ShippingService::join('fumaco_shipping_condition as a', 'fumaco_shipping_service.shipping_service_id', 'a.shipping_service_id')
            ->whereIn('a.shipping_service_id', $shipping_services_arr)->get();

        foreach($shipping_services as $row){
            $expected_delivery_date = $this->delivery_leadtime($row->min_leadtime, $row->max_leadtime);

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
                $shipping_offer_rates[] = [
                    'shipping_service_name' => $row->shipping_service_name,
                    'expected_delivery_date' => $expected_delivery_date,
					'min_lead_time' => $row->min_leadtime, //
					'max_lead_time' => $row->max_leadtime, //
                    'shipping_cost' => $shipping_cost,
                    'external_carrier' => false,
                    'allow_delivery_after' => 0,
                    'pickup' => false,
                    'stores' => [],
                ];
            }
        }

		return $shipping_offer_rates;
	}

	public function orderFailed() {
		return view('frontend.checkout.failed');
	}

	public function paymentCallback(Request $request) {
		return ($request->urlType == 'return') ? 'Retry' : 'OK';
	}
}
