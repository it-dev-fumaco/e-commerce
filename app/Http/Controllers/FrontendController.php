<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use DB;
use Auth;

use Illuminate\Pagination\LengthAwarePaginator;

class FrontendController extends Controller
{   
    public function signupForm() {
        return view('frontend.register');
    }

    public function index(Request $request) {
        // get sorting value 
        $sortby = $request->sortby;
        switch ($sortby) {
            case 'Price':
                $sortby = 'f_original_price';
                break;
            case 'Product Name':
                $sortby = 'f_name_name';
                break;
            default:
                $sortby = 'f_order_by';
                break;
        }

        if ($request->has('s')) {
            $orderby = ($request->order) ? $request->order : 'asc';
            $search_by = $request->by;
            $search_str = $request->s;

            $product_list = [];
            $blogs = [];
            if ($request->s == null) {
                if (in_array($search_by, ['products', 'all', ''])) {
                    $product_list = DB::table('fumaco_items')->where('f_status', 1)->where('f_featured', 1)->get();
                }

                if (in_array($search_by, ['blogs', 'all', ''])) {
                    $blogs = DB::table('fumaco_blog')->where('blog_featured', 1)
                        ->where('blog_enable', 1)->get();
                }
            } else {
                if (in_array($search_by, ['products', 'all', ''])) {
                    $product_list = DB::table('fumaco_items')
                        ->where('f_brand', 'LIKE', "%".$search_str."%")
                        ->orWhere('f_parent_code', 'LIKE', "%".$search_str."%")
                        ->orWhere('f_category', 'LIKE', "%".$search_str."%")
                        ->orWhere(function($q) use ($search_str) {
                            $search_strs = explode(" ", $search_str);
                            foreach ($search_strs as $str) {
                                $q->where('f_description', 'LIKE', "%".$str."%");
                            }
    
                            $q->orWhere('f_idcode', 'LIKE', "%".$search_str."%")
                                ->orWhere('f_item_classification', 'LIKE', "%".$search_str."%");
                        })
                        ->where('f_status', 1)->where('f_status', 1)
                        ->orderBy($sortby, $orderby)->get();
                }

                if (in_array($search_by, ['blogs', 'all', ''])) {
                    $blogs = DB::table('fumaco_blog')->where('blog_enable', 1)
                    ->where(function($q) use ($search_str) {
                        $search_strs = explode(" ", $search_str);
                        foreach ($search_strs as $str) {
                            $q->where('blogtitle', 'LIKE', "%".$str."%")
                                ->orWhere('blog_caption', 'LIKE', "%".$str."%")
                                ->orWhere('blogcontent', 'LIKE', "%".$str."%");
                        }
                    })
                    ->get();
                } 
            } 

            $results = [];
            foreach($product_list as $item){
                $image = DB::table('fumaco_items_image_v1')->where('idcode', $item->f_idcode)->first();
                $results[] = [
                    'id' => $item->id,
                    'item_code' => $item->f_idcode,
                    'item_name' => $item->f_name_name,
                    'original_price' => $item->f_original_price,
                    'is_discounted' => $item->f_discount_trigger,
                    'discounted_price' => $item->f_price,
                    'on_sale' => $item->f_onsale,
                    'discount_percent' => $item->f_discount_percent,
                    'image' => ($image) ? $image->imgprimayx : null,
                    'comment_count' => null,
                    'publish_date' => null,
                    'title' => null,
                    'type' => null,
                    'caption' => null,
                    'slug' => $item->slug
                ];
            }

            foreach($blogs as $blog){
                $blog_comment = DB::table('fumaco_comments')->where('blog_id', $blog->id)->where('blog_status', 1)->count();
                $results[] = [
                    'item_code' => null,
                    'item_name' => null,
                    'original_price' => 0,
                    'is_discounted' => 0,
                    'discounted_price' => 0,
                    'on_sale' => 0,
                    'discount_percent' => 0,
                    'id' => $blog->id,
                    'comment_count' => $blog_comment,
                    'image' => $blog->{'blogprimayimage-journal'},
                    'publish_date' => $blog->datepublish,
                    'title' => $blog->blogtitle,
                    'type' => $blog->blogtype,
                    'caption' => $blog->blog_caption,
                ];
            }

            // Get current page form url e.x. &page=1
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            // Create a new Laravel collection from the array data
            $itemCollection = collect($results);
            // Define how many items we want to be visible in each page
            $perPage = 16;
            // Slice the collection to get the items to display in current page
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            // Create our paginator and pass it to the view
            $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
            // set url path for generted links
            $paginatedItems->setPath($request->url());
            $results = $paginatedItems;

            $products = [];
            $blogs = [];
            foreach ($results as $result) {
                if($result['item_code'] != null) {
                    $products[] = [
                        'id' => $result['id'],
                        'item_code' => $result['item_code'],
                        'item_name' => $result['item_name'],
                        'original_price' => $result['original_price'],
                        'is_discounted' => $result['is_discounted'],
                        'discounted_price' => $result['discounted_price'],
                        'on_sale' => $result['on_sale'],
                        'discount_percent' => $result['discount_percent'],
                        'image' => $result['image'],
                        'slug' => $result['slug']
                    ];
                } else {
                    $blogs[] = [
                        'id' => $result['id'],
                        'comment_count' => $result['comment_count'],
                        'image' => $result['image'],
                        'publish_date' => $result['publish_date'],
                        'title' => $result['title'],
                        'type' => $result['type'],
                        'caption' => $result['caption'],
                    ];
                }
            }

            return view('frontend.search_results', compact('results', 'blogs', 'products'));
        }

        $carousel_data = DB::table('fumaco_header')->where('fumaco_status', 1)
            ->orderBy('fumaco_active', 'desc')->get();

        $blogs = DB::table('fumaco_blog')->where('blog_featured', 1)
            ->where('blog_enable', 1)->take(3)->get();
        $best_selling = DB::table('fumaco_items')->where('f_status', 1)->where('f_featured', 1)->limit(4)->get();
        $on_sale = DB::table('fumaco_items')->where('f_status', 1)->where('f_onsale', 1)->limit(4)->get();
        $best_selling_arr = [];
        $on_sale_arr = [];

        foreach($best_selling as $bs){
            $bs_img = DB::table('fumaco_items_image_v1')->where('idcode', $bs->f_idcode)->first();

            $bs_item_name = $bs->f_name_name;
            $best_selling_arr[] = [
                'id' => $bs->id,
                'item_code' => $bs->f_idcode,
                'item_name' => $bs_item_name,
                'orig_price' => $bs->f_original_price,
                'is_discounted' => $bs->f_discount_trigger,
                'new_price' => $bs->f_price,
                'discount' => $bs->f_discount_percent,
                'bs_img' => ($bs_img) ? $bs_img->imgprimayx : null,
                'slug' => $bs->slug
            ];
        }

        foreach($on_sale as $os){
            $os_img = DB::table('fumaco_items_image_v1')->where('idcode', $os->f_idcode)->first();

            $os_item_name = $os->f_name_name;
            $on_sale_arr[] = [
                'id' => $os->id,
                'item_code' => $os->f_idcode,
                'item_name' => $os_item_name,
                'orig_price' => $os->f_original_price,
                'is_discounted' => $os->f_discount_trigger,
                'new_price' => $os->f_price,
                'os_img' => ($os_img) ? $os_img->imgprimayx : null,
                'discount_percent' => $os->f_discount_percent,
                'slug' => $bs->slug
            ];
        }

        $page_meta = DB::table('fumaco_pages')->where('is_homepage', 1)->first();

        return view('frontend.homepage', compact('carousel_data', 'blogs', 'best_selling_arr', 'on_sale_arr', 'page_meta'));
    }

    public function newsletterSubscription(Request $request){
        DB::beginTransaction();
        try{
            $checker = DB::table('fumaco_subscribe')->where('email', $request->email)->count();

            if($checker > 0){
                return redirect()->back()->with('error_subscribe', 'Email already subscribed!');
            }

            $insert = [
                'email' => $request->email,
                'status' => 1,
                'ip_logs' => $request->ip()
            ];

            DB::table('fumaco_subscribe')->insert($insert);

         
            $featured_items = DB::table('fumaco_items')->where('f_status', 1)->where('f_featured', 1)->limit(4)->get();
            $featured = [];

            foreach($featured_items as $row){
                $bs_img = DB::table('fumaco_items_image_v1')->where('idcode', $row->f_idcode)->first();

                $bs_item_name = $row->f_name_name;
                $featured[] = [
                    'item_code' => $row->f_idcode,
                    'item_name' => $bs_item_name,
                    'orig_price' => $row->f_original_price,
                    'is_discounted' => $row->f_discount_trigger,
                    'new_price' => $row->f_price,
                    'discount' => $row->f_discount_percent,
                    'image' => ($bs_img) ? $bs_img->imgprimayx : null
                ];
            }

            Mail::send('emails.new_subscriber', ['featured' => $featured], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Thank you for subscribing - FUMACO');
            });

            // check for failures
            if (Mail::failures()) {
                return redirect()->back()->with('error', 'An error occured. Please try again.');
            }

            DB::commit();

            return redirect('/thankyou');
        }catch(Exception $e){
            DB::rollback();
        }
    }

    public function subscribeThankyou(){
        return view('frontend.subscribe_thankyou');
    }

    // returns an array of product category
    public function getProductCategories() {
        $item_categories = DB::table('fumaco_categories')->where('publish', 1)->get();

        return response()->json($item_categories);
    }

    public function pagesList(Request $request){
        // Policy Pages
        if($request->ajax()){
            $pages = DB::table('fumaco_pages')->select('page_title', 'slug')->where('is_homepage', 0)->get();

            return response()->json($pages);
        }
    
    }

    public function viewPage($slug){
        $pages = DB::table('fumaco_pages')->where('slug', $slug)->first();

        if(!$pages){
            return abort(404);
        }

        return view('frontend.policy_page', compact('pages'));
    }


    // get website settings
    public function websiteSettings() {
        return DB::table('fumaco_settings')->first();
    }

    public function userRegistration(Request $request){
        DB::beginTransaction();
        try{
            $user_check = DB::table('fumaco_users')->where('username', $request->username)->get();
            
            if(count($user_check) > 0){
                return redirect()->back()->with('error', 'Record not created, username already exists.');
            }

            if($request->password != $request->confirm_password){
                return redirect()->back()->with('error', 'Record not created, password/s do not match.');
            }

            $new_user = [
                'username' => trim($request->username),
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
                'f_name' => $request->first_name,
                'f_lname' => $request->last_name,
                'f_email' => 'fumacoco_dev',
                'f_temp_passcode' => 'fumaco12345'
            ];

            if(isset($request->subscribe)){
                $checker = DB::table('fumaco_subscribe')->where('email', $request->username)->count();
                if($checker == 0){
                    $newsletter = [
                        'email' => $request->username,
                        'status' => 1,
                        'ip_logs' => $request->ip()
                    ];
        
                    DB::table('fumaco_subscribe')->insert($newsletter);
                }
            }

            DB::table('fumaco_users')->insert($new_user);

            Mail::to(trim($request->username))
                ->queue(new WelcomeEmail(['username' => trim($request->username), 'password' => $request->password]));
            // check for failures
            if (Mail::failures()) {
                return redirect()->back()->with('error', 'There was a problem in user registration. Please try again.');
            }

            DB::commit();

            return redirect('/login')->with('success', 'Account successfully created. You can login now!');
        }catch(Exception $e){
            DB::rollback();
        }
    }

    public function viewAboutPage() {
        $about_data = DB::table('fumaco_about')->first();

        $partners = DB::table('fumaco_about_partners')->where('xstatus', 1)
            ->orderBy('partners_sort', 'asc')->get();

        return view('frontend.about_page', compact('about_data', 'partners'));
    }

    public function viewJournalsPage(Request $request) {
        $blog_carousel = DB::table('fumaco_blog')->where('blog_enable', 1)
            ->where('blog_featured', 1)->orderBy('blog_active', 'desc')->get();

        $blog_count = DB::table('fumaco_blog')->where('blog_enable', 1)->get();

        $app_count = DB::table('fumaco_blog')->where('blog_enable', 1)
            ->where('blogtype', 'In Applications')->get();

        $soln_count = DB::table('fumaco_blog')->where('blog_enable', 1)
            ->where('blogtype', 'Solutions')->get();

        $prod_count = DB::table('fumaco_blog')->where('blog_enable', 1)
            ->where('blogtype', 'Products')->get();

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
                'caption' => $blogs->blog_caption,
                'type' => $blogs->blogtype,
                'slug' => $blogs->slug
            ];
        }

        return view('frontend.journals', compact('blog_carousel', 'blog_count', 'app_count', 'soln_count', 'prod_count', 'blog_list', 'blogs_arr'));
    }

    public function viewBlogPage($slug) {
        $blog = DB::table('fumaco_blog')->where('slug', $slug)->orWhere('id', $slug)->first();

        $blog_comment = DB::table('fumaco_comments')->where('blog_id', $blog->id)->where('blog_type', 1)->where('blog_status', 1)->get();

        $comment_count = DB::table('fumaco_comments')->where('blog_id', $blog->id)->where('blog_status', 1)->get();

        $comments_arr = [];
        foreach($blog_comment as $comment){
            $blog_reply = DB::table('fumaco_comments')->where('blog_id', $blog->id)->where('blog_type', 2)->where('reply_id', $comment->id)->where('blog_status', 1)->get();

            $comments_arr[] = [
                'id' => $comment->id,
                'email' => $comment->blog_email,
                'name' => $comment->blog_name,
                'comment' => $comment->blog_comments,
                'reply_comment' => $blog_reply
            ];
        }

        $id = $blog->id;
        $date = Carbon::now();

        return view('frontend.blogs', compact('blog', 'comments_arr', 'id', 'date', 'comment_count'));
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

    public function viewContactPage() {
        $fumaco_contact = DB::table('fumaco_contact')->get();

        $fumaco_map = DB::table('fumaco_map_1')->first();

        return view('frontend.contact', compact('fumaco_contact', 'fumaco_map'));
    }

    public function addContact(Request $request){
        DB::beginTransaction();
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'subject' => ['required', 'string', 'max:255'],
                'comment' => ['required', 'string', 'max:255'],
                'g-recaptcha-response' => ['required',function ($attribute, $value, $fail) {
                    $secret_key = '6LfbWpwcAAAAAPKCS6T0eiS06UkINMtn5NBpde54';
                    $response = $value;
                    $userIP = $_SERVER['REMOTE_ADDR'];
                    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$response&remoteip=$userIP";
                    $response = \file_get_contents($url);
                    $response = json_decode($response);
                    if (!$response->success) {
                        $fail('ReCaptcha failed.');
                    }
                }],
            ],
            [
                'g-recaptcha-response' => [
                    'required' => 'Please check ReCaptcha.'
                ]
            ]);

            $new_contact = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->comment,
                'ip_address' => $request->ip(),
                'xstatus' => 'Sent'
            ];

            DB::table('fumaco_contact_list')->insert($new_contact);

            // send email to fumaco staff
            $email_recipient = DB::table('email_config')->first();
            $email_recipient = ($email_recipient) ? explode(",", $email_recipient->email_recipients) : [];
            if (count(array_filter($email_recipient)) > 0) {
                Mail::send('emails.new_contact', ['new_contact' => $new_contact, 'client' => 0], function($message) use ($email_recipient) {
                    $message->to($email_recipient);
                    $message->subject('New Contact - FUMACO');
                });
            }
            // send email to client 
            Mail::send('emails.new_contact', ['new_contact' => $new_contact, 'client' => 1], function($message) use ($request) {
                $message->to(trim($request->email));
                $message->subject('Contact Us - FUMACO');
            });

            // check for failures
            if (Mail::failures()) {
                return redirect()->back()->with('error', 'An error occured. Please try again.');
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Thank you for contacting us! We have recieved your message.');
        }catch(Exception $e){
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function viewProducts($category_id, Request $request) {
        if(request()->isMethod('post')) {
            $variables = [];
            if ($request->attr) {
                foreach($request->attr as $attr => $values) {
                    $variables[$attr] = implode("+", $values);
                }
            }

            $variables['sortby'] = $request->sortby;
            $variables['sel_attr'] = $request->sel_attr;

            return redirect(request()->fullUrlWithQuery($variables));
        }

        $product_category = DB::table('fumaco_categories')->where('slug', $category_id)->orWhere('id', $category_id)->first();

        if(!$product_category) {
            return view('error');
        }
        // get requested filters
        $request_data = $request->except(['page', 'sel_attr', 'sortby', 'brand', 'order']);
        $attribute_name_filter = array_keys($request_data);
        $attribute_value_filter = [];
        $brand_filter = $request->brand;
        foreach($request_data as $data) {
            foreach (explode('+', $data) as $value) {
                $attribute_value_filter[] = $value;
            }
        }

        $brand_filter = ($brand_filter) ? array_values(explode('+', $brand_filter)) : [];

        // get items based on filters
        $filtered_items = DB::table('fumaco_items as a')
            ->join('fumaco_items_attributes as b', 'a.f_idcode', 'b.idcode')
            ->join('fumaco_attributes_per_category as c', 'c.id', 'b.attribute_name_id')
            ->when(count($brand_filter) > 0, function($c) use ($brand_filter) {
                $c->whereIn('a.f_brand', $brand_filter);
            })
            ->when(count($request_data) > 0, function($c) use ($attribute_name_filter, $attribute_value_filter) {
                $c->whereIn('c.slug', $attribute_name_filter)->whereIn('b.attribute_value', $attribute_value_filter);
            })
            ->where('a.f_status', 1)->select('c.slug', 'b.attribute_value', 'a.f_idcode')->get();
            // ->pluck('a.f_idcode');

        $filtered_items = collect($filtered_items)->groupBy('f_idcode')->map(function($i, $q) use ($attribute_name_filter){
            $diff = array_diff($attribute_name_filter, array_column($i->toArray(), 'slug'));
            if (count($diff) == 0) {
                return array_column($i->toArray(), 'attribute_value');
            }
        });

        $filtered_items = array_keys(array_filter($filtered_items->toArray()));

        // get item attributes based on item category (sidebar)
        $filters = DB::table('fumaco_items as a')
            ->join('fumaco_items_attributes as b', 'a.f_idcode', 'b.idcode')
            ->join('fumaco_attributes_per_category as c', 'c.id', 'b.attribute_name_id')
            ->when(count($request_data) > 0, function($c) use ($filtered_items) {
                $c->whereIn('a.f_idcode', $filtered_items);
            })
            ->where('a.f_cat_id', $product_category->id)->where('a.f_status', 1)
            ->where('c.status', 1)->select('c.attribute_name', 'b.attribute_value')
            ->groupBy('c.attribute_name', 'b.attribute_value')->get();

        $filters = collect($filters)->groupBy('attribute_name')->map(function($r, $d){
            return array_unique(array_column($r->toArray(), 'attribute_value'));
        });

        // get distinct brands for filtering
        $brands = DB::table('fumaco_items')->where('f_cat_id', $product_category->id)
            ->when(count($request_data) > 0, function($c) use ($filtered_items) {
                $c->whereIn('f_idcode', $filtered_items);
            })
            ->where('f_status', 1)->whereNotNull('f_brand')->distinct('f_brand')->pluck('f_brand');

        $filters['Brand'] = $brands;

        if(isset($request->sel_attr)) {
            // get item attributes of selected checkbox in filters (sidebar)
            $selected_attr = DB::table('fumaco_items as a')
                ->join('fumaco_items_attributes as b', 'a.f_idcode', 'b.idcode')
                ->join('fumaco_attributes_per_category as c', 'c.id', 'b.attribute_name_id')
                ->where('a.f_cat_id', $product_category->id)->where('a.f_status', 1)
                ->where('c.slug', $request->sel_attr)
                ->where('c.status', 1)->select('c.attribute_name', 'b.attribute_value')
                ->groupBy('c.attribute_name', 'b.attribute_value')->get();

            $selected_attr = collect($selected_attr)->groupBy('attribute_name')->map(function($r, $d){
                return array_unique(array_column($r->toArray(), 'attribute_value'));
            });

            $filters = array_merge($filters->toArray(), $selected_attr->toArray());
        }

        // get sorting value 
        $sortby = $request->sortby;
        switch ($sortby) {
            case 'Price':
                $sortby = 'f_original_price';
                break;
            case 'Product Name':
                $sortby = 'f_name_name';
                break;
            default:
                $sortby = 'f_order_by';
                break;
        }

        $orderby = ($request->order) ? $request->order : 'asc';

        // get items based on category id
        $products = DB::table('fumaco_items')->where('f_cat_id', $product_category->id)
            ->when(count($request->except(['page', 'sel_attr', 'sortby', 'order'])) > 0, function($c) use ($filtered_items) {
                $c->whereIn('f_idcode', $filtered_items);
            })
            ->where('f_status', 1)->orderBy($sortby, $orderby)->paginate(15);

        $products_arr = [];
        foreach ($products as $product) {
            $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $product->f_idcode)->first();

            $item_name = strip_tags($product->f_name_name);
            $products_arr[] = [
                'id' => $product->id,
                'item_code' => $product->f_idcode,
                'item_name' => $item_name,
                'image' => ($item_image) ? $item_image->imgprimayx : null,
                'price' => $product->f_original_price,
                'discounted_price' => number_format(str_replace(",","",$product->f_price), 2),
                'is_discounted' => $product->f_discount_trigger,
                'on_sale' => $product->f_onsale,
                'discount_percent' => $product->f_discount_percent,
                'slug' => $product->slug
            ];
        }

        return view('frontend.product_list', compact('product_category', 'products_arr', 'products', 'filters'));
    }

    public function viewProduct($slug) { // Product Page
        $product_details = DB::table('fumaco_items')->where('slug', $slug)->orWhere('f_idcode', $slug)->first();
        if (!$product_details) {
            return view('error');
        }

        // get items with the same parent item code
        $variant_items = DB::table('fumaco_items')
            ->where('f_status', 1)->whereNotNull('f_parent_code')
            ->where('f_parent_code', $product_details->f_parent_code)->pluck('f_idcode');

        // get attributes of all variant items
        $variant_attributes = DB::table('fumaco_items_attributes as a')
            ->join('fumaco_attributes_per_category as c', 'c.id', 'a.attribute_name_id')
            ->whereIn('idcode', $variant_items)->where('c.status', 1)->orderBy('a.idx', 'asc')->get();
        $variant_attributes = collect($variant_attributes)->groupBy('attribute_name');

        $attrib = DB::table('fumaco_items_attributes as a')
            ->join('fumaco_attributes_per_category as c', 'c.id', 'a.attribute_name_id')
            ->where('idcode', $product_details->f_idcode);
        
        $na_check = DB::table('fumaco_categories')->where('id', $product_details->f_cat_id)->first();
     
        $attributes = $attrib->orderBy('idx', 'asc')->pluck('a.attribute_value', 'c.attribute_name');
        $filtered_attributes = $attributes;
        if($na_check->hide_none == 1){
            $filtered_attributes = $attrib->where('a.attribute_value', 'NOT LIKE', '%n/a%')->orderBy('idx', 'asc')->pluck('a.attribute_value', 'c.attribute_name');
        }

        $variant_attr_arr = [];
        if (count($variant_items) > 1) {
            foreach ($variant_attributes as $attr => $value) {
                $values = collect($value)->groupBy('attribute_value')->map(function($d, $i) {
                    return array_unique(array_column($d->toArray(), 'idcode'));
                });

                $variant_attr_arr[$attr] = $values;
            }
        }

        $product_images = DB::table('fumaco_items_image_v1')->where('idcode', $product_details->f_idcode)->get();

        $related_products_query = DB::table('fumaco_items as a')
            ->join('fumaco_items_relation as b', 'a.f_idcode', 'b.related_item_code')
            ->where('b.item_code', $product_details->f_idcode)->where('a.f_status', 1)
            ->select('a.id', 'a.f_idcode', 'a.f_original_price', 'a.f_discount_trigger', 'a.f_price', 'a.f_name_name', 'a.slug')
            ->get();

        $related_products = [];
        foreach($related_products_query as $row) {
            $image = DB::table('fumaco_items_image_v1')->where('idcode', $row->f_idcode)->first();

            $related_products[] = [
                'id' => $row->id,
                'item_code' => $row->f_idcode,
                'item_name' => $row->f_name_name,
                'orig_price' => $row->f_original_price,
                'is_discounted' => $row->f_discount_trigger,
                'new_price' => $row->f_price,
                'image' => ($image) ? $image->imgprimayx : null,
                'slug' => $row->slug
            ];
        }

        return view('frontend.product_page', compact('product_details', 'product_images', 'attributes', 'variant_attr_arr', 'related_products', 'filtered_attributes'));
    }

    public function viewWishlist() {
        $wishlist_query = DB::table('datawishlist')
            ->where('userid', Auth::user()->id)->paginate(10);

        $wishlist_arr = [];
        foreach ($wishlist_query as $wishlist) {
            $item_image = DB::table('fumaco_items_image_v1')
                ->where('idcode', $wishlist->item_code)->first();
            $wishlist_arr[] = [
                'wishlist_id' => $wishlist->id,
                'item_code' => $wishlist->item_code,
                'item_name' => $wishlist->item_name,
                'item_price' => $wishlist->item_price,
                'image' => ($item_image) ? $item_image->imgprimayx : null,
            ];
        }

        return view('frontend.wishlist', compact('wishlist_arr', 'wishlist_query'));
    }

    public function deleteWishlist($id) {
        DB::beginTransaction();
        try {
            DB::table('datawishlist')->where('id', $id)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Product has been removed from your wishlist.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function viewOrders() {
        $orders = DB::table('fumaco_order')->where('order_account', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);

        $orders_arr = [];
        $items_arr = [];
 
        foreach($orders as $order){
            $order_items = DB::table('fumaco_order_items')->where('order_number', $order->order_number)->get();
            foreach($order_items as $item){
                $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $item->item_code)->first();

                $items_arr[] = [
                    'image' => ($item_image) ? $item_image->imgprimayx : null,
                    'item_code' => $item->item_code,
                    'item_name' => $item->item_name,
                    'qty' => $item->item_qty,
                    'discount' => $item->item_discount,
                    'orig_price' => $item->item_original_price,
                    'price' => $item->item_price,
                ];
            }

            $orders_arr[] = [
                'order_number' => $order->order_number,
                'date' => date('M d, Y - h:m: A', strtotime($order->order_date)),
                'status' => $order->order_status,
                'edd' => $order->estimated_delivery_date,
                'items' => $items_arr,
                'subtotal' => $order->order_subtotal,
                'shipping_name' => $order->order_shipping,
                'shipping_fee' => $order->order_shipping_amount,
                'grand_total' => ($order->order_shipping_amount + $order->order_subtotal),
            ];
        }

        return view('frontend.orders', compact('orders', 'orders_arr'));
    }

    public function viewOrder($order_id) {
        $ordered_items = DB::table('fumaco_order_items')->where('order_number', $order_id)->get();
        $items = [];
        foreach ($ordered_items as $item) {
            $item_image = DB::table('fumaco_items_image_v1')
                ->where('idcode', $item->item_code)->first();
            $items[] = [
                'order_number' => $item->order_number,
                'item_name' => $item->item_name,
                'item_code' => $item->item_code,
                'item_price' => $item->item_price,
                'image' => ($item_image) ? $item_image->imgprimayx : null,
                'quantity' => $item->item_qty,
                'price' => $item->item_price,
                'amount' => $item->item_total_price
            ];
        }
        
        return view('frontend.view_order', compact('items'));
    }

    public function viewAccountDetails() {
        return view('frontend.profile.account_details');
    }

    public function updateAccountDetails($id, Request $request) {
        DB::beginTransaction();
        try {

            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_no' => 'required',
                'email_address' => 'required|email'
            ]);

            DB::table('fumaco_users')->where('id', $id)->update(
                [
                    'f_name' => $request->first_name,
                    'f_lname' => $request->last_name,
                    'username' => $request->email_address,
                    'f_mobilenumber' => $request->mobile_no,
                    'f_business' => $request->business_name,
                    'f_website' => $request->website,
                    'f_job_position' => $request->job_position,
                ]
            );

            DB::commit();

            return redirect()->back()->with('success', 'Account has been updated.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.'); 
        }
    }

    public function viewChangePassword() {
        return view('frontend.profile.change_password');
    }

    public function updatePassword($id, Request $request) {
        DB::beginTransaction();
        try {
            if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
                // The passwords matches
                return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
            }
    
            if(strcmp($request->get('current_password'), $request->get('new_password')) == 0){
                //Current password and new password are same
                return redirect()->back()->with("error","New password cannot be same as your current password. Please choose a different password.");
            }
    
            $validatedData = $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string|min:6|confirmed',
                'new_password_confirmation' => 'required|string'
            ]);
    
            //Change Password
             DB::table('fumaco_users')->where('id', $id)
                ->update(['password'=> Hash::make($request->new_password)]);

            DB::commit();
    
            return redirect()->back()->with("success", "Password changed successfully!");
        } catch (Exception $e) {
            DB::rollback();
    
            return redirect()->back()->with("error", "An error occured. Please try again.");
        }
    }

    public function viewAddresses() {
        $default_billing_address = DB::table('fumaco_users')
            ->where('id', Auth::user()->id)->first();

        $billing_addresses = DB::table('fumaco_user_add')
            ->where('user_idx', Auth::user()->id)->where('address_class', 'Billing')->get();

        $shipping_addresses = DB::table('fumaco_user_add')
            ->where('user_idx', Auth::user()->id)->where('address_class', 'Delivery')->get();

        return view('frontend.profile.address_list', compact('default_billing_address', 'billing_addresses', 'shipping_addresses'));
    }

    public function deleteAddress($id, $type) {
        DB::beginTransaction();
        try {
            $address_details = DB::table('fumaco_user_add')->where('id', $id)->first();
            if($address_details) {
                if ($address_details->xdefault) {
                    return redirect()->back()->with('error', 'Cannot delete default billing address.');
                }
    
                DB::table('fumaco_user_add')->where('id', $id)->delete();

                DB::commit();
            }
           
            return redirect()->back()->with('success', 'Address has been deleted.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function setDefaultAddress($id, $type) {
        DB::beginTransaction();
        try {
            $address_class = ($type == 'billing') ? 'Billing' : 'Delivery';
            $address_details = DB::table('fumaco_user_add')->where('id', $id)->first();
            if($address_details) {
                if (!$address_details->xdefault) {
                    DB::table('fumaco_user_add')->where('user_idx', Auth::user()->id)
                        ->where('id', '!=', $id)->where('address_class', $address_class)
                        ->update(['xdefault' => 0]);

                    DB::table('fumaco_user_add')->where('user_idx', Auth::user()->id)
                        ->where('id', $id)->update(['xdefault' => 1]);

                    DB::commit();

                    $address = DB::table('fumaco_user_add')->where('user_idx', Auth::user()->id)->where('xdefault', 1)->where('address_class', $address_class)->first();

                    $address_details = [
                        'fname' => $address->xcontactname1,
                        'lname' => $address->xcontactlastname1,
                        'address_line1' => $address->xadd1,
                        'address_line2' => $address->xadd2,
                        'province' => $address->xprov,
                        'city' => $address->xcity,
                        'brgy' => $address->xbrgy,
                        'postal_code' => $address->xpostal,
                        'country' => $address->xcountry,
                        'address_type' => $address->add_type,
                        'business_name' => $address->xbusiness_name,
                        'tin' => $address->xtin_no,
                        'email_address' => $address->xcontactemail1,
                        'mobile_no' => $address->xmobile_number,
                        'contact_no' => $address->xcontactnumber1,
                        'same_as_billing' => 0
                    ];

                    if($address_class == 'Delivery'){
                        session()->put('fumShipDet', $address_details);
                    }else{
                        session()->put('fumBillDet', $address_details);
                    }

                    return redirect()->back()->with('success', 'Default ' . $type .' address has been changed.');
                }
            }


            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function addAddressForm($type) {
        return view('frontend.profile.address_form', compact('type'));
    }

    public function saveAddress($type, Request $request) {
        DB::beginTransaction();
        try {
            $validator = $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'contact_no' => 'required',
                'email_address' => 'required|email',
                'address_line1' => 'required',
                'province' => 'required',
                'city' => 'required',
                'barangay' => 'required',
                'postal_code' => 'required',
                'country' => 'required',
                'address_type' => 'required',
            ]);

            $address_class = ($type == 'billing') ? 'Billing' : 'Delivery';            

            DB::table('fumaco_user_add')->insert(
                [
                    'address_class' => $address_class,
                    'xadd1' => $request->address_line1,
                    'xadd2' => $request->address_line2,
                    'xprov' => $request->province,
                    'xcity' => $request->city,
                    'xbrgy' => $request->barangay,
                    'xpostal' => $request->postal_code,
                    'xcountry' => $request->country,
                    'user_idx' => Auth::user()->id,
                    'add_type' => $request->address_type,
                    'xcontactname1' => $request->first_name,
                    'xcontactlastname1' => $request->last_name,
                    'xcontactnumber1' => $request->contact_no,
                    'xmobile_number' => $request->mobile_no,
                    'xcontactemail1' => $request->email_address,
                    'xbusiness_name' => ($request->address_type == 'Business Address') ? $request->business_name : null,
                    'xtin_no' => $request->tin_no,
                ]
            );

            DB::commit();

            return redirect('/myprofile/address')->with('success', ucfirst($type) .' address has been saved.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect('/myprofile/address')->with('error', 'An error occured. Please try again.');
        }
    }

    public function viewOrderTracking(Request $request) {
        $order_details = DB::table('fumaco_order')->where('order_number', $request->id)->first();

        $track_order_details = DB::table('track_order')->where('track_code', $request->id)->get();

        $ordered_items = DB::table('fumaco_order_items')->where('order_number', $request->id)->get();
        $items = [];
        foreach ($ordered_items as $item) {
            $item_image = DB::table('fumaco_items_image_v1')
                ->where('idcode', $item->item_code)->first();
            $items[] = [
                'order_number' => $item->order_number,
                'item_code' => $item->item_code,
                'item_name' => $item->item_name,
                'item_price' => $item->item_price,
                'image' => ($item_image) ? $item_image->imgprimayx : null,
                'quantity' => $item->item_qty,
                'price' => $item->item_price,
                'amount' => $item->item_total_price
            ];
        }

        return view('frontend.track_order', compact('order_details', 'items', 'track_order_details'));
    }

    // get item code based on variants selected in product page
    public function getVariantItemCode(Request $request) {
        $attr_collection = collect($request->attr);
        $attr_names = array_keys($attr_collection->toArray());
        $attr_values = $attr_collection->values();

        // get variant items based on $request->parent
        $variant_codes = DB::table('fumaco_items')
            ->where('f_parent_code', $request->parent)->pluck('f_idcode');

        // get attributes of all variant items
        $variant_attributes = DB::table('fumaco_items as a')
            ->join('fumaco_items_attributes as b', 'a.f_idcode', 'b.idcode')
            ->join('fumaco_attributes_per_category as c', 'c.id', 'b.attribute_name_id')
            ->whereIn('c.slug', $attr_names)->whereIn('b.attribute_value', $attr_values)
            ->where('a.f_status', 1)->where('c.status', 1)->where('a.f_parent_code', $request->parent)
            ->select('c.slug', 'b.attribute_value', 'b.idcode')
            ->orderBy('b.idx', 'asc')->get();

        // group variant attributes by item code
        $grouped_attr = collect($variant_attributes)->groupBy('idcode');
        foreach ($grouped_attr as $item_code => $values) {
            $attr_values = collect($values)->mapWithKeys(function($e){
                return [$e->slug => $e->attribute_value];
            });
            // get difference between two arrays
            $diff = array_diff_assoc($attr_collection->toArray(),$attr_values->toArray());
            if (count($diff) <= 0) {
                return $item_code;
            }
        }

        $selected_cb = $request->selected_cb;
        $item_code = DB::table('fumaco_items as a')
            ->join('fumaco_items_attributes as b', 'a.f_idcode', 'b.idcode')
            ->join('fumaco_attributes_per_category as c', 'c.id', 'b.attribute_name_id')
            ->where('a.f_parent_code', $request->parent)->where('c.slug', $selected_cb)
            ->where('b.attribute_value', $attr_collection[$selected_cb])->where('a.f_status', 1)->select('a.slug', 'a.f_idcode')->first();

        return ($item_code) ? ($item_code->slug) ? $item_code->slug : $item_code->f_idcode : $request->id;
    }
}
