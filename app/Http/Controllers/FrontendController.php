<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use DB;
use Auth;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Support\Str;
use Adrianorosa\GeoLocation\GeoLocation;

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
                        ->orWhere('f_name_name', 'LIKE', "%".$search_str."%")
                        ->orWhere(function($q) use ($search_str) {
                            $search_strs = explode(" ", $search_str);
                            foreach ($search_strs as $str) {
                                $q->where('f_description', 'LIKE', "%".$str."%");
                            }
    
                            $q->orWhere('f_idcode', 'LIKE', "%".$search_str."%")
                                ->orWhere('f_item_classification', 'LIKE', "%".$search_str."%")
                                ->orWhere('keywords', 'LIKE', '%'.$search_str.'%');
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

                $is_new_item = 0;
                if($item->f_new_item == 1){
                    if($item->f_new_item_start <= Carbon::now() and $item->f_new_item_end >= Carbon::now()){
                        $is_new_item = 1;
                    }
                }

                $results[] = [
                    'id' => $item->id,
                    'item_code' => $item->f_idcode,
                    'item_name' => $item->f_name_name,
                    'category' => $item->f_category,
                    'category_id' => $item->f_cat_id,
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
                    'slug' => $item->slug,
                    'f_qty' => $item->f_qty,
                    'f_reserved_qty' => $item->f_reserved_qty,
                    'is_new_item' => $is_new_item
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
                    'blog_slug' => $blog->slug,
                    'f_qty' => 0,
                    'f_reserved_qty' => 0
                ];
            }

            $searched_item_codes = collect($results)->whereNotNull('item_code')->pluck('item_code');

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

            $all_item_discount = DB::table('fumaco_on_sale')->whereDate('start_date', '<=', Carbon::now()->toDateString())->whereDate('end_date', '>=', Carbon::now()->toDateString())->where('status', 1)->where('apply_discount_to', 'All Items')->first();
            
            foreach ($results as $result) {
                if($result['item_code'] != null) {
                    $on_stock = ($result['f_qty'] - $result['f_reserved_qty']) > 0 ? 1 : 0;

                    $category_discount = DB::table('fumaco_on_sale as sale')->join('fumaco_on_sale_categories as cat_sale', 'sale.id', 'cat_sale.sale_id')->whereDate('sale.start_date', '<=', Carbon::now())->whereDate('sale.end_date', '>=', Carbon::now())->where('status', 1)->where('cat_sale.category_id', $result['category_id'])->first();

                    $product_price = $result['original_price'];
                    $discount_from_sale = 0;
                    $sale_discount_rate = null;
                    $sale_discount_type = null;
                    if($all_item_discount){
                        $discount_from_sale = 1;
                        $sale_discount_rate = $all_item_discount->discount_rate;
                        $sale_discount_type = $all_item_discount->discount_type;
                        if($all_item_discount->discount_type == 'By Percentage'){
                            $product_price = $result['original_price'] - ($result['original_price'] * ($all_item_discount->discount_rate/100));
                        }else if($all_item_discount->discount_type == 'Fixed Amount'){
                            $discount_from_sale = 0;
                            if($result['original_price'] > $all_item_discount->discount_rate){
                                $discount_from_sale = 1;
                                $product_price = $result['original_price'] - $all_item_discount->discount_rate;
                            }
                        }
                    }else if($category_discount){
                        $discount_from_sale = 1;
                        $sale_discount_rate = $category_discount->discount_rate;
                        $sale_discount_type = $category_discount->discount_type;
                        if($category_discount->discount_type == 'By Percentage'){
                            $product_price = $result['original_price'] - ($result['original_price'] * ($category_discount->discount_rate/100));
                        }else if($category_discount->discount_type == 'Fixed Amount'){
                            $discount_from_sale = 0;
                            if($result['original_price'] > $category_discount->discount_rate){
                                $discount_from_sale = 1;
                                $product_price = $result['original_price'] - $category_discount->discount_rate;
                            }
                        }
                    }

                    $product_review_per_code = DB::table('fumaco_product_review')
                        ->where('status', '!=', 'pending')->where('item_code', $result['item_code'])->get();

                    $products[] = [
                        'id' => $result['id'],
                        'item_code' => $result['item_code'],
                        'item_name' => $result['item_name'],
                        'original_price' => $result['original_price'],
                        'is_discounted' => $result['is_discounted'],
                        'is_discounted_from_sale' => $discount_from_sale,
                        'sale_discounted_price' => $product_price,
                        'sale_discount_rate' => $sale_discount_rate,
                        'sale_discount_type' => $sale_discount_type,
                        'discounted_price' => $result['discounted_price'],
                        'on_sale' => $result['on_sale'],
                        'discount_percent' => $result['discount_percent'],
                        'image' => $result['image'],
                        'slug' => $result['slug'],
                        'is_new_item' => $result['is_new_item'],
                        'on_stock' => $on_stock,
                        'product_reviews' => $product_review_per_code
                    ];
                } else {
                    $blogs[] = [
                        'id' => $result['id'],
                        'comment_count' => $result['comment_count'],
                        'image' => $result['image'],
                        'blog_slug' => $result['blog_slug'],
                        'publish_date' => $result['publish_date'],
                        'title' => $result['title'],
                        'type' => $result['type'],
                        'caption' => $result['caption'],
                    ];
                }
            }
            $recently_added_arr = [];
            if($request->s != ''){// Save search terms
                $loc = GeoLocation::lookup($request->ip());

                $search_data = [
                    'search_term' => $request->s,
                    'ip' => $request->ip(),
                    'city' => $loc->getCity(),
                    'region' => $loc->getRegion(),
                    'country' => $loc->getCountry(),
                    'latitude' => $loc->getLatitude(),
                    'longtitude' => $loc->getLongitude(),
                    'date_last_searched' => Carbon::now()
                ];

                $search_results_data = [
                    'search_term' => $request->s,
                    'date_searched' => Carbon::now()
                ];

                $item_code_array = [];
                $blog_id_array = [];
                $prod_results = null;
                $blog_results = null;

                if($products){
                    $item_code_array = collect($products)->map(function($result){
                        return $result['item_code'];
                    });

                    $item_codes = $item_code_array->sort()->values()->all();
                    $prod_results = collect($item_codes)->implode(',');

                    $search_data['prod_results_count'] = count($products);
                    $search_results_data['prod_results'] = $prod_results;
                }
                
                if($blogs){
                    $blog_id_array = collect($blogs)->map(function($result){
                        return $result['id'];
                    });

                    $blog_ids = $blog_id_array->sort()->values()->all();
                    $blog_results = collect($blog_ids)->implode(',');

                    $search_data['blog_results_count'] = count($blogs);
                    $search_results_data['blog_results'] = $blog_results;
                }

                DB::table('fumaco_search_terms')->insert($search_data);

                $search_id = DB::table('fumaco_search_terms')->orderBy('id', 'desc')->pluck('id')->first();

                $search_results_data['search_id'] = $search_id;
                $checker = DB::table('fumaco_search_results')->where('search_term', $search_data['search_term'])->where('prod_results', $prod_results)->where('blog_results', $blog_results)->get();

                if(count($checker) == 0){
                    DB::table('fumaco_search_results')->insert($search_results_data);
                }

                $recently_added_items = DB::table('fumaco_items')->whereNotIn('f_idcode', $searched_item_codes)->where('f_status', 1)->where('f_new_item', 1)->whereDate('f_new_item_start', '<=', Carbon::now())->whereDate('f_new_item_end', '>=', Carbon::now())->get();

                $recently_added_arr = [];
                foreach($recently_added_items as $recent){
                    $image = DB::table('fumaco_items_image_v1')->where('idcode', $recent->f_idcode)->pluck('imgprimayx')->first();
                    $product_reviews = DB::table('fumaco_product_review')->where('status', '!=', 'pending')->where('item_code', $recent->f_idcode)->get();

                    $recent_category_discount = DB::table('fumaco_on_sale as sale')->join('fumaco_on_sale_categories as cat_sale', 'sale.id', 'cat_sale.sale_id')->whereDate('sale.start_date', '<=', Carbon::now())->whereDate('sale.end_date', '>=', Carbon::now())->where('status', 1)->where('cat_sale.category_id', $recent->f_cat_id)->first();

                    $recent_product_price = null;
                    $recent_discount_from_sale = 0;
                    $recent_sale_discount_rate = null;
                    $recent_sale_discount_type = null;
                    if($all_item_discount){
                        $recent_discount_from_sale = 1;
                        $recent_sale_discount_rate = $all_item_discount->discount_rate;
                        $recent_sale_discount_type = $all_item_discount->discount_type;
                        if($all_item_discount->discount_type == 'By Percentage'){
                            $recent_product_price = $recent->f_original_price - ($recent->f_original_price * ($all_item_discount->discount_rate/100));
                        }else if($all_item_discount->discount_type == 'Fixed Amount'){
                            $recent_discount_from_sale = 0;
                            if($recent->f_original_price > $all_item_discount->discount_rate){
                                $recent_discount_from_sale = 1;
                                $recent_product_price = $recent->f_original_price - $all_item_discount->discount_rate;
                            }
                        }
                    }else if($recent_category_discount){
                        $recent_discount_from_sale = 1;
                        $recent_sale_discount_rate = $recent_category_discount->discount_rate;
                        $recent_sale_discount_type = $recent_category_discount->discount_type;
                        if($recent_category_discount->discount_type == 'By Percentage'){
                            $recent_product_price = $recent->f_original_price - ($recent->f_original_price * ($recent_category_discount->discount_rate/100));
                        }else if($recent_category_discount->discount_type == 'Fixed Amount'){
                            $recent_discount_from_sale = 0;
                            if($recent->f_original_price > $recent_category_discount->discount_rate){
                                $recent_discount_from_sale = 1;
                                $recent_product_price = $recent->f_original_price - $recent_category_discount->discount_rate;
                            }
                        }
                    }

                    $recently_added_arr[] = [
                        'id' => $recent->id,
                        'item_code' => $recent->f_idcode,
                        'item_name' => $recent->f_name_name,
                        'orig_price' => $recent->f_original_price,
                        'sale_discounted_price' => $recent_product_price,
                        'sale_discount_rate' => $recent_sale_discount_rate,
                        'sale_discount_type' => $recent_sale_discount_type,
                        'is_discounted' => $recent->f_discount_trigger,
                        'is_discounted_from_sale' => $recent_discount_from_sale,
                        'on_stock' => $recent->f_qty - $recent->f_reserved_qty > 0 ? 1 : 0,
                        'new_price' => $recent->f_price,
                        'discount' => $recent->f_discount_percent,
                        'image' => $image,
                        'slug' => $recent->slug,
                        'is_new_item' => $recent->f_new_item,
                        'product_reviews' => $product_reviews
                    ];
                }
            }

            return view('frontend.search_results', compact('results', 'blogs', 'products', 'recently_added_arr'));
        }

        $carousel_data = DB::table('fumaco_header')->where('fumaco_status', 1)->orderBy('fumaco_active', 'desc')->get();
        $onsale_carousel_data = DB::table('fumaco_on_sale')->where('status', 1)->where('banner_image', '!=', null)->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())->get();
        // return $onsale_carousel_data;

        $blogs = DB::table('fumaco_blog')->where('blog_featured', 1)
            ->where('blog_enable', 1)->take(3)->get();
        $best_selling = DB::table('fumaco_items')->where('f_status', 1)->where('f_featured', 1)->get();
        $on_sale = DB::table('fumaco_items')->where('f_status', 1)->where('f_onsale', 1)->get();
        $best_selling_arr = [];
        $on_sale_arr = [];

        $bs_all_item_discount = DB::table('fumaco_on_sale')->whereDate('start_date', '<=', Carbon::now()->toDateString())->whereDate('end_date', '>=', Carbon::today()->toDateString())->where('status', 1)->where('apply_discount_to', 'All Items')->first();

        foreach($best_selling as $bs){
            $bs_img = DB::table('fumaco_items_image_v1')->where('idcode', $bs->f_idcode)->first();

            $is_new_item = 0;
            if($bs->f_new_item == 1){
                if($bs->f_new_item_start <= Carbon::now() and $bs->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }

            $bs_all_item_discount = DB::table('fumaco_on_sale')->whereDate('start_date', '<=', Carbon::now()->toDateString())->whereDate('end_date', '>=', Carbon::today()->toDateString())->where('status', 1)->where('apply_discount_to', 'All Items')->first();
            $bs_category_discount = DB::table('fumaco_on_sale as sale')->join('fumaco_on_sale_categories as cat_sale', 'sale.id', 'cat_sale.sale_id')->whereDate('sale.start_date', '<=', Carbon::now())->whereDate('sale.end_date', '>=', Carbon::now())->where('status', 1)->where('cat_sale.category_id', $bs->f_cat_id)->first();

            $bs_product_price = null;
            $discount_from_sale = 0;
            $bs_sale_discount_rate = null;
            $bs_sale_discount_type = null;
            if($bs_all_item_discount){
                $discount_from_sale = 1;
                $bs_sale_discount_rate = $bs_all_item_discount->discount_rate;
                $bs_sale_discount_type = $bs_all_item_discount->discount_type;
                if($bs_all_item_discount->discount_type == 'By Percentage'){
                    $bs_product_price = $bs->f_original_price - ($bs->f_original_price * ($bs_all_item_discount->discount_rate/100));
                }else if($bs_all_item_discount->discount_type == 'Fixed Amount'){
                    $discount_from_sale = 0;
                    if($bs->f_original_price > $bs_all_item_discount->discount_rate){
                        $discount_from_sale = 1;
                        $bs_product_price = $bs->f_original_price - $bs_all_item_discount->discount_rate;
                    }
                }
            }else if($bs_category_discount){
                $discount_from_sale = 1;
                $bs_sale_discount_rate = $bs_category_discount->discount_rate;
                $bs_sale_discount_type = $bs_category_discount->discount_type;
                if($bs_category_discount->discount_type == 'By Percentage'){
                    $bs_product_price = $bs->f_original_price - ($bs->f_original_price * ($bs_category_discount->discount_rate/100));
                }else if($bs_category_discount->discount_type == 'Fixed Amount'){
                    $discount_from_sale = 0;
                    if($bs->f_original_price > $bs_category_discount->discount_rate){
                        $discount_from_sale = 1;
                        $bs_product_price = $bs->f_original_price - $bs_category_discount->discount_rate;
                    }
                }
            }

            $bs_item_name = $bs->f_name_name;

            $on_stock = ($bs->f_qty - $bs->f_reserved_qty) > 0 ? 1 : 0;

            // get product reviews
            $product_reviews = DB::table('fumaco_product_review')
                ->where('status', '!=', 'pending')->where('item_code', $bs->f_idcode)->get();
            $best_selling_arr[] = [
                'id' => $bs->id,
                'item_code' => $bs->f_idcode,
                'item_name' => $bs_item_name,
                'orig_price' => $bs->f_original_price,
                'sale_discounted_price' => $bs_product_price,
                'sale_discount_rate' => $bs_sale_discount_rate,
                'sale_discount_type' => $bs_sale_discount_type,
                'is_discounted' => $bs->f_discount_trigger,
                'is_discounted_from_sale' => $discount_from_sale,
                'on_stock' => $on_stock,
                'new_price' => $bs->f_price,
                'discount' => $bs->f_discount_percent,
                'bs_img' => ($bs_img) ? $bs_img->imgprimayx : null,
                'slug' => $bs->slug,
                'is_new_item' => $is_new_item,
                'product_reviews' => $product_reviews
            ];
        }

        foreach($on_sale as $os){
            $os_img = DB::table('fumaco_items_image_v1')->where('idcode', $os->f_idcode)->first();

            $is_new_item = 0;
            if($os->f_new_item == 1){
                if($os->f_new_item_start <= Carbon::now() and $os->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }

            $os_item_name = $os->f_name_name;
            $on_stock = ($os->f_qty - $os->f_reserved_qty) > 0 ? 1 : 0;

            // get product reviews
            $product_reviews = DB::table('fumaco_product_review')
                ->where('status', '!=', 'pending')->where('item_code', $os->f_idcode)->get();
            $on_sale_arr[] = [
                'id' => $os->id,
                'item_code' => $os->f_idcode,
                'item_name' => $os_item_name,
                'orig_price' => $os->f_original_price,
                'is_discounted' => $os->f_discount_trigger,
                'new_price' => $os->f_price,
                'on_stock' => $on_stock,
                'os_img' => ($os_img) ? $os_img->imgprimayx : null,
                'discount_percent' => $os->f_discount_percent,
                'slug' => $os->slug,
                'is_new_item' => $is_new_item,
                'product_reviews' => $product_reviews
            ];
        }

        $image_for_sharing = null;
        // get image for social media sharing
        $default_image_for_sharing = DB::table('fumaco_social_image')->where('is_default', 1)->where('page_type', 'main_page')->first();
        if ($default_image_for_sharing) {
            $image_for_sharing = ($default_image_for_sharing->filename) ? asset('/storage/social_images/'. $default_image_for_sharing->filename) : null;
        } 

        $page_meta = DB::table('fumaco_pages')->where('is_homepage', 1)->first();

        return view('frontend.homepage', compact('carousel_data', 'onsale_carousel_data', 'blogs', 'best_selling_arr', 'on_sale_arr', 'page_meta', 'image_for_sharing'));
    }

    public function getAutoCompleteData(Request $request){
        if($request->ajax()){
            $item_keywords = DB::table('fumaco_items')
                ->where('f_name_name', 'LIKE', '%'.$request->search_term.'%')
                ->where('f_status', 1)->select('f_name_name')->limit(8)->get();

            if(count($item_keywords) == 0){
                $item_keywords = $item_keywords->where('keywords', 'LIKE', '%'.$request->search_term.'%');
            }

            $items_arr = [];
            foreach($item_keywords as $item){
                $image = null;
                $items_arr[] = [
                    'item_name' => $item->f_name_name,
                    // 'image' => $image,
                ];
            }

            return response()->json(collect($items_arr)->pluck('item_name'));
        }
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
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'username' => 'required|email|unique:fumaco_users,username',
                'password' => 'required|confirmed|min:6',
                'g-recaptcha-response' => ['required',function ($attribute, $value, $fail) {
                    $secret_key = config('recaptcha.api_secret_key');
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
                'password.confirmed' => 'Password does not match.',
                'g-recaptcha-response' => [
                    'required' => 'Please check ReCaptcha.'
                ]
            ]);

            $user = new User;
            $user->username = trim($request->username);
            $user->password = Hash::make($request->password);
            $user->f_name = $request->first_name;
            $user->f_lname = $request->last_name;
            $user->f_email = 'fumacoco_dev';
            $user->f_temp_passcode = 'fumaco12345';
            $user->save();

            $token = Str::random(64);
            UserVerify::create([
                'user_id' => $user->id, 
                'token' => $token
            ]);

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

            Mail::send('emails.verify_email', ['token' => $token], function($message) use($request){
                $message->to($request->username);
                $message->subject('Verify email from Fumaco.com');
            });

            DB::commit();

            return redirect('/myprofile/verify/email')->with('email', $request->username);
        }catch(Exception $e){
            DB::rollback();
        }
    }

    public function resendVerification($email) {
        $existing = User::where('username', $email)->first();
        if ($existing) {
            $token = Str::random(64);
            UserVerify::create([
                'user_id' => $existing->id, 
                'token' => $token
            ]);

            Mail::send('emails.verify_email', ['token' => $token], function($message) use($email){
                $message->to($email);
                $message->subject('Verify email from Fumaco.com');
            });
        }

        return redirect()->back()->with(['email' => $email, 'resend' => true]);
    }

    public function verifyAccount($token) {
        $verifyUser = UserVerify::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';
        if(!is_null($verifyUser) ){
            $user = User::find($verifyUser->user_id);

            if(!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your email is verified. You can now login.";

                Mail::send('emails.welcome', ['username' => trim($user->username), 'password' => $user->password], function($message) use($user){
                    $message->to($user->username);
                    $message->subject('Welcome Email from Fumaco.com');
                });
            } else {
                $message = "Your email is already verified. You can now login.";
            }
        }
     
        return redirect('/login')->with('success', $message);
    }

    public function emailVerify() {
        if (!session('email')) {
            return redirect('/');
        }

        return view('frontend.email_verify');
    }

    public function viewAboutPage() {
        $about_data = DB::table('fumaco_about')->first();

        $partners = DB::table('fumaco_about_partners')->where('xstatus', 1)
            ->orderBy('partners_sort', 'asc')->get();

        $bg1 = explode('.',$about_data->background_1);
        $bg2 = explode('.',$about_data->background_2);
        $bg3 = explode('.',$about_data->background_3);

        $image_for_sharing = null;
        // get image for social media sharing
        $default_image_for_sharing = DB::table('fumaco_social_image')->where('is_default', 1)->where('page_type', 'main_page')->first();
        if ($default_image_for_sharing) {
            $image_for_sharing = ($default_image_for_sharing->filename) ? asset('/storage/social_images/'. $default_image_for_sharing->filename) : null;
        } 

        return view('frontend.about_page', compact('about_data', 'partners', 'bg1', 'bg2', 'bg3', 'image_for_sharing'));
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

        $blog_tags = DB::table('fumaco_blog_tag')->where('blog_id', $blog->id)->first();
        
        $tags = '';
        if($blog_tags){
            $tags = explode(',', str_replace(array('"','"'), '',trim($blog_tags->tagname, '[]')));
        }

        $comment_count = DB::table('fumaco_comments')->where('blog_id', $blog->id)->where('blog_status', 1)->get();

        $comments_arr = [];
        foreach($blog_comment as $comment){
            $replies_arr = [];
            $blog_reply = DB::table('fumaco_comments')->where('blog_id', $blog->id)->where('blog_type', 2)->where('reply_id', $comment->id)->where('blog_status', 1)->get();
            foreach($blog_reply as $r){
                $replies_arr[] = [
                    'blog_name' => $r->blog_name,
                    'blog_date' => Carbon::parse($r->blog_date)->format('M d, Y h:m A'),
                    'blog_comments' => $r->blog_comments
                ];
            }
            $comments_arr[] = [
                'id' => $comment->id,
                'email' => $comment->blog_email,
                'name' => $comment->blog_name,
                'comment' => $comment->blog_comments,
                'reply_comment' => $replies_arr,
                'date' => Carbon::parse($comment->blog_date)->format('M d, Y h:m A')
            ];
        }

        $id = $blog->id;

        return view('frontend.blogs', compact('blog', 'comments_arr', 'id', 'comment_count', 'blog_tags', 'tags'));
    }

    public function viewContactPage() {
        $fumaco_contact = DB::table('fumaco_contact')->get();

        $fumaco_map = DB::table('fumaco_map_1')->first();

        $image_for_sharing = null;
        // get image for social media sharing
        $default_image_for_sharing = DB::table('fumaco_social_image')->where('is_default', 1)->where('page_type', 'main_page')->first();
        if ($default_image_for_sharing) {
            $image_for_sharing = ($default_image_for_sharing->filename) ? asset('/storage/social_images/'. $default_image_for_sharing->filename) : null;
        } 

        return view('frontend.contact', compact('fumaco_contact', 'fumaco_map', 'image_for_sharing'));
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
                    $secret_key = config('recaptcha.api_secret_key');
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
        $request_data = $request->except(['page', 'sel_attr', 'sortby', 'brand', 'order', 'fbclid']);
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

        $image_for_sharing = null;
        // get image for social media sharing
        $default_image_for_sharing = DB::table('fumaco_social_image')->where('is_default', 1)->where('category_id', $product_category->id)->first();
        if ($default_image_for_sharing) {
            $image_for_sharing = ($default_image_for_sharing->filename) ? asset('/storage/social_images/'. $default_image_for_sharing->filename) : null;
        } else {
            $default_image_for_sharing = DB::table('fumaco_social_image')->where('is_default', 1)->where('page_type', 'main_page')->first();
            if ($default_image_for_sharing) {
                $image_for_sharing = ($default_image_for_sharing->filename) ? asset('/storage/social_images/'. $default_image_for_sharing->filename) : null;
            }
        }

        // get items based on category id
        $products = DB::table('fumaco_items')->where('f_cat_id', $product_category->id)
            ->when(count($request->except(['page', 'sel_attr', 'sortby', 'order'])) > 0, function($c) use ($filtered_items) {
                $c->whereIn('f_idcode', $filtered_items);
            })
            ->where('f_status', 1)->orderBy($sortby, $orderby)->paginate(15);

        $all_item_discount = DB::table('fumaco_on_sale')->whereDate('start_date', '<=', Carbon::now()->toDateString())->whereDate('end_date', '>=', Carbon::now()->toDateString())->where('status', 1)->where('apply_discount_to', 'All Items')->first();

        $category_discount = DB::table('fumaco_on_sale as sale')->join('fumaco_on_sale_categories as cat_sale', 'sale.id', 'cat_sale.sale_id')->whereDate('sale.start_date', '<=', Carbon::now())->whereDate('sale.end_date', '>=', Carbon::now())->where('status', 1)->where('cat_sale.category_id', $product_category->id)->first();

        $products_arr = [];
        foreach ($products as $product) {
            $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $product->f_idcode)->first();

            $product_price = $product->f_original_price;
            $discount_from_sale = 0;
            $sale_discount_rate = null;
            $sale_discount_type = null;
            if($all_item_discount){
                $discount_from_sale = 1;
                $sale_discount_rate = $all_item_discount->discount_rate;
                $sale_discount_type = $all_item_discount->discount_type;
                if($all_item_discount->discount_type == 'By Percentage'){
                    $product_price = $product->f_original_price - ($product->f_original_price * ($all_item_discount->discount_rate/100));
                }else if($all_item_discount->discount_type == 'Fixed Amount'){
                    $discount_from_sale = 0;
                    if($product->f_original_price > $all_item_discount->discount_rate){
                        $discount_from_sale = 1;
                        $product_price = $product->f_original_price - $all_item_discount->discount_rate;
                    }
                }
            }else if($category_discount){
                $discount_from_sale = 1;
                $sale_discount_rate = $category_discount->discount_rate;
                $sale_discount_type = $category_discount->discount_type;
                if($category_discount->discount_type == 'By Percentage'){
                    $product_price = $product->f_original_price - ($product->f_original_price * ($category_discount->discount_rate/100));
                }else if($category_discount->discount_type == 'Fixed Amount'){
                    $discount_from_sale = 0;
                    if($product->f_original_price > $category_discount->discount_rate){
                        $discount_from_sale = 1;
                        $product_price = $product->f_original_price - $category_discount->discount_rate;
                    }
                }
            }

            $item_name = strip_tags($product->f_name_name);
            $on_stock = ($product->f_qty - $product->f_reserved_qty) > 0 ? 1 : 0;
            $product_review_per_code = DB::table('fumaco_product_review')
                ->where('status', '!=', 'pending')->where('item_code', $product->f_idcode)->get();
            $products_arr[] = [
                'id' => $product->id,
                'item_code' => $product->f_idcode,
                'item_name' => $item_name,
                'image' => ($item_image) ? $item_image->imgprimayx : null,
                'price' => $product->f_original_price,
                'discounted_price' => $product->f_discount_trigger == 1 ? number_format(str_replace(",","",$product->f_price), 2) : $product_price,
                'is_discounted' => $product->f_discount_trigger,
                'is_discounted_from_sale' => $discount_from_sale,
                'sale_discount_rate' => $sale_discount_rate,
                'sale_discount_type' => $sale_discount_type,
                'on_sale' => $product->f_onsale,
                'on_stock' => $on_stock,
                'discount_percent' => $product->f_discount_percent,
                'slug' => $product->slug,
                'is_new_item' => $product->f_new_item,
                'product_reviews' => $product_review_per_code
            ];
        }

        return view('frontend.product_list', compact('product_category', 'products_arr', 'products', 'filters', 'image_for_sharing', 'category_discount'));
    }

    private function getProductCardDetails($item_code){
        $item = DB::table('fumaco_items as item')->join('fumaco_items_image_v1 as img', 'item.f_idcode', 'img.idcode')->where('item.f_idcode', $item_code)->first();

        $is_new = 0;

        if($item->f_new_item == 1){
            if($item->f_new_item_start <= Carbon::now() and $item->f_new_item_end >= Carbon::now()){
                $is_new = 1;
            }
        }

        $all_item_discount = DB::table('fumaco_on_sale')->whereDate('start_date', '<=', Carbon::now()->toDateString())->whereDate('end_date', '>=', Carbon::now()->toDateString())->where('status', 1)->where('apply_discount_to', 'All Items')->first();

        $category_discount = DB::table('fumaco_on_sale as sale')->join('fumaco_on_sale_categories as cat_sale', 'sale.id', 'cat_sale.sale_id')->whereDate('sale.start_date', '<=', Carbon::now())->whereDate('sale.end_date', '>=', Carbon::now())->where('status', 1)->where('cat_sale.category_id', $item->f_cat_id)->first();

        $product_price = $item->f_original_price;
        $discount_from_sale = 0;
        $discount_rate = null;
        $discount_type = null;
        if($all_item_discount){
            $discount_from_sale = 1;
            $discount_rate = $all_item_discount->discount_rate;
            $discount_type = $all_item_discount->discount_type;
            if($all_item_discount->discount_type == 'By Percentage'){
                $product_price = $item->f_original_price - ($item->f_original_price * ($all_item_discount->discount_rate/100));
            }else if($all_item_discount->discount_type == 'Fixed Amount'){
                $discount_from_sale = 0;
                if($item->f_original_price > $all_item_discount->discount_rate){
                    $discount_from_sale = 1;
                    $product_price = $item->f_original_price - $all_item_discount->discount_rate;
                }
            }
        }else if($category_discount){
            $discount_from_sale = 1;
            $discount_rate = $category_discount->discount_rate;
            $discount_type = $category_discount->discount_type;
            if($category_discount->discount_type == 'By Percentage'){
                $product_price = $item->f_original_price - ($item->f_original_price * ($category_discount->discount_rate/100));
            }else if($category_discount->discount_type == 'Fixed Amount'){
                $discount_from_sale = 0;
                if($item->f_original_price > $category_discount->discount_rate){
                    $discount_from_sale = 1;
                    $product_price = $item->f_original_price - $category_discount->discount_rate;
                }
            }
        }

        $on_stock = ($item->f_qty - $item->f_reserved_qty) > 0 ? 1 : 0;

        // get product reviews
        $product_review_per_code = DB::table('fumaco_product_review')->where('status', '!=', 'pending')->where('item_code', $item->f_idcode)->get();

        $product_card_data = [
            'id' => $item->id,
            'item_code' => $item->f_idcode,
            'item_name' => $item->f_name_name,
            'orig_price' => $item->f_original_price,
            'sale_discounted_price' => $product_price,
            'sale_discount_rate' => $discount_rate,
            'sale_discount_type' => $discount_type,
            'is_discounted' => $item->f_discount_trigger,
            'is_discounted_from_sale' => $discount_from_sale,
            'discount_percent' => $item->f_discount_percent,
            'new_price' => $item->f_price,
            'on_stock' => $on_stock,
            'primary_image' => ($item->imgprimayx) ? $item->imgprimayx : null,
            'original_image' => ($item->imgoriginalx) ? $item->imgoriginalx : null,
            'slug' => $item->slug,
            'is_new_item' => $is_new,
            'product_reviews' => $product_review_per_code
        ];
        return $product_card_data;
    }

    public function viewProduct($slug) { // Product Page
        $product_details = DB::table('fumaco_items')->where('slug', $slug)->orWhere('f_idcode', $slug)->first();
        if (!$product_details) {
            return redirect('/');
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

        $all_item_discount = DB::table('fumaco_on_sale')->whereDate('start_date', '<=', Carbon::now()->toDateString())->whereDate('end_date', '>=', Carbon::now()->toDateString())->where('status', 1)->where('apply_discount_to', 'All Items')->first();

        $category_discount = DB::table('fumaco_on_sale as sale')->join('fumaco_on_sale_categories as cat_sale', 'sale.id', 'cat_sale.sale_id')->whereDate('sale.start_date', '<=', Carbon::now())->whereDate('sale.end_date', '>=', Carbon::now())->where('status', 1)->where('cat_sale.category_id', $product_details->f_cat_id)->first();

        $product_price = $product_details->f_original_price;
        $discount_from_sale = 0;
        $sale_discount_rate = null;
        $sale_discount_type = null;
        if($all_item_discount){
            $discount_from_sale = 1;
            $sale_discount_rate = $all_item_discount->discount_rate;
            $sale_discount_type = $all_item_discount->discount_type;
            if($all_item_discount->discount_type == 'By Percentage'){
                $product_price = $product_details->f_original_price - ($product_details->f_original_price * ($all_item_discount->discount_rate/100));
            }else if($all_item_discount->discount_type == 'Fixed Amount'){
                $discount_from_sale = 0;
                if($product_details->f_original_price > $all_item_discount->discount_rate){
                    $discount_from_sale = 1;
                    $product_price = $product_details->f_original_price - $all_item_discount->discount_rate;
                }
            }
        }else if($category_discount){
            $discount_from_sale = 1;
            $sale_discount_rate = $category_discount->discount_rate;
            $sale_discount_type = $category_discount->discount_type;
            if($category_discount->discount_type == 'By Percentage'){
                $product_price = $product_details->f_original_price - ($product_details->f_original_price * ($category_discount->discount_rate/100));
            }else if($category_discount->discount_type == 'Fixed Amount'){
                $discount_from_sale = 0;
                if($product_details->f_original_price > $category_discount->discount_rate){
                    $discount_from_sale = 1;
                    $product_price = $product_details->f_original_price - $category_discount->discount_rate;
                }
            }
        }

        $compare_arr = [];
        $variant_attr_array = [];
        $products_to_compare = null;
        $variant_attributes_to_compare = null;
        $attributes_to_compare = null;
        $attribute_names = null;
        $product_comparison_id = DB::table('product_comparison_attribute')->where('item_code', $product_details->f_idcode)->where('status', 1)->pluck('product_comparison_id')->first();
        if($product_comparison_id){
            $compare_query = DB::table('product_comparison_attribute as compare_attrib')->join('fumaco_attributes_per_category as cat_attrib', 'compare_attrib.attribute_name_id', 'cat_attrib.id')->where('compare_attrib.product_comparison_id', $product_comparison_id);

            $attributes_clone = Clone $compare_query;
            $attributes_to_compare = $attributes_clone->get();
            
            $variant_attributes_to_compare = collect($attributes_to_compare)->groupBy('attribute_name');
            $attribute_names = $attributes_clone->groupBy('attribute_name')->select('attribute_name')->get();

            foreach ($variant_attributes_to_compare as $attrib => $attrib_name) {
                $idcode_to_compare = collect($attrib_name)->groupBy('item_code')->map(function($d) {
                    return $d[0]->attribute_value;
                });
                $variant_attr_array[$attrib] = $idcode_to_compare;
            }

            $products_clone = Clone $compare_query;
            $products_to_compare = $products_clone->groupBy('compare_attrib.item_code')->select('compare_attrib.item_code')->get();
            foreach($products_to_compare as $compare){
                $image = DB::table('fumaco_items_image_v1')->where('idcode', $compare->item_code)->first();
                $item_details = DB::table('fumaco_items')->where('f_idcode', $compare->item_code)->first();
                $compare_product_price = $item_details->f_original_price;
                $compare_discount_from_sale = 0;
                $compare_sale_discount_rate = null;
                $compare_sale_discount_type = null;
                if($all_item_discount){
                    $compare_discount_from_sale = 1;
                    $compare_sale_discount_rate = $all_item_discount->discount_rate;
                    $compare_sale_discount_type = $all_item_discount->discount_type;
                    if($all_item_discount->discount_type == 'By Percentage'){
                        $compare_product_price = $item_details->f_original_price - ($item_details->f_original_price * ($all_item_discount->discount_rate/100));
                    }else if($all_item_discount->discount_type == 'Fixed Amount'){
                        $compare_discount_from_sale = 0;
                        if($item_details->f_original_price > $all_item_discount->discount_rate){
                            $compare_discount_from_sale = 1;
                            $compare_product_price = $item_details->f_original_price - $all_item_discount->discount_rate;
                        }
                    }
                }else if($category_discount){
                    $compare_discount_from_sale = 1;
                    $compare_sale_discount_rate = $category_discount->discount_rate;
                    $compare_sale_discount_type = $category_discount->discount_type;
                    if($category_discount->discount_type == 'By Percentage'){
                        $compare_product_price = $item_details->f_original_price - ($item_details->f_original_price * ($category_discount->discount_rate/100));
                    }else if($category_discount->discount_type == 'Fixed Amount'){
                        $compare_discount_from_sale = 0;
                        if($item_details->f_original_price > $category_discount->discount_rate){
                            $compare_discount_from_sale = 1;
                            $compare_product_price = $item_details->f_original_price - $category_discount->discount_rate;
                        }
                    }
                }

                $compare_arr[] = [
                    'item_code' => $compare->item_code,
                    'item_image' => $image->imgoriginalx,
                    'original_price' => $item_details->f_original_price,
                    'individual_discount_rate' => $item_details->f_discount_percent,
                    'price' =>  $item_details->f_onsale == 1 ? $item_details->f_price : $compare_product_price,
                    'slug' => $item_details->slug,
                    'product_name' => $item_details->f_name_name,
                    'discounted_from_item' => $item_details->f_discount_trigger,
                    'discounted_from_sale' => $compare_discount_from_sale,
                    'sale_discount_rate' => $compare_sale_discount_rate,
                    'sale_discount_type' => $compare_sale_discount_type,
                    'on_stock' => $item_details->f_qty > 0 ? 1 : 0
                ];
            }
        }

        $most_searched_items = DB::table('fumaco_search_results')->whereNotNull('prod_results')->select('search_term', 'prod_results')->groupBy('search_term', 'prod_results')->get();

        $item_code_results_arr = [];

        $searched_item_codes = collect($most_searched_items)->pluck('prod_results')->implode(',');
        $searched_item_code_array = array_count_values(explode(',', $searched_item_codes));
        arsort($searched_item_code_array, SORT_NUMERIC);

        $most_searched = [];
        foreach(collect($searched_item_code_array)->keys() as $item_code){
            $this->getProductCardDetails($item_code);

            $product_card_data = $this->getProductCardDetails($item_code);
            
            $most_searched[] = [
                'id' => $product_card_data['id'],
                'item_code' => $product_card_data['item_code'],
                'item_name' => $product_card_data['item_name'],
                'orig_price' => $product_card_data['orig_price'],
                'sale_discounted_price' => $product_card_data['sale_discounted_price'],
                'sale_discount_rate' => $product_card_data['sale_discount_rate'],
                'sale_discount_type' => $product_card_data['sale_discount_type'],
                'is_discounted' => $product_card_data['is_discounted'],
                'is_discounted_from_sale' => $product_card_data['is_discounted_from_sale'],
                'discount_percent' => $product_card_data['discount_percent'],
                'new_price' => $product_card_data['new_price'],
                'on_stock' => $product_card_data['on_stock'],
                'image' => $product_card_data['primary_image'],
                'slug' => $product_card_data['slug'],
                'is_new_item' => $product_card_data['is_new_item'],
                'product_reviews' => $product_card_data['product_reviews']
            ];
        }

        $product_images = DB::table('fumaco_items_image_v1')->where('idcode', $product_details->f_idcode)->get();

        $related_products_query = DB::table('fumaco_items as a')
            ->join('fumaco_items_relation as b', 'a.f_idcode', 'b.related_item_code')
            ->where('b.item_code', $product_details->f_idcode)->where('a.f_status', 1)
            ->select('a.id', 'a.f_idcode', 'a.f_original_price', 'a.f_discount_trigger', 'a.f_price', 'a.f_name_name', 'a.slug', 'a.f_qty', 'a.f_reserved_qty', 'a.f_onsale', 'a.f_new_item', 'a.f_new_item_start', 'a.f_new_item_end', 'a.f_discount_percent', 'a.f_category', 'a.f_cat_id')
            ->get();

        $rp_all_item_discount = DB::table('fumaco_on_sale')->whereDate('start_date', '<=', Carbon::now()->toDateString())->whereDate('end_date', '>=', Carbon::now()->toDateString())->where('status', 1)->where('apply_discount_to', 'All Items')->first();

        $related_products = [];
        foreach($related_products_query as $row) {
            $image = DB::table('fumaco_items_image_v1')->where('idcode', $row->f_idcode)->first();

            $is_new_item = 0;
            if($row->f_new_item == 1){
                if($row->f_new_item_start <= Carbon::now() and $row->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }

            $rp_category = DB::table('fumaco_categories')->where('name', $row->f_category)->select('id')->first();
            $rp_category_discount = DB::table('fumaco_on_sale as sale')->join('fumaco_on_sale_categories as cat_sale', 'sale.id', 'cat_sale.sale_id')->whereDate('sale.start_date', '<=', Carbon::now())->whereDate('sale.end_date', '>=', Carbon::now())->where('status', 1)->where('cat_sale.category_id', $row->f_cat_id)->first();

            $rp_product_price = null;
            $rp_discount_from_sale = 0;
            $rp_sale_discount_rate = null;
            $rp_sale_discount_type = null;
            if($all_item_discount){
                $rp_discount_from_sale = 1;
                $rp_sale_discount_rate = $all_item_discount->discount_rate;
                $rp_sale_discount_type = $all_item_discount->discount_type;
                if($all_item_discount->discount_type == 'By Percentage'){
                    $rp_product_price = $row->f_original_price - ($row->f_original_price * ($all_item_discount->discount_rate/100));
                }else if($all_item_discount->discount_type == 'Fixed Amount'){
                    $rp_discount_from_sale = 0;
                    if($row->f_original_price > $all_item_discount->discount_rate){
                        $rp_discount_from_sale = 1;
                        $rp_product_price = $row->f_original_price - $all_item_discount->discount_rate;
                    }
                }
            }else if($rp_category_discount){
                $rp_discount_from_sale = 1;
                $rp_sale_discount_rate = $rp_category_discount->discount_rate;
                $rp_sale_discount_type = $rp_category_discount->discount_type;
                if($rp_category_discount->discount_type == 'By Percentage'){
                    $rp_product_price = $row->f_original_price - ($row->f_original_price * ($rp_category_discount->discount_rate/100));
                }else if($category_discount->discount_type == 'Fixed Amount'){
                    $rp_discount_from_sale = 0;
                    if($row->f_original_price > $rp_category_discount->discount_rate){
                        $rp_discount_from_sale = 1;
                        $rp_product_price = $row->f_original_price - $rp_category_discount->discount_rate;
                    }
                }
            }

            $on_stock = ($row->f_qty - $row->f_reserved_qty) > 0 ? 1 : 0;

                 // get product reviews
            $product_review_per_code = DB::table('fumaco_product_review')
                ->where('status', '!=', 'pending')->where('item_code', $row->f_idcode)->get();
            $related_products[] = [
                'id' => $row->id,
                'item_code' => $row->f_idcode,
                'item_name' => $row->f_name_name,
                'orig_price' => $row->f_original_price,
                'sale_discounted_price' => $rp_product_price,
                'sale_discount_rate' => $rp_sale_discount_rate,
                'sale_discount_type' => $rp_sale_discount_type,
                'is_discounted' => $row->f_discount_trigger,
                'is_discounted_from_sale' => $rp_discount_from_sale,
                'discount_percent' => $row->f_discount_percent,
                'new_price' => $row->f_price,
                'on_stock' => $on_stock,
                'image' => ($image) ? $image->imgprimayx : null,
                'slug' => $row->slug,
                'is_new_item' => $is_new_item,
                'product_reviews' => $product_review_per_code
            ];
        }

        // get product reviews
        $product_reviews = DB::table('fumaco_product_review as a')->join('fumaco_users as b', 'a.user_id', 'b.id')
            ->where('status', '!=', 'pending')->where('item_code', $product_details->f_idcode)->select('a.*', 'b.f_name', 'b.f_lname')->orderBy('a.created_at', 'desc')->paginate(5);
        // get total rating
        $total_rating = DB::table('fumaco_product_review as a')->join('fumaco_users as b', 'a.user_id', 'b.id')
            ->where('status', '!=', 'pending')->where('item_code', $product_details->f_idcode)->sum('a.rating');
        
        return view('frontend.product_page', compact('product_details', 'product_images', 'attributes', 'variant_attr_arr', 'related_products', 'filtered_attributes', 'discount_from_sale', 'sale_discount_type', 'sale_discount_rate', 'product_price', 'products_to_compare', 'variant_attributes_to_compare', 'compare_arr', 'attributes_to_compare', 'variant_attr_array', 'attribute_names', 'product_reviews', 'total_rating', 'most_searched'));
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
        $completed_statuses = DB::table('order_status')->where('update_stocks', 1)->select('status')->get();
        $active_order_statuses = array('Order Placed', 'Order Confirmed', 'Out for Delivery');

        $orders = DB::table('fumaco_order')->where('user_email', Auth::user()->username)
            ->whereNotIn('order_status', $active_order_statuses)
            ->orderBy('id', 'desc')->paginate(10);

        $new_orders = DB::table('fumaco_order')->where('user_email', Auth::user()->username)
            ->whereNotIn('order_status', collect($completed_statuses)->pluck('status'))
            ->where('order_status', '!=', 'Cancelled')
            ->orderBy('id', 'desc')->paginate(10);


        $orders_arr = [];
        $new_orders_arr = [];
 
        foreach($orders as $order){
            $items_arr = [];

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
                'date' => date('M d, Y - h:i A', strtotime($order->order_date)),
                'status' => $order->order_status,
                'edd' => $order->estimated_delivery_date,
                'date_delivered' => $order->date_delivered,
                'items' => $items_arr,
                'subtotal' => $order->order_subtotal,
                'shipping_name' => $order->order_shipping,
                'shipping_fee' => $order->order_shipping_amount,
                'grand_total' => ($order->order_shipping_amount + ($order->order_subtotal - $order->discount_amount)),
                'voucher_code' => $order->voucher_code,
                'discount_amount' => $order->discount_amount,
            ];
        }

        foreach($new_orders as $key => $new_order){
            $items_arr = [];

            $order_items = DB::table('fumaco_order_items')->where('order_number', $new_order->order_number)->get();

            $track_order_details = DB::table('track_order')->where('track_code', $new_order->order_number)->where('track_active', 1)->select('track_status', 'track_date_update')->get();

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

            $order_status = DB::table('order_status as s')
                ->join('order_status_process as p', 's.order_status_id', 'p.order_status_id')
                ->where('shipping_method', $new_order->order_shipping)
                ->select('s.status', 's.status_description', 'p.order_sequence')
                ->orderBy('order_sequence', 'asc')
                ->get();

            $new_orders_arr[] = [
                'order_number' => $new_order->order_number,
                'date' => date('M d, Y - h:i A', strtotime($new_order->order_date)),
                'status' => $new_order->order_status,
                'edd' => $new_order->estimated_delivery_date,
                'items' => $items_arr,
                'subtotal' => $new_order->order_subtotal,
                'shipping_name' => $new_order->order_shipping,
                'shipping_fee' => $new_order->order_shipping_amount,
                'grand_total' => ($new_order->order_shipping_amount + ($new_order->order_subtotal - $new_order->discount_amount)),
                'pickup_date' => $new_order->pickup_date,
                'order_tracker' => $track_order_details,
                'ship_status' => $order_status,
                'voucher_code' => $new_order->voucher_code,
                'discount_amount' => $new_order->discount_amount,
            ];
        }

        return view('frontend.orders', compact('orders', 'orders_arr', 'new_orders', 'new_orders_arr'));
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
                $default = DB::table('fumaco_user_add')->where('user_idx', $address_details->user_idx)->where('address_class', $address_details->address_class)->first();
                if ($address_details->xdefault) {
                    DB::table('fumaco_user_add')->where('id', $default->id)->update(['xdefault' => 1]);
                }
    
                DB::table('fumaco_user_add')->where('id', $id)->delete();

                DB::commit();

                $address_class = $type == 'shipping' ? 'Delivery' : 'Billing';

                $address = DB::table('fumaco_user_add')->where('id', $default->id)->first();
                if($address){
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
                }
            }
           
            return redirect()->back()->with('success', 'Address has been deleted.');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function updateAddress(Request $request, $id, $type){
        DB::beginTransaction();
        try {
            // return $request->all();
            $rules = array(
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile' => 'required',
                'email' => 'required|email',
                'address1' => 'required',
                'province' => 'required',
                'city' => 'required',
                'brgy' => 'required',
                'postal' => 'required',
                'country' => 'required',
                'Address_type1_1' => 'required',
                'business_name' => $request->Address_type1_1 == 'Business Address' ? 'required' : ''
            );
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return redirect()->back()->with('error', 'All fields with * are required.');
            }
            $update = [
                'add_type' => $request->Address_type1_1,
                'xbusiness_name' => $request->business_name,
                'xtin_no' => $request->tin,
                'xadd1' => $request->address1,
                'xadd2' => $request->address2,
                'xprov' => $request->province,
                'xcity' => $request->city,
                'xbrgy' => $request->brgy,
                'xpostal' => $request->postal,
                'xcountry' => $request->country,
                'xmobile_number' => $request->mobile,
                'xcontactname1' => $request->first_name,
                'xcontactlastname1' => $request->last_name,
                'xcontactnumber1' => $request->contact,
                'xcontactemail1' => $request->email 
            ];

            DB::table('fumaco_user_add')->where('id', $id)->update($update);

            DB::commit();

            $address = DB::table('fumaco_user_add')->where('id', $id)->first();

            if(isset($request->checkout) and $address->xdefault == 1){ // Edit from order summary page
                $address_class = $type == 'shipping' ? 'Delivery' : 'Billing';

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
            }

            return redirect()->back()->with('success', 'Address Updated');
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
            
            $checker = DB::table('fumaco_user_add')->where('user_idx', Auth::user()->id)->where('address_class', $address_class)->count();

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
                    'xdefault' => $checker > 0 ? 0 : 1
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
        $order_status = '';
        if($order_details){
            $order_status = DB::table('order_status as s')
                ->join('order_status_process as p', 's.order_status_id', 'p.order_status_id')
                ->where('shipping_method', $order_details->order_shipping)
                ->select('s.status', 's.status_description', 'p.order_sequence')
                ->orderBy('order_sequence', 'asc')
                ->get();
        }
        

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

        // return $track_order_details;

        return view('frontend.track_order', compact('order_details', 'items', 'track_order_details', 'order_status'));
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
            ->select('c.slug', 'b.attribute_value', 'b.idcode', 'a.slug as item_slug')
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
                return ($values[0]->item_slug) ? $values[0]->item_slug : $item_code;
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
