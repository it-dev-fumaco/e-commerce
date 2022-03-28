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
		$carousel_data = DB::table('fumaco_header')->get();
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

		return view('backend.dashboard.home_crud', compact('carousel_arr', 'page'));
	}

	public function add_header_carousel(Request $request){
		DB::beginTransaction();
		try{
			$checker = DB::table('fumaco_header')->get();

			$img = $request->file('fileToUpload');

			$filename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
			$extension = pathinfo($img->getClientOriginalName(), PATHINFO_EXTENSION);

			$filename = Str::slug($filename, '-');

			$image_name = $filename.".".$extension;

			$image_error = '';
			$rules = array(
				'uploadFile' => 'image|max:500000'
			);

			$validation = Validator::make($request->all(), $rules);

			if ($validation->fails()){
				$image_error = "Sorry, your file is too large.";
				return redirect()->back()->with('image_error', $image_error);
			}

			if($extension != "jpg" and $extension != "png" and $extension != "jpeg" and $extension != "gif"){
				$image_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";
				return redirect()->back()->with('image_error', $image_error);
			}

			foreach($checker as $c){
				if($c->fumaco_image1 == $image_name){
					$image_error = "Sorry, file already exists.";
					return redirect()->back()->with('image_error', $image_error);
				}
			}

			$add_carousel = [
				'fumaco_title' => $request->heading,
				'fumaco_caption' => $request->caption,
				'fumaco_url' => $request->url,
				'fumaco_btn_name' => $request->btn_name,
				'fumaco_active' => 0,
				'fumaco_status' => 1,
				'fumaco_image1' => $image_name,
				'fumaco_image2' => $image_name,
				'created_by' => Auth::user()->username,
				'last_modified_by' => Auth::user()->username,
			];

			$webp = Webp::make($request->file('fileToUpload'));

			if ($webp->save(storage_path('/app/public/journals/'.$filename.'.webp'))) {
				// File is saved successfully
				$destinationPath = storage_path('/app/public/journals/');
				$img->move($destinationPath, $img->getClientOriginalName());
			}

			$insert = DB::table('fumaco_header')->insert($add_carousel);
            DB::commit();
			return redirect()->back()->with('success', 'Record Updated Successfully.');
		}catch(Exception $e){
			DB::rollback();
		}
	}

    public function set_header_active(Request $request){
		DB::beginTransaction();
		try{
			$checker = DB::table('fumaco_header')->where('id', $request->id)->first();
			if($checker->fumaco_active == 1){
				return redirect()->back()->with('error', 'Header is already active');
			}

			if($checker->fumaco_status == 0){
				return redirect()->back()->with('error', 'Header is disabled');
			}

			$checker2 = DB::table('fumaco_header')->where('id', '!=', $request->id)->where('fumaco_active', 1)->exists();
			if($checker2){
				return redirect()->back()->with('error', 'Another item is currently active. Please remove active status of existing one.');
			}

			DB::table('fumaco_header')->where('id', $request->id)->update(['fumaco_active' => 1, 'last_modified_by' => Auth::user()->username]);
            DB::commit();
			return redirect()->back()->with('active_success', 'Record Updated Successfully');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'An error occured. Please try again');
		}
    }

	public function remove_header_active(Request $request){
		DB::beginTransaction();
		try{
			$checker = DB::table('fumaco_header')->where('id', $request->id)->first();

			if($checker->fumaco_active == 0){
				return redirect()->back()->with('remove_active', 'Header is not active');
			}

			$update = DB::table('fumaco_header')->where('id', $request->id)->update(['fumaco_active' => 0, 'last_modified_by' => Auth::user()->username]);
            DB::commit();
			return redirect()->back()->with('remove_success', 'Record Updated Successfully');
		}catch(Exception $e){
			DB::rollback();
		}
    }

	public function remove_header(Request $request){
		DB::beginTransaction();
		try{
			$delete = DB::table('fumaco_header')->where('id', $request->id)->delete();
            DB::commit();
			return redirect()->back()->with('remove_header', 'Record Updated Successfully');
		}catch(Exception $e){
			DB::rollback();
		}
    }

}
