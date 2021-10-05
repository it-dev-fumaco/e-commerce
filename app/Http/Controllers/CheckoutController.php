<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class CheckoutController extends Controller
{
	public function reviewOrder() {
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

			$cart_arr[] = [
				'item_code' => $item->f_idcode,
				'item_description' => $item->f_name_name,
				'price' => $item->f_price,
				'amount' => ($item->f_price * $cart[$item->f_idcode]['quantity']),
				'quantity' => $cart[$item->f_idcode]['quantity'],
				'stock_qty' => $item->f_qty,
				'item_image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg'
			];
		}

		return view('frontend.checkout.review_order', compact('cart_arr', 'cart'));
	}

	public function billingForm() {
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

			$cart_arr[] = [
				'item_code' => $item->f_idcode,
				'item_desc' => $item->f_name_name,
				'price' => $item->f_price,
				'shipping' => $cart['shipping']['shipping_fee'],
				'subtotal' => ($item->f_price * $cart[$item->f_idcode]['quantity']),
				'quantity' => $cart[$item->f_idcode]['quantity'],
				'grand_total' => ($cart['shipping']['shipping_fee'] + ($item->f_price * $cart[$item->f_idcode]['quantity']))
			];
		}

		return view('frontend.checkout.billing_address_form', compact('cart_arr', 'cart'));
	}

	public function checkoutSummary(Request $request){
        DB::beginTransaction();
		try{
			$base_url = DB::table('fumaco_settings')->first();

			$same_address = 0;

			$order_no = 'FUM-'.uniqid();

			$user_type = '';

			if(!Auth::check()){
				$first_name = $request->fname;
				$last_name = $request->lname;
				$email = $request->email;
				$bill_address1 = $request->Address1_1;
				$bill_address2 = $request->Address2_1;
				$bill_province = $request->province1_1;
				$bill_city = $request->City_Municipality1_1;
				$bill_brgy = $request->Barangay1_1;
				$bill_postal = $request->postal1_1;
				$bill_country = $request->country_region1_1;
				$bill_address_type = $request->Address_type1_1;
				$bill_mobile = $request->mobilenumber1_1;
				$bill_contact = $request->contactnumber1_1;
				$user_type = 'Guest';
				$username= ' ';
				$user_id = 0;
				$item_code = $request->item_code;
				$item_desc = $request->item_desc;

				if (isset($request->myCheck)){
					$same_address = 1;
					$ship_address1 = $request->Address1_1;
					$ship_address2 = $request->Address2_1;
					$ship_province = $request->province1_1;
					$ship_city = $request->City_Municipality1_1;
					$ship_brgy = $request->Barangay1_1;
					$ship_postal = $request->postal1_1;
					$ship_country = $request->country_region1_1;
					$ship_address_type = $request->Address_type1_1;
					$ship_email = $request->mobilenumber1_1;
					$ship_contact = $request->contactnumber1_1;
				}else{
					$same_address = 0;
					$ship_address1 = $request->ship_Address1_1;
					$ship_address2 = $request->ship_Address2_1;
					$ship_province = $request->ship_province1_1;
					$ship_city = $request->ship_City_Municipality1_1;
					$ship_brgy = $request->ship_Barangay1_1;
					$ship_postal = $request->ship_postal1_1;
					$ship_country = $request->ship_country_region1_1;
					$ship_address_type = $request->ship_Address_type1_1;
					$ship_email = $request->ship_email;
					$ship_contact = $request->ship_contactnumber1_1;
				}				
			}else{
				$o_email = Auth::user()->username;

				$user = DB::table('fumaco_users')->where('username', $o_email)->first();
				$user_id = $user->id;

				$user_bill_address = DB::table('fumaco_user_add_bill')->where('xdefault_b', 1)->where('user_idx_b', $user_id)->first();
				$user_ship_address = DB::table('fumaco_user_add')->where('xdefault', 1)->where('user_idx', $user_id)->first();

				$first_name = $user->f_name;
				$last_name = $user->f_lname;
				$email = $user_bill_address->xcontactemail1_b;
				$bill_address1 = $user_bill_address->xadd1_b;
				$bill_address2 = $user_bill_address->xadd2_b;
				$bill_province = $user_bill_address->xprov_b;
				$bill_city = $user_bill_address->xcity_b;
				$bill_brgy = $user_bill_address->xbrgy_b;
				$bill_postal = $user_bill_address->xpostal_b;
				$bill_country = $user_bill_address->xcountry_b;
				$bill_address_type = $user_bill_address->add_type_b;
				$bill_mobile = $user->f_mobilenumber;
				$bill_contact = $user_bill_address->xcontactnumber1_b;

				$ship_address1 = $user_ship_address->xadd1;
				$ship_address2 = $user_ship_address->xadd2;
				$ship_province = $user_ship_address->xprov;
				$ship_city = $user_ship_address->xcity;
				$ship_brgy = $user_ship_address->xbrgy;
				$ship_postal = $user_ship_address->xpostal;
				$ship_country = $user_ship_address->xcountry;
				$ship_address_type = $user_ship_address->add_type;
				$ship_mobile = $user->f_mobilenumber;
				$ship_contact = $user_ship_address->xcontactnumber1;
				$user_type = 'Member';
				$username = $email;
				$item_code = $request->form_item_code;
				$item_desc = $request->form_item_desc;
			}
			$temp_arr[] = [
				'xtempcode' => uniqid(),
				'xfname' => $first_name,
				'xlname' => $last_name,
				'xadd1' => $bill_address1,
				'xadd2' => $bill_address2,
				'xprov' => $bill_province,
				'xcity' => $bill_city,
				'xbrgy' => $bill_brgy,
				'xpostal' => $bill_postal,
				'xcountry' => $bill_country,
				'xaddresstype' => $bill_address_type,
				'xemail' => $email,
				'xmobile' => $bill_mobile,
				'xcontact' => $bill_contact,
				'xshippadd1' => $ship_address1,
				'xshippadd2' => $ship_address2,
				'xshiprov' => $ship_province,
				'xshipcity' => $ship_city,
				'xshipbrgy' => $ship_brgy,
				'xshippostalcode' => $ship_postal,
				'xshipcountry' => $ship_country,
				'xshiptype' => $ship_address_type,
				'xlogs' => $order_no,
				'order_status' => 'Item Purchase',
				'order_tracker_code' => $order_no,
				'order_shipping_type' => '', 
				'order_ip' => $request->ip(),
				'xusertype' => $user_type,
				'xusernamex' => $username,
				'xstatus' => 1,
				'xuser_id' => $user_id
			];

			$summary_arr[] = [
				'item_code' => $item_code,
				'item_desc' => $item_desc,
				'shipping' => $request->shipping,
				'price' => $request->price,
				'subtotal' =>$request->subtotal,
				'quantity' => $request->quantity,
				'grand_total' => $request->grand_total,
				'same_address' => $same_address,
				'base_url' => $base_url->set_value,
				'ship_contact' => $ship_contact,
				'address' => $temp_arr
			];

			$insert = DB::table('fumaco_temp')->insert($temp_arr);
			DB::commit();
			return view('frontend.checkout.check_out_summary', compact('summary_arr'));
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}		
	}
}
