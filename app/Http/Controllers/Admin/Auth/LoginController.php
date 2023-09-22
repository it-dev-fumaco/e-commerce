<?php

namespace App\Http\Controllers\Admin\Auth;

use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Show the login form.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin/dashboard');
        }

        return view('backend.login');
    }

    /**
     * Login the admin.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request) {
        //Validation...     
        $this->validator($request);
        //Login the admin...
        if(Auth::guard('admin')->attempt(['username' => $request->username,'password' => $request->password], 0)){
            //Authentication passed...
            $checker = DB::table('fumaco_admin_user')->where('username', $request->username)->first();

            if($checker->xstatus == 0){
                Auth::guard('admin')->logout();
                return redirect('/admin/login')->withInput()->with('d_info','Your admin account is deactivated.');
            }

            $otp = rand(11111, 99999);
            $api = DB::table('api_setup')->where('type', 'sms_gateway_api')->first();
            if(!$api or !$checker->mobile_number){
                $error_message = !$checker->mobile_number ? 'Your account does not have a registered mobile number. ' : 'OTP cannot be sent. ';
                Auth::guard('admin')->logout();
                return redirect('/admin/login')->withInput()->with('d_info', $error_message.'Please contact the system administrator');
            }

            $phone = $checker->mobile_number[0] == '0' ? '63'.substr($checker->mobile_number, 1) : $checker->mobile_number;

            $sms = Http::asForm()->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->post($api->base_url, [
                'api_key' => $api->api_key,
                'api_secret' => $api->api_secret_key,
                'from' => 'FUMACO',
                'to' => preg_replace("/[^0-9]/", "", $phone),
                'text' => 'TWO-FACTOR AUTHENTICATION: Your One-Time PIN is '.$otp.' to login in Fumaco Website Admin Page, valid only within 10 mins. For any help, please contact us at it@fumaco.com'
            ]);

            $sms_response = json_decode($sms, true);

            if(isset($sms_response['error'])){
                Auth::guard('admin')->logout();
                $error = $sms['error']['code'] == 409 ? 'No mobile number not found. ' : 'Mobile number is invalid. ';
                return redirect('/admin/login')->withInput()->with('d_info', $error.'Please contact the system administrator');
            }

            $details = [
                'otp' => $otp,
                'otp_time_sent' => Carbon::now(),
                'otp_status' => 0,
                'last_login' => Carbon::now(),
                'last_login_ip' => $request->ip()
            ];

            DB::table('fumaco_admin_user')->where('username', $request->username)->update($details);
            return redirect('/admin/verify');
        }

        //Authentication failed...
        //Redirect to admin login page...
        return $this->loginFailed();
    }

    /**
     * Logout the admin.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
      //logout the admin...
      Auth::guard('admin')->logout();
      return redirect('/admin/login')->with('info','Admin has been logged out!');
    }

    /**
     * Validate the form data.
     * 
     * @param \Illuminate\Http\Request $request
     * @return 
     */
    private function validator(Request $request)
    {
      //validate the form...
      //validation rules.
        $rules = [
            'username' => 'required|email|exists:fumaco_admin_user|min:5|max:191',
            'password' => 'required|string|min:4|max:255',
        ];

        // custom validation error messages.
        $messages = [
            'username.exists' => 'These credentials do not match our records.',
        ];

        //validate the request.
        $request->validate($rules,$messages);
    }

    /**
     * Redirect back after a failed login.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed()
    {
      //Login failed...
      return redirect()->back()->withInput()->with('error','Login failed, please try again!');
    }
}