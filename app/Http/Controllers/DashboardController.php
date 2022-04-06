<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;
use Mail;
use DateTime;
use App\Http\Traits\ProductTrait;

class DashboardController extends Controller
{
	public function verify(){
		$user_id = Auth::user()->id;
		return view('auth.verify_otp', compact('user_id'));
	}

	public function resendOTP(){
		$otp = rand(111111, 999999);
		$api = DB::table('api_setup')->where('type', 'sms_gateway_api')->first();
		$phone = Auth::user()->mobile_number[0] == '0' ? '63'.substr(Auth::user()->mobile_number, 1) : Auth::user()->mobile_number;

		$sms = Http::asForm()->withHeaders([
			'Accept' => 'application/json',
			'Content-Type' => 'application/x-www-form-urlencoded',
		])->post($api->base_url, [
			'api_key' => $api->api_key,
			'api_secret' => $api->api_secret_key,
			'from' => 'FUMACO',
			'to' => preg_replace("/[^0-9]/", "", $phone),
			'text' => 'TWO-FACTOR AUTHENTICATION: Your One-Time PIN is '.$otp.' to login in Fumaco Website, valid only within 10 mins. For any help, please contact us at it@fumaco.com'
		]);

		$sms_response = json_decode($sms->getBody(), true);

		$details = [
			'otp' => $otp,
			'otp_time_sent' => Carbon::now()
		];

		DB::table('fumaco_admin_user')->where('id', Auth::user()->id)->update($details);

		$status = isset($sms_response['error']) ? 0 : 1;
		return response()->json(['status' => $status]);
	}

	public function verifyOTP(Request $request){
        if($request->otp != Auth::user()->otp){
            return redirect()->back()->with('error', 'OTP is incorrect and/or expired.');
        }

        $time_sent = Carbon::parse(Auth::user()->otp_time_sent);
        $now = Carbon::now()->toDateTimeString();

        $expiration_check = $time_sent->diffInMinutes($now);

        if($expiration_check > 10){
            return redirect()->back()->with('error', 'OTP is incorrect and/or expired.');
        }

		DB::table('fumaco_admin_user')->where('id', Auth::user()->id)->update(['otp_status' => 1, 'last_login' => Carbon::now(), 'last_login_ip' => $request->ip()]);
		return redirect('/admin/dashboard');
	}
	
	public function index(Request $request) {
		$users = DB::table('fumaco_users')->where('is_email_verified', 1)->count();

		$orders = DB::table('fumaco_order')->get();
		$excluded_statuses = DB::table('order_status')->where('update_stocks', 1)->get();

		$new_orders = collect($orders)->whereNotIn('order_status', collect($excluded_statuses)->pluck('status'))->where('order_status', '!=', 'Cancelled')->count();
		$total_sales = collect($orders)->where('order_status', '!=', 'Cancelled')->sum('amount_paid');
		$total_orders = collect($orders)->count();

		$most_searched = DB::table(DB::raw('(SELECT search_term, COUNT(*) as count FROM fumaco_search_terms GROUP BY search_term) AS subquery'))
			->select('search_term', 'count')
			->orderBy('count', 'desc')
			->limit(10)
			->get();

		$search_terms = [];
		foreach($most_searched as $search){
			$location = DB::table(DB::raw('(SELECT search_term, city, region, country, COUNT(*) as count FROM fumaco_search_terms where search_term = "'.$search->search_term.'" GROUP BY search_term, city, region, country ) AS subquery'))
				->select('search_term', 'city', 'region', 'country', 'count')
				->orderBy('count', 'desc')
				->get();

			$search_terms[] = [
				'search_term' => $search->search_term,
				'search_term_count' => $search->count,
				'location' => $location,
			];
		}

		// sales per month
		$sales_arr = [];

		for($month = 1; $month <= 12; $month++){
			if($request->year){
				$sales = DB::table('fumaco_order')->where('order_status', '!=', 'Cancelled')->whereMonth('order_date', $month)->whereYear('order_date', $request->year)->sum('amount_paid');
			}else{
				$sales = DB::table('fumaco_order')->where('order_status', '!=', 'Cancelled')->whereMonth('order_date', $month)->whereYear('order_date', Carbon::now()->format('Y'))->sum('amount_paid');
			}
			
			$month_name = DateTime::createFromFormat('!m', $month);

			$sales_arr[] = [
				'month' => $month,
				'sales' => number_format((float)$sales, 2, '.', ''),
				'month_name' => "'".$month_name->format('M')."'",
				'js_month_name' => $month_name->format('M')
			];
		}
		$sales_year = DB::table('fumaco_order')->selectRaw('YEAR(order_date)')->distinct()->get();

		if($request->ajax()){
			$sales_data = collect($sales_arr)->pluck('sales')->implode(',');
			$month_names = json_encode(collect($sales_arr)->pluck('js_month_name')->implode(','));
 			return response()->json([$sales_data, $month_names]);
		}

		// items on cart
		$cart_transactions = DB::table('fumaco_cart')->select('transaction_id', 'user_email', 'user_type')
			->groupBy('transaction_id', 'user_email', 'user_type')->get();
		$cart_arr = [];

		foreach($cart_transactions as $cart){
			$items = DB::table('fumaco_cart')->where('transaction_id', $cart->transaction_id)->orderBy('last_modified_at', 'desc')->get();
			$last_online = collect($items)->pluck('last_modified_at')->first();
			$status = 'Abandoned';
			if(Carbon::parse($last_online)->format('M d') == Carbon::now()->format('M d')){
				$time_difference = Carbon::parse($last_online)->diff(Carbon::now());
				if($time_difference->format('%h') < 8){
					$status = 'Active';
				}
			}
			
			$items_arr = [];
			foreach($items as $item){
				$slug = DB::table('fumaco_items')->where('f_idcode', $item->item_code)->pluck('slug')->first();

				$items_arr[] = [
					'item_code' => $item->item_code,
					'qty' => $item->qty,
					'slug' => $slug
				];
			}

			$cart_arr[] = [
				'transaction_id' => $cart->transaction_id,
				'owner' => $cart->user_email,
				'user_type' => $cart->user_type,
				'total_qty' => collect($items_arr)->sum('qty'),
				'items' => $items_arr,
				'last_online' => $last_online,
				'status' => $status
			]; 
		}

		$orders = DB::table('fumaco_order')->whereNotIn('order_status', collect($excluded_statuses)->pluck('status'))->where('order_status', '!=', 'Cancelled')->select('order_number', 'order_email')->distinct()->get();

		$converted_orders = [];

		foreach($orders as $order){
			$order_details = DB::table('fumaco_order')->where('order_number', $order->order_number)->first();
			$ordered_items = DB::table('fumaco_order_items')->where('order_number', $order->order_number)->orderBy('update_date', 'desc')->get();
			$last_online = collect($ordered_items)->pluck('update_date')->first();
			$orders_arr = [];

			$store_address = null;
			if($order_details->order_shipping == 'Store Pickup') {
				$store = DB::table('fumaco_store')->where('store_name', $order_details->store_location)->first();
				$store_address = ($store) ? $store->address : null;
			}

			foreach($ordered_items as $items){
				$slug = DB::table('fumaco_items')->where('f_idcode', $item->item_code)->pluck('slug')->first();

				$orders_arr[] = [
					'order_number' => $items->order_number,
					'item_code' => $items->item_code,
					'item_name' => $items->item_name,
					'qty' => $items->item_qty,
					'item_price' => $items->item_price,
					'item_discount' => $items->item_discount,
					'item_total' => $items->item_total_price,
					'slug' => $slug
				];
			}

			$converted_orders[] = [
				'transaction_id' => $order->order_number,
				'owner' => $order->order_email,
				'user_type' => 'member',
				'items' => $orders_arr,
				'last_online' => $last_online,
				'status' => 'Converted',
				'first_name' => $order_details->order_name,
				'last_name' => $order_details->order_lastname,
				'bill_contact_person' => $order_details->order_contactperson,
				'ship_contact_person' => $order_details->order_ship_contactperson,
				'email' => $order_details->order_email,
				'contact' => $order_details->order_contact == 0 ? '' : $order_details->order_contact ,
				'date' => Carbon::parse($order_details->order_update)->format('M d, Y - h:m A'),
				'total_qty' => collect($orders_arr)->sum('qty'),
				'ordered_items' => $items_arr,
				'order_tracker_code' => $order_details->tracker_code,
				'payment_method' => $order_details->order_payment_method,
				'cust_id' => $order_details->order_account,
				'bill_address1' => $order_details->order_bill_address1,
				'bill_address2' => $order_details->order_bill_address2,
				'bill_province' => $order_details->order_bill_prov,
				'bill_city' => $order_details->order_bill_city,
				'bill_brgy' => $order_details->order_bill_brgy,
				'bill_country' => $order_details->order_bill_country,
				'bill_postal' => $order_details->order_bill_postal,
				'bill_email' => $order_details->order_bill_email,
				'bill_contact' => $order_details->order_bill_contact,
				'ship_address1' => $order_details->order_ship_address1,
				'ship_address2' => $order_details->order_ship_address2,
				'ship_province' => $order_details->order_ship_prov,
				'ship_city' => $order_details->order_ship_city,
				'ship_brgy' => $order_details->order_ship_brgy,
				'ship_country' => $order_details->order_ship_country,
				'ship_postal' => $order_details->order_ship_postal,
				'shipping_name' => $order_details->order_shipping,
				'shipping_amount' => $order_details->order_shipping_amount,
				'grand_total' => ($order_details->order_shipping_amount + ($order_details->order_subtotal - $order_details->discount_amount)),
				'order_status' => $order_details->order_status,
				'estimated_delivery_date' => $order_details->estimated_delivery_date,
				'payment_id' => $order_details->payment_id,
				'payment_method' => $order_details->order_payment_method,
				'subtotal' => $order_details->order_subtotal,
				'order_type' => $order_details->order_type,
				'user_email' => $order_details->user_email,
				'billing_business_name' => $order_details->billing_business_name,
				'shipping_business_name' => $order_details->shipping_business_name,
				'pickup_date' => Carbon::parse($order_details->pickup_date)->format('M d, Y'),
				'store_address' => $store_address,
				'store' => $order_details->store_location,
				'voucher_code' => $order_details->voucher_code,
				'discount_amount' => $order_details->discount_amount
			]; 
		}
		$merged = collect($cart_arr)->merge($converted_orders);
		$cart_collection = $merged->sortBy('last_online', SORT_REGULAR, true)->values()->all();

		// Get current page form url e.x. &page=1
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		// Create a new Laravel collection from the array data
		$itemCollection = collect($cart_collection);
		// Define how many items we want to be visible in each page
		$perPage = 10;
		// Slice the collection to get the items to display in current page
		$currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
		// Create our paginator and pass it to the view
		$paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
		// set url path for generted links
		$paginatedItems->setPath($request->url());
		$cart_collection = $paginatedItems;

		$abandoned_cart = DB::table('fumaco_temp')->orderBy('xdateupdate', 'desc')->paginate(10, ['*'], 'abandoned_page');

		$abandoned_order_numbers = collect($abandoned_cart->items())->map(function($result){
			return $result->order_tracker_code;
		});

		$items = DB::table('fumaco_order_items as order')
			->join('fumaco_items as items', 'order.item_code', 'items.f_idcode')
			->whereIn('order.order_number', $abandoned_order_numbers)
			->select('order.*', 'items.slug')
			->get();

		$guest_items = DB::table('fumaco_cart as cart')
			->join('fumaco_items as items', 'cart.item_code', 'items.f_idcode')
			->whereIn('cart.transaction_id', $abandoned_order_numbers)
			->select('cart.*', 'items.slug', 'items.f_name_name as item_name', 'items.f_onsale', 'items.f_price', 'items.f_original_price')
			->get();

		$abandoned_items = collect($items)->groupBy('order_number');
		$guest_abandoned_items = collect($guest_items)->groupBy('transaction_id');

		$abandoned_arr = [];
		foreach($abandoned_cart as $abandoned){
			$items_arr = [];
			$active = 1;

			if(in_array($abandoned->last_transaction_page, ['Shopping Cart', 'Shopping Cart Page'])){
				if(isset($guest_abandoned_items[$abandoned->order_tracker_code])){
					foreach($guest_abandoned_items[$abandoned->order_tracker_code] as $items){
						$items_arr[] = [
							'item_code' => $items->item_code,
							'item_name' => $items->item_name,
							'slug' => $items->slug,
							'qty' => $items->qty,
							'item_price' => $items->f_onsale == 1 ? $items->f_price : $items->f_original_price,
							'total_price' => $items->f_onsale == 1 ? $items->qty * $items->f_price : $items->qty * $items->f_original_price
						];
					}
				}else{
					$active = 0;
				}
			}else{
				if(isset($abandoned_items[$abandoned->order_tracker_code])){
					foreach($abandoned_items[$abandoned->order_tracker_code] as $items){
						$items_arr[] = [
							'item_code' => $items->item_code,
							'item_name' => $items->item_name,
							'slug' => $items->slug,
							'qty' => $items->item_qty,
							'item_price' => $items->item_price,
							'total_price' => $items->item_total_price
						];
					}
				}else{
					$active = 0;
				}
			}

			$location = null;
			if($abandoned->ip_city or $abandoned->ip_region){
				$location = $abandoned->ip_city.', '.$abandoned->ip_region;
			}

			$abandoned_arr[] = [
				'name' => trim($abandoned->xfname . ' ' . $abandoned->xlname),
				'email' => $abandoned->xemail,
				'items' => $items_arr,
				'transaction' => $abandoned->last_transaction_page,
				'total_amount' => collect($items_arr)->sum('total_price'),
				'total_items' => collect($items_arr)->sum('qty'),
				'transaction_date' => $abandoned->xdateupdate,
				'order_number' => $abandoned->order_tracker_code,
				'ip_address' => $abandoned->order_ip,
				'location' => $location,
				'active' => $active,
			];
		}

		$today = Carbon::now()->format('Y-m-d');
		$weekbefore = Carbon::now()->subDays(7)->format('Y-m-d');
		$recent_searches_query = DB::table('fumaco_search_terms')->whereBetween('created_at', [$weekbefore, $today])
			->select('search_term', DB::raw('COUNT(id) as count'))->groupBy('search_term')
			->orderBy('count', 'desc')->limit(10)->get()->toArray();

		$recent_searches_terms = array_column($recent_searches_query, 'search_term');

		$recent_searches_location = DB::table('fumaco_search_terms')
			->whereIn('search_term', $recent_searches_terms)->whereBetween('created_at', [$weekbefore, $today])
			->select('search_term', 'city', 'region', 'country', DB::raw('COUNT(id) as count'))
			->groupBy('search_term', 'city', 'region', 'country')->get();

		$recent_searches_location = collect($recent_searches_location)->groupBy('search_term')->toArray();

		$recent_searches = [];
		foreach($recent_searches_query as $row){
			$location = array_key_exists($row->search_term, $recent_searches_location) ? $recent_searches_location[$row->search_term] : [];

			$recent_searches[] = [
				'search_term' => $row->search_term,
				'search_term_count' => $row->count,
				'location' => $location,
			];
		}

		return view('backend.dashboard.index', compact('new_orders', 'total_orders', 'users', 'total_sales', 'most_searched', 'sales_arr', 'sales_year', 'search_terms', 'cart_collection', 'cart_transactions', 'abandoned_cart', 'abandoned_arr', 'recent_searches'));
	}

	public function sendAbandonedCartEmail($transaction_id){
		$cart_details = DB::table('fumaco_cart')->where('transaction_id', $transaction_id)->select('user_email', 'item_code', 'qty')->get();

		$username = null;
		$customer_name = null;
		$abandon_details = [];

		if(count($cart_details) > 0){
			$item_codes = collect($cart_details)->map(function($result){
				return $result->item_code;
			});

			$username = collect($cart_details)->pluck('user_email')->first();
			$abandon_details = collect($cart_details);

			$user = DB::table('fumaco_users')->where('username', $username)->first();
			$customer_name = $user->f_name.' '.$user->f_lname;
		}else{
			$customer_info = DB::table('fumaco_temp')->where('order_tracker_code', $transaction_id)->select('xemail', 'xcontact_person', 'xshipcontact_person')->first();
			$user = DB::table('fumaco_users')->where('username', $customer_info->xemail)->first();

			$username = $customer_info->xemail;
			if($user){
				$customer_name = $user->f_name.' '.$user->f_lname;
			}else{
				$customer_name = $customer_info->xcontact_person ? $customer_info->xcontact_person : $customer_info->xshipcontact_person;
			}

			$order_items = DB::table('fumaco_order_items')->where('order_number', $transaction_id)->select('item_code', 'item_qty as qty')->get();

			$item_codes = collect($order_items)->map(function($result){
				return $result->item_code;
			});

			$abandon_details = collect($order_items);
		}

		if($username){
			$item_details = DB::table('fumaco_items')->whereIn('f_idcode', $item_codes)->get();
			$item_detail = collect($item_details)->groupBy('f_idcode');

			$item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $item_codes)->get();
			$image = collect($item_images)->groupBy('idcode');

			$cart_arr = [];
			foreach($abandon_details as $cart){
				$price = 0;
				if(isset($item_detail[$cart->item_code])){
					$price = $item_detail[$cart->item_code][0]->f_discount_trigger == 1 ? $item_detail[$cart->item_code][0]->f_price : $item_detail[$cart->item_code][0]->f_original_price;
				}

				$cart_arr[] = [
					'item_code' => $cart->item_code,
					'image' => isset($image[$cart->item_code]) ? $image[$cart->item_code][0]->imgprimayx : null,
					'qty' => $cart->qty,
					'name' => isset($item_detail[$cart->item_code]) ? $item_detail[$cart->item_code][0]->f_name_name : null,
					'price' => $price,
					'total_price_per_item' => $price * $cart->qty
				];
			}

			Mail::send('emails.abandoned_cart', ['cart_details' => $cart_arr, 'status' => 'Abandoned', 'username' => $username, 'customer_name' => $customer_name], function($message) use($username){
				$message->to(trim($username));
				$message->subject("Let's check this off your list - FUMACO");
			});

			// if(count(Mail::failures()) > 0) { // for email error checking
			// 	return Mail::failures();
			// }
		}

		return redirect()->back()->with('success', 'Email Sent!');
	}
}
