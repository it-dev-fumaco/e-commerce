<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use DB;
use Mail;
use Auth;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Traits\GeneralTrait;

class OrderController extends Controller
{
    use GeneralTrait;
    public function orderList(Request $request){
        $search_id = ($request->search) ? $request->search : '';
        $order_status = ($request->order_status) ? $request->order_status : '';

        $exluded_status = ['Cancelled', 'Delivered', 'Order Completed', 'Order Delivered'];

        $orders = DB::table('fumaco_order')->where('order_number', 'LIKE', '%'.$search_id.'%')->where('order_status', 'LIKE', '%'.$order_status.'%')->whereNotIn('order_status', $exluded_status)->orderBy('id', 'desc')->paginate(10);

        $orders_arr = [];

        foreach($orders as $o){
            $items_arr = [];
            $items = DB::table('fumaco_order_items')->where('order_number', $o->order_number)->get();
            foreach($items as $i){

                $bundle_items = DB::table('fumaco_product_bundle_item')->where('parent_item_code', $i->item_code)->get();
                $items_arr[] = [
                    'order_number' => $i->order_number,
                    'item_code' => $i->item_code,
                    'item_name' => $i->item_name,
                    'item_qty' => $i->item_qty,
                    'item_price' => $i->item_price,
                    'item_discount' => $i->item_discount,
                    'item_total' => $i->item_total_price,
                    'bundle' => $bundle_items
                ];
            }

            $store_address = null;
			if($o->order_shipping == 'Store Pickup') {
				$store = DB::table('fumaco_store')->where('store_name', $o->store_location)->first();
				$store_address = ($store) ? $store->address : null;
			}

            $order_status = DB::table('order_status as s')
            ->join('order_status_process as p', 's.order_status_id', 'p.order_status_id')
            ->where('shipping_method', $o->order_shipping)
            ->orderBy('order_sequence', 'asc')
            ->get();

            $orders_arr[] = [
                'order_id' => $o->id,
                'order_date' => $o->order_date,
                'order_no' => $o->order_number,
                'first_name' => $o->order_name,
                'last_name' => $o->order_lastname,
                'bill_contact_person' => $o->order_contactperson,
                'ship_contact_person' => $o->order_ship_contactperson,
                'email' => $o->order_email,
                'contact' => $o->order_contact == 0 ? '' : $o->order_contact ,
                'date' => Carbon::parse($o->order_date)->format('M d, Y - h:i A'),
                'ordered_items' => $items_arr,
                'order_tracker_code' => $o->tracker_code,
                'issuing_bank' => $o->issuing_bank,
                'cust_id' => $o->order_account,
                'bill_address1' => $o->order_bill_address1,
                'bill_address2' => $o->order_bill_address2,
                'bill_province' => $o->order_bill_prov,
                'bill_city' => $o->order_bill_city,
                'bill_brgy' => $o->order_bill_brgy,
                'bill_country' => $o->order_bill_country,
                'bill_postal' => $o->order_bill_postal,
                'bill_email' => $o->order_bill_email,
                'bill_contact' => $o->order_bill_contact,
                'ship_address1' => $o->order_ship_address1,
                'ship_address2' => $o->order_ship_address2,
                'ship_province' => $o->order_ship_prov,
                'ship_city' => $o->order_ship_city,
                'ship_brgy' => $o->order_ship_brgy,
                'ship_country' => $o->order_ship_country,
                'ship_postal' => $o->order_ship_postal,
                'shipping_name' => $o->order_shipping,
                'shipping_amount' => $o->order_shipping_amount,
                'grand_total' => ($o->order_shipping_amount + ($o->order_subtotal - $o->discount_amount)),
                'status' => $o->order_status,
                'estimated_delivery_date' => $o->estimated_delivery_date,
                'payment_id' => $o->payment_id,
                'payment_method' => $o->order_payment_method,
                'subtotal' => $o->order_subtotal,
                'order_type' => $o->order_type,
                'user_email' => $o->user_email,
                'billing_business_name' => $o->billing_business_name,
                'voucher_code' => $o->voucher_code,
                'discount_amount' => $o->discount_amount,
                'shipping_business_name' => $o->shipping_business_name,
                'pickup_date' => Carbon::parse($o->pickup_date)->format('M d, Y'),
                'store_address' => $store_address,
                'store' => $o->store_location,
                'order_status' => $order_status,
                'deposit_slip_image' => $o->deposit_slip_image,
                'payment_status' => $o->payment_status
            ];
        }

        return view('backend.orders.order_list', compact('orders_arr', 'orders'));
    }

    public function cancelledOrders(Request $request){
        $search_id = ($request->search) ? $request->search : '';
        $orders = DB::table('fumaco_order')->where('order_number', 'LIKE', '%'.$search_id.'%')->where('order_status', 'Cancelled')->orderBy('id', 'desc')->paginate(10);

        $orders_arr = [];

        foreach($orders as $o){
            $items_arr = [];
            $items = DB::table('fumaco_order_items')->where('order_number', $o->order_number)->get();
            foreach($items as $i){
                $items_arr[] = [
                    'item_code' => $i->item_code,
                    'item_name' => $i->item_name,
                    'item_qty' => $i->item_qty,
                    'item_price' => $i->item_price,
                    'item_discount' => $i->item_discount,
                    'item_total' => $i->item_total_price,
                ];
            }
            $orders_arr[] = [
                'order_no' => $o->order_number,
                'first_name' => $o->order_name,
                'last_name' => $o->order_lastname,
                'bill_contact_person' => $o->order_contactperson,
                'ship_contact_person' => $o->order_ship_contactperson,
                'email' => $o->order_email,
                'date' => Carbon::parse($o->order_update)->format('M d, Y - h:m A'),
                'ordered_items' => $items_arr,
                'order_tracker_code' => $o->tracker_code,
                'payment_method' => $o->order_payment_method,
                'cust_id' => $o->order_account,
                'bill_address1' => $o->order_bill_address1,
                'bill_address2' => $o->order_bill_address2,
                'bill_province' => $o->order_bill_prov,
                'bill_city' => $o->order_bill_city,
                'bill_brgy' => $o->order_bill_brgy,
                'bill_country' => $o->order_bill_country,
                'bill_postal' => $o->order_bill_postal,
                'ship_address1' => $o->order_ship_address1,
                'ship_address2' => $o->order_ship_address2,
                'ship_province' => $o->order_ship_prov,
                'ship_city' => $o->order_ship_city,
                'ship_brgy' => $o->order_ship_brgy,
                'ship_country' => $o->order_ship_country,
                'ship_postal' => $o->order_ship_postal,
                'shipping_name' => $o->order_shipping,
                'shipping_amount' => $o->order_shipping_amount,
                'grand_total' => ($o->order_shipping_amount + $o->order_subtotal),
                'status' => $o->order_status,
                'estimated_delivery_date' => $o->estimated_delivery_date,
                'payment_id' => $o->payment_id,
                'payment_method' => $o->order_payment_method,
                'subtotal' => $o->order_subtotal,
                'date_cancelled' => Carbon::parse($o->date_cancelled)->format('M d, Y - h:m A')
            ];
        }

        return view('backend.orders.cancelled_orders', compact('orders_arr', 'orders'));
    }

    public function deliveredOrders(Request $request){
        $search_id = ($request->search) ? $request->search : '';
        $orders = DB::table('fumaco_order')->where('order_number', 'LIKE', '%'.$search_id.'%')
            ->whereNotIn('order_status', ['Order Placed', 'Order Confirmed', 'Out for Delivery', 'Ready for Pickup', 'Cancelled'])
            ->orderBy('id', 'desc')->paginate(10);

        $orders_arr = [];

        foreach($orders as $o){
            $items_arr = [];
            $items = DB::table('fumaco_order_items')->where('order_number', $o->order_number)->get();
            foreach($items as $i){
                $items_arr[] = [
                    'item_code' => $i->item_code,
                    'item_name' => $i->item_name,
                    'item_qty' => $i->item_qty,
                    'item_price' => $i->item_price,
                    'item_discount' => $i->item_discount,
                    'item_total' => $i->item_total_price,
                ];
            }
            $orders_arr[] = [
                'order_no' => $o->order_number,
                'first_name' => $o->order_name,
                'last_name' => $o->order_lastname,
                'bill_contact_person' => $o->order_contactperson,
                'ship_contact_person' => $o->order_ship_contactperson,
                'email' => $o->order_email,
                'date' => Carbon::parse($o->order_update)->format('M d, Y - h:m A'),
                'ordered_items' => $items_arr,
                'order_tracker_code' => $o->tracker_code,
                'payment_method' => $o->order_payment_method,
                'cust_id' => $o->order_account,
                'bill_address1' => $o->order_bill_address1,
                'bill_address2' => $o->order_bill_address2,
                'bill_province' => $o->order_bill_prov,
                'bill_city' => $o->order_bill_city,
                'bill_brgy' => $o->order_bill_brgy,
                'bill_country' => $o->order_bill_country,
                'bill_postal' => $o->order_bill_postal,
                'ship_address1' => $o->order_ship_address1,
                'ship_address2' => $o->order_ship_address2,
                'ship_province' => $o->order_ship_prov,
                'ship_city' => $o->order_ship_city,
                'ship_brgy' => $o->order_ship_brgy,
                'ship_country' => $o->order_ship_country,
                'ship_postal' => $o->order_ship_postal,
                'shipping_name' => $o->order_shipping,
                'shipping_amount' => $o->order_shipping_amount,
                'grand_total' => ($o->order_shipping_amount + $o->order_subtotal),
                'status' => $o->order_status,
                'estimated_delivery_date' => $o->estimated_delivery_date,
                'date_delivered' => $o->date_delivered,
                'payment_id' => $o->payment_id,
                'payment_method' => $o->order_payment_method,
                'subtotal' => $o->order_subtotal
            ];
        }
        return view('backend.orders.delivered_orders', compact('orders_arr', 'orders'));
    }

    public function statusUpdate(Request $request){
        DB::beginTransaction();
		try{
            $now  = Carbon::now()->toDateTimeString();
            $status = $request->status;
            $delivery_date = "";
            $date_cancelled = "";
            $sms_message = null;

            if($status) {
                $order_status_check = DB::table('order_status')->where('status', $status)->first();

                $order_details = DB::table('fumaco_order')->where('order_number', $request->order_number)->first();
                $ordered_items = DB::table('fumaco_order_items')->where('order_number', $request->order_number)->get();
                
                if($order_status_check){
                    if($order_status_check->update_stocks == 1){ // check if stocks needs to update
                        foreach($ordered_items as $orders){
                            $items = DB::table('fumaco_items')->select('f_reserved_qty', 'stock_source', 'f_qty')->where('f_idcode', $orders->item_code)->first();
                            $qty_left = $items->f_reserved_qty - $orders->item_qty;
    
                            $quantity_update = [
                                'f_reserved_qty' => $qty_left
                            ];
    
                            if($items->stock_source == 0){
                                $quantity_update['f_qty'] = $items->f_qty - $orders->item_qty;
                            }
        
                            DB::table('fumaco_items')->where('f_idcode', $orders->item_code)->update($quantity_update);
                        }
                    }
                }

                if($status == 'Order Delivered' or $status == 'Order Completed'){
                    $delivery_date = Carbon::now()->toDateTimeString();
                }

                $payment_status = 'Payment Received';
                if($order_details->order_payment_method == 'Bank Deposit'){
                    $payment_status = isset($request->payment_received) ? 'Payment Received' : $order_details->payment_status;
                }

                $orders_arr = [
                    'order_status' => $status,
                    'payment_status' => $payment_status,
                    'order_update' => $now,
                    'date_delivered' => $delivery_date,
                    'date_cancelled' => $date_cancelled,
                    'last_modified_by' => Auth::user()->username,
                ];

                $track_order = [
                    'track_code' => $request->order_number,
                    'track_item' => 'Item Purchase',
                    'track_description' => $status == 'Cancelled' ? 'Cancelled' : $order_status_check->status_description,
                    'track_date' => Carbon::now()->toDateTimeString(),
                    'track_status' => $status,
                    'track_ip' => $request->ip(),
                    'transaction_member' => isset($request->member) ? 'Member' : 'Guest',
                    'track_active' => 1,
                    'track_payment_status' => $payment_status,
                    'last_modified_by' => Auth::user()->username
                ];

                $items = [];
                foreach($ordered_items as $row) {
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
                }

                $total_amount = $order_details->amount_paid;
                
                $track_url = $request->root().'/track_order/'.$request->order_number;
                $sms_short_url = $this->generateShortUrl($request->root(), $track_url);

                $sms = [];
                $message = null;

                if ($status == 'Order Confirmed'){
                    $checker = DB::table('track_order')->where('track_code', $request->order_number)->where('track_status', 'Out for Delivery')->count();

                    if($checker > 0){
                        DB::table('track_order')->where('track_code', $request->order_number)->whereIn('track_status', ['Out for Delivery', 'Order Confirmed'])->update(['track_active' => 0]);
                    }

                    $message = 'Hi '.$order_details->order_name . ' ' . $order_details->order_lastname.'!, your payment of '.$total_amount.' has been confirmed. Click '.$sms_short_url.' to track your order.';

                    if($order_details->order_payment_method == 'Bank Deposit'){
                        $leadtime_arr = [];
                        foreach($ordered_items as $item){
                            $category_id = DB::table('fumaco_items')->where('f_idcode', $item->item_code)->pluck('f_cat_id')->first();
                            if($order_details->order_shipping != 'Store Pickup') {
                                $shipping = DB::table('fumaco_shipping_service as shipping_service')
                                    ->join('fumaco_shipping_zone_rate as zone_rate', 'shipping_service.shipping_service_id', 'zone_rate.shipping_service_id')
                                    ->where('shipping_service.shipping_service_name', $order_details->order_shipping)
                                    ->where('zone_rate.province_name', $order_details->order_ship_prov)
                                    ->first();
                            } else {
                                $shipping = DB::table('fumaco_shipping_service')->where('shipping_service_name', $order_details->order_shipping)->first();
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

                        $total_amount = $order_details->order_subtotal + $order_details->order_shipping_amount;
                        $orders_arr['deposit_slip_token_used'] = 1;

                        $message = 'Hi '.$order_details->order_name . ' ' . $order_details->order_lastname.' your order '.$request->order_number.' with an amount of P '.number_format($total_amount, 2).' has been received, please allow '.$min_leadtime.' to '.$max_leadtime.' business days to process your order. Click ' . $sms_short_url . ' to track your order.';

                        $store_address = null;
                        if($order_details->order_shipping == 'Store Pickup') {
                            $store = DB::table('fumaco_store')->where('store_name', $order_details->store_location)->first();
                            $store_address = ($store) ? $store->address : null;
                        }

                        $order = [
                            'order_details' => $order_details,
                            'items' => $items,
                            'store_address' => $store_address,
                            'payment' => $total_amount
                        ];
                        $confirmed_bank_deposit_email = $order_details->order_email;

                        $customer_name = $order_details->order_name . ' ' . $order_details->order_lastname;
                        
                        try {
                            Mail::send('emails.order_confirmed_bank_deposit', $order, function($message) use($confirmed_bank_deposit_email){
                                $message->to(trim($confirmed_bank_deposit_email));
                                $message->subject('Order Confirmed - FUMACO');
                            });
                        } catch (\Swift_TransportException  $e) {
                            DB::rollback();
                            
                            return redirect()->back()->with('error', 'An error occured. Please try again.');
                        }

                        if(Mail::failures()){
                            DB::rollback();
                            return redirect()->back()->with('error', 'An error occured. Please try again.');
                        }

                        DB::table('fumaco_order')->where('order_number', $request->order_number)->update(['amount_paid' => $total_amount]);
                    }

                }

                if ($status == 'Out for Delivery') {
                    
                    try {
                        Mail::send('emails.out_for_delivery', ['order_details' => $order_details, 'status' => $status, 'items' => $items], function($message) use($order_details, $status){
                            $message->to(trim($order_details->order_email));
                            $message->subject($status . ' - FUMACO');
                        });
                    } catch (\Swift_TransportException  $e) {
                        DB::rollback();
                        
                        return redirect()->back()->with('error', 'An error occured. Please try again.');
                    }

                    if(Mail::failures()){
                        DB::rollback();
                        return redirect()->back()->with('error', 'An error occured. Please try again.');
                    }

                    $message = 'Hi '.$order_details->order_name . ' ' . $order_details->order_lastname.'!, your order '.$request->order_number.' with an amount of P '.number_format($total_amount, 2).' is now shipped out. Click '.$sms_short_url.' to track your order.';
                }

                if ($status == 'Order Delivered') {
                    $customer_name = $order_details->order_name . ' ' . $order_details->order_lastname;
                    
                    try {
                        Mail::send('emails.delivered', ['id' => $order_details->order_number, 'customer_name' => $customer_name], function($message) use($order_details, $status){
                            $message->to(trim($order_details->order_email));
                            $message->subject('Order Delivered - FUMACO');
                        });
                    } catch (\Swift_TransportException  $e) {
                        DB::rollback();
                        
                        return redirect()->back()->with('error', 'An error occured. Please try again.');
                    }

                    if(Mail::failures()){
                        DB::rollback();
                        return redirect()->back()->with('error', 'An error occured. Please try again.');
                    }

                    $message = 'Hi '.$order_details->order_name . ' ' . $order_details->order_lastname.'!, your order '.$request->order_number.' with an amount of P '.number_format($total_amount, 2).' has been delivered. Click '.$sms_short_url.' to track your order.';
                }

                if($status == 'Ready for Pickup'){
                    $message = 'Hi '.$order_details->order_name . ' ' . $order_details->order_lastname.'!, your order '.$request->order_number.' with an amount of P '.number_format($total_amount, 2).' is now ready for pickup. Click '.$sms_short_url.' to track your order.';
                }

                $phone = null;
                if($order_details->order_bill_contact || $order_details->order_contact){
                    $phone = $order_details->order_bill_contact ? $order_details->order_bill_contact : $order_details->order_contact;
                    $phone = preg_replace("/[^0-9]/", "", $phone);
                    if($phone[0] == 0){
                        $phone = '63'.substr($phone, 1);
                    }else if(substr($phone, 0, 2) != '63' || $phone[0] == '9'){
                        $phone = '63'.$phone;
                    }
                }

                if(in_array($status, ['Order Confirmed', 'Out for Delivery', 'Order Delivered', 'Ready for Pickup'])){
                    $sms_api = DB::table('api_setup')->where('type', 'sms_gateway_api')->first();
                    if ($sms_api and $sms_short_url) {
                        $sms = Http::asForm()->withHeaders([
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/x-www-form-urlencoded',
                        ])->post($sms_api->base_url, [
                            'api_key' => $sms_api->api_key,
                            'api_secret' => $sms_api->api_secret_key,
                            'from' => 'FUMACO',
                            'to' => $phone,
                            'text' => $message
                        ]);

                        $sms_response = json_decode($sms, true);

                        if(isset($sms_response['error'])){
                            DB::rollback();
                            $error = $sms_response['error']['code'] == 409 ? 'No mobile number found.' : 'Mobile number is invalid.';
                            return redirect()->back()->with('error', 'An error occured. '.$error);
                        }
                    }
                }

                DB::table('fumaco_order')->where('order_number', $request->order_number)->update($orders_arr);
                DB::table('track_order')->insert($track_order);

                DB::commit();
            }

            return redirect()->back()->with('success', 'Order <b>'.$request->order_number.'</b> status has been updated.');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

    public function resendDepositSlip(Request $request){
        DB::beginTransaction();
		try{
            $new_token = hash('sha256', Carbon::now()->toDateTimeString());
            $now = Carbon::now()->toDateTimeString();

            $order_details = DB::table('fumaco_order')->where('order_number', $request->order_number)->first();

            if(!$order_details){
                return redirect()->back()->with('error', 'Order Number not found!');
            }

            $order_items = DB::table('fumaco_order_items')->where('order_number', $request->order_number)->get();
            $item_codes = collect($order_items)->pluck('item_code');

            $images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $item_codes)->get();
            $image = collect($images)->groupBy('idcode');

            $subtotal = collect($order_items)->sum('item_total_price');

            DB::table('fumaco_order')->where('order_number', $request->order_number)->update([
                'deposit_slip_token' => $new_token,
                'deposit_slip_token_date_created' => $now
            ]);

            $items = [];
            foreach($order_items as $row) {
                $items[] = [
                    'item_code' => $row->item_code,
                    'item_name' => $row->item_name,
                    'price' => $row->item_price,
                    'discount' => $row->item_discount,
                    'qty' => $row->item_qty,
                    'amount' => $row->item_total_price,
                    'image' => isset($image[$row->item_code]) ? $image[$row->item_code][0]->imgprimayx : null
                ];
            }

            $store_address = null;
            if($order_details->order_shipping == 'Store Pickup') {
                $store = DB::table('fumaco_store')->where('store_name', $order_details->store_location)->first();
                $store_address = ($store) ? $store->address : null;
            }

            $bank_accounts = DB::table('fumaco_bank_account')->where('is_active', 1)->get();
				
            $order = [
                'order_details' => $order_details,
                'items' => $items,
                'store_address' => $store_address,
                'bank_accounts' => $bank_accounts,
                'new_token' => $new_token
            ];

            $sms_api = DB::table('api_setup')->where('type', 'sms_gateway_api')->first();
            $customer_name = $order_details->order_name.' '.$order_details->order_lastname;
            // $phone = $request->billing_number[0] == '0' ? '63'.substr($request->billing_number, 1) : $request->billing_number;
            $phone = null;
            if($order_details->order_contact){
                $phone = preg_replace("/[^0-9]/", "", $order_details->order_contact);
                if($phone[0] == 0){
                    $phone = '63'.substr($phone, 1);
                }else if(substr($phone, 0, 2) != '63' || $phone[0] == '9'){
                    $phone = '63'.$phone;
                }
            }

            $email = $order_details->order_email;

            $deposit_slip = $request->root().'/upload_deposit_slip/'.$new_token;
            $deposit_slip_url = $this->generateShortUrl($request->root(), $deposit_slip);

            $sms_message = 'Hi '.$customer_name.'!, to process your order please settle your payment thru bank deposit. Click '.$deposit_slip_url.' to upload your bank deposit slip.';

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
                
                $sms_response = json_decode($sms, true);

                if(isset($sms_response['error'])){
                    DB::rollback();
                    $error = $sms_response['error']['code'] == 409 ? 'No mobile number not found.' : 'Mobile number is invalid.';
                    return redirect()->back()->with('error', 'An error occured. '.$error);
                }
            }
            
            try {
                Mail::send('emails.order_success_bank_deposit', $order, function($message) use ($email) {
                    $message->to($email);
                    $message->subject('Order Placed - Bank Deposit - FUMACO');
                });
            } catch (\Swift_TransportException  $e) {
                DB::rollback();

                return redirect()->back()->with('error', 'An error occured. Please try again.');
            }

            if(Mail::failures()){
                DB::rollback();
                return redirect()->back()->with('error', 'An error occured. Please try again.');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Deposit Slip Upload Link Sent!');
        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function checkPaymentStatus(Request $request) {
        $payment_id = $request->payment_id;
        $output = [];
        $details = [];
        if ($request->isMethod('post')) {
            $api = DB::table('api_setup')->where('type', 'payment_api')->first();

            $details = DB::table('fumaco_order')->where('payment_id', $payment_id)->first();

            if(!$details) {
                return back()->with('error', 'Payment ID <b>' . $payment_id . '</b> not found.');
            }

            $amount_paid = number_format($details->amount_paid, 2, ".", "");

            $string = $api->password . $api->service_id . $payment_id . $amount_paid . 'PHP';
            $hash = hash('sha256', $string);

            switch ($details->order_payment_method) {
                case 'Credit Card':
                    $payment_method = 'CC';
                    break;
                case 'Credit Card (MOTO)':
                    $payment_method = 'MO';
                    break;
                case 'Direct Debit':
                    $payment_method = 'DD';
                    break;
                case 'e-Wallet':
                    $payment_method = 'WA';
                    break;
                default:
                    $payment_method = 'ANY';
                    break;
            }

            $data = [
                'TransactionType' => 'QUERY',
                'PymtMethod' => $payment_method,
                'ServiceID' => $api->service_id,
                'PaymentID'=> $payment_id,
                'Amount' => $amount_paid,
                'CurrencyCode' => 'PHP',
                'HashValue' => $hash
            ];

            $response = Http::asForm()->post($api->base_url, $data);

            parse_str($response, $output);
        }

        return view('backend.orders.track_payment_status', compact('output', 'payment_id', 'details'));
    }

    public function statusList(){
        $status_list = DB::table('order_status')->paginate(10);
        return view('backend.orders.status_list', compact('status_list'));
    }

    public function paymentStatusList(){
        $status_list = DB::table('fumaco_payment_status')->paginate(10);
        return view('backend.orders.payment_status', compact('status_list'));
    }

    public function paymentStatusAddForm(){
        return view('backend.orders.payment_status_add');
    }

    public function paymentStatusAdd(Request $request){
        DB::beginTransaction();
		try{
            $rules = array(
				'status_name' => 'required|unique:fumaco_payment_status,status',
			);

			$validation = Validator::make($request->all(), $rules);

            if ($validation->fails()){
				return redirect()->back()->with('error', "Payment Status Name must be unique.");
			}

            $status_sequence = DB::table('fumaco_payment_status')->orderBy('id', 'desc')->pluck('status_sequence')->first();

            $insert = [
                'status' => $request->status_name,
                'status_description' => $request->status_description,
                'status_sequence' => $status_sequence ? $status_sequence + 1 : 1,
                'updates_status' => isset($request->updates_status) ? 1 : 0,
                'created_by' => Auth::user()->username
            ];

            DB::table('fumaco_payment_status')->insert($insert);
            DB::commit();
            return redirect('/admin/payment/status_list')->with('success', 'Payment Status Added!');
        }catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

    public function paymentStatusEditForm($id){
        $status = DB::table('fumaco_payment_status')->where('id', $id)->first();
        return view('backend.orders.payment_status_edit', compact('status'));
    }

    public function paymentStatusEdit($id, Request $request){
        DB::beginTransaction();
		try{
            $checker = DB::table('fumaco_payment_status')->where('id', '!=', $id)->where('status', $request->status_name)->count();

            if($checker > 0){
                return redirect()->back()->with('error', "Payment Status Name must be unique.");
            }

            $update = [
                'status' => $request->status_name,
                'status_description' => $request->status_description,
                'updates_status' => isset($request->updates_status) ? 1 : 0,
                'last_modified_by' => Auth::user()->username
            ];

            DB::table('fumaco_payment_status')->where('id', $id)->update($update);
            DB::commit();
            return redirect('/admin/payment/status_list')->with('success', 'Payment Status Updated!');
        }catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

    public function paymentStatusDelete($id){
        DB::beginTransaction();
		try{
            DB::table('fumaco_payment_status')->where('id', $id)->delete();
            DB::commit();
            return redirect('/admin/payment/status_list')->with('success', 'Payment Status Deleted!');
        }catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

    public function addStatusForm(){
        return view('backend.orders.add_status');
    }

    public function addStatus(Request $request){
        DB::beginTransaction();
		try{
            $rules = array(
				'status_name' => 'required|unique:order_status,status',
			);

			$validation = Validator::make($request->all(), $rules);

            if ($validation->fails()){
				return redirect()->back()->with('error', "Order Status Name must be unique.");
			}

            $insert = [
                'status' => $request->status_name,
                'status_description' => $request->status_description,
                'update_stocks' => isset($request->update_stocks) ? 1 : 0,
                'created_by' => Auth::user()->username
            ];

            DB::table('order_status')->insert($insert);

            DB::commit();
            return redirect('/admin/order/status_list')->with('success', 'Order Status Added!');
        }catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

    public function editStatusForm($id){
        $status = DB::table('order_status')->where('order_status_id', $id)->first();
        return view('backend.orders.edit_status', compact('status'));
    }

    public function editStatus($id, Request $request){
        DB::beginTransaction();
		try{
            $checker = DB::table('order_status')->where('order_status_id', '!=', $id)->where('status', $request->status_name)->first();
            
            if($checker){
                return redirect()->back()->with('error', "Order Status Name must be unique.");
            }

            $update = [
                'status' => $request->status_name,
                'status_description' => $request->status_description,
                'update_stocks' => isset($request->update_stocks) ? 1 : 0,
                'last_modified_by' => Auth::user()->username
            ];

            DB::table('order_status')->where('order_status_id', $id)->update($update);

            DB::commit();
            return redirect('/admin/order/status_list')->with('success', 'Order Status Added!');
        }catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

    public function deleteStatus($id){
        DB::beginTransaction();
		try{
            DB::table('order_status')->where('order_status_id', $id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Order Status Deleted!');
        }catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

    public function sequenceList(){
        $shipping_method = DB::table('order_status_process')->groupBy('shipping_method')->select('shipping_method')->paginate(10);

        $sequence_arr = [];
        foreach($shipping_method as $shipping){
            $status_process = DB::table('order_status as stat')->join('order_status_process as prc', 'stat.order_status_id', 'prc.order_status_id')->where('shipping_method', $shipping->shipping_method)->select('stat.status')->orderBy('prc.order_sequence', 'asc')->get();
            $sequence_arr[] = [
                'shipping_method' => $shipping->shipping_method,
                'status_sequence' => $status_process
            ];
        }

        // return $sequence_arr;

        return view('backend.orders.status_process_list', compact('shipping_method', 'sequence_arr'));
    }

    public function addSequenceForm(){
        $order_status = DB::table('order_status')->get();

        return view('backend.orders.add_status_process', compact('order_status'));
    }

    public function addSequence(Request $request){
        DB::beginTransaction();
		try{
            $rules = array(
				'shipping_name' => 'required|unique:order_status_process,shipping_method',
			);

			$validation = Validator::make($request->all(), $rules);

            if ($validation->fails()){
				return redirect()->back()->with('error', "Order Status Sequence Name must be unique.");
			}

            if(!$request->status){
                return redirect()->back()->with('error', 'Please select a status');
            }

            foreach($request->status as $order_sequence_id => $status){
                $insert = [
                    'order_sequence' => $order_sequence_id + 1,
                    'shipping_method' => $request->shipping_name,
                    'order_status_id' => $status
                ];
                DB::table('order_status_process')->insert($insert);
            }
            DB::commit();
            return redirect('/admin/order/sequence_list')->with('success', 'Order Status Sequence Added!');
        }catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

    public function deleteSequence($shipping){
        DB::beginTransaction();
		try{
            DB::table('order_status_process')->where('shipping_method', $shipping)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Order Status Deleted!');
        }catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

    public function printOrder($order_id){
        $orders = DB::table('fumaco_order')->where('order_number', $order_id)->first();

        $items_arr = [];
        $items = DB::table('fumaco_order_items')->where('order_number', $orders->order_number)->get();
        foreach($items as $i){
            $items_arr[] = [
                'order_number' => $i->order_number,
                'item_code' => $i->item_code,
                'item_name' => $i->item_name,
                'item_qty' => $i->item_qty,
                'item_price' => $i->item_price,
                'item_discount' => $i->item_discount,
                'item_total' => $i->item_total_price,
            ];
        }

        $store_address = null;
        if($orders->order_shipping == 'Store Pickup') {
            $store = DB::table('fumaco_store')->where('store_name', $orders->store_location)->first();
            $store_address = ($store) ? $store->address : null;
        }

        $order_status = DB::table('order_status as s')
            ->join('order_status_process as p', 's.order_status_id', 'p.order_status_id')
            ->where('shipping_method', $orders->order_shipping)
            ->orderBy('order_sequence', 'asc')
            ->get();

        $orders_arr = [
            'order_no' => $orders->order_number,
            'first_name' => $orders->order_name,
            'last_name' => $orders->order_lastname,
            'bill_contact_person' => $orders->order_contactperson,
            'ship_contact_person' => $orders->order_ship_contactperson,
            'email' => $orders->order_email,
            'contact' => $orders->order_contact == 0 ? '' : $orders->order_contact ,
            'date' => Carbon::parse($orders->order_update)->format('M d, Y - h:m A'),
            'ordered_items' => $items_arr,
            'order_tracker_code' => $orders->tracker_code,
            'payment_method' => $orders->order_payment_method,
            'cust_id' => $orders->order_account,
            'bill_address1' => $orders->order_bill_address1,
            'bill_address2' => $orders->order_bill_address2,
            'bill_province' => $orders->order_bill_prov,
            'bill_city' => $orders->order_bill_city,
            'bill_brgy' => $orders->order_bill_brgy,
            'bill_country' => $orders->order_bill_country,
            'bill_postal' => $orders->order_bill_postal,
            'bill_email' => $orders->order_bill_email,
            'bill_contact' => $orders->order_bill_contact,
            'ship_address1' => $orders->order_ship_address1,
            'ship_address2' => $orders->order_ship_address2,
            'ship_province' => $orders->order_ship_prov,
            'ship_city' => $orders->order_ship_city,
            'ship_brgy' => $orders->order_ship_brgy,
            'ship_country' => $orders->order_ship_country,
            'ship_postal' => $orders->order_ship_postal,
            'shipping_name' => $orders->order_shipping,
            'shipping_amount' => $orders->order_shipping_amount,
            'grand_total' => ($orders->order_shipping_amount + ($orders->order_subtotal - $orders->discount_amount)),
            'status' => $orders->order_status,
            'estimated_delivery_date' => $orders->estimated_delivery_date,
            'payment_id' => $orders->payment_id,
            'payment_method' => $orders->order_payment_method,
            'subtotal' => $orders->order_subtotal,
            'order_type' => $orders->order_type,
            'user_email' => $orders->user_email,
            'billing_business_name' => $orders->billing_business_name,
            'shipping_business_name' => $orders->shipping_business_name,
            'pickup_date' => Carbon::parse($orders->pickup_date)->format('M d, Y'),
            'store_address' => $store_address,
            'store' => $orders->store_location,
            'order_status' => $order_status,
            'voucher_code' => $orders->voucher_code,
            'discount_amount' => $orders->discount_amount
        ];

        return view('backend.orders.print_order', compact('orders_arr'));
    }

    public function viewItemOnCart(Request $request) {
        return view('backend.item_on_cart.item_on_cart');
    }

    public function viewItemOnCartByLocation(Request $request) {
        if ($request->ajax()) {
            $list_per_location = DB::table('fumaco_cart')
                ->where('last_modified_at', '>=', Carbon::now()->subDays(1))
                ->get();

            $per_location = collect($list_per_location)->groupBy('city');
            $cart_per_loc = [];
            foreach ($per_location as $loc => $rows) {
                $item_arr = [];
                $items = [];
                foreach ($rows as $item) {
                    if (!in_array($item->item_code, $item_arr)) {
                        $items[] = [
                            'item_code' => $item->item_code,
                            'item_description' => $item->item_description,
                        ];
                    }

                    array_push($item_arr, $item->item_code);
                }

                $cart_per_loc[] = [
                    'location' => $loc,
                    'items' => $items,
                    'item_codes' => array_count_values($item_arr)
                ];
            }

            // Get current page form url e.x. &page=1
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            // Create a new Laravel collection from the array data
            $itemCollection = collect($cart_per_loc);
            // Define how many items we want to be visible in each page
            $perPage = 10;
            // Slice the collection to get the items to display in current page
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            // Create our paginator and pass it to the view
            $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
            // set url path for generted links
            $paginatedItems->setPath($request->url());
            $cart_per_loc = $paginatedItems;

            return view('backend.item_on_cart.item_on_cart_by_location', compact('cart_per_loc'));
        }
    }

    public function viewItemOnCartByItem(Request $request) {
        if ($request->ajax()) {
            $list_per_item = DB::table('fumaco_cart')
                ->where('last_modified_at', '>=', Carbon::now()->subDays(1))
                ->select('item_code', 'item_description', DB::raw('count(item_code) as count'))
                ->orderBy(DB::raw('count(item_code)'), 'desc')
                ->groupBy('item_code', 'item_description')->paginate(10);

            return view('backend.item_on_cart.item_on_cart_by_item', compact('list_per_item'));
        }
    }

    public function viewAbandonedItemOnCart(Request $request) {
        if ($request->ajax()) {
            $abandoned_cart = DB::table('fumaco_cart')->where('user_type', 'guest')
                ->when($request->q, function ($query) use ($request) {
                    return $query->where('item_description', 'LIKE', "%".$request->q."%")->orWhere('ip', 'LIKE', "%".$request->q."%");
                })
                ->where('last_modified_at', '<=', Carbon::now()->subDays(1))
                ->orderBy('last_modified_at', 'desc')->paginate(10);

            return view('backend.item_on_cart.abandoned_cart_items', compact('abandoned_cart'));
        }
    }

    public function cancelOrder($id, Request $request) {
        DB::beginTransaction();
        try {
            $output = [];
            if ($id) {
                $api = DB::table('api_setup')->where('type', 'payment_api')->first();

                $details = DB::table('fumaco_order')->where('id', $id)->first();

                if(!$details) {
                    return back()->with('error', 'Order ID <b>' . $id . '</b> not found.');
                }

                if(!$request->is_admin) {
                    if (Auth::user()->username != $details->order_bill_email) {
                        return back()->with('error', 'Invalid transaction.');
                    }
                }

                $dt = Carbon::now();
                $dt2 = Carbon::parse($details->order_date);
                $is_same_day = ($dt->isSameDay($dt2));

                if (!$is_same_day) {
                    return back()->with('error', 'Cannot cancel order <b>' . $details->order_number . '</b>. Order can only be cancelled for the transaction within the same day as the order date.');
                }

                $transaction_success = true;
                if($details->order_payment_method != 'Bank Deposit') {
                    $amount_paid = number_format($details->amount_paid, 2, ".", "");

                    $string = $api->password . $api->service_id . $details->payment_id . $amount_paid . 'PHP';
                    $hash = hash('sha256', $string);
    
                    switch ($details->order_payment_method) {
                        case 'Credit Card':
                            $payment_method = 'CC';
                            break;
                        case 'Credit Card (MOTO)':
                            $payment_method = 'MO';
                            break;
                        case 'Direct Debit':
                            $payment_method = 'DD';
                            break;
                        case 'e-Wallet':
                            $payment_method = 'WA';
                            break;
                        default:
                            $payment_method = 'ANY';
                            break;
                    }
    
                    $data = [
                        'TransactionType' => 'RSALE',
                        'PymtMethod' => $payment_method,
                        'ServiceID' => $api->service_id,
                        'PaymentID'=> $details->payment_id,
                        'Amount' => $amount_paid,
                        'CurrencyCode' => 'PHP',
                        'HashValue' => $hash
                    ];
    
                    $response = Http::asForm()->post($api->base_url, $data);
    
                    parse_str($response, $output);

                    if ($output['TxnStatus'] != 0) {
                        if($request->is_admin) {
                            if($payment_method == 'WA') {
                                return redirect()->back()->with('error', 'Unable to refund for e-Wallet.');
                            }
                            
                            return redirect()->back()->with('error', 'Failed to cancel order <b>'.$details->order_number.'</b><br>Error Message: <b>' . $output['TxnMessage'] . '</b>');
                        } else {
                            return redirect()->back()->with('error', 'Failed to cancel order <b>'.$details->order_number.'</b>');
                        }
                    }
                }

                if ($transaction_success) {
                    $date_cancelled = Carbon::now()->toDateTimeString();
                    $order_items = DB::table('fumaco_order_items as foi')->join('fumaco_items as fi', 'foi.item_code', 'fi.f_idcode')
                        ->where('foi.order_number', $details->order_number)->select('foi.item_qty', 'foi.item_code', 'fi.f_reserved_qty', 'foi.item_name', 'foi.item_price', 'foi.item_discount', 'foi.item_total_price')->get();

                    $items = [];
                    foreach($order_items as $row){
                        $r_qty = $row->f_reserved_qty - $row->item_qty;
                        DB::table('fumaco_items')->where('f_idcode', $row->item_code)
                            ->update(['f_reserved_qty' => $r_qty, 'last_modified_by' => Auth::user()->username]);

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
                    }

                    DB::table('fumaco_order')->where('id', $id)
                        ->update(['order_status' => 'Cancelled', 'last_modified_by' => Auth::user()->username, 'date_cancelled' => $date_cancelled]);

                    DB::commit();

                    $store_address = null;
                    if($details->order_shipping == 'Store Pickup') {
                        $store = DB::table('fumaco_store')->where('store_name', $details->store_location)->first();
                        $store_address = ($store) ? $store->address : null;
                    }

                    $email_recipient = DB::table('email_config')->first();
                    $email_recipient = ($email_recipient) ? explode(",", $email_recipient->email_recipients) : [];
                    $order = [
                        'order_details' => $details,
                        'items' => $items,
                        'store_address' => $store_address
                    ];

                    if ($details->order_bill_email) {
                        $customer_email = $details->order_bill_email;
                        try {
                            Mail::send('emails.cancelled_order_customer', $order, function($message) use ($customer_email) {
                                $message->to($customer_email);
                                $message->subject('Cancelled Order - FUMACO');
                            });
                        } catch (\Swift_TransportException  $e) {
                            
                        }
                    }

                    if (count(array_filter($email_recipient)) > 0) {
                        try {
                            Mail::send('emails.cancelled_order_admin', $order, function($message) use ($email_recipient) {
                                $message->to($email_recipient);
                                $message->subject('Cancelled Order - FUMACO');
                            });
                        } catch (\Swift_TransportException  $e) {
                           
                        }
                    }

                    return redirect()->back()->with('success', 'Order <b>'.$details->order_number.'</b> has been cancelled.');
                }
            }
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function uploadDepositSlip($id, Request $request) {
        $order_details = DB::table('fumaco_order')->where('id', $id)->first();
        if(!$order_details) {
            return redirect()->back()->with('error', 'Order id ' . $id . ' not found.');
        }

        $customer_name = $order_details->order_name . ' ' . $order_details->order_lastname;
        $order_number = $order_details->order_number;
        $date_uploaded = Carbon::now()->format('d-m-y');

        $image_filename = $customer_name . '-' . $order_number . '-' . $date_uploaded;

        if($request->hasFile('deposit_slip_image')){
            $image = $request->file('deposit_slip_image');

            $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
            $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";

            $destinationPath = storage_path('/app/public/deposit_slips/');

           $extension = strtolower(pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION));

            $image_name = $image_filename.".".$extension;
            if(!in_array($extension, $allowed_extensions)){
                return redirect()->back()->with('error', $extension_error);
            }

            $image->move($destinationPath, $image_name);

            DB::table('fumaco_order')->where('id', $id)->update([
                'deposit_slip_image' => $image_name,
                'deposit_slip_date_uploaded' => Carbon::now()->toDateTimeString(),
                'payment_status' => 'Payment For Confirmation'
            ]);

            DB::table('track_order')->insert([
                'track_code' => $order_number,
                'track_date' => Carbon::now()->toDateTimeString(),
                'track_item' => 'Item Purchase',
                'track_description' => 'Your order is on processing',
                'track_status' => 'Order Placed',
                'track_payment_status' => 'Payment For Confirmation',
                'track_ip' => $order_details->order_ip,
                'track_active' => 1,
                'transaction_member' => $order_details->order_type,
                'last_modified_by' => Auth::user()->username
            ]);
            
            // send notification to accounting
            $order = ['order_details' => $order_details];

            $email_recipient = DB::table('fumaco_admin_user')->where('user_type', 'Accounting Admin')->pluck('username');
            $recipients = collect($email_recipient)->toArray();
            if (count(array_filter($recipients)) > 0) {
                try {
                    Mail::send('emails.deposit_slip_notif', $order, function($message) use ($recipients) {
                        $message->to($recipients);
                        $message->subject('Awaiting Confirmation - FUMACO');
                    });
                } catch (\Swift_TransportException  $e) {
                    
                }
            }
        }

        return redirect()->back()->with('success', 'Deposit Slip for order <b>'.$order_details->order_number.'</b> has been uploaded.');
    }

    public function confirmBuffer(Request $request){
        session()->flash('for_confirmation', $request->order_number);
        return redirect('/admin/order/order_lists/');
    }
}
