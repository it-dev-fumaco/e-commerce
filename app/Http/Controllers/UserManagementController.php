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
}
