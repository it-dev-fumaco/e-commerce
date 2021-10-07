<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class OrderController extends Controller
{
    public function order_list(){
        $orders = DB::table('fumaco_temp')->where('xstatus', 2)->get();

        $orders_arr = [];

        foreach($orders as $o){
            $items = DB::table('fumaco_order_items')->where('order_number', $o->xlogs)->first();
        //     $item_data2_fumaco = $data_1['xadd1'];
        //     $item_data3_fumaco = $data_1['xadd2'];

        //     $item_data4_fumaco = $data_1['xprov'];
        //     $item_data5_fumaco = $data_1['xcity'];
        //     $item_data6_fumaco = $data_1['xbrgy'];
        //     $item_data7_fumaco = $data_1['xpostal'];
        //     $item_data8_fumaco = $data_1['xcountry'];
        //     $item_data9_fumaco = $data_1['xaddresstype'];

        //     $item_data10_fumaco = $data_1['xemail'];
        //     $item_data11_fumaco = $data_1['xmobile'];
        //     $item_data12_fumaco = $data_1['xcontact'];

        //     $item_data13_fumaco = $data_1['xshippadd1'];
        //     $item_data14_fumaco = $data_1['xshippadd2'];
        //     $item_data15_fumaco = $data_1['xshiprov'];
        //     $item_data16_fumaco = $data_1['xshipcity'];
        //     $item_data17_fumaco = $data_1['xshipbrgy'];
        //     $item_data18_fumaco = $data_1['xshippostalcode'];
        //     $item_data19_fumaco = $data_1['xshipcountry'];
        //     $item_data20_fumaco = $data_1['xshiptype'];

        // <p><strong>Customer Information Address : </strong> '.$item_data2_fumaco.' '.$item_data3_fumaco.', '.$item_data4_fumaco.' '.$item_data5_fumaco.' '.$item_data6_fumaco.' '.$item_data8_fumaco.' '.$item_data7_fumaco.'</p>

        // <p><strong>Customer Shipping Address : </strong> '.$item_data13_fumaco.' '.$item_data14_fumaco.', '.$item_data15_fumaco.' '.$item_data16_fumaco.' '.$item_data17_fumaco.' '.$item_data19_fumaco.' '.$item_data18_fumaco.'</p>
            $orders_arr[] = [
                'order_no' => $o->xlogs,
                'first_name' => $o->xfname,
                'last_name' => $o->xlname,
                'email' => $o->xemail,
                'date' => $o->xdateupdate,
                'order_tracker_code' => $o->order_tracker_code,
                'cust_id' => $o->xtempcode,
                'item_name' => $items->item_name,
                'item_qty' => $items->item_qty,
                'item_price' => $items->item_price,
                'item_total' => $items->item_total_price,
                'bill_address1' => $o->xadd1,
                'bill_address2' => $o->xadd2,
                'bill_province' => $o->xprov,
                'bill_city' => $o->xcity,
                'bill_brgy' => $o->xbrgy,
                'bill_country' => $o->xcountry,
                'ship_address1' => $o->xadd1,
                'ship_address2' => $o->xadd2,
                'ship_province' => $o->xprov,
                'ship_city' => $o->xcity,
                'ship_brgy' => $o->xbrgy,
                'ship_country' => $o->xcountry,
            ];
        }

        return view('backend.orders.order_list', compact('orders_arr'));
    }
}
