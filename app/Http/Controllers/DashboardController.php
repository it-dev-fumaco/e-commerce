<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

class DashboardController extends Controller
{
	public function index() {
		$name = Auth::user()->account_name;
		return view('backend.dashboard.index', compact('name'));
	}

}
