<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;

class SettingsController extends Controller
{
	public function erpApiSetup() {
        $api_details = DB::table('api_setup')->where('type', 'erp_api')->first();

		return view('backend.settings.erp_api_setup', compact('api_details'));
	}

    public function paymentApiSetup() {
        $api_details = DB::table('api_setup')->where('type', 'payment_api')->first();

		return view('backend.settings.payment_api_setup', compact('api_details'));
	}

    public function saveApiCredentials(Request $request) {
        DB::beginTransaction();
        try {
            $request->validate([
                'base_url' => 'required|url',
            ]);
        
            $row = DB::table('api_setup')->where('type', $request->api_type)->first();
            if ($row) {
                DB::table('api_setup')->where('id', $row->id)->update([
                    'base_url' => $request->base_url,
                    'api_key' => $request->api_key,
                    'api_secret_key' => $request->api_secret_key,
                    'merchant_type' => $request->merchant_type,
                    'service_id' => $request->service_id,
                    'password' => $request->password
                ]);
            } else {
                DB::table('api_setup')->insert([
                    'type' => $request->api_type,
                    'base_url' => $request->base_url,
                    'api_key' => $request->api_key,
                    'api_secret_key' => $request->api_secret_key,
                    'merchant_type' => $request->merchant_type,
                    'service_id' => $request->service_id,
                    'password' => $request->password
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'API Configuration has been saved.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

}
