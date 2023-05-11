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
use Exception;

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
				'btn_position' => $carousel->btn_position,
				'url' => $carousel->fumaco_url,
				'xl_img' => $carousel->fumaco_image1,
				'lg_img' => $carousel->fumaco_image3,
				'sm_img' => $carousel->fumaco_image2,
				'text-color' => $carousel->text_color,
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
			$img = $request->file('fileToUpload');
			$tablet_img = $request->file('tablet_image');
			$mobile_img = $request->file('mobile_image');

			// desktop image
			$filename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
			$extension = pathinfo($img->getClientOriginalName(), PATHINFO_EXTENSION);

			// tablet image
			$tablet_filename = pathinfo($tablet_img->getClientOriginalName(), PATHINFO_FILENAME);
			$tablet_extension = pathinfo($tablet_img->getClientOriginalName(), PATHINFO_EXTENSION);

			// mobile image
			$mobile_filename = pathinfo($mobile_img->getClientOriginalName(), PATHINFO_FILENAME);
			$mobile_extension = pathinfo($mobile_img->getClientOriginalName(), PATHINFO_EXTENSION);

			$filename = Str::slug($filename, '-');
			$tablet_filename = Str::slug($tablet_filename, '-');
			$mobile_filename = Str::slug($mobile_filename, '-');

			$image_name = $filename.".".$extension;
			$tablet_image_name = $tablet_filename.".".$tablet_extension;
			$mobile_image_name = $mobile_filename.".".$mobile_extension;

			$checker = DB::table('fumaco_header')->where('fumaco_image1', $image_name)->orWhere('fumaco_image2', $mobile_image_name)->orWhere('fumaco_image3', $tablet_image_name)->first();

			$image_error = '';
			$rules = array(
				'uploadFile' => 'image|max:500000'
			);

			$validation = Validator::make($request->all(), $rules);
			$allowed_extensions = array("jpg", "png", "jpeg", "gif");

			$extension_check = array_diff([$extension, $tablet_extension, $mobile_extension], $allowed_extensions);

			if ($validation->fails()){
				return redirect()->back()->with('error', "Sorry, your file is too large.");
			}

			if($extension_check){
				return redirect()->back()->with('error', "Sorry, only JPG, JPEG, PNG and GIF files are allowed.");
			}

			if($checker){
				$existing_img_records = [$checker->fumaco_image1, $checker->fumaco_image2, $checker->fumaco_image3];
				$existing_filename = collect(array_intersect([$extension, $tablet_extension, $mobile_extension], $existing_img_records))->first();
				$existing_filename = $existing_filename ? $existing_filename : 'file';

				return redirect()->back()->with('error', "Sorry, ".$existing_filename." already exists.");
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
				'fumaco_image3' => $tablet_image_name,
				'created_by' => Auth::user()->username,
				'last_modified_by' => Auth::user()->username
			];

			$this->uploadImage($img, $filename, $image_name); // convert desktop image
			$this->uploadImage($mobile_img, $mobile_filename, $mobile_image_name); // convert mobile image
			$this->uploadImage($tablet_img, $tablet_filename, $tablet_image_name); // convert tablet image

			DB::table('fumaco_header')->insert($add_carousel);
            DB::commit();
			return redirect()->back()->with('success', 'Record Updated Successfully.');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
	}

	public function edit_header_carousel($id, Request $request){
		DB::beginTransaction();
		try{
			$carousel = [
				'fumaco_title' => $request->heading,
				'fumaco_caption' => $request->caption,
				'text_color' => $request->text_color,
				'fumaco_url' => $request->url,
				'fumaco_btn_name' => $request->btn_name,
				'btn_position' => $request->btn_position,
				'fumaco_status' => 1,
				'last_modified_by' => Auth::user()->username,
				'last_modified_at' => Carbon::now()->toDateTimeString()
			];
			
			$checker = DB::table('fumaco_header')->select('fumaco_image1', 'fumaco_image2', 'fumaco_image3')->get();
			$allowed_extensions = array("jpg", "png", "jpeg", "gif");

			if($request->has('fileToUpload')){
				$desktop_img_check = collect($checker)->pluck('fumaco_image1');

				$img = $request->file('fileToUpload');
				// desktop image
				$filename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
				$extension = pathinfo($img->getClientOriginalName(), PATHINFO_EXTENSION);

				$filename = Str::slug($filename, '-');
				$image_name = $filename.".".$extension;

				$rules = array(
					'uploadFile' => 'image|max:500000'
				);

				$validation = Validator::make($request->all(), $rules);

				if ($validation->fails()){
					return redirect()->back()->with('error', "Sorry, your file is too large.");
				}

				if(!in_array($extension, $allowed_extensions)){
					return redirect()->back()->with('error', "Sorry, only JPG, JPEG, PNG and GIF files are allowed.");
				}

				if(in_array($image_name, $desktop_img_check->toArray())){
					return redirect()->back()->with('error', "Sorry, file already exists.");
				}

				$this->uploadImage($img, $filename, $image_name); // convert desktop image

				$carousel['fumaco_image1'] = $image_name;
			}

			if($request->has('tablet_image')){
				$tablet_img_check = collect($checker)->pluck('fumaco_image3');
	
				$tablet_img = $request->file('tablet_image');
	
				// tablet image
				$tablet_filename = pathinfo($tablet_img->getClientOriginalName(), PATHINFO_FILENAME);
				$tablet_extension = pathinfo($tablet_img->getClientOriginalName(), PATHINFO_EXTENSION);
	
				$tablet_filename = Str::slug($tablet_filename, '-');
	
				$tablet_image_name = $tablet_filename.".".$tablet_extension;
	
				$rules = array(
					'uploadFile' => 'image|max:500000'
				);
	
				$validation = Validator::make($request->all(), $rules);
	
				if ($validation->fails()){
					return redirect()->back()->with('error', "Sorry, your file is too large.");
				}
	
				if(!in_array($tablet_extension, $allowed_extensions)){
					return redirect()->back()->with('error', "Sorry, only JPG, JPEG, PNG and GIF files are allowed.");
				}
	
				if(in_array($tablet_image_name, $tablet_img_check->toArray())){
					return redirect()->back()->with('error', "Sorry, file already exists.");
				}
	
				$this->uploadImage($tablet_img, $tablet_filename, $tablet_image_name); // convert tablet image

				$carousel['fumaco_image3'] = $tablet_image_name;
			}

			if($request->has('mobile_image')){
				$mobile_img_check = collect($checker)->pluck('fumaco_image2');
	
				$mobile_img = $request->file('mobile_image');
	
				// mobile image
				$mobile_filename = pathinfo($mobile_img->getClientOriginalName(), PATHINFO_FILENAME);
				$mobile_extension = pathinfo($mobile_img->getClientOriginalName(), PATHINFO_EXTENSION);
	
				$mobile_filename = Str::slug($mobile_filename, '-');
	
				$mobile_image_name = $mobile_filename.".".$mobile_extension;
	
				$rules = array(
					'uploadFile' => 'image|max:500000'
				);
	
				$validation = Validator::make($request->all(), $rules);
	
				if ($validation->fails()){
					return redirect()->back()->with('error', "Sorry, your file is too large.");
				}
	
				if(!in_array($mobile_extension, $allowed_extensions)){
					return redirect()->back()->with('error', "Sorry, only JPG, JPEG, PNG and GIF files are allowed.");
				}
	
				if(in_array($mobile_image_name, $mobile_img_check->toArray())){
					return redirect()->back()->with('error', "Sorry, file already exists.");
				}
	
				$this->uploadImage($mobile_img, $mobile_filename, $mobile_image_name); // convert mobile image

				$carousel['fumaco_image2'] = $mobile_image_name;
			}

			DB::table('fumaco_header')->where('id', $id)->update($carousel);
            DB::commit();
			return redirect()->back()->with('success', 'Record Updated Successfully.');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again.');
		}
	}

    public function set_header_active(Request $request, $carousel_id){
		DB::beginTransaction();
		try{
			$checker = DB::table('fumaco_header')->where('id', $carousel_id)->first();
			if($checker->fumaco_status == 0){
				return response()->json(['success' => 0, 'message' => 'Header is disabled']);
			}

			DB::table('fumaco_header')->update(['fumaco_active' => 0, 'last_modified_by' => Auth::user()->username]);
			if($request->publish){
				DB::table('fumaco_header')->where('id', $carousel_id)->update(['fumaco_active' => 1, 'last_modified_by' => Auth::user()->username]);
			}
            DB::commit();
			return response()->json(['success' => 1, 'message' => 'Record Updated Successfully']);
		}catch(Exception $e){
			DB::rollback();
			return response()->json(['success' => 0, 'message' => 'An error occured. Please try again']);
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
