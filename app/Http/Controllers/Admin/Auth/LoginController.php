<?php

namespace App\Http\Controllers\Admin\Auth;

use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
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

            DB::table('fumaco_admin_user')->where('username', $request->username)->update(['last_login' => Carbon::now(), 'last_login_ip' => $request->ip()]);
            return redirect('/admin/dashboard');
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