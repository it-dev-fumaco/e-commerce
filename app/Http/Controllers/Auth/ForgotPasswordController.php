<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    public function sendResetLinkEmail(Request $request) {
        DB::beginTransaction();
        try {
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

            return back()->with('message', 'We have e-mailed your password reset link!');
        } catch (Exception $e) {
            DB::rollback();

            return back()->with('error', 'An error occured. Please try again.');
        }
    }  
}
