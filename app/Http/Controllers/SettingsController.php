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

    public function emailSetup() {
        $details = [];
        $email_config = DB::table('email_config')->first();
        if($email_config) {
            $details = $email_config;
        }
        
        return view('backend.settings.email_setup', compact('details'));
    }

    public function saveEmailSetup(Request $request) {
        DB::beginTransaction();
        try {
            $request->validate([
                'driver' => 'required',
                'host' => 'required',
                'port' => 'required|integer',
                'encryption' => 'required',
                'username' => 'required',
                'password' => 'required',
                'address' => 'required',
                'name' => 'required',
            ]);

            $data = [
                'driver' => $request->driver,
                'host' => $request->host,
                'port' => $request->port,
                'encryption' => $request->encryption,
                'username' => $request->username,
                'password' => $request->password,
                'address' => $request->address,
                'name' => $request->name,
            ];

            $email_config = DB::table('email_config')->first();
            if($email_config) {
                DB::table('email_config')->where('id', $email_config->id)
                    ->update($data);
            } else {
                DB::table('email_config')->insert($data);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Email Configuration has been saved.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function saveEmailRecipients(Request $request) {
        DB::beginTransaction();
        try {
            // trim spaces
            $email_recipients = explode(",", str_replace(' ', '', $request->email_recipients));

            // check if exploeded email recipient is a valid email
            foreach ($email_recipients as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return redirect()->back()->withInput()->with('error_1', "$email is not a valid email address");
                }
            }

            DB::table('email_config')->update(['email_recipients' => str_replace(' ', '', $request->email_recipients)]);
            
            DB::commit();

            return redirect()->back()->with('success_1', 'Email Recipients has been saved.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error_1', 'An error occured. Please try again.');
        }
    }
}