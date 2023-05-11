<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;
use Newsletter;
use Exception;
class CustomerController extends Controller
{
    public function viewCustomers(Request $request){
        $users = DB::table('fumaco_users')->where('is_email_verified', 1)
            ->when($request->q, function($c) use ($request) {
                $c->where('f_name', 'LIKE', '%'.$request->q.'%')->orWhere('f_lname', 'LIKE', '%'.$request->q.'%')->orWhere('username', 'LIKE', '%'.$request->q.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $customer_groups = DB::table('fumaco_customer_group')->pluck('customer_group_name', 'id');

        return view('backend.customer.customer', compact('users', 'customer_groups'));
    }

    public function viewCustomerProfile($id){
        $customer = DB::table('fumaco_users')->where('id', $id)->first();

        $total_sales = DB::table('fumaco_order')->where('order_status', '!=', 'Cancelled')->where('order_email', $customer->username)->sum('amount_paid');

        $pricelist = DB::table('fumaco_price_list')->get();

        $customer_groups = DB::table('fumaco_customer_group')->pluck('customer_group_name', 'id');

        $cart_items = DB::table('fumaco_cart')->where('user_email', $customer->username)->paginate(10);

        return view('backend.customer.customer_profile', compact('customer', 'cart_items', 'total_sales', 'pricelist', 'customer_groups'));
    }

    public function changeCustomerGroup($id, Request $request){
        DB::beginTransaction();
		try{
            $user_info = DB::table('fumaco_users')->where('id', $id)->first();
            $price_detail = DB::table('fumaco_price_list')->where('id', $request->pricelist)->first();
            $customer_groups = DB::table('fumaco_customer_group')->pluck('customer_group_name', 'id');
            $customer_group = (array_key_exists($request->customer_group, $customer_groups->toArray())) ? $customer_groups[$request->customer_group] : null;

            DB::table('fumaco_users')->where('id', $id)->update([
                'customer_group' => $request->customer_group,
                'business_name' => ($customer_group == 'Business') ? $request->business_name : null,
                'pricelist_id' => ($customer_group == 'Business') ? ($request->pricelist ? $request->pricelist : null) : null,
                'pricelist' => ($customer_group == 'Business') ? (($price_detail) ? $price_detail->price_list_name : null) : null
            ]);

            $former_customer_group = isset($customer_groups[$user_info->customer_group]) ? $customer_groups[$user_info->customer_group] : null;
            $updated_customer_group = isset($customer_groups[$request->customer_group]) ? $customer_groups[$request->customer_group] : null;

            if(Newsletter::hasMember($user_info->username) == 1){
                Newsletter::removeTags([$former_customer_group], $user_info->username);
                Newsletter::addTags([$updated_customer_group], $user_info->username);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Customer Profile has been updated.');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

    public function getCustomerAddress($address_type, $user_id, Request $request) {
        $address_list = DB::table('fumaco_user_add')
            ->where('user_idx', $user_id)->where('address_class', $address_type)->orderBy('xdefault', 'desc')->paginate(6);

        return view('backend.customer.address_list', compact('address_list', 'address_type'));
    }

    public function getCustomerOrders($user_id, Request $request) {
        if ($request->current) {
            $order_history = DB::table('fumaco_order')->where('order_account', $user_id)
                ->where('order_status', '!=', 'Delivered')
                ->where('order_status', '!=', 'Order Delivered')
                ->where('order_status', '!=', 'Order Completed')
                ->where('order_status', '!=', 'Cancelled')
                ->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $order_history = DB::table('fumaco_order')->where('order_account', $user_id)->orderBy('created_at', 'desc')->paginate(10);
        }

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
                'id' => $order->id,
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

        return view('backend.customer.orders', compact('order_history', 'orders_arr'));
    }

    public function viewOrderDetails($id) {
        $order = DB::table('fumaco_order')->where('id', $id)->first();

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

        $orders_arr = [
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
            'bill_email' => $order->order_bill_email,
            'bill_contact' => $order->order_bill_contact,
            'store_location' => $order->store_location,
            'store_address' => $store_address,
            'grand_total' => $order->order_shipping_amount + ($order->order_subtotal - $order->discount_amount),
            'ordered_items' => $items_arr,
            'email' => $order->order_email,
            'contact' => $order->order_contact == 0 ? '' : $order->order_contact ,
        ];

        return view('backend.customer.order_details', compact('orders_arr'));
    }
}
