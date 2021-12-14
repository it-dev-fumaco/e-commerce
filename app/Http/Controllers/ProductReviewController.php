<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

class ProductReviewController extends Controller
{
    public function submitProductReview(Request $request) {
        DB::beginTransaction();
        try {
            DB::table('fumaco_product_review')->insert([
                'item_code' => $request->item_code,
                'message' => $request->message,
                'rating' => ($request->rating) ? $request->rating : 0,
                'user_email' => Auth::user()->username,
                'ip' => $request->ip(),
            ]);
    
            DB::commit();

            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
}