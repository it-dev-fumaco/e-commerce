<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

class DashboardController extends Controller
{
	public function index() {
		$orders = DB::table('fumaco_order')->get();

		$new_orders = collect($orders)->where('order_status', '!=', 'Order Delivered')->where('order_status', '!=', 'Cancelled')->where('order_status', '!=', 'Delivered')->count();

		$total_orders = collect($orders)->count();

		$users = DB::table('fumaco_users')->where('is_email_verified', 1)->count();

		$total_sales = collect($orders)->where('order_status', '!=', 'Cancelled')->sum('amount_paid');

		return view('backend.dashboard.index', compact('new_orders', 'total_orders', 'users', 'total_sales'));
	}

}
