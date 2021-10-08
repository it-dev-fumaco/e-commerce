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
}
