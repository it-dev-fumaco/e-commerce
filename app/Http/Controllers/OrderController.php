<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use DB;
use Mail;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function orderList(Request $request){

        $search_id = ($request->search) ? $request->search : '';
        $order_status = ($request->order_status) ? $request->order_status : '';

        $orders = DB::table('fumaco_order')->where('order_number', 'LIKE', '%'.$search_id.'%')->where('order_status', 'LIKE', '%'.$order_status.'%')->where('order_status', '!=', 'Cancelled')->where('order_status', '!=', 'Delivered')->orderBy('id', 'desc')->paginate(10);

        $orders_arr = [];
        $items_arr = [];

        foreach($orders as $o){
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
                'subtotal' => $o->order_subtotal
            ];
        }
        return view('backend.orders.order_list', compact('orders_arr', 'orders'));
    }

    public function cancelledOrders(Request $request){
        $search_id = ($request->search) ? $request->search : '';
        $orders = DB::table('fumaco_order')->where('order_number', 'LIKE', '%'.$search_id.'%')->where('order_status', 'Cancelled')->orderBy('id', 'desc')->paginate(10);

        $orders_arr = [];
        $items_arr = [];

        foreach($orders as $o){
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
        $orders = DB::table('fumaco_order')->where('order_number', 'LIKE', '%'.$search_id.'%')->where('order_status', 'Delivered')->orderBy('id', 'desc')->paginate(10);

        $orders_arr = [];
        $items_arr = [];

        foreach($orders as $o){
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

            if($status) {
                $ordered_items = DB::table('fumaco_order_items')->where('order_number', $request->order_number)->get();
                if($status == 'Delivered'){
                    $delivery_date = Carbon::now()->toDateTimeString();
                    foreach($ordered_items as $orders){
                        $items = DB::table('fumaco_items')->select('f_reserved_qty')->where('f_idcode', $orders->item_code)->first();
                        $qty_left = $items->f_reserved_qty - $orders->item_qty;
    
                        DB::table('fumaco_items')->where('f_idcode', $orders->item_code)->update(['f_reserved_qty' => $qty_left]);
                    }
                }
    
                if($status == 'Cancelled'){
                    $date_cancelled = Carbon::now()->toDateTimeString();
                    foreach($ordered_items as $orders){
                        $items = DB::table('fumaco_items')->select('f_reserved_qty', 'f_qty')->where('f_idcode', $orders->item_code)->first();
                        $r_qty = $items->f_reserved_qty - $orders->item_qty;
                        $f_qty = $items->f_qty + $orders->item_qty;
    
                        DB::table('fumaco_items')->where('f_idcode', $orders->item_code)->update(['f_reserved_qty' => $r_qty, 'f_qty' => $f_qty]);
                    }
                }
    
                $orders_arr = [
                    'order_status' => $status,
                    'order_update' => $now,
                    'date_delivered' => $delivery_date,
                    'date_cancelled' => $date_cancelled
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
        
                if ($status == 'Out for Delivery') {
                    $order_details = DB::table('fumaco_order')->where('order_number', $request->order_number)->first();
                    Mail::send('emails.out_for_delivery', ['order_details' => $order_details, 'status' => $status, 'items' => $items], function($message) use($order_details, $status){
                        $message->to(trim($order_details->order_email));
                        $message->subject($status . ' - FUMACO');
                    });
                }

                if ($status == 'Delivered') {
                    $order_details = DB::table('fumaco_order')->where('order_number', $request->order_number)->first();
                    $customer_name = $order_details->order_name . ' ' . $order_details->order_lastname;
                    Mail::send('emails.delivered', ['id' => $order_details->order_number, 'customer_name' => $customer_name], function($message) use($order_details, $status){
                        $message->to(trim($order_details->order_email));
                        $message->subject('Order Delivered - FUMACO');
                    });
                }
                
                DB::table('fumaco_order')->where('order_number', $request->order_number)->update($orders_arr);
    
                DB::table('track_order')->where('track_code', $request->order_number)->update(['track_status' => $status, 'track_date_update' => $now]);
    
                DB::commit();
            }

            return redirect()->back()->with('success', 'Order <b>'.$request->order_number.'</b> status has been updated.');
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
            
            $response = Http::asForm()->post('https://pay.e-ghl.com/IPGSG/Payment.aspx', $data);

            parse_str($response, $output);
        }

        return view('backend.orders.track_payment_status', compact('output', 'payment_id', 'details'));
    }
}
