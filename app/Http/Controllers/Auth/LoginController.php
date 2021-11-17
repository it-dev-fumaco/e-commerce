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

            if ($request->has('summary')){
                return redirect('/checkout/summary');
            }
            
            return redirect('/');
        }

        return redirect()->back()->withInput()
            ->with('error', 'Your email address or password is incorrect, please try again');
    }

    public function logout(){
        Auth::logout();

        return redirect('/');
    }

    public function loginUsingFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    public function callbackFromFacebook() {
        try {
            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('facebook_id', $user->id)->first();
            if($finduser){
                Auth::loginUsingId($finduser->id);

                return redirect('/');
            }else{
                $newUser = new User;
                $newUser->username = trim($user->email);
                $newUser->password = password_hash($user->getName().'@'.$user->getId(), PASSWORD_DEFAULT);
                $newUser->f_name = $user->name;
                $newUser->facebook_id = $user->id;
                $newUser->f_email = 'fumacoco_dev';
                $newUser->f_temp_passcode = 'fumaco12345';
                $newUser->is_email_verified = 1;
                $newUser->save();

                Auth::loginUsingId($newUser->id);

                return redirect('/');
            }
        } catch (\Throwable $th) {
            return redirect('/login')->with('error', 'Your email address or password is incorrect, please try again');
        }
    }

    public function loginUsingGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function callbackFromGoogle() {
        try {
            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)->first();
            if($finduser){
                Auth::loginUsingId($finduser->id);

                return redirect('/');
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

                return redirect('/');
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
            $finduser = User::where('linkedin_id', $user->id)->first();
            if($finduser){
                Auth::loginUsingId($finduser->id);

                return redirect('/');
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

                return redirect('/');
            }
        } catch (\Throwable $th) {
            return redirect('/login')->with('error', 'Your email address or password is incorrect, please try again');
        }
    }
}
