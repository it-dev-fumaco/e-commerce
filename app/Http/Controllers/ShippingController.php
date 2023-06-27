<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingService;
use App\Models\ShippingZoneRate;
use App\Models\ShippingCondition;
use DB;
use Carbon\Carbon;
use Auth;
use DateTime;
use Exception;

class ShippingController extends Controller
{
    public function viewAddForm() {
        $stores = DB::table('fumaco_store')->get();

        $categories = DB::table('fumaco_categories')->pluck('name', 'id');

        return view('backend.shipping.add', compact('stores', 'categories'));
    }

    public function viewList() {
        $shipping_services = ShippingService::all();
        
        return view('backend.shipping.list', compact('shipping_services'));
    }

    public function viewHolidays(Request $request){
        $hol_str = $request->holiday;
        $holidays = DB::table('fumaco_holiday')->where('holiday_name', 'LIKE', '%'.$hol_str.'%');

        if($request->holiday_month != ''){
            $holidays->whereMonth('holiday_date', $request->holiday_month);
        }

        if($request->holiday_year != ''){
            $holidays->whereYear('holiday_date', $request->holiday_year);
        }

        $holidays = $holidays->orderBy('holiday_date', 'asc')->paginate(10);

        $holidays_arr = [];

        $year_now = Carbon::now()->format('Y');

        $years = DB::table('fumaco_holiday')->select(DB::raw('YEAR(holiday_date) as year'))->distinct()->pluck('year');

        foreach($holidays as $holiday){
            $holidays_arr[] = [
                'id' => $holiday->holiday_id,
                'name' => $holiday->holiday_name,
                'date' => Carbon::parse($holiday->holiday_date)->format('M d'),
                'year' => Carbon::parse($holiday->holiday_date)->format('Y'),
                'created_at' => $holiday->created_by,
                'last_modified_at' => $holiday->last_modified_at,
                'created_by' => $holiday->created_by,
                'last_modified_by' => $holiday->last_modified_by,
            ];
        }

        return view('backend.shipping.holiday_list', compact('holidays', 'holidays_arr', 'years', 'year_now'));
    }

    public function addHolidayForm(){
        return view('backend.shipping.add_holiday');
    }

    private function adjustOrderDates($new_holiday){ // check and update existing estimated delivery/pickup dates
        $excluded_statuses = DB::table('order_status')->where('update_stocks', 1)->select('status')->get();
            
        $estimated_delivery_dates = DB::table('fumaco_order')->whereNotIn('order_status', collect($excluded_statuses)->pluck('status'))->where('order_status', '!=', 'Cancelled')->select('id', 'order_number', 'order_status', 'estimated_delivery_date', 'pickup_date', 'order_shipping')->get();

        $update_leadtime = 0;
        $update_pickup_date = 0;

        foreach($estimated_delivery_dates as $edd){
            $date = $edd->estimated_delivery_date ? explode(' - ', $edd->estimated_delivery_date) : null;
            $year = $edd->estimated_delivery_date ? explode(', ', $edd->estimated_delivery_date) : null;
            $month = $edd->estimated_delivery_date ? explode(' ', $edd->estimated_delivery_date) : null;

            $min_leadtime = null;
            $max_leadtime = null;
            $pickup_date = null;

            $new_min_leadtime = null;
            $new_max_leadtime = null;
            $new_leadtime = null;
            $new_pickup_date = null;
            $holiday_checker = null;

            if($edd->order_shipping != 'Store Pickup'){
                $update_pickup_date = 0;
                if(DateTime::createFromFormat('M d, Y', $date[0])){ // check and convert min leadtime to proper date format
                    $min_leadtime = $date[0];
                }else{
                    $min_leadtime = $date[0].', '.$year[1];
                }

                if(DateTime::createFromFormat('M d, Y', $date[1])){ // check and convert max leadtime to proper date format
                    $max_leadtime = $date[1];
                }else{
                    $max_leadtime = $month[0].' '.$date[1];
                }

                if(Carbon::parse($min_leadtime)->format('Y') == Carbon::parse($new_holiday)->format('Y') and Carbon::parse($max_leadtime)->format('Y') == Carbon::parse($new_holiday)->format('Y')){
                    if($min_leadtime == $new_holiday){
                        $update_leadtime = 1;
                        $new_min_leadtime = Carbon::parse($min_leadtime)->addDays(1);
                        $new_max_leadtime = Carbon::parse($max_leadtime)->addDays(1);
                    }else if($min_leadtime <= $new_holiday and $max_leadtime >= $new_holiday){
                        $update_leadtime = 1;
                        $new_max_leadtime = Carbon::parse($max_leadtime)->addDays(1);
                    }
                }
                
                if($update_leadtime == 1){
                    if(Carbon::parse($new_min_leadtime)->format('M d, Y') == Carbon::parse($new_max_leadtime)->format('M d, Y')){
                        $new_leadtime = Carbon::parse($new_min_leadtime)->format('M d, Y');
                    }else if(Carbon::parse($new_min_leadtime)->format('Y') == Carbon::parse($new_max_leadtime)->format('Y')){ // Same Year
                        if(Carbon::parse($new_min_leadtime)->format('M') == Carbon::parse($new_max_leadtime)->format('M')){ // Same Month
                            $new_leadtime = Carbon::parse($new_min_leadtime)->format('M d').' - '.Carbon::parse($new_max_leadtime)->format('d, Y');
                        }else{
                            $new_leadtime = Carbon::parse($new_min_leadtime)->format('M d').' - '.Carbon::parse($new_max_leadtime)->format('M d, Y');
                        }
                    } else {
                        $new_leadtime = Carbon::parse($new_min_leadtime)->format('M d, Y'). ' - ' .Carbon::parse($new_max_leadtime)->format('M d, Y');
                    }
                }
            }else{
                $update_leadtime = 0;
                $pickup_date = $edd->pickup_date;

                if(Carbon::parse($pickup_date)->format('M d, Y') == $new_holiday){
                    $update_pickup_date = 1;
                    $new_pickup_date = Carbon::parse($pickup_date)->addDays(1);
                }
            }

            if($update_leadtime == 1){
                DB::table('fumaco_order')->where('id', $edd->id)->update([
                    'estimated_delivery_date' => $new_leadtime
                ]);
            }
            
            if($update_pickup_date == 1){
                DB::table('fumaco_order')->where('id', $edd->id)->update([
                    'pickup_date' => $new_pickup_date
                ]);
            }
        }
    }

    public function addHoliday(Request $request){
        DB::beginTransaction();
        try {
            $checker = DB::table('fumaco_holiday')->whereDate('holiday_date', Carbon::parse($request->date)->format('Y-m-d'))->exists();
            
            if($checker){
                return redirect()->bacK()->with('error', 'Holiday Date Exists.');
            }

            $insert = [
                'holiday_date' => $request->date,
                'holiday_name' => $request->name,
                'created_by' => Auth::user()->username,
                'last_modified_by' => Auth::user()->username,
            ];

            DB::table('fumaco_holiday')->insert($insert);

            $this->adjustOrderDates($new_holiday = Carbon::parse($request->date)->format('M d, Y'));

            DB::commit();
            return redirect('/admin/holiday/list')->with('success', 'Holiday Added.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function editHoliday(Request $request){
        DB::beginTransaction();
        try {
            $checker = DB::table('fumaco_holiday')->where('holiday_id', '!=', $request->id)->whereDate('holiday_date', Carbon::parse($request->date)->format('Y-m-d'))->exists();
            
            if($checker){
                return redirect()->bacK()->with('error', 'Holiday Date Exists.');
            }

            $update = [
                'holiday_date' => $request->date,
                'holiday_name' => $request->name,
                'last_modified_by' => Auth::user()->username,
            ];

            DB::table('fumaco_holiday')->where('holiday_id', $request->id)->update($update);

            $this->adjustOrderDates($new_holiday = Carbon::parse($request->date)->format('M d, Y'));

            DB::commit();
            return redirect()->back()->with('success', 'Holiday Edited.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function deleteHoliday($id){
        DB::beginTransaction();
        try {
            DB::table('fumaco_holiday')->where('holiday_id', $id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Holiday Deleted.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function saveShipping(Request $request) {
        DB::beginTransaction();
        try {
            if(!in_array($request->shipping_service_type, ['Store Pickup', 'Transportify', 'Lalamove'])){
                $shipping_calculation = $request->shipping_calculation;
                if(!$shipping_calculation) {
                    return response()->json([
                        'status' => 0, 
                        'message' => 'Please select shipping condition.', 
                        'redirect_to' => null, 
                        'new' => 0
                    ]);
                }
                
                if($shipping_calculation == 'Flat Rate'){
                    $amount = $request->amount;
                    $min_charge_amount = 0;
                    $max_charge_amount = 0;
                } else {
                    $amount = 0;
                    $min_charge_amount = $request->min_charge_amount;
                    $max_charge_amount = $request->max_charge_amount;
                }
            } else {
                $shipping_calculation = null;
                $amount = 0;
                $min_charge_amount = 0;
                $max_charge_amount = 0;
            }

            $shipping_service = new ShippingService;
            $shipping_service->shipping_service_name = $request->shipping_service_type;
            $shipping_service->min_leadtime = $request->min_leadtime;
            $shipping_service->max_leadtime = $request->max_leadtime;
            $shipping_service->shipping_service_description = $request->shipping_service_description;
            $shipping_service->shipping_calculation = $shipping_calculation;
            $shipping_service->amount = $amount;
            $shipping_service->min_charge_amount = $min_charge_amount;
            $shipping_service->max_charge_amount = $max_charge_amount;
            $shipping_service->created_by = Auth::user()->username;
            $shipping_service->last_modified_by = Auth::user()->username;
            $shipping_service->save();
            $shipping_service->shipping_service_id;

            if($request->shipping_service_type == 'Store Pickup'){
                $stores = [];
                $store_arr = [];
                if($request->store){
                    foreach ($request->store as $e => $row){
                        if(in_array($request->store[$e], $store_arr)){
                            return response()->json([
                                'status' => 0, 
                                'message' => 
                                'Store has been selected multiple times.', 
                                'redirect_to' => null, 
                                'new' => 0
                            ]);
                        }else{
                            array_push($store_arr, $request->store[$e]);
                        }

                        $stores[] = [
                            'shipping_service_id' => $shipping_service->shipping_service_id,
                            'store_location_id' => $request->store[$e],
                            'allowance_in_hours' => $request->allowed_hours[$e],
                            'created_by' => Auth::user()->username,
                            'last_modified_by' => Auth::user()->username,
                        ];
                    }

                    DB::table('fumaco_shipping_service_store')->insert($stores);
                }
            }

            if($request->shipping_service_type != 'Store Pickup'){
                $shipping_zone_rates = [];
                if($request->province){
                    foreach ($request->province as $e => $row) {
                        $city_text = ($request->city_text[$e] != 'ALL') ? $request->city_text[$e] : null;
                        $location = \GoogleMaps::load('geocoding')
                            ->setParam (['address' => $city_text . ' ' . $request->province_text[$e]])
                            ->get();

                        $output= json_decode($location);

                        if ($output->status != "OK" && isset($output->status)) {
                            return response()->json([
                                'status' => 0, 
                                'message' => 'An error occured. Google Maps API not properly configured.', 
                                'redirect_to' => null, 
                                'new' => 0
                            ]);
                        }

                        if(isset($output->results[0])){
                            $latitude = $output->results[0]->geometry->location->lat;
                            $longitude = $output->results[0]->geometry->location->lng;
                        }else{
                            $latitude = null;
                            $longitude = null;
                        }

                        $shipping_zone_rates[] = [
                            'shipping_service_id' => $shipping_service->shipping_service_id,
                            'province_code' => $request->province[$e],
                            'city_code' => $request->city[$e],
                            'province_name' => $request->province_text[$e],
                            'city_name' => $request->city_text[$e],
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'created_by' => Auth::user()->username,
                            'last_modified_by' => Auth::user()->username,
                        ];
                    }
                }

                $shipping_conditions = [];
                if($shipping_calculation != 'Flat Rate'){
                    if($request->conditional_op){
                        foreach ($request->conditional_op as $e => $row) {
                            if(!is_numeric($request->shipping_amount[$e])) {
                                return response()->json([
                                    'status' => 0, 
                                    'message' => 'Invalid input in shipping amount field.', 
                                    'redirect_to' => null, 
                                    'new' => 0
                                ]);
                            }

                            $shipping_conditions[] = [
                                'shipping_service_id' => $shipping_service->shipping_service_id,
                                'type' => $request->condition[$e],
                                'conditional_operator' => $request->conditional_op[$e],
                                'value' => $request->value[$e],
                                'shipping_amount' => $request->shipping_amount[$e],
                                'created_by' => Auth::user()->username,
                                'last_modified_by' => Auth::user()->username,
                            ];
                        }
                    }
                }

                ShippingCondition::insert($shipping_conditions);
                ShippingZoneRate::insert($shipping_zone_rates);
            }

            if(isset($request->product_category)) {
                $category_arr = [];
                foreach($request->product_category as $i => $category) {
                    $category_arr[] = [
                        'shipping_service_id' => $shipping_service->shipping_service_id,
                        'category_id' => $category,
                        'condition' => isset($request->c_conditional_op[$i]) ? $request->c_conditional_op[$i] : null,
                        'qty' => isset($request->c_value[$i]) ? $request->c_value[$i] : null,
                        'min_leadtime' => $request->c_min_leadtime[$i],
                        'max_leadtime' => $request->c_max_leadtime[$i],
                        'created_by' => Auth::user()->username,
                        'last_modified_by' => Auth::user()->username
                    ];
                }

                DB::table('fumaco_shipping_product_category')->insert($category_arr);
            }

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Shipping Service has been created.',
                'redirect_to' => '/admin/shipping/'. $shipping_service->shipping_service_id .'/edit',
                'new' => 1
            ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 0, 
                'message' => 'An error occured. Please try again.', 
                'redirect_to' => null, 
                'new' => 0
            ]);
        }
    }

    public function viewShipping($id) {
        $details = ShippingService::find($id);
        $shipping_zone_rates = ShippingZoneRate::where('shipping_service_id', $id)->get();
        $shipping_conditions = ShippingCondition::where('shipping_service_id', $id)->get();
        $shipping_service_stores = DB::table('fumaco_shipping_service_store as a')
            ->join('fumaco_store as b', 'a.store_location_id', 'b.store_id')
            ->where('shipping_service_id', $id)->get();
        $stores = DB::table('fumaco_store')->get();

        $categories = DB::table('fumaco_categories as a')->join('fumaco_shipping_product_category as b', 'a.id', 'b.category_id')->where('b.shipping_service_id', $id)->get();

        $product_categories = DB::table('fumaco_categories')->pluck('name', 'id');

        return view('backend.shipping.view', compact('details', 'shipping_zone_rates', 'shipping_conditions', 'stores', 'shipping_service_stores', 'categories', 'product_categories'));
    }

    public function updateShipping($id, Request $request){
        DB::beginTransaction();
        try {
            if(!in_array($request->shipping_service_type, ['Store Pickup', 'Transportify', 'Lalamove'])){
                $shipping_calculation = $request->shipping_calculation;
                
                if($shipping_calculation == 'Flat Rate'){
                    $amount = $request->amount;
                    $min_charge_amount = 0;
                    $max_charge_amount = 0;
                } else {
                    $amount = 0;
                    $min_charge_amount = $request->min_charge_amount;
                    $max_charge_amount = $request->max_charge_amount;
                }
            } else {
                $shipping_calculation = null;
                $amount = 0;
                $min_charge_amount = 0;
                $max_charge_amount = 0;
            }
            
            $shipping_service = ShippingService::find($id);
            $shipping_service->shipping_service_name = $request->shipping_service_type;
            $shipping_service->min_leadtime = $request->min_leadtime;
            $shipping_service->max_leadtime = $request->max_leadtime;
            $shipping_service->shipping_service_description = $request->shipping_service_description;
            $shipping_service->shipping_calculation = $shipping_calculation;
            $shipping_service->amount = $amount;
            $shipping_service->min_charge_amount = $min_charge_amount;
            $shipping_service->max_charge_amount = $max_charge_amount;
            $shipping_service->last_modified_by = Auth::user()->username;
            $shipping_service->save();

            $shipping_zone_rate_id = (!$request->shipping_zone_rate_id) ? [] : $request->shipping_zone_rate_id;

            if(!isset($request->province)){
                ShippingZoneRate::where('shipping_service_id', $id)->delete();
            }else{
                ShippingZoneRate::where('shipping_service_id', $id)->whereNotIn('shipping_zone_rate_id', $shipping_zone_rate_id)->delete();
            }

            if(!isset($request->store)){
                DB::table('fumaco_shipping_service_store')->where('shipping_service_id', $id)->delete();
            }else{
                DB::table('fumaco_shipping_service_store')->where('shipping_service_id', $id)->whereNotIn('shipping_service_store_id', $request->shipping_service_store_id)->delete();
            }

            if($request->shipping_service_type == 'Store Pickup'){
                $stores = [];
                $store_arr = [];
                if($request->store){
                    foreach ($request->store as $e => $row){
                        if(in_array($request->store[$e], $store_arr)){
                            return response()->json([
                                'status' => 0, 
                                'message' => 'Store has been selected multiple times.', 
                                'redirect_to' => null, 
                                'new' => 0
                            ]);
                        }else{
                            array_push($store_arr, $request->store[$e]);
                        }

                        if(isset($request->shipping_service_store_id[$e])){
                            $values = [
                                'store_location_id' => $request->store[$e],
                                'allowance_in_hours' => $request->allowed_hours[$e],
                            ];

                            DB::table('fumaco_shipping_service_store')->where('shipping_service_store_id', $request->shipping_service_store_id[$e])->update($values);
                        }else{
                            $stores[] = [
                                'shipping_service_id' => $shipping_service->shipping_service_id,
                                'store_location_id' => $request->store[$e],
                                'allowance_in_hours' => $request->allowed_hours[$e],
                            ];
                        }
                    }

                    DB::table('fumaco_shipping_service_store')->insert($stores);
                }
            }

            ShippingCondition::where('shipping_service_id', $id)->delete();
            if($request->shipping_service_name != 'Store Pickup'){
                $shipping_zone_rates = [];
                if($request->province){
                    foreach ($request->province as $e => $row) {
                        $city_text = ($request->city_text[$e] != 'ALL') ? $request->city_text[$e] : null;
                        $location = \GoogleMaps::load('geocoding')
                            ->setParam (['address' => $city_text . ' ' . $request->province_text[$e]])
                            ->get();

                        $output= json_decode($location);

                        if ($output->status != "OK" && isset($output->status)) {
                            return response()->json([
                                'status' => 0, 
                                'message' => 'An error occured. Google Maps API not properly configured.', 
                                'redirect_to' => null, 
                                'new' => 0
                            ]);
                        }

                        if(isset($output->results[0])){
                            $latitude = $output->results[0]->geometry->location->lat;
                            $longitude = $output->results[0]->geometry->location->lng;
                        }else{
                            $latitude = null;
                            $longitude = null;
                        }

                        if(isset($request->shipping_zone_rate_id[$e])){
                            $values = [
                                'province_code' => $request->province[$e],
                                'city_code' => $request->city[$e],
                                'province_name' => $request->province_text[$e],
                                'city_name' => $request->city_text[$e],
                                'latitude' => $latitude,
                                'longitude' => $longitude,
                            ];
            
                            ShippingZoneRate::where('shipping_zone_rate_id', $request->shipping_zone_rate_id[$e])->update($values);
                        }else{
                            $shipping_zone_rates[] = [
                                'shipping_service_id' => $shipping_service->shipping_service_id,
                                'province_code' => $request->province[$e],
                                'city_code' => $request->city[$e],
                                'province_name' => $request->province_text[$e],
                                'city_name' => $request->city_text[$e],
                                'latitude' => $latitude,
                                'longitude' => $longitude,
                                'created_by' => Auth::user()->username,
                                'last_modified_by' => Auth::user()->username,
                            ];
                        }
                    }

                    ShippingZoneRate::insert($shipping_zone_rates);
                }
                
                $shipping_conditions = [];
                if($shipping_calculation != 'Flat Rate'){
                    if($request->conditional_op){
                        foreach ($request->conditional_op as $e => $row) {
                            if(!is_numeric($request->shipping_amount[$e])) {
                                return response()->json([
                                    'status' => 0, 
                                    'message' => 'Invalid input in shipping amount field.', 
                                    'redirect_to' => null, 
                                    'new' => 0
                                ]);
                            }

                            $shipping_conditions[] = [
                                'shipping_service_id' => $shipping_service->shipping_service_id,
                                'type' => $request->condition[$e],
                                'conditional_operator' => $request->conditional_op[$e],
                                'value' => $request->value[$e],
                                'shipping_amount' => $request->shipping_amount[$e],
                                'created_by' => Auth::user()->username,
                                'last_modified_by' => Auth::user()->username,
                            ];
                        }
                    }
                }

                ShippingCondition::insert($shipping_conditions);
            }

            DB::table('fumaco_shipping_product_category')->where('shipping_service_id', $id)->delete();
            if(isset($request->product_category)) {
                $category_arr = [];

                foreach($request->product_category as $i => $category) {
                    $category_arr[] = [
                        'shipping_service_id' => $id,
                        'category_id' => $category,
                        'condition' => isset($request->c_conditional_op[$i]) ? $request->c_conditional_op[$i] : null,
                        'qty' => isset($request->c_value[$i]) ? $request->c_value[$i] : null,
                        'min_leadtime' => $request->c_min_leadtime[$i],
                        'max_leadtime' => $request->c_max_leadtime[$i],
                        'created_by' => Auth::user()->username,
                        'last_modified_by' => Auth::user()->username
                    ];
                }

                DB::table('fumaco_shipping_product_category')->insert($category_arr);
            }
                        
            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Shipping Service has been updated.',
                'redirect_to' => null,
                'new' => 0
            ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 0, 
                'message' => 'An error occured. Please try again.', 
                'redirect_to' => null, 
                'new' => 0
            ]);
        }
    }

    public function deleteShipping($id){
        DB::beginTransaction();
        try {
            ShippingService::where('shipping_service_id', $id)->delete();
            ShippingZoneRate::where('shipping_service_id', $id)->delete();
            ShippingCondition::where('shipping_service_id', $id)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Shipping Service has been deleted.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
}
