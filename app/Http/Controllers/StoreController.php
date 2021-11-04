<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class StoreController extends Controller
{
    public function viewList(Request $request) {
        $stores = DB::table('fumaco_store')
            ->when($request->q, function($s) use ($request) {
                return $s->where('store_name', 'like', '%'.$request->q.'%');
            })->paginate(10);

        return view('backend.store.list', compact('stores'));
    }

    public function viewAddForm() {
        return view('backend.store.add');
    }

    public function saveStore(Request $request) {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'store_name' => 'required|unique:fumaco_store',
                    'address' => 'required',
                    'available_from' => 'required',
                    'available_to' => 'required',
                ]
            );

            $id = DB::table('fumaco_store')->insertGetId([
                'store_name' => $request->store_name,
                'address' => $request->address,
                'available_from' => Carbon::parse($request->available_from)->format('H:i'),
                'available_to' => Carbon::parse($request->available_to)->format('H:i'),
            ]);

            DB::commit();

            return redirect('/admin/store/' . $id . '/edit')->with('success', 'Store has been created.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function viewStore($id) {
        $store = DB::table('fumaco_store')->where('store_id', $id)->first();
        
        return view('backend.store.view', compact('store'));
    }

    public function updateStore($id, Request $request) {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'store_name' => 'required',
                    'address' => 'required',
                    'available_from' => 'required',
                    'available_to' => 'required',
                ]
            );

            DB::table('fumaco_store')->where('store_id', $id)->update([
                'store_name' => $request->store_name,
                'address' => $request->address,
                'available_from' => Carbon::parse($request->available_from)->format('H:i'),
                'available_to' => Carbon::parse($request->available_to)->format('H:i'),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Store has been updated.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function deleteStore($id){
        DB::beginTransaction();
        try {
            DB::table('fumaco_store')->where('store_id', $id)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Store has been deleted.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
}
