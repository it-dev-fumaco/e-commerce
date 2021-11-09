<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingService;
use App\Models\ShippingZoneRate;
use App\Models\ShippingCondition;
use DB;
use Carbon\Carbon;
use Auth;

class ShippingController extends Controller
{
    public function viewAddForm() {
        $stores = DB::table('fumaco_store')->get();

        return view('backend.shipping.add', compact('stores'));
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
                'year' => Carbon::parse($holiday->holiday_date)->format('Y')
            ];
        }

        return view('backend.shipping.holiday_list', compact('holidays', 'holidays_arr', 'years', 'year_now'));
    }

    public function addHolidayForm(){
        return view('backend.shipping.add_holiday');
    }

    public function addHoliday(Request $request){
        DB::beginTransaction();
        try {
            $insert = [
                'holiday_date' => $request->date,
                'holiday_name' => $request->name,
                'created_by' => Auth::user()->username,
                'last_modified_by' => Auth::user()->username,
            ];

            DB::table('fumaco_holiday')->insert($insert);
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
            $update = [
                'holiday_date' => $request->date,
                'holiday_name' => $request->name,
                'last_modified_by' => Auth::user()->username,
            ];

            DB::table('fumaco_holiday')->where('holiday_id', $request->id)->update($update);
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
            if($request->shipping_service_type != 'Store Pickup'){
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

        return view('backend.shipping.view', compact('details', 'shipping_zone_rates', 'shipping_conditions', 'stores', 'shipping_service_stores'));
    }

    public function updateShipping($id, Request $request){
        DB::beginTransaction();
        try {
            if($request->shipping_service_type != 'Store Pickup'){
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

            if(!isset($request->province)){
                ShippingZoneRate::where('shipping_service_id', $id)->delete();
            }else{
                ShippingZoneRate::where('shipping_service_id', $id)->whereNotIn('shipping_zone_rate_id', $request->shipping_zone_rate_id)->delete();
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
                            ];

                            DB::table('fumaco_shipping_service_store')->where('shipping_service_store_id', $request->shipping_service_store_id[$e])->update($values);
                        }else{
                            $stores[] = [
                                'shipping_service_id' => $shipping_service->shipping_service_id,
                                'store_location_id' => $request->store[$e],
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
