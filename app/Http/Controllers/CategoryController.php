<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;

class CategoryController extends Controller
{
    public function viewCategories(){
        $categories = DB::table('fumaco_categories')->get();

        return view('backend.category.category', compact('categories'));
    }

    public function editCategory(Request $request, $id){
        DB::beginTransaction();
        try {
            if($request->edit_cat_slug){
                $checker = DB::table('fumaco_categories')->where('slug', $request->edit_cat_slug)->where('id', '!=', $request->id)->count();
                if($checker > 0){
                    return redirect()->back()->with('error', 'Slug must be unique.');
                }
            }
            $cat_edit = [
                'name' => $request->edit_cat_name,
                'image' => $request->edit_cat_icon,
                'slug' => $request->edit_cat_slug,
                'hide_none' => (isset($request->hide_na)) ? 1 : 0,
                'external_link' => ($request->edit_is_external_link) ? $request->external_link : null,
                'meta_keywords' => $request->cat_meta_keywords,
                'meta_description' => $request->cat_meta_desc,
                'last_modified_by' => Auth::user()->username,
            ];

            DB::table('fumaco_categories')->where('id', $id)->update($cat_edit);

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
                'code' => " ",
                'external_link' => ($request->is_external_link) ? $request->external_link : null,
                'created_by' => Auth::user()->username,
                'last_modified_by' => Auth::user()->username,
            ];
            if($request->add_cat_slug){
                $cat_slugs = DB::table('fumaco_categories')->pluck('slug');
                if(in_array($request->add_cat_slug, $cat_slugs->toArray())){
                    return redirect()->back()->with('error', 'Slug must be unique');
                }
            }

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
            $existing_item = DB::table('fumaco_items')->where('f_cat_id', $id)->exists();
            if ($existing_item) {
                return redirect()->back()->with('error', 'Cannot delete category '. $id .'. Already linked to items.');
            }
            DB::table('fumaco_categories')->where('id', $id)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Product category '. $id .' has been removed.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function sortItems(Request $request, $id){
        $search = '';
        if(request()->isMethod('post')) {
            $search = $request->q;
        }

        $items = DB::table('fumaco_items')->where('f_cat_id', $id)->where('f_idcode', 'LIKE', '%'.$search.'%')->orderBy('f_order_by', 'asc')->paginate(10);

        $category = DB::table('fumaco_categories')->where('id', $id)->first();

        $count = DB::table('fumaco_items')->where('f_cat_id', $id)->count();

        $order_no = DB::table('fumaco_items')->where('f_cat_id', $id)->select('f_order_by')->groupBy('f_order_by')->get();
        $order = collect($order_no);

        return view('backend.category.sort_items', compact('items', 'count', 'order', 'id', 'category'));
    }

    public function resetOrder($id){
        DB::beginTransaction();
        try {
            DB::table('fumaco_items')->where('f_idcode', $id)->update(['f_order_by' => 'P', 'last_modified_by' => Auth::user()->username]);
            DB::commit();
            return redirect()->back()->with('success', 'Order No. Changed.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function changeSort(Request $request, $id){
        DB::beginTransaction();
        try {
            $checker = DB::table('fumaco_items')->where('f_cat_id', $id)->where('f_order_by', $request->item_row)->get();
            // dd(count($checker));
            if(count($checker) > 0){
                return redirect()->back()->with('error', 'Sorry, order number '.$request->item_row.' is taken.');
            }
            DB::table('fumaco_items')->where('f_idcode', $request->item_code)->update(['f_order_by' => $request->item_row, 'last_modified_by' => Auth::user()->username]);
            DB::commit();
            return redirect()->back()->with('success', 'Order No. Changed.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function publishCategory(Request $request){
        DB::beginTransaction();
        try {
            DB::table('fumaco_categories')->where('id', $request->cat_id)->update(['publish' => $request->publish, 'last_modified_by' => Auth::user()->username]);
            DB::commit();
            return response()->json(['status' => 1, 'message' => 'Test']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
}
