<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Auth;
use DB;

class PaymentMethodController extends Controller
{
    public function viewList(Request $request) {
        $list = DB::table('fumaco_payment_method')
            ->when($request->q, function ($query) use ($request) {
                return $query->where('payment_method_name', 'LIKE', "%".$request->q."%");
            })
            ->paginate(10);

        return view('backend.payment_method.list', compact('list'));
    }

    public function savePaymentMethod(Request $request) {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'payment_method_name' => 'required',
                'issuing_bank' => 'required',
            ]);

            $existing = DB::table('fumaco_payment_method')->where('payment_method_name', $request->payment_method_name)
                ->where('issuing_bank', $request->issuing_bank)->exists();

            if ($existing) {
                return redirect()->back()->with('error', 'Record already exists.');
            }

            $data = [
                'payment_method_name' => $request->payment_method_name,
                'payment_type' => $request->payment_type,
                'issuing_bank' => $request->issuing_bank,
                'remarks' => $request->remarks,
                'created_by' => Auth::user()->username
            ];

            DB::table('fumaco_payment_method')->insert($data);

            DB::commit();

            return redirect()->back()->with('success', 'Payment method has been added.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function updatePaymentMethod($id, Request $request) {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'payment_method_name' => 'required',
                'payment_type' => 'required',
                'issuing_bank' => 'required',
            ]);

            $data = [
                'payment_method_name' => $request->payment_method_name,
                'payment_type' => $request->payment_type,
                'issuing_bank' => $request->issuing_bank,
                'remarks' => $request->remarks,
                'is_enabled' => $request->is_enabled ? 1 : 0,
                'created_by' => Auth::user()->username
            ];

            DB::table('fumaco_payment_method')->where('payment_method_id', $id)->update($data);

            DB::commit();

            return redirect()->back()->with('success', 'Payment method has been updated.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function deletePaymentMethod($id) {
        DB::table('fumaco_payment_method')->where('payment_method_id', $id)->delete();

        return redirect()->back()->with('success', 'Payment method has been deleted.');
    }
}