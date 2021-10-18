<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

use App\Models\ShippingService;
use App\Models\ShippingZoneRate;
use App\Models\ShippingCondition;

class CheckoutController extends Controller
{
	public function billingForm() {
		return view('frontend.checkout.billing_address_form');
	}

	public function setBillingForm(){
		return view('frontend.checkout.set_billing');
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

			return redirect('/checkout/summary')->with('add_success', 'Record Updated');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}	
		
	}

	public function setAddress(Request $request){
		DB::beginTransaction();
		try{
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

			if (isset($request->myCheck)){
				$bill_address_arr = [
					'address_class' => 'Billing',
					'user_idx' => $user_id,
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
			}else{
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
				
			}

			$bill_insert = DB::table('fumaco_user_add')->insert($bill_address_arr);
			$ship_insert = DB::table('fumaco_user_add')->insert($ship_address_arr);

			$request->session()->put('order_no', 'FUM-'.random_int(10000000, 99999999));

			DB::commit();

			return redirect('/checkout/summary')->with('add_success', 'Record Updated');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}	
	}

	public function checkoutSummary(Request $request){
        DB::beginTransaction();
		try{
			$base_url = DB::table('fumaco_settings')->first();

			$same_address = 0;

			$user_type = '';

			if(!Auth::check() and request()->isMethod('post')) {
				$order_no = 'FUM-'.random_int(10000000, 99999999);
				$first_name = $request->fname;
				$last_name = $request->lname;
				$email = $request->ship_email;
				$ship_address1 = $request->ship_Address1_1;
				$ship_address2 = ($request->ship_Address2_1) ? $request->ship_Address2_1 : " ";
				$ship_province = $request->ship_province1_1;
				$ship_city = $request->ship_City_Municipality1_1;
				$ship_brgy = $request->ship_Barangay1_1;
				$ship_postal = $request->ship_postal1_1;
				$ship_country = $request->ship_country_region1_1;
				$ship_address_type = $request->ship_Address_type1_1;
				$ship_email = $request->ship_email;
				$ship_contact = ($request->contactnumber1_1) ? $request->contactnumber1_1 : " ";
				$ship_mobile = $request->ship_mobilenumber1_1;
				
				$user_type = 'Guest';
				$username= ' ';
				$user_id = 0;
				$item_code = $request->item_code;
				$item_desc = $request->item_desc;

				if (isset($request->myCheck)){
					$same_address = 1;
					$bill_firstname = $request->fname;
					$bill_lastname = $request->lname;
					$bill_address1 = $request->ship_Address1_1;
					$bill_address2 = ($request->ship_Address2_1) ? $request->ship_Address2_1 : " ";
					$bill_province = $request->ship_province1_1;
					$bill_city = $request->ship_City_Municipality1_1;
					$bill_brgy = $request->ship_Barangay1_1;
					$bill_postal = $request->ship_postal1_1;
					$bill_country = $request->ship_country_region1_1;
					$bill_address_type = $request->ship_Address_type1_1;
					$bill_email = $request->ship_email;
					$bill_mobile = $request->ship_mobilenumber1_1;
				}else{
					$same_address = 0;
					$bill_firstname = $request->bill_fname;
					$bill_lastname = $request->bill_lname;
					$bill_address1 = $request->Address1_1;
					$bill_address2 = ($request->Address2_1) ? $request->Address2_1 : " ";
					$bill_province = $request->province1_1;
					$bill_city = $request->City_Municipality1_1;
					$bill_brgy = $request->Barangay1_1;
					$bill_postal = $request->postal1_1;
					$bill_country = $request->country_region1_1;
					$bill_address_type = $request->Address_type1_1;
					$bill_mobile = $request->mobilenumber1_1;
					$bill_email = $request->email;
				}				
			}else{
				$o_email = Auth::user()->username;
				$order_no = $request->session()->get('order_no');

				$user = DB::table('fumaco_users')->where('username', $o_email)->first();
				$user_id = $user->id;

				$user_bill_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id)->where('address_class', 'Billing')->first();
				$user_ship_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id)->where('address_class', 'Delivery')->first();
				$bill = collect($user_bill_address);
				$ship = collect($user_ship_address);
				$add_check = count($bill->diff($ship));
				$same_address = ($add_check > 3) ? 0 : 1;

				$first_name = $user->f_name;
				$last_name = $user->f_lname;
				$user_type = 'Member';
				$item_code = $request->form_item_code;
				$item_desc = $request->form_item_desc;
				$ship_mobile = (!empty($user_ship_address->xmobile_number) ? $user_ship_address->xmobile_number : 0 );
				$bill_mobile = (!empty($user_bill_address->xmobile_number) ? $user_bill_address->xmobile_number : 0 );

				$email = $user_bill_address->xcontactemail1;
				$bill_firstname = $user_bill_address->xcontactname1;
				$bill_lastname = $user_bill_address->xcontactlastname1;
				$bill_address1 = $user_bill_address->xadd1;
				$bill_address2 = $user_bill_address->xadd2;
				$bill_province = $user_bill_address->xprov;
				$bill_city = $user_bill_address->xcity;
				$bill_brgy = $user_bill_address->xbrgy;
				$bill_postal = $user_bill_address->xpostal;
				$bill_country = $user_bill_address->xcountry;
				$bill_address_type = $user_bill_address->add_type;
				$bill_contact = $user_bill_address->xcontactnumber1;
				$username = $email;

				$ship_address1 = $user_ship_address->xadd1;
				$ship_address2 = $user_ship_address->xadd2;
				$ship_province = $user_ship_address->xprov;
				$ship_city = $user_ship_address->xcity;
				$ship_brgy = $user_ship_address->xbrgy;
				$ship_postal = $user_ship_address->xpostal;
				$ship_country = $user_ship_address->xcountry;
				$ship_address_type = $user_ship_address->add_type;
				$ship_contact = $user_ship_address->xcontactnumber1;
				$ship_mobile = $user_ship_address->xmobile_number;
			}
			$temp_arr[] = [
				'xtempcode' => uniqid(),
				'xfname' => $first_name,
				'xlname' => $last_name,
				'xcontact_person' => $bill_firstname. " " . $bill_lastname,
				'xshipcontact_person' => $first_name. " " . $last_name,
				'xadd1' => $bill_address1,
				'xadd2' => ($bill_address2) ? $bill_address2 : " ",
				'xprov' => $bill_province,
				'xcity' => $bill_city,
				'xbrgy' => $bill_brgy,
				'xpostal' => $bill_postal,
				'xcountry' => $bill_country,
				'xaddresstype' => $bill_address_type,
				'xemail' => $email,
				'xmobile' => $bill_mobile,
				'xcontact' => ($ship_contact) ? $ship_contact : 0,
				'xshippadd1' => $ship_address1,
				'xshippadd2' => ($ship_address2) ? $ship_address2 : " ",
				'xshiprov' => $ship_province,
				'xshipcity' => $ship_city,
				'xshipbrgy' => $ship_brgy,
				'xshippostalcode' => $ship_postal,
				'xshipcountry' => $ship_country,
				'xshiptype' => $ship_address_type,
				'xlogs' => $order_no,
				'order_status' => 'Order Pending',
				'order_tracker_code' => $order_no,
				'order_shipping_type' => '', 
				'order_ip' => $request->ip(),
				'xusertype' => $user_type,
				'xusernamex' => $username,
				'xstatus' => 2,
				'xuser_id' => $user_id
			];

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

				$price = ($item->f_discount_trigger) ? $item->f_price : $item->f_original_price;

				$order_check = DB::table('fumaco_order_items')->where('order_number', $order_no)
					->where('item_code', $item->f_idcode)->count();

				$cart_arr[] = [
					'item_code' => $item->f_idcode,
					'item_description' => $item->f_name_name,
					'price' => $price,
					'original_price' => $item->f_original_price,
					'discount' => $item->f_discount_percent,
					'subtotal' => ($price * $cart[$item->f_idcode]['quantity']),
					'quantity' => $cart[$item->f_idcode]['quantity'],
					'stock_qty' => $item->f_qty,
					'item_image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg'
				];

				$orders_arr[] = [
					'order_number' => $order_no,
					'item_code' => $item->f_idcode,
					'item_name' => $item->f_name_name,
					'item_qty' => $cart[$item->f_idcode]['quantity'],
					'item_price' => $item->f_price,
					'item_original_price' => $item->f_original_price,
					'item_discount' => $item->f_discount_percent,
					'item_status' => 2,
					'date_update' => Carbon::now()->toDateTimeString(),
					'ip_address' => $request->ip(),
					'item_total_price' => ($price * $cart[$item->f_idcode]['quantity'])
				];
				if($order_check < 1){
					DB::table('fumaco_order_items')->insert($orders_arr);
				}
			}

			$summary_arr[] = [
				'shipping' => $cart['shipping']['shipping_fee'],
				'subtotal' => collect($cart_arr)->sum('subtotal'),
				'grand_total' => ($cart['shipping']['shipping_fee'] + collect($cart_arr)->sum('subtotal')),
				'same_address' => $same_address,
				'base_url' => $base_url->set_value,
				'ship_mobile' => $ship_mobile,
				'bill_mobile' => $bill_mobile,
				'address' => $temp_arr
			];

			$checker = DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->count();
			if($checker < 1){
				$insert = DB::table('fumaco_temp')->insert($temp_arr);
			}
			DB::commit();
			request()->session()->put('summary_arr', $summary_arr);
			request()->session()->put('cart_arr', $cart_arr);

			return redirect('/checkout/summary_view');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}		
	}
	
	public function checkoutSummaryView(Request $request){
		$summary_arr = $request->session()->get('summary_arr');
		$cart_arr = $request->session()->get('cart_arr');

		if (!isset($cart_arr)) {
			return redirect('/cart');
		}

		$order_id = $summary_arr[0]['address'][0]['order_tracker_code'];
		$shipping_rates = $this->getShippingRates($order_id);
		
		return view('frontend.checkout.check_out_summary', compact('summary_arr', 'cart_arr', 'shipping_rates'));
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
	// update shipping id and amount in checkout summary page (fumaco_temp table)
	// note: $id = order_tracker_code
	public function updateShippingAmount($id, Request $request) {
		if($request->ajax()) {
			DB::beginTransaction();
			try {
				$submit_form = ($request->submit) ? 1 : 0;
				$temp = DB::table('fumaco_temp')->where('order_tracker_code', $id)->first();
				if (!$temp) {
					return response()->json(['status' => 2, 'message' => 'Temp order not found.']);
				}
				DB::table('fumaco_temp')->where('order_tracker_code', $id)->update([
					'shipping_name' => $request->s_name,
					'shipping_amount' => $request->s_amount,
				]);
	
				DB::commit();
	
				return response()->json(['status' => 1, 'message' => 'Temp order updated.', 's' => $submit_form]);
			} catch (Exception $e) {
				DB::rollback();
	
				return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
			}
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
						$payment_method = 'Credit Card';
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
					'order_number' => $temp->order_tracker_code,
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
					'order_email' => $temp->xemail,
					'order_contact' => $temp->xcontact,
					'order_subtotal' => $subtotal,
					'order_shipping' => $temp->shipping_name,
					'order_shipping_amount' => $temp->shipping_amount,
					'order_ip' => $temp->order_ip,
				  	'order_date' => $now,
					'order_status' => "Order Placed",
					'order_payment_method' => $payment_method,
					'tracker_code' => $temp->order_tracker_code,
					'payment_id' => $request->PaymentID,
					'bank_ref_no' => $request->BankRefNo,
					'issuing_bank' => $request->IssuingBank,
					'payment_transaction_time' => $request->RespTime,
					'amount_paid' => $request->Amount
				]);

				// insert order in tracking order table
				DB::table('track_order')->insert([
					'track_code' => $temp->order_tracker_code,
					'track_date' => $now,
					'track_item' => 'Item Purchase',
					'track_description' => 'Your order is on processing',
					'track_status' => 'Order Placed',
					'track_ip' => $temp->order_ip,
					'transaction_member' => (Auth::check()) ? Auth::user()->id : 'Guest'
				]);
			}

			$order_details = DB::table('fumaco_order')->where('order_number', $temp->order_tracker_code)->first();

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
			session()->forget('summary_arr');
			session()->forget('cart_arr');
			
			DB::commit();

			return view('frontend.checkout.success', compact('order_details', 'items'));
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
            for ($i = 0; $i < count($output['results'][0]['address_components']); $i++) {
                $address_type = $output['results'][0]['address_components'][$i]['types'][0];
                if(isset($components[$address_type])){
                    array_push($arr, $output['results'][0]['address_components'][$i][$components[$address_type]]);
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
        $max_leadtime_d = $max_leadtime->format('d');

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

	private function getShippingRates($order_no){
		$temp = DB::table('fumaco_temp')->where('order_tracker_code', $order_no)->first();
		if (!$temp) {
			return [];
		}

		$address = $temp->xshippadd1 . ' ' . $temp->xshippadd2 . ' ' . $temp->xshipbrgy. ' ' . $temp->xshipcity . ' ' . $temp->xshiprov . ' ' .	$temp->xshipcountry;
		$region = $temp->xshiprov;
		$city = $temp->xshipcity;

		$order_items = DB::table('fumaco_order_items as a')->join('fumaco_items as b', 'b.f_idcode', 'a.item_code')
			->where('a.order_number', $order_no)->get();
		$total_weight_of_items = 0;
		$total_amount = collect($order_items)->sum('item_total_price');

		$total_cubic_cm = 0;
        foreach ($order_items as $row) {
            $cubic_cm = ($row->f_package_length * $row->f_package_width * $row->f_package_height);
            $cubic_cm = $cubic_cm * $row->item_qty;

            $total_cubic_cm += $cubic_cm;
            $total_weight_of_items += $row->f_package_weight * $row->item_qty;

            $packs[] = [
                "dimensions" => [(float)$row->f_package_length, (float)$row->f_package_width, (float)$row->f_package_height],
                "weight" => (float)$row->f_package_weight,
                "quantity" =>  (int)$row->item_qty
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
                    'shipping_cost' => $shipping_cost,
                    'external_carrier' => false,
                    'allow_delivery_after' => 0,
                    'pickup' => false,
                    'stores' => [],
                ];
            }
        }

        $store_pickup_query = ShippingService::where('shipping_service_name', 'Store Pickup')->get();
        foreach($store_pickup_query as $row){
            $stores = DB::table('store_location')
                ->join('shipping_service_store', 'shipping_service_store.store_location_id', 'store_location.store_id')
                ->where('shipping_service_id', $row->shipping_service_id)->select('store_name', 'available_from', 'available_to')->get();

            $shipping_offer_rates[] = [
                'shipping_service_name' => $row->shipping_service_name,
                'expected_delivery_date' => null,
                'shipping_cost' => '-',
                'external_carrier' => false,
                'allow_delivery_after' => 0,
                'pickup' => true,
                'stores' => $stores,
            ];
        }

		return $shipping_offer_rates;
	}
}
