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
            ->paginate(10);

        $user_arr = [];
        $ship_arr = [];
        $bill_arr = [];
        $ship_address1 = "";
        $ship_address2 = "";
        $ship_province = "";
        $ship_city = "";
        $ship_brgy = "";
        $ship_postal = "";
        $ship_country = "";
        $ship_type = "";
        $bill_address1 = "";
        $bill_address2 = "";
        $bill_province = "";
        $bill_city = "";
        $bill_brgy = "";
        $bill_postal = "";
        $bill_country = "";
        $bill_type = "";
        $has_ship = 0;
        $has_bill = 0;

        foreach($user_info as $user){
            $bill_address = DB::table('fumaco_user_add')->where('user_idx', $user->id)->where('xdefault', 1)->where('address_class', 'Billing')->first();
            $ship_address = DB::table('fumaco_user_add')->where('user_idx', $user->id)->where('xdefault', 1)->where('address_class', 'Delivery')->first();

            // if customer has address
            if($ship_address){
                $has_ship = 1;
                $ship_address1 = $ship_address->xadd1;
                $ship_address2 = $ship_address->xadd2;
                $ship_province = $ship_address->xprov;
                $ship_city = $ship_address->xcity;
                $ship_brgy = $ship_address->xbrgy;
                $ship_postal = $ship_address->xpostal;
                $ship_country = $ship_address->xcountry;
                $ship_type = $ship_address->add_type;
            }

            if($bill_address){
                $has_bill = 1;
                $bill_address1 = $bill_address->xadd1;
                $bill_address2 = $bill_address->xadd2;
                $bill_province = $bill_address->xprov;
                $bill_city = $bill_address->xcity;
                $bill_brgy = $bill_address->xbrgy;
                $bill_postal = $bill_address->xpostal;
                $bill_country = $bill_address->xcountry;
                $bill_type = $bill_address->add_type;
            }

            $user_arr[] = [
                'id' => $user->id,
                'first_name' => $user->f_name,
                'last_name' => $user->f_lname,
                'email' => $user->username,
                'contact' => $user->f_mobilenumber,
                'shipping_address' => $has_ship,
                'ship_address1' => $ship_address1,
                'ship_address2' => $ship_address2,
                'ship_province' => $ship_province,
                'ship_city' => $ship_city,
                'ship_brgy' => $ship_brgy,
                'ship_postal' => $ship_postal,
                'ship_country' => $ship_country,
                'ship_type' => $ship_type,
                'billing_address' => $has_bill,
                'bill_address1' => $bill_address1,
                'bill_address2' => $bill_address2,
                'bill_province' => $bill_province,
                'bill_city' => $bill_city,
                'bill_brgy' => $bill_brgy,
                'bill_postal' => $bill_postal,
                'bill_country' => $bill_country,
                'bill_type' => $bill_type,
                'created_at' => $user->created_at,
                'no_of_visits' => number_format($user->no_of_visits),
                'last_login' => $user->last_login
            ];
        }
        // dd($user_arr);

        return view('backend.customer.customer', compact('user_info', 'user_arr'));
    }
}
