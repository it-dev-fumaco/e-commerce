<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

class BlogController extends Controller
{
    public function viewSubscribers(Request $request){
        $email_str = $request->email;
        $subscribers = DB::table('fumaco_subscribe')->where('email', 'LIKE', '%'.$email_str.'%')->paginate(10);
        
        $subs_arr = [];
        foreach($subscribers as $sub){
            $users = DB::table('fumaco_users')->get();
            foreach($users as $user){
                $membership_status = $user->username == $sub->email ? 'Member' : 'Guest';
            }  
            
            $subs_arr[] = [
                'email' => $sub->email,
                'status' => $sub->status,
                'membership_status' => $membership_status
            ];
        }

        return view('backend.blog.subscribers', compact('subscribers', 'subs_arr'));
    }
}
