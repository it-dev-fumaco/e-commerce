<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

class DashboardController extends Controller
{
	public function index(Request $request) {
		$users = DB::table('fumaco_users')->where('is_email_verified', 1)->count();

		$orders = DB::table('fumaco_order')->get();
		$new_orders = collect($orders)->where('order_status', '!=', 'Order Delivered')->where('order_status', '!=', 'Cancelled')->where('order_status', '!=', 'Delivered')->count();
		$total_sales = collect($orders)->where('order_status', '!=', 'Cancelled')->sum('amount_paid');
		$total_orders = collect($orders)->count();

		$most_searched = DB::table('fumaco_search_terms')->orderBy('frequency', 'desc')->limit(10)->get();

		// sales per month
		$sales_arr = [];
		for($month = 1; $month <= 12; $month++){
			if($request->year){
				$sales = DB::table('fumaco_order')->where('order_status', '!=', 'Cancelled')->whereMonth('order_date', $month)->whereYear('order_date', $request->year)->sum('amount_paid');
			}else{
				$sales = DB::table('fumaco_order')->where('order_status', '!=', 'Cancelled')->whereMonth('order_date', $month)->whereYear('order_date', Carbon::now()->format('Y'))->sum('amount_paid');
			}
			$sales_arr[] = [
				'month' => $month,
				'sales' => number_format((float)$sales, 2, '.', '')
			];
		}
		$sales_year = DB::table('fumaco_order')->selectRaw('YEAR(order_date)')->distinct()->get();

		return view('backend.dashboard.index', compact('new_orders', 'total_orders', 'users', 'total_sales', 'most_searched', 'sales_arr', 'sales_year'));
	}

}
