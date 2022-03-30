<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Auth;
use Webp;
use DB;


class HomeCRUDController extends Controller
{
    public function home_crud(){
		$carousel_data = DB::table('fumaco_header')->paginate(10);
		$carousel_arr = [];
		foreach($carousel_data as $carousel){
			$carousel_arr[] = [
				'id' => $carousel->id,
				'title' => $carousel->fumaco_title,
				'caption' => $carousel->fumaco_caption,
				'btn_name' => $carousel->fumaco_btn_name,
				'url' => $carousel->fumaco_url,
				'lg_img' => $carousel->fumaco_image1,
				'sm_img' => $carousel->fumaco_image2,
				'is_active' => $carousel->fumaco_active == 1 ? 'primary' : '',
				'status' => $carousel->fumaco_status == 1 ? 'primary' : 'danger'
			];
		}
        $page = DB::table('fumaco_pages')->where('is_homepage', 1)->first();

		return view('backend.dashboard.home_crud', compact('carousel_data', 'carousel_arr', 'page'));
	}

	private function uploadImage(object $image, $filename, $img_name){
		$webp = Webp::make($image);

		if ($webp->save(storage_path('/app/public/journals/'.$filename.'.webp'))) {
			// File is saved successfully
			$destinationPath = storage_path('/app/public/journals/');
			$image->move($destinationPath, $img_name);
		}
	}

	public function add_header_carousel(Request $request){
		DB::beginTransaction();
		try{
			$checker = DB::table('fumaco_header')->select('fumaco_image1', 'fumaco_image2')->get();
			$desktop_img_check = collect($checker)->map(function ($check) {
				return $check->fumaco_image1;
			});

			$mobile_img_check = collect($checker)->map(function ($check) {
				return $check->fumaco_image2;
			});

			$img = $request->file('fileToUpload');
			$mobile_img = $request->file('mobile_image');

			// desktop image
			$filename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
			$extension = pathinfo($img->getClientOriginalName(), PATHINFO_EXTENSION);

			// mobile image
			$mobile_filename = pathinfo($mobile_img->getClientOriginalName(), PATHINFO_FILENAME);
			$mobile_extension = pathinfo($mobile_img->getClientOriginalName(), PATHINFO_EXTENSION);

			$filename = Str::slug($filename, '-');
			$mobile_filename = Str::slug($mobile_filename, '-');

			$image_name = $filename.".".$extension;
			$mobile_image_name = $mobile_filename.".".$mobile_extension;

			$image_error = '';
			$rules = array(
				'uploadFile' => 'image|max:500000'
			);

			$validation = Validator::make($request->all(), $rules);
			$allowed_extensions = array("jpg", "png", "jpeg", "gif");

			if ($validation->fails()){
				return redirect()->back()->with('error', "Sorry, your file is too large.");
			}

			if(!in_array($extension, $allowed_extensions) or !in_array($mobile_extension, $allowed_extensions)){
				return redirect()->back()->with('error', "Sorry, only JPG, JPEG, PNG and GIF files are allowed.");
			}

			if(in_array($image_name, $desktop_img_check->toArray()) or in_array($mobile_image_name, $mobile_img_check->toArray())){
				return redirect()->back()->with('error', "Sorry, file already exists.");
			}

			$add_carousel = [
				'fumaco_title' => $request->heading,
				'fumaco_caption' => $request->caption,
				'text_color' => $request->text_color,
				'fumaco_url' => $request->url,
				'fumaco_btn_name' => $request->btn_name,
				'btn_position' => $request->btn_position,
				'fumaco_active' => 0,
				'fumaco_status' => 1,
				'fumaco_image1' => $image_name,
				'fumaco_image2' => $mobile_image_name,
				'created_by' => Auth::user()->username,
				'last_modified_by' => Auth::user()->username,
			];

			$this->uploadImage($img, $filename, $image_name); // convert desktop image
			$this->uploadImage($mobile_img, $mobile_filename, $mobile_image_name); // convert mobile image

			DB::table('fumaco_header')->insert($add_carousel);
            DB::commit();
			return redirect()->back()->with('success', 'Record Updated Successfully.');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
	}

    public function set_header_active($carousel_id){
		DB::beginTransaction();
		try{
			$checker = DB::table('fumaco_header')->where('id', $carousel_id)->first();
			if($checker->fumaco_active == 1){
				return redirect()->back()->with('error', 'Header is already active');
			}

			if($checker->fumaco_status == 0){
				return redirect()->back()->with('error', 'Header is disabled');
			}

			$checker2 = DB::table('fumaco_header')->where('id', '!=', $carousel_id)->where('fumaco_active', 1)->exists();
			if($checker2){
				return redirect()->back()->with('error', 'Another item is currently active. Please remove active status of existing one.');
			}

			DB::table('fumaco_header')->where('id', $carousel_id)->update(['fumaco_active' => 1, 'last_modified_by' => Auth::user()->username]);
            DB::commit();
			return redirect()->back()->with('success', 'Record Updated Successfully');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again');
		}
    }

	public function remove_header_active($carousel_id){
		DB::beginTransaction();
		try{
			$checker = DB::table('fumaco_header')->where('id', $carousel_id)->first();

			if($checker->fumaco_active == 0){
				return redirect()->back()->with('error', 'Header is not active');
			}

			DB::table('fumaco_header')->where('id', $carousel_id)->update(['fumaco_active' => 0, 'last_modified_by' => Auth::user()->username]);
            DB::commit();
			return redirect()->back()->with('success', 'Record Updated Successfully');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

	public function remove_header($carousel_id){
		DB::beginTransaction();
		try{
			DB::table('fumaco_header')->where('id', $carousel_id)->delete();
            DB::commit();
			return redirect()->back()->with('success', 'Record Updated Successfully');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
    }

}
