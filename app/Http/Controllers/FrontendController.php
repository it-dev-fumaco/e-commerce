<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class FrontendController extends Controller
{
    public function index() {
        $website_settings = DB::table('fumaco_settings')->first();
        $item_categories = DB::table('fumaco_categories')->get();
        $carousel_data = DB::table('fumaco_header')->where('fumaco_status', 1)->orderBy('fumaco_active', 'desc')->get();

        $blogs = DB::table('fumaco_blog')->where('blog_featured', 1)->where('blog_enable', 1)->take(3)->get();
        $display = DB::table('fumaco_items')->where('f_status', 1);
        $best_selling = Clone $display->take(4)->get();
        $on_sale = Clone $display->where('f_onsale', 1)->take(4)->get();
        $best_selling_arr = [];
        $on_sale_arr = [];

        foreach($best_selling as $bs){
            $bs_img = DB::table('fumaco_items_image_v1')->where('idcode', $bs->f_idcode)->first();

            $best_selling_arr[] = [
                'item_code' => $bs->f_idcode,
                'item_name' => $bs->f_name_name,
                'orig_price' => $bs->f_original_price,
                'new_price' => $bs->f_price,
                'bs_img' => ($bs_img) ? $bs_img->imgprimayx : 'test.jpg'
            ];
        }

        foreach($on_sale as $os){
            $os_img = DB::table('fumaco_items_image_v1')->where('idcode', $os->f_idcode)->first();

            $on_sale_arr[] = [
                'item_code' => $os->f_idcode,
                'item_name' => $os->f_name_name,
                'orig_price' => $os->f_original_price,
                'new_price' => $os->f_price,
                'os_img' => ($os_img) ? $os_img->imgprimayx : 'test.jpg'
            ];
        }

        return view('frontend.homepage', compact('website_settings', 'item_categories', 'carousel_data', 'blogs', 'best_selling_arr', 'on_sale_arr'));
    }

    public function viewAboutPage() {
        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();

        $about_data = DB::table('fumaco_about')->first();

        $partners = DB::table('fumaco_about_partners')->where('xstatus', 1)->orderBy('partners_sort', 'asc')->get();

        return view('frontend.about_page', compact('website_settings', 'item_categories', 'about_data', 'partners'));
    }

    public function viewJournalsPage(Request $request) {
        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();

        $about_data = DB::table('fumaco_about')->first();
        
        $blog_carousel = DB::table('fumaco_blog')->where('blog_enable', 1)->where('blog_featured', 1)->orderBy('blog_active', 'desc')->get();

        $blog_count = DB::table('fumaco_blog')->where('blog_enable', 1)->get();

        $app_count = DB::table('fumaco_blog')->where('blog_enable', 1)->where('blogtype', 'In Applications')->get();

        $soln_count = DB::table('fumaco_blog')->where('blog_enable', 1)->where('blogtype', 'Solutions')->get();

        $prod_count = DB::table('fumaco_blog')->where('blog_enable', 1)->where('blogtype', 'Products')->get();

        if($request->type != ''){
            $blog_list = DB::table('fumaco_blog')->where('blog_enable', 1)->where('blogtype', $request->type)->get();
        }else{
            $blog_list = DB::table('fumaco_blog')->where('blog_enable', 1)->get();
        }

        $blogs_arr = [];
        foreach($blog_list as $blogs){
            $blog_comment = DB::table('fumaco_comments')->where('blog_id', $blogs->id)->where('blog_status', 1)->get();

            $blogs_arr[] = [
                'id' => $blogs->id,
                'comment_count' => $blog_comment->count(),
                'image' => $blogs->{'blogprimayimage-journal'},
                'publish_date' => $blogs->datepublish,
                'title' => $blogs->blogtitle,
                'type' => $blogs->blogtype
            ];
        }

        return view('frontend.journals', compact('website_settings', 'item_categories', 'about_data', 'blog_carousel', 'blog_count', 'app_count', 'soln_count', 'prod_count', 'blog_list', 'blogs_arr'));
    }

    public function viewBlogPage(Request $request) {
        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();

        $about_data = DB::table('fumaco_about')->first();

        $blog = DB::table('fumaco_blog')->where('id', $request->id)->first();

        $blog_comment = DB::table('fumaco_comments')->where('blog_id', $request->id)->where('blog_type', 1)->where('blog_status', 1)->get();

        $comments_arr = [];
        foreach($blog_comment as $comment){
            $blog_reply = DB::table('fumaco_comments')->where('blog_id', $request->id)->where('blog_type', 2)->where('reply_id', $comment->id)->where('blog_status', 1)->get();

            $comments_arr[] = [
                'id' => $comment->id,
                'email' => $comment->blog_email,
                'name' => $comment->blog_name,
                'comment' => $comment->blog_comments,
                'reply_comment' => $blog_reply
            ];
        }

        $id = $request->id;
        $date = Carbon::now();

        return view('frontend.blogs', compact('website_settings', 'item_categories', 'about_data', 'blog', 'comments_arr', 'id', 'date'));
    }

    public function addComment(Request $request){
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
        return redirect()->back()->with('comment_message', 'Hello! Your comment has been received, please wait for approval.');
    }

    public function addReply(Request $request){
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
        return redirect()->back()->with('reply_message', 'Hello! Your reply has been received, please wait for approval.');
    }

    public function viewContactPage() {
        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();

        $about_data = DB::table('fumaco_about')->first();

        $fumaco_contact = DB::table('fumaco_contact')->get();

        $fumaco_map = DB::table('fumaco_map_1')->first();

        return view('frontend.contact', compact('website_settings', 'item_categories', 'about_data', 'fumaco_contact', 'fumaco_map'));
    }

    public function addContact(Request $request){
        $new_contact = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->comment,
            'ip_address' => $request->ip(),
            'xstatus' => 'Sent'
        ];

        $to = $request->email;
        $from = 'contact@fumaco.com';
        $subject = 'Fumaco Contact Us';
        $headers = '';

        $insert = DB::table('fumaco_contact_list')->insert($new_contact);

        return redirect()->back()->with('message', 'Thank you for contacting us!. We have recieved your message.');
    }

    public function viewProducts($category_id) {
        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();

        $product_category = DB::table('fumaco_categories')->where('id', $category_id)->first();

        $products = DB::table('fumaco_items')->where('f_cat_id', $category_id)
            ->where('f_status', 1)->orderBy('f_order_by', 'asc')->paginate(15);

        $products_arr = [];
        foreach ($products as $product) {
            $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $product->f_idcode)->first();

            $item_name = strip_tags($product->f_name_name);
            if (strlen($item_name) > 70) {
                // truncate string
                $stringCut = substr($item_name, 0, 70);
                $endPoint = strrpos($stringCut, ' ');
                //if the string doesn't contain any space then it will cut without word basis.
                $item_name = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                $item_name .= '...';
            }

            $products_arr[] = [
                'item_code' => $product->f_idcode,
                'item_name' => $item_name,
                'image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg',
                'price' => $product->f_original_price,
                'discounted_price' => number_format(str_replace(",","",$product->f_price),2),
                'is_discounted' => $product->f_discount_trigger
            ];
        }

        return view('frontend.product_list', compact('website_settings', 'item_categories', 'product_category', 'products_arr', 'products'));
    }

    public function viewProduct($item_code) {
        $website_settings = DB::table('fumaco_settings')->first();

        $item_categories = DB::table('fumaco_categories')->get();

        $product_details = DB::table('fumaco_items')->where('f_idcode', $item_code)->first();

        $product_images = DB::table('fumaco_items_image_v1')->where('idcode', $item_code)->get();

        $attributes = DB::table('fumaco_items_attributes')->where('idcode', $item_code)->orderBy('idx', 'asc')->get();

        return view('frontend.product_page', compact('website_settings', 'item_categories', 'product_details', 'product_images', 'attributes'));
    }
}
