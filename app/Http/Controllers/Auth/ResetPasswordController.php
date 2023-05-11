<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Mail; 
use Hash;
use Exception;

class ResetPasswordController extends Controller
{
    public function showResetForm($token) {
        $email = DB::table('password_resets')->where('token', $token)->pluck('email')->first();
        return view('auth.passwords.reset', ['token' => $token, 'email' => $email]);
    }

    public function reset(Request $request) {
        DB::beginTransaction();
        try {
            $request->validate([
                'username' => 'required|email|exists:fumaco_users',
                'password' => 'required|string|min:6|confirmed',   
                'password_confirmation' => 'required'
            ]);
            
            $updatePassword = DB::table('password_resets')->where([
                'email' => $request->username, 
                'token' => $request->token
            ])->first();

            if(!$updatePassword){
                return back()->withInput()->with('error', 'Invalid token!');
            }
            
            DB::table('fumaco_users')->where('username', $request->username)->update(['password' => Hash::make($request->password)]);
            
            DB::table('password_resets')->where(['email'=> $request->username])->delete();

            Mail::send('emails.change_password_success', ['username' => $request->username], function($message) use($request){
                $message->to(trim($request->username));
                $message->subject('Your password has been changed - FUMACO');
            });

            DB::commit();
            return redirect('/login')->with('success', 'Your password has been changed!');
        } catch (Exception $e) {
            DB::rollback();

            return back()->withInput()->with('error', 'An error occured. Please try again.');
        }
    }
}
