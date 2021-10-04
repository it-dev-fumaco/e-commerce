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

    public function saveApiCredentials(Request $request) {
        DB::beginTransaction();
        try {
            $request->validate([
                'base_url' => 'required|url',
                'api_key' => 'required',
                'api_secret_key' => 'required'
            ]);
        
            $row = DB::table('api_setup')->where('type', 'erp_api')->first();
            if ($row) {
                DB::table('api_setup')->where('id', $row->id)->update([
                    'base_url' => $request->base_url,
                    'api_key' => $request->api_key,
                    'api_secret_key' => $request->api_secret_key
                ]);
            } else {
                DB::table('api_setup')->insert([
                    'type' => 'erp_api',
                    'base_url' => $request->base_url,
                    'api_key' => $request->api_key,
                    'api_secret_key' => $request->api_secret_key
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
