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
use Storage;
use File;
use ZipArchive;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    public function viewPages(Request $request){
        if($request->ajax()) {
            $pages = DB::table('fumaco_pages')->where('is_homepage', 0)->get();

            // return view('backend.pages.list', compact('pages'));
            return response()->json($pages);
        }
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

    public function viewContact(){
        $address = DB::table('fumaco_contact')->paginate(10);

        return view('backend.pages.list_contact', compact('address'));
    }

    public function editContactForm($id){
        $address = DB::table('fumaco_contact')->where('id', $id)->first();

        return view('backend.pages.edit_contact', compact('address'));
    }

    public function editContact(Request $request, $id){
        DB::beginTransaction();
        try {
            $checker = DB::table('fumaco_contact')->where('id', '!=', $id)->where('office_title', $request->title)->first();
            if($checker){
                return redirect()->back()->with('error', 'Office title must be unique.');
            }
            $update = [
                'office_title' => $request->title,
                'office_address' => $request->address,
                'office_phone' => $request->phone,
                'office_mobile' => $request->mobile,
                'office_email' => $request->email,
                'last_modified_by' => Auth::user()->username
            ];

            DB::table('fumaco_contact')->where('id', $id)->update($update);

            DB::commit();
            return redirect()->back()->with('success', 'Address has been updated.');
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function addContactForm(){
        return view('backend.pages.add_contact');
    }

    public function addContact(Request $request){
        DB::beginTransaction();
        try {
            $checker = DB::table('fumaco_contact')->where('office_title', $request->title)->first();
            if($checker){
                return redirect()->back()->with('error', 'Office title must be unique.');
            }

            $insert = [
                'office_title' => $request->title,
                'office_address' => $request->address,
                'office_phone' => $request->phone,
                'office_mobile' => $request->mobile,
                'office_email' => $request->email,
                'created_by' => Auth::user()->username,
                'created_at' => Carbon::now()->toDateTimeString()
            ];

            DB::table('fumaco_contact')->insert($insert);

            DB::commit();
            return redirect('/admin/pages/contact')->with('success', 'Address added.');
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function deleteContact($id){
        DB::beginTransaction();
        try {

            DB::table('fumaco_contact')->where('id', $id)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Address added.');
        } catch (Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
    
    public function viewAbout(){
        $about = DB::table('fumaco_about')->first();

        return view('backend.pages.edit_about', compact('about'));
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

    public function addSponsor(Request $request){
        DB::beginTransaction();
        try {
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

            $destinationPath = storage_path('/app/public/sponsors/');

            $sponsor_img = $request->file('sponsor_img');

            $name_first = pathinfo($sponsor_img->getClientOriginalName(), PATHINFO_FILENAME);
            $ext_first = pathinfo($sponsor_img->getClientOriginalName(), PATHINFO_EXTENSION);

            $name_first = Str::slug($name_first, '-');

            $sponsor_image_name = $name_first.".".$ext_first;

            if(!in_array($ext_first, $allowed_extensions)){
                return redirect()->back()->with('image_error', $extension_error);
            }

            $webp_first = Webp::make($request->file('sponsor_img'));

            if ($webp_first->save(storage_path('/app/public/sponsors/'.$name_first.'.webp'))) {
                $sponsor_img->move($destinationPath, $sponsor_image_name);
            }

            $insert = [
                'name_img' => $request->sponsor_name,
                'url' => $request->sponsor_url,
                'image' => $sponsor_image_name,
                'created_by' => Auth::user()->username,
                'xstatus' => 1
            ];

            DB::table('fumaco_about_partners')->insert($insert);

            DB::commit();
            return redirect()->back()->with('success', 'Sponsor Added.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function viewSponsors(){
        $sponsors = DB::table('fumaco_about_partners')->orderBy('partners_sort', 'asc')->paginate(10);

        $sponsors_count = DB::table('fumaco_about_partners')->count();

        $last_mod = DB::table('fumaco_about_partners')->orderBy('last_modified_at', 'desc')->first();

        return view('backend.pages.list_sponsors', compact('sponsors', 'sponsors_count', 'last_mod'));
    }

    public function deleteSponsor($id){
        DB::beginTransaction();
        try {
            $delete_img = DB::table('fumaco_about_partners')->where('id', $id)->first();
            $image_name = explode('.', $delete_img->image);

            unlink(storage_path('app/public/sponsors/'.$delete_img->image));
            unlink(storage_path('app/public/sponsors/'.$image_name[0].'.webp'));

            DB::table('fumaco_about_partners')->where('id', $id)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Sponsor Removed.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function updateSort(Request $request, $id){
        DB::beginTransaction();
        try {
            DB::table('fumaco_about_partners')->where('id', $id)->update(['partners_sort' => $request->item_row, 'last_modified_by' => Auth::user()->username]);
            DB::commit();
            return redirect()->back()->with('success', 'Sort Updated.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function resetSort($id){
        DB::beginTransaction();
        try {
            DB::table('fumaco_about_partners')->where('id', $id)->update(['partners_sort' => 'P', 'last_modified_by' => Auth::user()->username]);
            DB::commit();
            return redirect()->back()->with('success', 'Sort Updated.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function searchList(Request $request){
        $search_list = DB::table(DB::raw('(SELECT search_term, COUNT(*) as count FROM fumaco_search_terms where search_term LIKE "%'.$request->q.'%" GROUP BY search_term) AS subquery'))
            ->select('search_term', 'count')
            ->orderBy('count', 'desc')
            ->paginate(10);
        $search_arr = [];

        foreach($search_list as $key => $search){
            $product_results = [];
            $blog_results = [];
            $location = DB::table(DB::raw('(SELECT search_term, city, region, country, COUNT(*) as count FROM fumaco_search_terms where search_term = "'.$search->search_term.'" GROUP BY search_term, city, region, country ) AS subquery'))
				->select('search_term', 'city', 'region', 'country', 'count')
				->orderBy('count', 'desc')
				->get();

            $search_results = DB::table('fumaco_search_results')->where('search_term', $search->search_term)->orderBy('date_searched', 'desc')->first();
            
            $products = $search_results ? explode(',', $search_results->prod_results) : [];
            $blogs = $search_results ? explode(',', $search_results->blog_results) : [];
            $products_count = count($products);
            $blogs_count = count($blogs);
 
            if($products){
                foreach($products as $product){
                    $image = DB::table('fumaco_items_image_v1 as img')->where('idcode', $product)->first();
                    $details = DB::table('fumaco_items')->where('f_idcode', $product)->first();
                    $product_results[] = [
                        'image' => $image ? $image->imgprimayx : null,
                        'product_name' => $details ? $details->f_name_name : null,
                        'item_code' => $product
                    ];
                }
            }

            if($blogs){
                foreach($blogs as $blog){
                    $blog_details = DB::table('fumaco_blog')->where('id', $blog)->first();
                    $blog_results[] = [
                        'title' => $blog_details ? $blog_details->blogtitle : null
                    ];
                }
            }
            
            $search_arr[] = [
                'id' => $key + 1,
                'search_term' => $search->search_term,
                'frequency' => $search->count,
                'location' => $location,
                'product_results' => $product_results,
                'blog_results' => $blog_results,
                'results_count' => $products_count + $blogs_count,
            ];
        }

        return view('backend.search_terms', compact('search_arr', 'search_list'));
    }

	public function exportImagesView($export = 0){
        $exported_jpg = [];
        $exported_webp = [];
        $unable_to_export = [];
        $exported_images = [];
        $jpg_unable_to_export = [];
        $webp_unable_to_export = [];
        $duplicates = [];
        $now = Carbon::now();

        if($export){
            $item_images = DB::table('fumaco_items_image_v1')->where('exported', 0)->where('idcode', 'LR00402')->select('idcode', 'imgoriginalx')->get();

            if(count($item_images) == 0){
                session()->flash('error', 'All images already exported.');
                return view('backend.export_images', compact('exported_jpg', 'exported_webp', 'jpg_unable_to_export', 'webp_unable_to_export'));
            }

            if(!Storage::disk('public')->exists('/export_for_athena/jpg/')){ // check export folders
                Storage::disk('public')->makeDirectory('/export_for_athena/jpg/');
            }
    
            if(!Storage::disk('public')->exists('/export_for_athena/webp/')){ // check export folders
                Storage::disk('public')->makeDirectory('/export_for_athena/webp/');
            }

            $images = collect($item_images)->groupBy('idcode');
            $item_codes = array_keys($images->toArray());
            
            foreach($item_codes as $item_code){
                if(isset($images[$item_code])){
                    foreach($images[$item_code] as $i => $image){
                        $webp = explode('.', $image->imgoriginalx)[0].'.webp';
                        $microtime = round(microtime(true));

                        if(Storage::disk('public')->exists('/item_images/'.$image->idcode.'/gallery/original/'.$image->imgoriginalx)){ // check jpg
                            $extension = explode('.', $image->imgoriginalx)[1];
                            $athena_jpg_name = $microtime.$i.'-'.$image->idcode.'.'.$extension;
        
                            if(!Storage::disk('public')->exists('/export_for_athena/jpg/'.$athena_jpg_name)){
                                Storage::disk('public')->copy('/item_images/'.$image->idcode.'/gallery/original/'.$image->imgoriginalx, '/export_for_athena/jpg/'.$athena_jpg_name);
                                $exported_jpg[] = [
                                    'item_code' => $image->idcode,
                                    'image' => $athena_jpg_name
                                ];

                                DB::table('fumaco_items_image_v1')->where('idcode', $image->idcode)->where('imgoriginalx', $image->imgoriginalx)->update(['exported' => 1]);
                            }else{
                                array_push($duplicates, $athena_jpg_name);
                            }
                        }else{
                            array_push($jpg_unable_to_export, $image->imgoriginalx);
                        }

                        if(Storage::disk('public')->exists('/item_images/'.$image->idcode.'/gallery/original/'.$webp)){ // check webp
                            $athena_webp_name = $microtime.$i.'-'.$image->idcode.'.webp';
        
                            if(!Storage::disk('public')->exists('/export_for_athena/webp/'.$athena_webp_name)){
                                Storage::disk('public')->copy('/item_images/'.$image->idcode.'/gallery/original/'.$webp, '/export_for_athena/webp/'.$athena_webp_name);
                                array_push($exported_webp, $athena_webp_name);
                            }else{
                                array_push($duplicates, $athena_webp_name);
                            }
                        }else{
                            array_push($webp_unable_to_export, $webp);
                        }
                    }
                }
            }

            // Zip Archive the exported files
            $zip = new \ZipArchive();
            $jpg_files = File::files(storage_path('/app/public/export_for_athena/jpg'));
            $webp_files = File::files(storage_path('/app/public/export_for_athena/webp'));

            $files = collect($jpg_files)->merge($webp_files);
            if ($zip->open(storage_path('/app/public/athena_images.zip'), \ZipArchive::CREATE)== TRUE){
                foreach ($files as $key => $value){
                    $relativeName = basename($value);
                    $zip->addFile($value, $relativeName);
                }

                $zip->close();
            }

            // Delete folder after archive to save space
            Storage::disk('public')->deleteDirectory('/export_for_athena/');
        }

		return view('backend.export_images', compact('exported_jpg', 'exported_webp', 'jpg_unable_to_export', 'webp_unable_to_export'));
	}

    public function download_athena_images(){
        if(!Storage::disk('public')->exists('/athena_images.zip')){
            return redirect()->back()->with('error', 'No exported files to download');
        }
        return response()->download(storage_path('/app/public/athena_images.zip'));
    }
}
