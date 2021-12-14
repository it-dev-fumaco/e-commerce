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

        $cart_items = DB::table('fumaco_cart')->where('user_email', $customer->username)->get();

        return view('backend.customer.customer_profile', compact('customer', 'shipping_address', 'billing_address', 'cart_items'));
    }
}
