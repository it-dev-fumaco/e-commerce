<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

class DashboardController extends Controller
{
	public function index() {
		$new_orders = DB::table('fumaco_order')->where('order_status', '!=', 'Cancelled')->where('order_status', '!=', 'Delivered')->count();

		$total_orders = DB::table('fumaco_order')->count();

		$users = DB::table('fumaco_users')->count();

		return view('backend.dashboard.index', compact('new_orders', 'total_orders', 'users'));
	}

}
