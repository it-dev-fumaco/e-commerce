<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

class UserManagementController extends Controller
{
    public function viewAdmin(Request $request){
        $admin_str = $request->email;
        $admin = DB::table('fumaco_admin_user')->where('username', 'LIKE', '%'.$admin_str.'%')->paginate(10);

        return view('backend.user_management.admin_list', compact('admin'));
    }

    public function editAdmin(Request $request){
        DB::beginTransaction();
        try {
            $acc_name = ($request->account_name) ? $request->account_name : ' ';
            DB::table('fumaco_admin_user')->where('username', $request->username)->update(['account_name' => $acc_name, 'user_type' => $request->user_type]); 
            DB::commit();
            return redirect()->back()->with('success', 'Admin Information Edited.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function addAdminForm(){
        return view('backend.user_management.admin_add');
    }

    public function addAdmin(Request $request){
        DB::beginTransaction();
        try {
            $checker = DB::table('fumaco_admin_user')->where('username', $request->username)->count();
            if($checker >= 1){
                return redirect()->back()->with('error', 'Username already exists.');
            }
            $insert = [
                'account_name' => $request->account_name,
                'username' => $request->username,
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
                'user_type' => $request->user_type
            ];

            if($request->password != $request->confirm){
                return redirect()->back()->with('error', 'Password/s do not match.');
            }

            DB::table('fumaco_admin_user')->insert($insert);
            DB::commit();
            return redirect()->back()->with('success', 'Admin Added.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function adminChangeStatus(Request $request){
        DB::beginTransaction();
        try {
            DB::table('fumaco_admin_user')->where('id', $request->admin_id)->update(['xstatus' => $request->status]);
            DB::commit();
            return response()->json(['status' => 1, 'message' => 'Status Changed']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
}
