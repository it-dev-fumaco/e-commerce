<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use DB;
use Auth;
use Carbon\Carbon;
use Webp;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    public function viewPages(){
        $pages = DB::table('fumaco_pages')->where('is_thirdpage', 0)->get();

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
                'meta_keywords' => $request->meta_keywords
            ];

            DB::table('fumaco_pages')->where('page_id', $id)->update($update);

            DB::commit();

            return redirect()->back()->with('success', $request->name.' has been updated.');
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
    
    public function viewAbout(){
        $about = DB::table('fumaco_about')->first();

        $sponsors = DB::table('fumaco_about_partners')->orderBy('partners_sort', 'asc')->paginate(10);

        return view('backend.pages.edit_about', compact('about', 'sponsors'));
    }

    public function editAbout(Request $request){
        DB::beginTransaction();
        try {
            foreach($request->all() as $key => $r){
                if(strip_tags($request->$key) == ''){
                    return redirect()->back()->with('error', 'Content cannot be empty.');
                }
            }
            
            $update = [
                'title' => $request->title,
                '1_title_1' => $request->title_1_1,
                '1_caption_1' => $request->caption_1_1,
                '1_caption_2' => $request->caption_2_1,
                '1_caption_3' => $request->caption_3_1,
                '1_year_1' => $request->caption4_head1,
                '1_year_2' => $request->caption5_head1,
                '1_year_1_details' => $request->caption4_caption1,
                '1_year_2_details' => $request->caption5_caption1,
                '2_title_1' => $request->title_1_2,
                '2_caption_1' => $request->caption_1_2,
                '2_caption_2' => $request->caption_2_2,
                '2_year_1' => $request->caption4_head2,
                '2_year_1_details' => $request->caption4_caption2,
                '3_title_1' => $request->title_1_3,
                '3_caption_1' => $request->caption_1_3,
                '3_caption_2' => $request->caption_2_3,
                '3_year_1' => $request->caption4_head3,
                '3_year_1_details' => $request->caption_4_3,
                'slogan_title' => $request->slogan_title,
                '4_title_1' => $request->title_1_4,
                '4_caption_1' => $request->caption_1_4,
                'last_modified_by' => Auth::user()->username
            ];


            DB::table('fumaco_about')->update($update);

            DB::commit();

            return redirect()->back()->with('success', 'About Us page has been updated.');
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function aboutBackground(Request $request){
        DB::beginTransaction();
        try {
            // return $request->all();
            $image_error = '';

			$rules = array(
				'uploadFile' => 'image|max:500000'
			);

            $validation = Validator::make($request->all(), $rules);

            $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
            $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";

            if ($validation->fails()){
				$image_error = "Sorry, your file is too large.";
				return redirect()->back()->with('image_error', $image_error);
			}

            $destinationPath = storage_path('/app/public/about/');
            
            if($request->hasFile('first_bg')){
                $img_first = $request->file('first_bg');

                $name_first = pathinfo($img_first->getClientOriginalName(), PATHINFO_FILENAME);
			    $ext_first = pathinfo($img_first->getClientOriginalName(), PATHINFO_EXTENSION);

                $name_first = Str::slug($name_first, '-');

                $first_image_name = $name_first.".".$ext_first;

                if(!in_array($ext_first, $allowed_extensions)){
                    return redirect()->back()->with('image_error', $extension_error);
                }

                $webp_first = Webp::make($request->file('first_bg'));

                if ($webp_first->save(storage_path('/app/public/about/'.$name_first.'.webp'))) {
                    $img_first->move($destinationPath, $first_image_name);
                }

                DB::table('fumaco_about')->update(['background_1' => $first_image_name, 'last_modified_by' => Auth::user()->username]);
            }

            if($request->hasFile('second_bg')){
                $img_second = $request->file('second_bg');

                $name_second = pathinfo($img_second->getClientOriginalName(), PATHINFO_FILENAME);
                $ext_second = pathinfo($img_second->getClientOriginalName(), PATHINFO_EXTENSION);

                $name_second = Str::slug($name_second, '-');

                $second_image_name = $name_second.".".$ext_second;

                if(!in_array($ext_second, $allowed_extensions)){
                	return redirect()->back()->with('image_error', $extension_error);
                }

                $webp_second = Webp::make($request->file('second_bg'));

                if ($webp_second->save(storage_path('/app/public/about/'.$name_second.'.webp'))) {
                    $img_second->move($destinationPath, $second_image_name);
                }

                DB::table('fumaco_about')->update(['background_2' => $second_image_name, 'last_modified_by' => Auth::user()->username]);
            }

            if($request->hasFile('third_bg')){
                $img_third = $request->file('third_bg');

                $name_third = pathinfo($img_third->getClientOriginalName(), PATHINFO_FILENAME);
                $ext_third = pathinfo($img_third->getClientOriginalName(), PATHINFO_EXTENSION);

                $name_third = Str::slug($name_third, '-');

                $third_image_name = $name_third.".".$ext_third;

                if(!in_array($ext_third, $allowed_extensions)){
                    return redirect()->back()->with('image_error', $extension_error);
                }

                $webp_third = Webp::make($request->file('third_bg'));

                if ($webp_third->save(storage_path('/app/public/about/'.$name_third.'.webp'))) {
                    $img_third->move($destinationPath, $third_image_name);
                }

                DB::table('fumaco_about')->update(['background_3' => $third_image_name, 'last_modified_by' => Auth::user()->username]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'About us updated.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
}
