<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;
use DB;
use Validator;

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
            DB::table('fumaco_admin_user')->where('username', $request->username)->update(['account_name' => $acc_name, 'user_type' => $request->user_type, 'mobile_number' => $request->mobile_number, 'last_modified_by' => Auth::user()->username]);
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

            $rules = array(
                'password' => 'required|string|min:4|max:255',
			);

            $validation = Validator::make($request->all(), $rules);

			if ($validation->fails()){
				$error = "Password should be at least 4 characters";
				return redirect()->back()->with('error', $error);
			}

            $insert = [
                'account_name' => $request->account_name,
                'username' => $request->username,
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
                'user_type' => $request->user_type,
                'created_by' => Auth::user()->username,
                'last_modified_by' => Auth::user()->username,
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
            DB::table('fumaco_admin_user')->where('id', $request->admin_id)->update(['xstatus' => $request->status, 'last_modified_by' => Auth::user()->username]);
            DB::commit();
            return response()->json(['status' => 1, 'message' => 'Status Changed']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function adminPasswordForm(){
        $user = DB::table('fumaco_admin_user')->where('id', Auth::user()->id)->first();
        return view('backend.user_management.change_password', compact('user'));
    }

    public function adminChangePassword(Request $request, $id){ //For System Admin
        DB::beginTransaction();
        try {
            if($request->password != $request->confirm){
                return redirect()->back()->with("error","New password/s do not match");
            }

            $user = DB::table('fumaco_admin_user')->where('id', $id)->first();
    
            $rules = array(
                'password' => 'required|string|min:4|max:255',
                'confirm' => 'required|string'
            );

			$validation = Validator::make($request->all(), $rules);

            if($validation->fails()){
				return redirect()->back()->with('error', 'Password should be at least 4 characters');
			}

            DB::table('fumaco_admin_user')->where('id', $id)->update(['password' => password_hash($request->password, PASSWORD_DEFAULT), 'last_modified_by' => Auth::user()->username]);
            DB::commit();

            return redirect()->back()->with('success', 'Password Changed.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function userChangePassword(Request $request){ //For Logged in User
        DB::beginTransaction();
        try {
            if($request->password != $request->confirm){
                return redirect()->back()->with("error","New password/s do not match");
            }

            if (!(Hash::check($request->get('current'), Auth::user()->password))) {
                return redirect()->back()->with("error","Your current password does not match with the password you provided. Please try again.");
            }
    
            if(strcmp($request->get('current'), $request->get('password')) == 0){
                //Current password and new password are same
                return redirect()->back()->with("error","New password cannot be same as your current password. Please choose a different password.");
            }
    
            $rules = array(
                'current' => 'required',
                'password' => 'required|string|min:4|max:255',
                'confirm' => 'required|string'
            );

			$validation = Validator::make($request->all(), $rules);

            if($validation->fails()){
				return redirect()->back()->with('error', 'Password should be at least 4 characters');
			}

            DB::table('fumaco_admin_user')->where('id', Auth::user()->id)->update(['password' => password_hash($request->password, PASSWORD_DEFAULT), 'last_modified_by' => Auth::user()->username]);
            DB::commit();

            return redirect()->back()->with('success', 'Password Changed.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
}
