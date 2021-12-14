<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;
use Illuminate\Support\Facades\Mail;

class ProductReviewController extends Controller
{
    public function submitProductReview(Request $request) {
        DB::beginTransaction();
        try {
            $data = [
                'item_code' => $request->item_code,
                'message' => $request->message,
                'rating' => ($request->rating) ? $request->rating : 0,
                'user_email' => Auth::user()->username,
                'ip' => $request->ip(),
            ];

            DB::table('fumaco_product_review')->insert($data);

            $email_recipient = DB::table('email_config')->first();
            $email_recipient = ($email_recipient) ? explode(",", $email_recipient->email_recipients) : [];
            if (count(array_filter($email_recipient)) > 0) {
                Mail::send('emails.new_product_review', ['data' => $data], function($message) use ($email_recipient) {
                    $message->to($email_recipient);
                    $message->subject('New Product Review - FUMACO');
                });
            }
    
            DB::commit();

            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
}