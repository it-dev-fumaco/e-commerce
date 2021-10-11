<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;


class CategoryController extends Controller
{
    public function viewCategories(){
        $categories = DB::table('fumaco_categories')->get();

        return view('backend.category.category', compact('categories'));
    }

    public function editCategory(Request $request, $id){
        DB::beginTransaction();
        try {
            $edit = [
                'name' => $request->edit_cat_name,
                'image' => $request->edit_cat_icon,
                'slug' => $request->edit_cat_slug
            ];

            DB::table('fumaco_categories')->where('id', $id)->update($edit);

            DB::commit();
            return redirect()->back()->with('success', 'Product category '. $request->edit_cat_name .' has been updated.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function addCategory(Request $request){
        DB::beginTransaction();
        try {
            $add = [
                'name' => $request->add_cat_name,
                'image' => $request->add_cat_icon,
                'slug' => $request->add_cat_slug,
                'code' => " "
            ];

            DB::table('fumaco_categories')->insert($add);

            DB::commit();
            return redirect()->back()->with('success', 'Product category '. $request->add_cat_name .' has been added.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function deleteCategory($id){
        DB::beginTransaction();
        try {
            DB::table('fumaco_categories')->where('id', $id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Product category '. $id .' has been removed.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function sortItems($id){
        $items = DB::table('fumaco_items')->where('f_cat_id', $id)->orderBy('f_order_by', 'asc')->paginate(10);

        return view('backend.category.sort_items', compact('items'));
    }

    public function changeSort(Request $request, $id){
        DB::beginTransaction();
        try {
            $checker = DB::table('fumaco_items')->where('f_cat_id', $id)->where('f_order_by', $request->item_row)->get();
            // dd(count($checker));
            if(count($checker) == 3){
                return redirect()->back()->with('error', 'Sorry, no more than 3 items are allowed in Row '.$request->item_row.'.');
            }
            DB::table('fumaco_items')->where('f_idcode', $request->item_code)->update(['f_order_by' => $request->item_row]);
            DB::commit();
            return redirect()->back()->with('success', 'Row Changed.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
}
