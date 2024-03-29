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
	use ProductTrait;

	public function verify(){
		$user_id = Auth::user()->id;
		return view('auth.verify_otp', compact('user_id'));
	}

	public function resendOTP(Request $request){
		$otp = rand(11111, 99999);
		$api = DB::table('api_setup')->where('type', 'sms_gateway_api')->first();
		$phone = Auth::user()->mobile_number[0] == '0' ? '63'.substr(Auth::user()->mobile_number, 1) : Auth::user()->mobile_number;

		if($request->channel == 'sms'){
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

			if(isset($sms_response['error'])){
				return response()->json(['status' => 0]);
			}
		}else{
			try {
				Mail::send('emails.admin_otp', ['otp' => $otp], function($message) {
					$message->to(Auth::user()->username);
					$message->subject('TWO-FACTOR Authentication');
				});
			} catch (\Swift_TransportException  $e) {
				return response()->json(['status' => 0]);
			}
		}
		
		$details = [
			'otp' => $otp,
			'otp_time_sent' => Carbon::now()
		];

		DB::table('fumaco_admin_user')->where('id', Auth::user()->id)->update($details);

		return response()->json(['status' => 1]);
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
	
	// /admin/dashboard
	public function index(Request $request) {
		$users = DB::table('fumaco_users')->where('is_email_verified', 1)->count();

		$excluded_statuses = DB::table('order_status')->where('update_stocks', 1)->pluck('status');
		$excluded_statuses = collect($excluded_statuses)->push('Cancelled');

		$excluded_statuses_imploded = $excluded_statuses->implode('","');
		$orders = DB::table('fumaco_order')->select('fumaco_order.*', DB::raw('(case when (order_status in ("'.$excluded_statuses_imploded.'")) then order_status else "New Order" end) as ref_status'), DB::raw('YEAR(order_date) year, MONTH(order_date) month'))->get();

		$orders_by_status = collect($orders)->groupBy('ref_status');
		$new_orders_arr = isset($orders_by_status['New Order']) ? $orders_by_status['New Order'] : [];
		$ordered_items_query = DB::table('fumaco_order_items')->whereIn('order_number', collect($new_orders_arr)->pluck('order_number'))->orderBy('update_date', 'desc')->get()->groupBy('order_number');

		$new_orders = count($new_orders_arr);
		$total_sales = collect($orders)->where('order_status', '!=', 'Cancelled')->sum('amount_paid');
		$total_orders = collect($orders)->count();

		// sales per month
		$sales_arr = [];
		$year = $request->year ? $request->year : Carbon::now()->format('Y');

		$sales_per_month = collect($orders)->map(function ($q) {
			$q->month_year = Carbon::parse($q->order_date)->format('M-Y');
			return $q;
		})->groupBy('month_year');

		for($month = 1; $month <= 12; $month++){
			$month_name = DateTime::createFromFormat('!m', $month);
			$month_name = $month_name->format('M');

			$sales = isset($sales_per_month[$month_name.'-'.$year]) ? collect($sales_per_month[$month_name.'-'.$year])->sum('amount_paid') : 0;

			$sales_arr[] = [
				'month' => $month,
				'sales' => number_format((float)$sales, 2, '.', ''),
				'month_name' => "'".$month_name."'",
				'js_month_name' => $month_name
			];
		}

		$sales_year = collect($orders)->map(function ($q) {
			return Carbon::parse($q->order_date)->format('Y');
		})->unique()->sort()->values()->all();

		if($request->ajax()){
			$sales_data = collect($sales_arr)->pluck('sales')->implode(',');
			$month_names = json_encode(collect($sales_arr)->pluck('js_month_name')->implode(','));
 			return response()->json([$sales_data, $month_names]);
		}

		// items on cart
		$cart_transactions = DB::table('fumaco_cart')->get();

		$item_codes = collect($new_orders_arr->pluck('item_code'))->merge(collect($cart_transactions)->pluck('item_code'))->filter()->unique()->values()->all();

		$item_details = DB::table('fumaco_items')->whereIn('f_idcode', $item_codes)->get()->groupBy('f_idcode');
		$cart_transactions = $cart_transactions->groupBy('transaction_id');
		$cart_arr = [];

		foreach($cart_transactions as $transaction_id => $cart){
			$last_online = collect($cart)->pluck('last_modified_at')->first();
			$status = 'Abandoned';
			if(Carbon::parse($last_online)->format('M d') == Carbon::now()->format('M d')){
				$time_difference = Carbon::parse($last_online)->diff(Carbon::now());
				if($time_difference->format('%h') < 8){
					$status = 'Active';
				}
			}
			
			$items_arr = [];
			foreach($cart as $item){
				$slug = isset($item_details[$item->item_code]) ? $item_details[$item->item_code][0]->slug : null;

				$items_arr[] = [
					'item_code' => $item->item_code,
					'qty' => $item->qty,
					'slug' => $slug
				];
			}

			$cart_arr[] = [
				'transaction_id' => $transaction_id,
				'owner' => $cart[0]->user_email,
				'user_type' => $cart[0]->user_type,
				'total_qty' => collect($items_arr)->sum('qty'),
				'items' => $items_arr,
				'last_online' => $last_online,
				'status' => $status
			]; 
		}

		$converted_orders = [];
		$stores = DB::table('fumaco_store')->pluck('address', 'store_name');

		foreach($new_orders_arr as $order){
			$ordered_items = isset($ordered_items_query[$order->order_number]) ? $ordered_items_query[$order->order_number] : [];
			$last_online = collect($ordered_items)->pluck('update_date')->first();
			$orders_arr = [];

			$store_address = null;
			if($order->order_shipping == 'Store Pickup') {
				$store_address = isset($stores[$order->store_location]) ? $stores[$order->store_location] : null;
			}

			foreach($ordered_items as $items){
				$slug = isset($item_details[$items->item_code]) ? $item_details[$items->item_code][0]->slug : null;

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
				'first_name' => $order->order_name,
				'last_name' => $order->order_lastname,
				'bill_contact_person' => $order->order_contactperson,
				'ship_contact_person' => $order->order_ship_contactperson,
				'email' => $order->order_email,
				'contact' => $order->order_contact == 0 ? '' : $order->order_contact ,
				'date' => Carbon::parse($order->order_update)->format('M d, Y - h:m A'),
				'total_qty' => collect($orders_arr)->sum('qty'),
				'ordered_items' => $orders_arr,
				'order_tracker_code' => $order->tracker_code,
				'payment_method' => $order->order_payment_method,
				'cust_id' => $order->order_account,
				'bill_address1' => $order->order_bill_address1,
				'bill_address2' => $order->order_bill_address2,
				'bill_province' => $order->order_bill_prov,
				'bill_city' => $order->order_bill_city,
				'bill_brgy' => $order->order_bill_brgy,
				'bill_country' => $order->order_bill_country,
				'bill_postal' => $order->order_bill_postal,
				'bill_email' => $order->order_bill_email,
				'bill_contact' => $order->order_bill_contact,
				'ship_address1' => $order->order_ship_address1,
				'ship_address2' => $order->order_ship_address2,
				'ship_province' => $order->order_ship_prov,
				'ship_city' => $order->order_ship_city,
				'ship_brgy' => $order->order_ship_brgy,
				'ship_country' => $order->order_ship_country,
				'ship_postal' => $order->order_ship_postal,
				'shipping_name' => $order->order_shipping,
				'shipping_amount' => $order->order_shipping_amount,
				'grand_total' => ($order->order_shipping_amount + ($order->order_subtotal - $order->discount_amount)),
				'order_status' => $order->order_status,
				'estimated_delivery_date' => $order->estimated_delivery_date,
				'payment_id' => $order->payment_id,
				'subtotal' => $order->order_subtotal,
				'order_type' => $order->order_type,
				'user_email' => $order->user_email,
				'billing_business_name' => $order->billing_business_name,
				'shipping_business_name' => $order->shipping_business_name,
				'pickup_date' => Carbon::parse($order->pickup_date)->format('M d, Y'),
				'store_address' => $store_address,
				'store' => $order->store_location,
				'voucher_code' => $order->voucher_code,
				'discount_amount' => $order->discount_amount
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

		$abandoned_cart_query1 = DB::table('fumaco_temp as ft')
			->join('fumaco_order_items as foi', 'ft.order_tracker_code', 'foi.order_number')
			->whereDate('ft.xdateupdate', '<', Carbon::now()->subHours(8))
			->select('ft.order_tracker_code', 'ft.xdateupdate')->groupBy('ft.order_tracker_code', 'ft.xdateupdate');

		$abandoned_cart = DB::table('fumaco_temp as ft')
			->join('fumaco_cart as fc', 'ft.order_tracker_code', 'fc.transaction_id')
			->whereDate('ft.xdateupdate', '<', Carbon::now()->subHours(8))
			->select('ft.order_tracker_code', 'ft.xdateupdate')->groupBy('ft.order_tracker_code', 'ft.xdateupdate')
			->unionAll($abandoned_cart_query1)->orderBy('xdateupdate', 'desc')->paginate(10, ['*'], 'abandoned_page');

		$abandoned_order_numbers = collect($abandoned_cart->items())->pluck('order_tracker_code');

		$abandoned_carts = DB::table('fumaco_temp')->whereIn('order_tracker_code', $abandoned_order_numbers)->orderBy('xdateupdate', 'desc')->get();

		$items = DB::table('fumaco_order_items as order')
			->join('fumaco_items as items', 'order.item_code', 'items.f_idcode')
			->whereIn('order.order_number', $abandoned_order_numbers)
			->select('order.*', 'items.slug')
			->get();

		$guest_items = DB::table('fumaco_cart as cart')
			->join('fumaco_items as items', 'cart.item_code', 'items.f_idcode')
			->whereIn('cart.transaction_id', $abandoned_order_numbers)
			->select('cart.*', 'items.slug', 'items.f_name_name as item_name', 'items.f_default_price')
			->get();

		$on_sale_items = $this->onSaleItems(collect($guest_items)->pluck('item_code'));

		$abandoned_items = collect($items)->groupBy('order_number');
		$guest_abandoned_items = collect($guest_items)->groupBy('transaction_id');

		$abandoned_arr = [];
		foreach($abandoned_carts as $abandoned){
			$items_arr = [];
			$active = 1;

			if(isset($guest_abandoned_items[$abandoned->order_tracker_code])){
				foreach($guest_abandoned_items[$abandoned->order_tracker_code] as $items){
					$f_onsale = false;
					$f_item_price = $items->f_default_price;
					if (array_key_exists($items->item_code, $on_sale_items)) {
						$f_onsale = $on_sale_items[$items->item_code]['on_sale'];
						$f_item_price = $on_sale_items[$items->item_code]['discounted_price'];
					}
					$items_arr[] = [
						'item_code' => $items->item_code,
						'item_name' => $items->item_name,
						'slug' => $items->slug,
						'qty' => $items->qty,
						'item_price' => $f_item_price,
						'total_price' => $items->qty * $f_item_price
					];
				}
			}else if(isset($abandoned_items[$abandoned->order_tracker_code])){
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

			$location = null;
			if($abandoned->ip_city or $abandoned->ip_region){
				$location = $abandoned->ip_city.', '.$abandoned->ip_region.', '.$abandoned->ip_country;
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
				'shipping_contact_person' => $abandoned->xshipcontact_person,
				'billing_address' => $abandoned->xadd1 . ' ' . $abandoned->xadd2 . ' ' . $abandoned->xbrgy . ' ' . $abandoned->xcity . ' ' . $abandoned->xprov . ' ' . $abandoned->xcountry,
				'billing_contact_person' => $abandoned->xemail,
				'billing_mobile' => $abandoned->xmobile,
				'shipping_method' => $abandoned->shipping_name,
				'shipping_address' => $abandoned->xshippadd1 . ' ' . $abandoned->xshippadd2 . ' ' . $abandoned->xshipbrgy . ' ' . $abandoned->xshipcity . ' ' . $abandoned->xshiprov . ' ' . $abandoned->xshipcountry,
			];
		}

		// Get top 10 most searched terms
		$most_searched = DB::table('fumaco_search_terms')->select('search_term', DB::raw('COUNT(*) as count'))->groupBy('search_term')->orderBy('count', 'desc')->limit(10)->get();
		$locations = DB::table('fumaco_search_terms')->whereIn('search_term', collect($most_searched)->pluck('search_term'))
			->select(DB::raw('lower(search_term) as search_term'), 'city', 'region', 'country', DB::raw('COUNT(*) as count'))
			->groupBy('search_term', 'city', 'region', 'country')
			->orderBy('count', 'desc')
			->get()->groupBy('search_term');

		$search_terms = [];
		foreach ($most_searched as $search) {
			$term = strtolower($search->search_term);

			$search_terms[] = [
				'search_term' => $search->search_term,
				'search_term_count' => $search->count,
				'location' => isset($locations[$term]) ? $locations[$term] : []
			];
		}

		// Get the 10 most recent searches of the week
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
			$customer_info = DB::table('fumaco_temp')->where('order_tracker_code', $transaction_id)->select('xemail', 'xcontact_person', 'xshipcontact_person')->first();

			if($user){
				$customer_name = $user->f_name.' '.$user->f_lname;
			}else if($customer_info){
				$customer_name = $customer_info->xcontact_person ? $customer_info->xcontact_person : $customer_info->xshipcontact_person;
			}else{
				$customer_name = 'Customer';
			}
		}else{
			$customer_info = DB::table('fumaco_temp')->where('order_tracker_code', $transaction_id)->select('xemail', 'xcontact_person', 'xshipcontact_person')->first();
			$user = DB::table('fumaco_users')->where('username', $customer_info->xemail)->first();

			$username = $customer_info->xemail;
			if($user){
				$customer_name = $user->f_name.' '.$user->f_lname;
			}else if($customer_info){
				$customer_name = $customer_info->xcontact_person ? $customer_info->xcontact_person : $customer_info->xshipcontact_person;
			}else{
				$customer_name = 'Customer';
			}

			$order_items = DB::table('fumaco_order_items')->where('order_number', $transaction_id)->select('item_code', 'item_qty as qty')->get();

			$item_codes = collect($order_items)->map(function($result){
				return $result->item_code;
			});

			$abandon_details = collect($order_items);
		}

		if($username){
			$sale = DB::table('fumaco_on_sale')
				->whereDate('start_date', '<=', Carbon::now()->toDateString())
				->whereDate('end_date', '>=', Carbon::today()->toDateString())
				->where('status', 1)->where('apply_discount_to', 'All Items')
				->select('discount_type', 'discount_rate')->first();

			$item_details = DB::table('fumaco_items')->whereIn('f_idcode', $item_codes)->get();
			$item_detail = collect($item_details)->groupBy('f_idcode');

			$item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $item_codes)->get();
			$image = collect($item_images)->groupBy('idcode');

			$clearance_sale_items = $this->isIncludedInClearanceSale(array_keys(collect($item_details)->toArray()));

			$on_sale_items = $this->onSaleItems(array_keys(collect($item_details)->toArray()));

			$sale_per_category = [];
			if (!$on_sale_items && !Auth::check()) {
				$item_categories = array_column($item_details->toArray(), 'f_cat_id');
				$sale_per_category = $this->getSalePerItemCategory($item_categories);
			}

			$cart_arr = [];
			foreach($abandon_details as $cart){
				$price = 0;
				$on_sale = false;
				$discount_type = $discount_rate = null;
				if (array_key_exists($cart->item_code, $on_sale_items)) {
					$on_sale = $on_sale_items[$cart->item_code]['on_sale'];
					$discount_type = $on_sale_items[$cart->item_code]['discount_type'];
					$discount_rate = $on_sale_items[$cart->item_code]['discount_rate'];
				}

				$item_detail = [
					'default_price' => isset($item_detail[$cart->item_code]) ? $item_detail[$cart->item_code][0]->f_default_price : 0,
					'category_id' => isset($item_detail[$cart->item_code]) ? $item_detail[$cart->item_code][0]->f_cat_id : null,
					'item_code' => $cart->item_code,
					'discount_type' => $discount_type,
					'discount_rate' => $discount_rate,
					'stock_uom' => isset($item_detail[$cart->item_code]) ? $item_detail[$cart->item_code][0]->f_stock_uom : null,
					'on_sale' => $on_sale
				];

				$is_on_clearance_sale = false;
				if (array_key_exists($cart->item_code, $clearance_sale_items)) {
					$item_detail['discount_type'] = $clearance_sale_items[$cart->item_code][0]->discount_type;
					$item_detail['discount_rate'] = $clearance_sale_items[$cart->item_code][0]->discount_rate;
					$is_on_clearance_sale = true;
				}
				$item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);

				$price = $item_price_data['discounted_price'];

				$cart_arr[] = [
					'item_code' => $cart->item_code,
					'image' => isset($image[$cart->item_code]) ? $image[$cart->item_code][0]->imgprimayx : null,
					'qty' => $cart->qty,
					'name' => isset($item_detail[$cart->item_code]) ? $item_detail[$cart->item_code][0]->f_name_name : null,
					'price' => $price,
					'total_price_per_item' => $price * $cart->qty
				];
			}

			try {
				Mail::send('emails.abandoned_cart', ['cart_details' => $cart_arr, 'status' => 'Abandoned', 'username' => $username, 'customer_name' => $customer_name], function($message) use($username){
					$message->to(trim($username));
					$message->subject("Let's check this off your list - FUMACO");
				});
			} catch (\Swift_TransportException  $e) {
				return redirect()->back()->with('error', 'Email not sent!');
			}
		}

		return redirect()->back()->with('success', 'Email Sent!');
	}
}
