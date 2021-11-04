<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;
use Webp;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function list_media(){
		$media = DB::table('fumaco_gallery')->get();

		return view('backend.dashboard.media_list', compact('media'));
	}

	public function delete_media_record(Request $request){
		DB::beginTransaction();
		try{
			$media = DB::table('fumaco_gallery')->where('id', $request->media_id)->first();

			$filepath = storage_path('/app/public/gallery/') . $media->mediafiles . '.' . $media->add_extension;
			$image_webp = storage_path('/app/public/gallery/') . $media->mediafiles . '.webp';

			if (file_exists($filepath)) {
				unlink($filepath);
			}

			if (file_exists($image_webp)) {
				unlink($image_webp);
			}

			DB::table('fumaco_gallery')->where('id', $request->media_id)->delete();

            DB::commit();

			return redirect()->back()->with('success', 'Media Deleted.');
		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('error', 'Error');
		}
	}

	public function add_media_form(){
		return view('backend.dashboard.media_form_add');
	}

	public function add_media_record(Request $request){
		DB::beginTransaction();
		try{
			$checker = DB::table('fumaco_gallery')->get();

			$img = $request->file('fileToUpload');

			$image_error = '';
			$rules = array(
				'uploadFile' => 'image|max:500000'
			);

			$validation = Validator::make($request->all(), $rules);

			$filename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
			$extension = pathinfo($img->getClientOriginalName(), PATHINFO_EXTENSION);

			$filename = Str::slug($filename, '-');

			$image_name = $filename.".".$extension;

			if ($validation->fails()){
				$image_error = "Sorry, your file is too large.";
				return redirect()->back()->with('image_error', $image_error);
			}

			if($extension != "jpg" and $extension != "png" and $extension != "jpeg" and $extension != "gif"  and $extension != "mp4"){
				$image_error = "Sorry, only JPG, JPEG, PNG, GIF and MP4 files are allowed.";
				return redirect()->back()->with('image_error', $image_error);
			}

			foreach($checker as $c){
				if($c->mediafiles == $image_name){
					$image_error = "Sorry, file already exists.";
					return redirect()->back()->with('image_error', $image_error);
				}

				if($c->medianame == $request->media_name){
					$image_error = "Sorry, duplicate file name.";
					return redirect()->back()->with('image_error', $image_error);
				}
			}

			$media_arr[] = [
				'medianame' => $request->media_name,
				'mediaurl' => '/app/public/gallery/',
				'mediafiles' => $filename,
				'add_extension' => $extension
			];
			
			$webp = Webp::make($request->file('fileToUpload'));

			if ($webp->save(storage_path('/app/public/gallery/'.$filename.'.webp'))) {
				// File is saved successfully
				$destinationPath = storage_path('/app/public/gallery/');
				$img->move($destinationPath, $image_name);
			}

			DB::table('fumaco_gallery')->insert($media_arr);

            DB::commit();

			return redirect()->back()->with('success', 'Media Successfully Added');
		}catch(Exception $e){
			DB::rollback();

			return redirect()->back()->with('image_error', 'Error');
		}
	}
}
