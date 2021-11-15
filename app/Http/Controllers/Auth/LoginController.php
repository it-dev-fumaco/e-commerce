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
            if ($request->has('summary')){
                return redirect('/checkout/summary');
            }
            return redirect('/');
        }

        return redirect()->back()->withInput()
            ->with('error', 'Login failed, please try again!');
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
                $newUser = User::create([
                    'username' => trim($user->email),
                    'password' => password_hash($user->getName().'@'.$user->getId(), PASSWORD_DEFAULT),
                    'f_name' => $user->name,
                    // 'f_lname' => $user->name,
                    'facebook_id'=> $user->id,
                    'f_email' => 'fumacoco_dev',
                    'f_temp_passcode' => 'fumaco12345'
                ]);

                Auth::loginUsingId($newUser->id);

                return redirect('/');
            }
        } catch (\Throwable $th) {
            throw $th;
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
                $newUser = User::create([
                    'username' => trim($user->email),
                    'password' => password_hash($user->getName().'@'.$user->getId(), PASSWORD_DEFAULT),
                    'f_name' => $user->name,
                    // 'f_lname' => $user->name,
                    'google_id'=> $user->id,
                    'f_email' => 'fumacoco_dev',
                    'f_temp_passcode' => 'fumaco12345'
                ]);

                Auth::loginUsingId($newUser->id);

                return redirect('/');
            }
        } catch (\Throwable $th) {
            throw $th;
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
                $newUser = User::create([
                    'username' => trim($user->email),
                    'password' => password_hash($user->getName().'@'.$user->getId(), PASSWORD_DEFAULT),
                    'f_name' => $user->name,
                    // 'f_lname' => $user->name,
                    'linkedin_id'=> $user->id,
                    'f_email' => 'fumacoco_dev',
                    'f_temp_passcode' => 'fumaco12345'
                ]);

                Auth::loginUsingId($newUser->id);

                return redirect('/');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
