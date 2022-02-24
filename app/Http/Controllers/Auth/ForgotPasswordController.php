<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request; 
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB; 
use Mail; 

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm() {
        return view('auth.passwords.email');
    }

    public function resetOptions(Request $request){
        $request->validate([
            'username' => 'required|email|exists:fumaco_users',
        ],
        ['exists' => 'Sorry, no existing account for this email.']);

        $phone = DB::table('fumaco_user_add')->where('xcontactemail1', $request->username)->where('address_class', 'Billing')->where('xdefault', 1)->pluck('xmobile_number')->first();

        $info_arr = [
            'email' => $request->username,
            'phone' => $phone ? $phone : null
        ];
        
        return view('auth.passwords.email', compact('info_arr'));
    }

    public function OTPForm(){
        $email = session()->get('forOTP');
        return view('auth.passwords.otp_form', compact('email'));
    }

    public function verifyOTP(Request $request){
        $otp_checker = DB::table('fumaco_users')->where('username', $request->username)->where('f_temp_passcode', $request->otp)->pluck('otp_time_sent')->first();
        if(!$otp_checker){
            return redirect()->back()->with('error', 'OTP is incorrect and/or expired.');
        }

        $time_sent = Carbon::parse($otp_checker);
        $now = Carbon::now()->toDateTimeString();

        $expiration_check = $time_sent->diffInMinutes($now);

        if($expiration_check > 10){
            return redirect()->back()->with('error', 'OTP is incorrect and/or expired.');
        }

        return redirect()->route('password.reset', session()->get('OTP'));
    }

    public function sendResetLinkEmail(Request $request) {
        DB::beginTransaction();
        try {
            if($request->has('otp')){
                $otp = rand(111111, 999999);
                $now = Carbon::now();

                DB::table('fumaco_users')->where('username', $request->username)->update([
                    'f_temp_passcode' => $otp,
                    'otp_time_sent' => $now->toDateTimeString()
                ]);

                $sms_api = DB::table('api_setup')->where('type', 'sms_gateway_api')->first();

                $message = 'RESET PASSWORD VERIFICATION: Your One-Time PIN is '.$otp.' to reset your password in Fumaco Website, valid only within 10 mins. For any help, please contact us at inquiries@fumaco.com';
                $phone = $request->phone[0] == '0' ? '63'.substr($request->phone, 1) : $request->phone;

                Http::asForm()->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])->post($sms_api->base_url, [
                    'api_key' => $sms_api->api_key,
                    'api_secret' => $sms_api->api_secret_key,
                    'from' => 'FUMACO',
                    'to' => preg_replace("/[^0-9]/", "", $phone),
                    'text' => $message
                ]);

                DB::table('password_resets')->insert([
                    'email' => $request->username, 
                    'token' => $otp, 
                    'created_at' => Carbon::now()
                ]);

                session()->put('forOTP', $request->username);
                session()->put('OTP', $otp);

                DB::commit();
                if($request->ajax()){
                    return 1;
                }

                return redirect()->route('password.otp_form');
            }else if($request->has('username')){
                $request->validate([
                    'username' => 'required|email|exists:fumaco_users',
                ],
                ['exists' => 'Sorry, no existing account for this email.']);

                $token = Str::random(64);
                DB::table('password_resets')->insert([
                    'email' => $request->username, 
                    'token' => $token, 
                    'created_at' => Carbon::now()
                ]);

                Mail::send('emails.forgot_password', ['token' => $token], function($message) use($request){
                    $message->to(trim($request->username));
                    $message->subject('Reset Password - FUMACO');
                });

                DB::commit();
                return redirect()->route('password.request')->with('message', 'We have e-mailed your password reset link!');
            }
        } catch (Exception $e) {
            DB::rollback();

            return back()->with('error', 'An error occured. Please try again.');
        }
    }
}
