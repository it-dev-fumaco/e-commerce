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
    public function newBlog(){
        return view('backend.blog.add');
    }

    public function addBlog(Request $request){
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
                $slug_check = DB::table('fumaco_blog')->where('slug', Str::slug($request->slug, '-'))->count();

                if($slug_check > 0){
                    return redirect()->back()->with('image_error', 'Slug must be unique');
                }
            }

            $blogs_insert = [
                'blogtitle' => $request->blog_title,
                'slug' => Str::slug($request->slug, '-'),
                'blogtype' => $request->blog_type,
                'blog_caption' => $request->blog_caption,
                'blogcontent' => $request->blog_content,
                'blogtags' => 0,
                'blog_status' => 1,
                'blogprimaryimage' => $primary_image_name,
                'blogprimayimage-mob' => $mb_image_name,
                'blogprimayimage-main' => $primary_image_name,
                'blogprimayimage-journal' => $journals_image_name,
                'blogprimayimage-home' => $home_image_name,
                'created_by' => Auth::user()->username,
                'blog_by' => Auth::user()->account_name,
                'datepublish' => Carbon::now()->format('M d, Y')
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


            DB::table('fumaco_blog')->insert($blogs_insert);
            DB::commit();
            return redirect('/admin/blog/list')->with('success', 'Blog Addded.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
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
            if(strip_tags($request->blog_content) == ''){
                return redirect()->back()->with('image_error', 'Blog content cannot be empty');
            }

            if($request->slug){
                $slug_check = DB::table('fumaco_blog')->where('id', '!=', $id)->where('slug', Str::slug($request->slug, '-'))->count();
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
                'last_modified_by' => Auth::user()->username
            ];

            DB::table('fumaco_blog')->where('id', $id)->update($blogs_update);
            DB::commit();
            return redirect()->back()->with('success', 'Blog Updated.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function editBlogImages(Request $request, $id){
        DB::beginTransaction();
        try {
            $rules = array(
				'uploadFile' => 'image|max:500000'
			);

			$validation = Validator::make($request->all(), $rules);

            if ($validation->fails()){
				$image_error = "Sorry, your file is too large.";
				return redirect()->back()->with('image_error', $image_error);
			}

            $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
            $extension_error = "Sorry, only JPG, JPEG, PNG and GIF files are allowed.";

            $destinationPath = storage_path('/app/public/journals/');

            if($request->hasFile('img_primary')){
                $img_primary = $request->file('img_primary');

                $name_primary = pathinfo($img_primary->getClientOriginalName(), PATHINFO_FILENAME);
			    $ext_primary = pathinfo($img_primary->getClientOriginalName(), PATHINFO_EXTENSION);

                $name_primary = Str::slug($name_primary, '-');

                $primary_image_name = $name_primary.".".$ext_primary;

                if(!in_array($ext_primary, $allowed_extensions)){
                    return redirect()->back()->with('image_error', $extension_error);
                }

                $webp_pr = Webp::make($request->file('img_primary'));

                if ($webp_pr->save(storage_path('/app/public/journals/'.$name_primary.'.webp'))) {
                    $img_primary->move($destinationPath, $primary_image_name);
                }

                DB::table('fumaco_blog')->where('id', $id)->update(['blogprimaryimage' => $primary_image_name, 'last_modified_by' => Auth::user()->username]);
            }

            if($request->hasFile('img_mb')){
                $img_mb = $request->file('img_mb');

                $name_mb = pathinfo($img_mb->getClientOriginalName(), PATHINFO_FILENAME);
			    $ext_mb = pathinfo($img_mb->getClientOriginalName(), PATHINFO_EXTENSION);

                $name_mb = Str::slug($name_mb, '-');
                
                $mb_image_name = $name_mb.".".$ext_mb;

                if(!in_array($ext_mb, $allowed_extensions)){
                    return redirect()->back()->with('image_error', $extension_error);
                }

                $webp_mb = Webp::make($request->file('img_mb'));

                if ($webp_mb->save(storage_path('/app/public/journals/'.$name_mb.'.webp'))) {
                    $img_mb->move($destinationPath, $mb_image_name);
                }
                
                DB::table('fumaco_blog')->where('id', $id)->update(['blogprimayimage-mob' => $mb_image_name, 'last_modified_by' => Auth::user()->username]);
            }

            if($request->hasFile('img_home')){
                $img_home = $request->file('img_home');
                
                $name_home = pathinfo($img_home->getClientOriginalName(), PATHINFO_FILENAME);
			    $ext_home = pathinfo($img_home->getClientOriginalName(), PATHINFO_EXTENSION);
                
                $name_home = Str::slug($name_home, '-');

                $home_image_name = $name_home.".".$ext_home;

                if(!in_array($ext_home, $allowed_extensions)){
                    return redirect()->back()->with('image_error', $extension_error);
                }

                $webp_hm = Webp::make($request->file('img_home'));

                if ($webp_hm->save(storage_path('/app/public/journals/'.$name_home.'.webp'))) {
                    $img_home->move($destinationPath, $home_image_name);
                }
                
                DB::table('fumaco_blog')->where('id', $id)->update(['blogprimayimage-home' => $home_image_name, 'last_modified_by' => Auth::user()->username]);
            }

            if($request->hasFile('img_journals')){
                $img_journals = $request->file('img_journals');

                $name_journals = pathinfo($img_journals->getClientOriginalName(), PATHINFO_FILENAME);
			    $ext_journals = pathinfo($img_journals->getClientOriginalName(), PATHINFO_EXTENSION);

                $name_journals = Str::slug($name_journals, '-');

                $journals_image_name = $name_journals.".".$ext_journals;

                if(!in_array($ext_journals, $allowed_extensions)){
                    return redirect()->back()->with('image_error', $extension_error);
                }

                $webp_jr = Webp::make($request->file('img_journals'));

                if ($webp_jr->save(storage_path('/app/public/journals/'.$name_journals.'.webp'))) {
                    $img_journals->move($destinationPath, $journals_image_name);
                }

                DB::table('fumaco_blog')->where('id', $id)->update(['blogprimayimage-journal' => $journals_image_name, 'last_modified_by' => Auth::user()->username]);
            }

            // check if blog has images
            $blog_check = DB::table('fumaco_blog')->where('id', $id)->where('blogprimaryimage', '!=', '')->where('blogprimayimage-mob', '!=', '')->where('blogprimayimage-home', '!=', '')->where('blogprimayimage-journal', '!=', '')->count();
            if($blog_check > 0){
                DB::table('fumaco_blog')->where('id', $id)->update(['blog_status' => 1]);
            }

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

    public function deleteBlogImage($id, $image){
        DB::beginTransaction();
        try {
            $delete_img = DB::table('fumaco_blog')->where('id', $id)->select($image)->first();
            $image_name = explode('.', $delete_img->$image);

            unlink(storage_path('app/public/journals/'.$delete_img->$image));
            unlink(storage_path('app/public/journals/'.$image_name[0].'.webp'));

            DB::table('fumaco_blog')->where('id', $id)->update([$image => null, 'blog_status' => 0, 'blog_enable' => 0, 'blog_featured' => 0]);
            DB::commit();
            return redirect()->back()->with('success', 'Image Deleted.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function setBlogActive($id){
        DB::beginTransaction();
        try {
            DB::table('fumaco_blog')->where('id', $id)->update(['blog_active' => 1, 'blog_enable' => 1, 'blog_featured' => 1]);
            DB::table('fumaco_blog')->where('id', '!=', $id)->update(['blog_active' => 0]);
            DB::commit();
            return redirect()->back()->with('success', 'Blog Updated.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function viewComments(Request $request){
        $comments = DB::table('fumaco_comments')->where('blog_email', 'LIKE', '%'.$request->q.'%')->orderBy('blog_date', 'desc')->paginate(10);

        return view('backend.blog.comments', compact('comments'));
    }

    public function commentStatus(Request $request){
        DB::beginTransaction();
        try{
            DB::table('fumaco_comments')->where('id', $request->comment_id)->update(['blog_status' => $request->approve]);
            DB::commit();
            return response()->json(['status' => 1, 'message' => 'Status Changed']);
        }catch(Exception $e){
            DB::rollback();
        }
    }

    public function deleteComment($id){
        DB::beginTransaction();
        try{
            DB::table('fumaco_comments')->where('id', $id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Comment deleted.');
        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error', 'An Error Occured. Please try again later.');
        }
    }

    public function addComment(Request $request){
        DB::beginTransaction();
        try{
            $add_comment = [
                'blog_type' => '1',
                'reply_id' => '0',
                'blog_id' => $request->idcode,
                'blog_name' => $request->fullname,
                'blog_email' => $request->fullemail,
                'blog_ip' => $request->ip(),
                'blog_comments' => $request->comment
            ];

            $insert = DB::table('fumaco_comments')->insert($add_comment);
            DB::commit();
            return redirect()->back()->with('comment_message', 'Hello! Your comment has been received, please wait for approval.');
        }catch(Exception $e){
            DB::rollback();
        }
    }

    public function addReply(Request $request){
        DB::beginTransaction();
        try{
            $add_reply = [
                'blog_type' => '2',
                'reply_id' => $request->reply_replyId,
                'blog_id' => $request->reply_blogId,
                'blog_name' => $request->reply_name,
                'blog_email' => $request->reply_email,
                'blog_ip' => $request->ip(),
                'blog_comments' => $request->reply_comment
            ];

            $insert = DB::table('fumaco_comments')->insert($add_reply);
            DB::commit();
            return redirect()->back()->with('reply_message', 'Hello! Your reply has been received, please wait for approval.');
        }catch(Exception $e){
            DB::rollback();
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
