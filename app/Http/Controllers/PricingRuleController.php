<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

class PricingRuleController extends Controller
{
    public function list(Request $request) {
        $price_rules = DB::table('fumaco_price_rule')->where('name', 'LIKE', '%'.$request->q.'%')->paginate(10);

        return view('backend.pricing_rule.list', compact('price_rules'));
    }

    public function add() {
        return view('backend.pricing_rule.add');
    }

    public function edit($id) {
        return view('backend.pricing_rule.edit');
    }

    public function delete($id, Request $request) {
        DB::beginTransaction();
        try {

            DB::table('fumaco_price_rule')->where('price_rule_id', $id)->delete();
            DB::table('fumaco_price_rule_applied_on')->where('price_rule_id', $id)->delete();
            DB::table('fumaco_price_rule_condition')->where('price_rule_id', $id)->delete();
            
            DB::commit();

            return redirect()->back()->with('success', 'Price Rule <b>'. $request->price_rule_name .'</b> has been deleted.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function updateStatus($id, Request $request) {
        DB::beginTransaction();
        try {
            DB::table('fumaco_price_rule')->where('price_rule_id', $id)->update(['enabled' => $request->status, 'last_modified_by' => Auth::user()->username]);
          
            DB::commit();

            return response()->json(['status' => 1, 'message' => 'Price Rule updated.']);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
        }
    }
}