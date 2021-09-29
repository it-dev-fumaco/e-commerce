<?php

namespace App\Http\Controllers\Auth;

use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
            return redirect('/');
        }

        return redirect()->back()->withInput()
            ->with('error', 'Login failed, please try again!');
    }

    public function logout(){
        Auth::logout();

        return redirect('/');
    }
}
