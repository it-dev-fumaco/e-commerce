<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

class CustomerController extends Controller
{
    public function viewCustomers(Request $request){
        $user_info = DB::table('fumaco_users')->where('is_email_verified', 1)
            ->when($request->q, function($c) use ($request) {
                $c->where('f_name', 'LIKE', '%'.$request->q.'%')->orWhere('f_lname', 'LIKE', '%'.$request->q.'%')->orWhere('username', 'LIKE', '%'.$request->q.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        foreach($user_info as $user){
            $user_arr[] = [
                'id' => $user->id,
                'first_name' => $user->f_name,
                'last_name' => $user->f_lname,
                'email' => $user->username,
                'contact' => $user->f_mobilenumber,
                'created_at' => $user->created_at,
                'no_of_visits' => number_format($user->no_of_visits),
                'last_login' => $user->last_login
            ];
        }

        return view('backend.customer.customer', compact('user_info', 'user_arr'));
    }

    public function viewCustomerProfile($id){
        $customer = DB::table('fumaco_users')->where('id', $id)->first();

        $address = DB::table('fumaco_user_add')->where('user_idx', $id)->orderBy('xdefault', 'desc')->get();

        $shipping_address = collect($address)->where('address_class', 'Delivery');
        $billing_address = collect($address)->where('address_class', 'Billing');

        $cart_items = DB::table('fumaco_cart')->where('user_email', $customer->username)->paginate(10);

        $order_history = DB::table('fumaco_order')->where('order_account', $id)->paginate(10);

        $orders_arr = [];
        foreach($order_history as $order){
            $ordered_items = DB::table('fumaco_order_items')->where('order_number', $order->order_number)->get();
            $items_arr = [];
            foreach($ordered_items as $orders){
                $items_arr[] = [
                    'order_number' => $orders->order_number,
                    'item_code' => $orders->item_code,
                    'item_name' => $orders->item_name,
                    'quantity' => $orders->item_qty,
                    'discount' => $orders->item_discount,
                    'price' => $orders->item_price,
                    'original_price' => $orders->item_original_price,
                    'total_price' => $orders->item_total_price
                ];
            }

            $ship_add2 = str_replace(' ', '',$order->order_ship_address2) ? $order->order_ship_address2.', ' : null;
            $order_shipping_address = $order->order_ship_address1.', '.$ship_add2.$order->order_ship_brgy.', '.$order->order_ship_city.', '.$order->order_ship_prov.', '.$order->order_ship_country.' '.$order->order_ship_postal;

            $bill_add2 = str_replace(' ', '',$order->order_bill_address2) ? $order->order_bill_address2.', ' : null;
            $order_billing_address = $order->order_bill_address1.', '.$bill_add2.$order->order_bill_brgy.', '.$order->order_bill_city.', '.$order->order_bill_prov.', '.$order->order_bill_country.' '.$order->order_bill_postal;

            $store_address = null;

            if($order->order_shipping == 'Store Pickup'){
                $store_address = DB::table('fumaco_store')->where('store_name', $order->store_location)->pluck('address')->first();
            }

            $orders_arr[] = [
                'order_number' => $order->order_number,
                'ordered_by' => $order->order_name.' '.$order->order_lastname,
                'order_email' => $order->order_email,
                'order_status' => $order->order_status,
                'subtotal' => $order->order_subtotal,
                'discount' => $order->discount_amount,
                'voucher_code' => $order->voucher_code,
                'shipping' => $order->order_shipping_amount,
                'amount_paid' => $order->amount_paid,
                'date_ordered' => $order->order_date,
                'estimated_delivery_date' => $order->estimated_delivery_date,
                'date_delivered' => $order->date_delivered,
                'date_cancelled' => $order->date_cancelled,
                'pickup_date' => $order->pickup_date,
                'payment_method' => $order->order_payment_method,
                'payment_id' => $order->payment_id,
                'shipping_method' => $order->order_shipping,
                'shipping_address_type' => $order->order_ship_type,
                'shipping_address' => $order_shipping_address,
                'shipping_business_name' => $order->shipping_business_name,
                'shipping_business_tin' => $order->shipping_tin,
                'billing_address_type' => $order->order_bill_type,
                'billing_address' => $order_billing_address,
                'billing_business_name' => $order->billing_business_name,
                'billing_business_tin' => $order->billing_tin,
                'store_location' => $order->store_location,
                'store_address' => $store_address,
                'grand_total' => $order->order_shipping_amount + ($order->order_subtotal - $order->discount_amount),
                'ordered_items' => $items_arr
            ];
        }

        // return $orders_arr;

        return view('backend.customer.customer_profile', compact('customer', 'shipping_address', 'billing_address', 'cart_items', 'order_history', 'orders_arr'));
    }
}
