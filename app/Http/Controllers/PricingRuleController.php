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

    public function save(Request $request) {
        DB::beginTransaction();
        try {
            $duration = explode(' - ', $request->duration);
            $from = $request->duration ? date('Y-m-d', strtotime($duration[0])) : null;
            $to = $request->duration ? date('Y-m-d', strtotime($duration[1])) : null;

            $values = [
                'name' => $request->name,
                'apply_on' => $request->apply_on,
                'valid_from' => $from,
                'valid_to' => $to,
                'discount_type' => $request->discount_type,
                'conditions_based_on' => $request->conditions_based_on,
                'created_by' => Auth::user()->username,
                'last_modified_by' => Auth::user()->username,
                'enabled' => $request->is_enabled ? $request->is_enabled : 0,
            ];

            $price_rule_id = DB::table('fumaco_price_rule')->insertGetId($values);

            $applied_on = $request->applied_on;
            $conditions = $request->range_from;

            if (!$applied_on && in_array($request->apply_on, ['Item Code', 'Category'])) {
                return response()->json(['status' => 0, 'message' => 'Please enter at least one (1) item code / category.']);
            }

            if (!$conditions) {
                return response()->json(['status' => 0, 'message' => 'Please enter at least one (1) price rule condition.']);
            }

            $price_rule_applied_on = $price_rule_conditions = [];
            if ($applied_on) {
                foreach ($applied_on as $i => $d) {
                    $price_rule_applied_on[] = [
                        'price_rule_id' => $price_rule_id,
                        'applied_on' => $d,
                        'created_by' => Auth::user()->username,
                        'last_modified_by' => Auth::user()->username,
                    ];
                }
            }

            DB::table('fumaco_price_rule_applied_on')->insert($price_rule_applied_on);
           
            if ($conditions) {
                foreach ($conditions as $s => $r) {
                    $price_rule_conditions[] = [
                        'price_rule_id' => $price_rule_id,
                        'range_from' => $conditions[$s],
                        'range_to' => $request->range_to[$s],
                        'rate' => $request->rate[$s],
                        'created_by' => Auth::user()->username,
                        'last_modified_by' => Auth::user()->username,
                    ];
                }
            }

            DB::table('fumaco_price_rule_condition')->insert($price_rule_conditions);

            DB::commit();

            return response()->json(['status' => 1, 'message' => 'Price rule <b>'. $request->name .'</b> has been added.', 'redirectTo' => '/admin/marketing/pricing_rule/' . $price_rule_id . '/edit']);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
        }
    }

    public function edit($id) {
        $price_rule = DB::table('fumaco_price_rule')->where('price_rule_id', $id)->first();
        if (!$price_rule) {
            return redirect('/admin/marketing/pricing_rule/list');
        }

        $price_rule_applied_on = DB::table('fumaco_price_rule_applied_on')->where('price_rule_id', $id)->get();
        $price_rule_condition = DB::table('fumaco_price_rule_condition')->where('price_rule_id', $id)->get();

        $items = $categories = [];
        if ($price_rule->apply_on == 'Item Code') {
            $items = DB::table('fumaco_items')->whereIn('f_idcode', collect($price_rule_applied_on)->pluck('applied_on'))->get();
        }

        if ($price_rule->apply_on == 'Category') {
            $categories = DB::table('fumaco_categories')->whereIn('id', collect($price_rule_applied_on)->pluck('applied_on'))->get();
        }

        return view('backend.pricing_rule.edit', compact('price_rule', 'price_rule_applied_on', 'price_rule_condition', 'items', 'categories'));
    }

    public function update($id, Request $request) {
        DB::beginTransaction();
        try {
            $duration = explode(' - ', $request->duration);
            $from = $request->duration ? date('Y-m-d', strtotime($duration[0])) : null;
            $to = $request->duration ? date('Y-m-d', strtotime($duration[1])) : null;

            $values = [
                'name' => $request->name,
                'apply_on' => $request->apply_on,
                'valid_from' => $from,
                'valid_to' => $to,
                'discount_type' => $request->discount_type,
                'conditions_based_on' => $request->conditions_based_on,
                'last_modified_by' => Auth::user()->username,
                'enabled' => $request->is_enabled ? $request->is_enabled : 0,
            ];

            DB::table('fumaco_price_rule')->where('price_rule_id', $id)->update($values);

            $applied_on = $request->applied_on;
            $conditions = $request->range_from;

            $new_applied_on = $request->new_applied_on;
            $new_conditions = $request->new_range_from;

            $applied_on = is_array($applied_on) ? $applied_on : [];
            $conditions = is_array($conditions) ? $conditions : [];

            $new_applied_on = $request->new_applied_on;
            $new_conditions = $request->new_range_from;

            if ($request->old_apply_on == $request->apply_on) {
                // delete removed rows in applied on
                DB::table('fumaco_price_rule_applied_on')->where('price_rule_id', $id)
                    ->whereNotIn('price_rule_applied_on_id', array_keys($applied_on))->delete();
            } else {
                DB::table('fumaco_price_rule_applied_on')->where('price_rule_id', $id)->delete();
            }

            // delete removed rows in conditions
            DB::table('fumaco_price_rule_condition')->where('price_rule_id', $id)
                ->whereNotIn('price_rule_condition_id', array_keys($conditions))->delete();

            if (in_array($request->apply_on, ['Item Code', 'Category'])) {
                if (!$applied_on && !$new_applied_on) {
                    return response()->json(['status' => 0, 'message' => 'Please enter at least one (1) item code / category.']);
                }
            }

            if (!$conditions && !$new_conditions) {
                return response()->json(['status' => 0, 'message' => 'Please enter at least one (1) price rule condition.']);
            }

            foreach ($applied_on as $price_rule_applied_on_id => $d) {
                DB::table('fumaco_price_rule_applied_on')->where('price_rule_applied_on_id', $price_rule_applied_on_id)
                    ->update([
                        'applied_on' => $d,
                        'last_modified_by' => Auth::user()->username,
                    ]);
            }

            foreach ($conditions as $price_rule_condition_id => $s) {
                DB::table('fumaco_price_rule_condition')->where('price_rule_condition_id', $price_rule_condition_id)
                    ->update([
                        'range_from' => $request->range_from[$price_rule_condition_id],
                        'range_to' => $request->range_to[$price_rule_condition_id],
                        'rate' => $request->rate[$price_rule_condition_id],
                        'last_modified_by' => Auth::user()->username,
                    ]);
            }

            // insert new rows
            $price_rule_applied_on = $price_rule_conditions = [];
            if ($new_applied_on) {
                foreach ($new_applied_on as $i => $d) {
                    $price_rule_applied_on[] = [
                        'price_rule_id' => $id,
                        'applied_on' => $d,
                        'created_by' => Auth::user()->username,
                        'last_modified_by' => Auth::user()->username,
                    ];
                }
            }

            DB::table('fumaco_price_rule_applied_on')->insert($price_rule_applied_on);
           
            if ($new_conditions) {
                foreach ($new_conditions as $s => $r) {
                    $price_rule_conditions[] = [
                        'price_rule_id' => $id,
                        'range_from' => $new_conditions[$s],
                        'range_to' => $request->new_range_to[$s],
                        'rate' => $request->new_rate[$s],
                        'created_by' => Auth::user()->username,
                        'last_modified_by' => Auth::user()->username,
                    ];
                }
            }

            DB::table('fumaco_price_rule_condition')->insert($price_rule_conditions);

            DB::commit();

            return response()->json(['status' => 1, 'message' => 'Price rule <b>'. $request->name .'</b> has been updated.']);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['status' => 0, 'message' => 'An error occured. Please try again.']);
        }
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