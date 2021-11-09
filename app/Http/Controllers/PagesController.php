<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use DB;
use Carbon\Carbon;
use Auth;

class PagesController extends Controller
{
    public function viewPages(){
        $pages = DB::table('fumaco_pages')->where('is_homepage', 0)->get();

        // return view('backend.pages.list', compact('pages'));
        return response()->json($pages);
    }

    public function editForm($page_id){
        $policy = DB::table('fumaco_pages')->where('page_id', $page_id)->first();

        return view('backend.pages.edit', compact('policy'));
    }

    public function editPage($id, Request $request){
        DB::beginTransaction();
        try {
            if(strip_tags($request->content1) == ''){
                return redirect()->back()->with('error', 'Content 1 cannot be empty.');
            }
            
            $update = [
                'page_name' => $request->name,
                'page_title' => $request->title,
                'slug' => $request->slug,
                'header' => $request->header,
                'content1' => $request->content1,
                'content2' => $request->content2,
                'content3' => $request->content3,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'last_modified_by' => Auth::user()->username,
            ];

            DB::table('fumaco_pages')->where('page_id', $id)->update($update);

            DB::commit();

            return redirect()->back()->with('success', $request->name.' has been updated.');
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    
    }
}
