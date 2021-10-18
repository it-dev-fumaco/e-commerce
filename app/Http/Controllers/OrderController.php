<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function orderList(Request $request){

        $search_id = ($request->search) ? $request->search : '';
        $order_status = ($request->order_status) ? $request->order_status : '';

        $orders = DB::table('fumaco_order')->where('order_number', 'LIKE', '%'.$search_id.'%')->where('order_status', 'LIKE', '%'.$order_status.'%')->orderBy('id', 'desc')->paginate(10);

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
                'date' => Carbon::parse($o->order_update)->format('Y-m-d h:i A'),
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
}
