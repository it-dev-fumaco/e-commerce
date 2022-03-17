<?php

namespace App\Http\Controllers\Auth;

use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function viewLoginPage() {
        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();

        return view('frontend.login', compact('website_settings', 'item_categories'));
    }

    public function login(Request $request){
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt(['username' => $request->username,'password' => $request->password], $request->remember)) {
            if (!Auth::user()->is_email_verified) {
                auth()->logout();
    
                return redirect()->back()->withInput()
                    ->with('error', '<p class="p-1 text-center">Please verify your email for a verification link. If you did not receive the email then <a href="/resend_verification/'.$request->username.'"  class="d-inline-block m-0">resend the verification email</a>.</p>');
            }

            $this->updateCartItemOwner();

            // save last login and no of visits
            $this->saveLoginDetails();

            $user_check = $this->checkEmail('Website Account');
            $soc_used = collect($user_check)->implode(', ');

            if ($request->has('summary')){
                return redirect('/checkout/summary');
            }
            
            return redirect('/')->with('accounts', $soc_used);
        }

        return redirect()->back()->withInput()
            ->with('error', 'Your email address or password is incorrect, please try again');
    }

    private function checkEmail($soc){
        $user_check = DB::table('fumaco_users')->where('id', Auth::user()->id)->first();

        $user_arr = [];
        if($soc != 'Website Account' and $user_check->f_email == 'Website Account'){
            array_push($user_arr, $user_check->f_email);
        }

        if($soc != 'Google' and $user_check->google_id){
            array_push($user_arr, 'Google');
        }

        if($soc != 'LinkedIn' and $user_check->linkedin_id){
            array_push($user_arr, 'LinkedIn');
        }

        if($soc != 'Facebook' and $user_check->facebook_id){
            array_push($user_arr, 'Facebook');
        }

        return $user_arr;
    }

    private function saveLoginDetails(){
        DB::beginTransaction();
        try {
            $checker = DB::table('fumaco_users')->where('id', Auth::user()->id)->first();
            DB::table('fumaco_users')->where('id', Auth::user()->id)->update([
                'last_login' => Carbon::now(),
                'no_of_visits' => $checker->no_of_visits ? $checker->no_of_visits + 1 : 1
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function logout(){
        Auth::logout();

        return redirect('/');
    }

    public function loginUsingGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function callbackFromGoogle() {
        try {
            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)->orWhere('username', $user->email)->first();
            if($finduser){
                Auth::loginUsingId($finduser->id);

                $this->updateCartItemOwner();
                $this->saveLoginDetails();

                if(!$finduser->google_id){
                    DB::table('fumaco_users')->where('username', $user->email)->update(['google_id' => $user->id]);
                }

                $user_check = $this->checkEmail('Google');
                $soc_used = collect($user_check)->implode(', ');

                return redirect('/')->with('accounts', $soc_used);
            }else{
                $newUser = new User;
                $newUser->username = trim($user->email);
                $newUser->password = password_hash($user->getName().'@'.$user->getId(), PASSWORD_DEFAULT);
                $newUser->f_name = $user->name;
                $newUser->google_id = $user->id;
                $newUser->f_email = 'fumacoco_dev';
                $newUser->f_temp_passcode = 'fumaco12345';
                $newUser->is_email_verified = 1;
                $newUser->save();

                Auth::loginUsingId($newUser->id);

                $this->updateCartItemOwner();
                $this->saveLoginDetails();

                $user_check = $this->checkEmail('Google');
                $soc_used = collect($user_check)->implode(', ');

                return redirect('/')->with('accounts', $soc_used);
            }
        } catch (\Throwable $th) {
            return redirect('/login')->with('error', 'Your email address or password is incorrect, please try again');
        }
    }

    public function loginUsingLinkedin() {
        return Socialite::driver('linkedin')->redirect();
    }

    public function callbackFromLinkedin() {
        try {
            $user = Socialite::driver('linkedin')->user();
            $finduser = User::where('linkedin_id', $user->id)->orWhere('username', $user->email)->first();
            if($finduser){
                Auth::loginUsingId($finduser->id);

                $this->updateCartItemOwner();
                $this->saveLoginDetails();

                if(!$finduser->linkedin_id){
                    DB::table('fumaco_users')->where('username', $user->email)->update(['linkedin_id' => $user->id]);
                }

                $user_check = $this->checkEmail('LinkedIn');
                $soc_used = collect($user_check)->implode(', ');

                return redirect('/')->with('accounts', $soc_used);
            }else{
                $newUser = new User;
                $newUser->username = trim($user->email);
                $newUser->password = password_hash($user->getName().'@'.$user->getId(), PASSWORD_DEFAULT);
                $newUser->f_name = $user->name;
                $newUser->linkedin_id = $user->id;
                $newUser->f_email = 'fumacoco_dev';
                $newUser->f_temp_passcode = 'fumaco12345';
                $newUser->is_email_verified = 1;
                $newUser->save();

                Auth::loginUsingId($newUser->id);

                $this->updateCartItemOwner();
                $this->saveLoginDetails();

                $user_check = $this->checkEmail('LinkedIn');
                $soc_used = collect($user_check)->implode(', ');

                return redirect('/')->with('accounts', $soc_used);
            }
        } catch (\Throwable $th) {
            return redirect('/login')->with('error', 'Your email address or password is incorrect, please try again');
        }
    }

    public function fbDataDeletionCallback(Request $request) {
        $signed_request = $request->get('signed_request');
        $data = $this->parse_signed_request($signed_request);
        $user_id = $data['user_id'];

        // here will delete the user base on the user_id from facebook
        User::where('facebook_id', $user_id)->forceDelete();

        // here will check if the user is deleted
        $isDeleted = User::where('facebook_id', $user_id)->find();

        if ($isDeleted === null) {
            return response()->json([
                'url' => 'https://www.fumaco.com/data_deletion_status?id=del' . $user_id,
                'confirmation_code' => 'del' . $user_id,
            ]);
        }

        return response()->json([
            'message' => 'operation not successful'
        ], 500);
    }

    private function parse_signed_request($signed_request) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $secret = config('service.facebook.client_secret');

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }
        
        return $data;
    }

    private function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public function loginFbSdk(Request $request) {
        try {
            $finduser = User::where('facebook_id', $request->id)->orWhere('username', $request->email)->first();

            if($finduser){
                Auth::loginUsingId($finduser->id);

                $this->updateCartItemOwner();
                $this->saveLoginDetails();

                if($finduser->facebook_id == null or $finduser->facebook_id == ''){
                    DB::table('fumaco_users')->where('id', $finduser->id)->update(['facebook_id' => $request->id]);
                }

                $user_check = $this->checkEmail('Facebook');
                $soc_used = collect($user_check)->implode(', ');
                session()->flash('accounts', $soc_used); 

                return response()->json(['status' => 200, 'message' => 'Logged in']);
            }else{
                $newUser = new User;
                $newUser->username = trim($request->email);
                $newUser->password = password_hash($request->email.'@'.$request->id, PASSWORD_DEFAULT);
                $newUser->f_name = $request->first_name;
                $newUser->f_lname = $request->last_name;
                $newUser->facebook_id = $request->id;
                $newUser->f_email = 'fumacoco_dev';
                $newUser->f_temp_passcode = 'fumaco12345';
                $newUser->is_email_verified = 1;
                $newUser->save();

                Auth::loginUsingId($newUser->id);

                $this->updateCartItemOwner();
                $this->saveLoginDetails();

                $user_check = $this->checkEmail('Facebook');
                $soc_used = collect($user_check)->implode(', ');
                session()->flash('accounts', $soc_used); 

                return response()->json(['status' => 200, 'message' => 'Logged in new user']);
            }
        } catch (Exception $th) {
            return response()->json(['status' => 500, 'message' => 'Incorrect username and/or password.']);
        }
    }

    private function updateCartItemOwner() {
        $transaction_id = session()->get('fumOrderNo');
        if($transaction_id) {
            // get existing items in cart
            $existing_items = DB::table('fumaco_cart')->where(['user_type' => 'member', 'user_email' => Auth::user()->username])->pluck('item_code');
            // delete item from cart if already exists
            DB::table('fumaco_cart')->where('transaction_id', $transaction_id)->where('user_type', 'guest')->whereIn('item_code', $existing_items)->delete();
            // update owner of items in the cart
            DB::table('fumaco_cart')->where('transaction_id', $transaction_id)->update(['user_type' => 'member', 'user_email' => Auth::user()->username]);
            // update cart transaction id
            DB::table('fumaco_cart')->where(['user_type' => 'member', 'user_email' => Auth::user()->username])->update(['transaction_id' => $transaction_id]);
        }
    }
}
