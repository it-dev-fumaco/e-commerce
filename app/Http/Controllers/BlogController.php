<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;
use Webp;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function viewBlogs(Request $request){
        $blogs = DB::table('fumaco_blog')->orderby('blog_featured', 'desc')->paginate(10);        
        return view('backend.blog.list', compact('blogs'));
    }

    public function publishBlog(Request $request){
        DB::beginTransaction();
        try {
            DB::table('fumaco_blog')->where('id', $request->blog_id)->update(['blog_enable' => $request->publish]);
            DB::commit();
            return response()->json(['status' => 1]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function featuredBlog(Request $request){
        DB::beginTransaction();
        try {
            DB::table('fumaco_blog')->where('id', $request->blog_id)->update(['blog_featured' => $request->feature]);
            DB::commit();
            return response()->json(['status' => 1]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function editBlogForm($id){
        $blog = DB::table('fumaco_blog')->where('id', $id)->first();
        return view('backend.blog.edit', compact('blog', 'id'));
    }

    public function editBlog(Request $request, $id){
        DB::beginTransaction();
        try {
            $img_primary = $request->file('img_primary');
            $img_mb = $request->file('img_mb');
            $img_home = $request->file('img_home');
            $img_journals = $request->file('img_journals');

			$image_error = '';

			$rules = array(
				'uploadFile' => 'image|max:500000'
			);
            
            $name_primary = pathinfo($img_primary->getClientOriginalName(), PATHINFO_FILENAME);
			$ext_primary = pathinfo($img_primary->getClientOriginalName(), PATHINFO_EXTENSION);

            $name_mb = pathinfo($img_mb->getClientOriginalName(), PATHINFO_FILENAME);
			$ext_mb = pathinfo($img_mb->getClientOriginalName(), PATHINFO_EXTENSION);

            $name_home = pathinfo($img_home->getClientOriginalName(), PATHINFO_FILENAME);
			$ext_home = pathinfo($img_home->getClientOriginalName(), PATHINFO_EXTENSION);

            $name_journals = pathinfo($img_journals->getClientOriginalName(), PATHINFO_FILENAME);
			$ext_journals = pathinfo($img_journals->getClientOriginalName(), PATHINFO_EXTENSION);

			$name_primary = Str::slug($name_primary, '-');
			$name_mb = Str::slug($name_mb, '-');
			$name_home = Str::slug($name_home, '-');
			$name_journals = Str::slug($name_journals, '-');

			$primary_image_name = $name_primary.".".$ext_primary;
			$mb_image_name = $name_mb.".".$ext_mb;
			$home_image_name = $name_home.".".$ext_home;
			$journals_image_name = $name_journals.".".$ext_journals;

			$validation = Validator::make($request->all(), $rules);

            if ($validation->fails()){
				$image_error = "Sorry, your file is too large.";
				return redirect()->back()->with('image_error', $image_error);
			}

            $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');

            if(!in_array($ext_primary, $allowed_extensions) or !in_array($ext_mb, $allowed_extensions) or !in_array($ext_home, $allowed_extensions) or !in_array($ext_journals, $allowed_extensions)){
                $image_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";
				return redirect()->back()->with('image_error', $image_error);
            }

            if(strip_tags($request->blog_content) == ''){
                $image_error = "Sorry, blog content cannot be empty.";
				return redirect()->back()->with('image_error', $image_error);
            }

            if($request->slug){
                $slug_check = DB::table('fumaco_blog')->where('id', '!=', $id)->where('slug', $request->slug)->count();
                if($slug_check > 0){
                    $image_error = "Sorry, slug must be unique.";
				    return redirect()->back()->with('image_error', $image_error);
                }
            }

            $blogs_update = [
                'blogtitle' => $request->blog_title,
                'slug' => Str::slug($request->slug, '-'),
                'blogtype' => $request->blog_type,
                'blog_caption' => $request->blog_caption,
                'blogcontent' => $request->blog_content,
                'blogtags' => 0,
                'blogprimaryimage' => $primary_image_name,
                'blogprimayimage-mob' => $mb_image_name,
                'blogprimayimage-main' => $primary_image_name,
                'blogprimayimage-journal' => $journals_image_name,
                'blogprimayimage-home' => $home_image_name,
                'last_modified_by' => Auth::user()->username
            ];

            $webp_pr = Webp::make($request->file('img_primary'));
            $webp_mb = Webp::make($request->file('img_mb'));
            $webp_hm = Webp::make($request->file('img_home'));
            $webp_jr = Webp::make($request->file('img_journals'));

            $destinationPath = storage_path('/app/public/journals/');

			if ($webp_pr->save(storage_path('/app/public/journals/'.$name_primary.'.webp'))) {
				$img_primary->move($destinationPath, $primary_image_name);
			}

            if ($webp_mb->save(storage_path('/app/public/journals/'.$name_mb.'.webp'))) {
				$img_mb->move($destinationPath, $mb_image_name);
			}

            if ($webp_hm->save(storage_path('/app/public/journals/'.$name_home.'.webp'))) {
				$img_home->move($destinationPath, $home_image_name);
			}

            if ($webp_jr->save(storage_path('/app/public/journals/'.$name_journals.'.webp'))) {
				$img_journals->move($destinationPath, $journals_image_name);
			}

            DB::table('fumaco_blog')->where('id', $id)->update($blogs_update);
            DB::commit();
            return redirect()->back()->with('success', 'Blog Updated.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function deleteBlog($id){
        DB::beginTransaction();
        try {
            DB::table('fumaco_blog')->where('id', $id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Blog Deleted.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function setBlogActive($id){
        DB::beginTransaction();
        try {
            DB::table('fumaco_blog')->where('id', $id)->update(['blog_active' => 1]);
            DB::table('fumaco_blog')->where('id', '!=', $id)->update(['blog_active' => 0]);
            DB::commit();
            return redirect()->back()->with('success', 'Blog Updated.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function viewSubscribers(Request $request){
        $email_str = $request->email;
        $subscribers = DB::table('fumaco_subscribe')->where('email', 'LIKE', '%'.$email_str.'%')->paginate(10);
        
        $subs_arr = [];
        foreach($subscribers as $sub){
            $users = DB::table('fumaco_users')->get();
            foreach($users as $user){
                $membership_status = $user->username == $sub->email ? 'Member' : 'Guest';
            }  
            
            $subs_arr[] = [
                'id' => $sub->id,
                'email' => $sub->email,
                'status' => $sub->status,
                'membership_status' => $membership_status
            ];
        }

        return view('backend.blog.subscribers', compact('subscribers', 'subs_arr'));
    }

    public function subscriberChangeStatus(Request $request){
        DB::beginTransaction();
        try {
            DB::table('fumaco_subscribe')->where('id', $request->sub_id)->update(['status' => $request->status]);
            DB::commit();
            return response()->json(['status' => 1, 'message' => 'Status Changed']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }
}
