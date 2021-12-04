<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;
use Webp;
use Illuminate\Support\Str;
use Auth;

class SocialImagesController extends Controller
{
    public function viewList(){
		$list = DB::table('fumaco_social_image')->paginate(10);

		$product_categories = DB::table('fumaco_categories')->pluck('name', 'id')->toArray();

        return view('backend.social_image.list', compact('list', 'product_categories'));
	}

    public function uploadImage(Request $request) {
        DB::beginTransaction();
		try{          
			$img = $request->file('img');

			$image_error = '';
			$rules = array(
				'img' => 'image|max:500000|unique:fumaco_social_image,filename'
			);

			$validation = Validator::make($request->all(), $rules);

			$filename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
			$extension = pathinfo($img->getClientOriginalName(), PATHINFO_EXTENSION);

			$filename = Str::slug($filename, '-');

			$image_name = $filename.".".$extension;

			if($extension != "jpg" and $extension != "png" and $extension != "jpeg" and $extension != "gif"  and $extension != "mp4"){
				$image_error = "Sorry, only JPG, JPEG, PNG, GIF and MP4 files are allowed.";

				return redirect()->back()->with('error', $image_error);
			}

			$data = [
				'filename' => $image_name,
				'page_type' => $request->page_type,
				'category_id' => $request->product_category,
				'created_by' => Auth::user()->username,
                'last_modified_by' => Auth::user()->username,
			];
			
			$webp = Webp::make($img);

			if ($webp->save(storage_path('/app/public/social_images/'.$filename.'.webp'))) {
				// File is saved successfully
				$destinationPath = storage_path('/app/public/social_images/');
				$img->move($destinationPath, $image_name);
			}

			DB::table('fumaco_social_image')->insert($data);

            DB::commit();

			return redirect()->back()->with('success', 'Image successfully added');
		}catch(Exception $e){
			DB::rollback();

			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

	public function deleteImage($id){
		DB::beginTransaction();
		try{
			$image = DB::table('fumaco_social_image')->where('id', $id)->first();

			$filepath = storage_path('/app/public/social_images/') . $image->filename;
			$image_webp = storage_path('/app/public/social_images/') . explode(".", $image->filename)[0] .'.webp';

            if ($image->filename) {
                if (file_exists($filepath)) {
                    unlink($filepath);
                }
    
                if (file_exists($image_webp)) {
                    unlink($image_webp);
                }
            }

			DB::table('fumaco_social_image')->where('id', $id)->delete();

            if($image->is_default) {
                $new_default_image = DB::table('fumaco_social_image')
					->where('page_type', $image->page_type)->where('category_id', $image->category_id)->first();
                if ($new_default_image) {
                   DB::table('fumaco_social_image')
                        ->where('id', $new_default_image->id)
                        ->update(['is_default' => 1, 'last_modified_by' => Auth::user()->username]);

                    DB::table('fumaco_social_image')->where('id', '!=', $new_default_image->id)
						->where('page_type', $image->page_type)->where('category_id', $image->category_id)
						->update(['is_default' => 0, 'last_modified_by' => Auth::user()->username]);
                }
            }

            DB::commit();

			return redirect()->back()->with('success', 'Image successfully deleted.');
		}catch(Exception $e){
			DB::rollback();

			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
	}

    public function setDefault($id) {
        DB::beginTransaction();
		try{
			$image = DB::table('fumaco_social_image')->where('id', $id)->first();
			if ($image) {
				DB::table('fumaco_social_image')
					->where('page_type', $image->page_type)->where('category_id', $image->category_id)
					->where('id', $id)->update(['is_default' => 1, 'last_modified_by' => Auth::user()->username]);

				DB::table('fumaco_social_image')->where('id', '!=', $id)
				->where('page_type', $image->page_type)->where('category_id', $image->category_id)
					->update(['is_default' => 0, 'last_modified_by' => Auth::user()->username]);

				DB::commit();
			}

            return redirect()->back();
		}catch(Exception $e){
			DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }
}
