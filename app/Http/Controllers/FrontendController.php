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
use Illuminate\Support\Facades\Http;
use Newsletter;
use Cache;
use Exception;

use App\Http\Traits\ProductTrait;
use App\Http\Traits\GeneralTrait;

use Illuminate\Pagination\LengthAwarePaginator;

use File;

class FrontendController extends Controller
{   
    use ProductTrait;
    use GeneralTrait;
    public function signupForm() {
        return view('frontend.register');
    }

    public function index(Request $request) {
        $clearance_sale_item_codes = DB::table('fumaco_on_sale as os')
            ->join('fumaco_on_sale_items as osi', 'os.id', 'osi.sale_id')
            ->join('fumaco_items as i', 'i.f_idcode', 'osi.item_code')
            ->where('os.is_clearance_sale', 1)->where('os.status', 1)->where('i.f_status', 1)
            ->pluck('i.f_idcode');

        // get sorting value 
        $sortby = $request->sortby;
        switch ($sortby) {
            case 'Price':
                $sortby = 'f_default_price';
                break;
            case 'Product Name':
                $sortby = 'f_name_name';
                break;
            default:
                $sortby = 'f_order_by';
                break;
        }

        // get sitewide sale
        $sale = DB::table('fumaco_on_sale')
            ->whereDate('start_date', '<=', Carbon::now()->toDateString())
            ->whereDate('end_date', '>=', Carbon::today()->toDateString())
            ->where('status', 1)->where('apply_discount_to', 'All Items')
            ->select('discount_type', 'discount_rate')->first();

        if ($request->has('s') && $request->s != null) {
            $orderby = ($request->order) ? $request->order : 'asc';
            $search_by = $request->by;
            $search_str = $request->s;

            $product_list = $blogs = $attribute_value_filter = [];

            $request_data = $request->attr ? array_diff_key($request->attr, array_flip(['brand'])) : [];

            $search_string = $request->s;
            $attribute_name_filter = $request_data ? array_keys($request_data) : [];
            $brand_filter = isset($request->attr['brand']) ? $request->attr['brand'] : [];
            foreach($request_data as $data) {
                $attribute_value_filter[] = $data;
            }

            // get items based on filters
            $filtered_items = $filtered_item_codes = [];
            if ($request->s == null) {
                if (in_array($search_by, ['products', 'all', ''])) {
                    $product_list = DB::table('fumaco_items')->where('f_status', 1)->where('f_featured', 1)
                        ->when(count($clearance_sale_item_codes) > 0, function($c) use ($clearance_sale_item_codes) {
                            $c->whereNotIn('f_idcode', $clearance_sale_item_codes);
                        })
                        ->select('f_idcode', 'f_default_price', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_qty', 'f_reserved_qty', 'slug', 'f_name_name', 'f_item_name', 'f_category', 'id', 'image_alt')->get();
                }

                if (in_array($search_by, ['blogs', 'all', ''])) {
                    $blogs = DB::table('fumaco_blog')->where('blog_featured', 1)
                        ->where('blog_enable', 1)->select('id', 'blogprimayimage-journal', 'datepublish', 'blogtitle', 'blogtype', 'blog_caption', 'slug')->get();
                }
            } else {
                if (in_array($search_by, ['products', 'all', ''])) {
                    $product_list = DB::table('fumaco_items')->where('f_status', 1)
                        ->where(function ($q) use ($search_str){
                            return $q->where('f_brand', 'LIKE', "%".$search_str."%")
                                ->orWhere('f_parent_code', 'LIKE', "%".$search_str."%")
                                ->orWhere('f_category', 'LIKE', "%".$search_str."%")
                                ->orWhere('f_name_name', 'LIKE', "%".$search_str."%")
                                ->orWhere('f_item_name', 'LIKE', "%".$search_str."%")
                                ->orWhere(function($q) use ($search_str) {
                                    $search_strs = explode(" ", $search_str);
                                    foreach ($search_strs as $str) {
                                        $q->where('f_description', 'LIKE', "%".$str."%");
                                    }
            
                                    $q->orWhere('f_idcode', 'LIKE', "%".$search_str."%")
                                        ->orWhere('f_item_classification', 'LIKE', "%".$search_str."%")
                                        ->orWhere('keywords', 'LIKE', '%'.$search_str.'%');
                                });
                        })
                        ->when(count($clearance_sale_item_codes) > 0, function($c) use ($clearance_sale_item_codes) {
                            $c->whereNotIn('f_idcode', $clearance_sale_item_codes);
                        })
                        ->select('f_idcode', 'f_default_price', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_qty', 'f_reserved_qty', 'slug', 'f_name_name', 'f_item_name', 'f_category', 'id', 'image_alt')
                        ->orderBy($sortby, $orderby)->get();

                    if(count($request_data) > 0 or count($brand_filter) > 0){
                        $filtered_items = DB::table('fumaco_items as a')
                            ->join('fumaco_items_attributes as b', 'a.f_idcode', 'b.idcode')
                            ->join('fumaco_attributes_per_category as c', 'c.id', 'b.attribute_name_id')->where('a.f_status', 1)
                            ->when(count($brand_filter) > 0, function($c) use ($brand_filter) {
                                $c->whereIn('a.f_brand', $brand_filter);
                            })
                            ->when(count($request_data) > 0, function($c) use ($attribute_name_filter, $attribute_value_filter) {
                                $c->whereIn('c.slug', $attribute_name_filter)->whereIn('b.attribute_value', $attribute_value_filter);
                            })
                            ->when(count($clearance_sale_item_codes) > 0, function($c) use ($clearance_sale_item_codes) {
                                $c->whereNotIn('f_idcode', $clearance_sale_item_codes);
                            })
                            ->select('c.slug', 'b.attribute_value', 'a.f_idcode', 'a.f_brand')->get();

                        if(count($brand_filter) > 0 and count($filtered_items) == 0){
                            $filtered_items = DB::table('fumaco_items')->whereIn('f_brand', $brand_filter)
                                ->when(count($clearance_sale_item_codes) > 0, function($c) use ($clearance_sale_item_codes) {
                                    $c->whereNotIn('f_idcode', $clearance_sale_item_codes);
                                })
                                ->select('f_idcode', 'f_brand')->get();
                        }

                        $filtered_item_codes = collect($filtered_items)->pluck('f_idcode');

                        $include_bulk_item_codes = [];
                        if($filtered_item_codes){ // double check for item codes with "-A"
                            $include_bulk_item_codes = DB::table('fumaco_items')
                                ->where(function($q) use ($filtered_item_codes){
                                    foreach($filtered_item_codes as $items){
                                        $q->orWhere('f_idcode', 'like', '%'.$items.'%');
                                    }
                                })
                                ->when(count($clearance_sale_item_codes) > 0, function($c) use ($clearance_sale_item_codes) {
                                    $c->whereNotIn('f_idcode', $clearance_sale_item_codes);
                                })
                                ->pluck('f_idcode');

                            $filtered_item_codes = collect($include_bulk_item_codes);
                        }

                        $product_list = $product_list->whereIn('f_idcode', $filtered_item_codes);
                    }
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
                    ->select('id', 'blogprimayimage-journal', 'datepublish', 'blogtitle', 'blogtype', 'blog_caption', 'slug')
                    ->get();
                } 
            }

            $on_sale_items = $this->onSaleItems(collect($product_list)->pluck('f_idcode'));

            $results = [];
            foreach($product_list as $row){
                $is_new_item = 0;
                if($row->f_new_item == 1){
                    if($row->f_new_item_start <= Carbon::now() and $row->f_new_item_end >= Carbon::now()){
                        $is_new_item = 1;
                    }
                }

                $on_sale = false;
                $discount_type = $discount_rate = null;
                if (array_key_exists($row->f_idcode, $on_sale_items)) {
                    $on_sale = $on_sale_items[$row->f_idcode]['on_sale'];
                    $discount_type = $on_sale_items[$row->f_idcode]['discount_type'];
                    $discount_rate = $on_sale_items[$row->f_idcode]['discount_rate'];
                }
        

                $results[] = [
                    'id' => $row->id,
                    'item_code' => $row->f_idcode,
                    'item_name' => $row->f_name_name,
                    'alt' => $row->image_alt ? $row->image_alt : $row->f_item_name,
                    'category' => $row->f_category,
                    'category_id' => $row->f_cat_id,
                    'default_price' => $row->f_default_price,
                    'is_discounted' => $on_sale,
                    'discount_rate' => $discount_rate,
                    'discount_type' => $discount_type,
                    'image' => null,
                    'comment_count' => null,
                    'publish_date' => null,
                    'title' => null,
                    'type' => null,
                    'caption' => null,
                    'slug' => $row->slug,
                    'on_stock' => ($row->f_qty - $row->f_reserved_qty) > 0 ? 1 : 0,
                    'is_new_item' => $is_new_item,
                    'stock_uom' => $row->f_stock_uom
                ];
            }

            foreach($blogs as $blog){
                $results[] = [
                    'id' => $blog->id,
                    'item_code' => null,
                    'item_name' => null,
                    'category' => null,
                    'category_id' => null,
                    'default_price' => 0,
                    'is_discounted' => 0,
                    'discount_rate' => 0,
                    'discount_type' => null,
                    'image' => $blog->{'blogprimayimage-journal'},
                    'comment_count' => 0,
                    'publish_date' => $blog->datepublish,
                    'title' => $blog->blogtitle,
                    'type' => $blog->blogtype,
                    'caption' => $blog->blog_caption,
                    'blog_slug' => $blog->slug,
                    'f_qty' => 0,
                    'f_reserved_qty' => 0,
                    'stock_uom' => null
                ];
            }

            $recently_added_items = collect($product_list)->where('f_new_item', 1)->pluck('f_idcode');

            // Get current page form url e.x. &page=1
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            // Create a new Laravel collection from the array data3
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

            $products = $blogs = [];

            $recently_added_item_codes = collect($recently_added_items)->toArray();

            $results_item_codes = array_column($results->items(), 'item_code');

            $product_reviews = $this->getProductRating($results_item_codes);

            $clearance_sale_items = [];
            if (count($results_item_codes) > 0) {
                $product_list_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $results_item_codes)
                    ->select('imgprimayx', 'idcode')->get();
                $product_list_images = collect($product_list_images)->groupBy('idcode')->toArray();

                $clearance_sale_items = $this->isIncludedInClearanceSale($results_item_codes);
            }

            $blog_ids = collect($results->items())->where('item_code', null);
            $blog_ids = array_column($blog_ids->toArray(), 'id');
            $blog_ids = (count($blog_ids) > 0) ? $blog_ids : [];  

            $blog_comments = DB::table('fumaco_comments')->whereIn('blog_id', $blog_ids)->where('blog_status', 1)
                 ->selectRaw('COUNT(id) as count, blog_id')->groupBy('blog_id')
                 ->pluck('count', 'blog_id');
            $sale_per_category = [];
            if (!$sale && !Auth::check()) {
                $item_categories = array_column($results->items(), 'category_id');
                $sale_per_category = $this->getSalePerItemCategory($item_categories);
            }

            if (Auth::check()) {
                $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

                $sale = $customer_group_sale ? $customer_group_sale : $sale;
            }
    
            foreach ($results as $result) {
                $r_item_code = $result['item_code'];
                if($r_item_code != null) {
                    $image = null;
                    if (array_key_exists($r_item_code, $product_list_images)) {
                        $image = $product_list_images[$r_item_code][0]->imgprimayx;
                    }

                    $item_detail = [
                        'default_price' => $result['default_price'],
                        'category_id' => $result['category_id'],
                        'item_code' => $r_item_code,
                        'discount_type' => $result['discount_type'],
                        'discount_rate' => $result['discount_rate'],
                        'stock_uom' => $result['stock_uom'],
                        'on_sale' => $result['is_discounted']
                    ];

                    $is_on_clearance_sale = false;
                    if (array_key_exists($r_item_code, $clearance_sale_items)) {
                        $item_detail['discount_type'] = $clearance_sale_items[$r_item_code][0]->discount_type;
                        $item_detail['discount_rate'] = $clearance_sale_items[$r_item_code][0]->discount_rate;
                        $is_on_clearance_sale = true;
                    }

                    // get item price, discounted price and discount rate
                    $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);
                    // get product reviews
                    $total_reviews = array_key_exists($r_item_code, $product_reviews) ? $product_reviews[$r_item_code]['total_reviews'] : 0;
                    $overall_rating = array_key_exists($r_item_code, $product_reviews) ? $product_reviews[$r_item_code]['overall_rating'] : 0;

                    $products[] = [
                        'id' => $result['id'],
                        'item_code' => $r_item_code,
                        'item_name' => $result['item_name'],
                        'alt' => $result['alt'],
                        'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                        'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                        'on_stock' => $result['on_stock'],
                        'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                        'discount_display' => $item_price_data['discount_display'],
                        'image' => $image,
                        'slug' => $result['slug'],
                        'is_new_item' => $result['is_new_item'],
                        'overall_rating' => $overall_rating,
                        'total_reviews' => $total_reviews,
                    ];
                } else {
                    $blog_comment_count = array_key_exists($result['id'], $blog_comments->toArray()) ? $blog_comments[$result['id']] : 0;
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
            $item_code_array = [];
            $blog_id_array = [];
            $prod_results = null;
            $blog_results = null;

            if($request->s != ''){// Save search terms
                $loc = GeoLocation::lookup($request->ip());

                $search_data = [
                    'search_term' => str_replace('"', "'", $request->s),
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

                if(empty($request->name)) {
                    DB::table('fumaco_search_terms')->insert($search_data);
                }

                $search_id = DB::table('fumaco_search_terms')->orderBy('id', 'desc')->pluck('id')->first();

                $search_results_data['search_id'] = $search_id;
                if(empty($request->name)) {
                    $checker = DB::table('fumaco_search_results')->where('search_term', $search_data['search_term'])->where('prod_results', $prod_results)->where('blog_results', $blog_results)->count();

                    if($checker == 0){
                        DB::table('fumaco_search_results')->insert($search_results_data);
                    }
                }

                $recently_added_items_query = DB::table('fumaco_items')->whereIn('f_idcode', $recently_added_item_codes)
                    ->when(count($clearance_sale_item_codes) > 0, function($c) use ($clearance_sale_item_codes) {
                        $c->whereNotIn('f_idcode', $clearance_sale_item_codes);
                    })
                    ->select('f_idcode', 'f_default_price', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_qty', 'f_reserved_qty', 'slug', 'f_name_name')->get();
           
                $recently_added_items_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $recently_added_item_codes)
                    ->select('imgprimayx', 'idcode')->get();
                $recently_added_items_images = collect($recently_added_items_images)->groupBy('idcode')->toArray();
                
                $sale_per_category = [];
                if (!$sale && !Auth::check()) {
                    $item_categories = array_column($recently_added_items_query->toArray(), 'f_cat_id');
                    $sale_per_category = $this->getSalePerItemCategory($item_categories);
                }

                if (Auth::check()) {
                    $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

                    $sale = $customer_group_sale ? $customer_group_sale : $sale;
                }

                if($request->ajax()){
                    return view('frontend.search_results_list', compact('products', 'blogs', 'recently_added_arr', 'results'));
                }
            }

            // Filters
            $filters = DB::table('fumaco_items as a')
                ->join('fumaco_items_attributes as b', 'a.f_idcode', 'b.idcode')
                ->join('fumaco_attributes_per_category as c', 'c.id', 'b.attribute_name_id')
                ->where('a.f_status', 1)->where('c.status', 1)
                ->when(count($item_code_array) > 0, function($c) use ($item_code_array) {
                    $c->whereIn('a.f_idcode', $item_code_array);
                })
                ->when(count($clearance_sale_item_codes) > 0, function($c) use ($clearance_sale_item_codes) {
                    $c->whereNotIn('a.f_idcode', $clearance_sale_item_codes);
                })
                ->select('c.attribute_name', 'b.attribute_value')
                ->groupBy('c.attribute_name', 'b.attribute_value')->get();

            $filters = collect($filters)->groupBy('attribute_name')->map(function($r, $d){
                return array_unique(array_column($r->toArray(), 'attribute_value'));
            });

            $brands = DB::table('fumaco_items')->where('f_status', 1)->whereNotNull('f_brand')
                ->when(count($request_data) > 1, function($c) use ($filtered_item_codes) {
                    $c->whereIn('f_idcode', $filtered_item_codes);
                })
                ->when(count($request_data) < 1, function($c) use ($products){
                    $c->whereIn('f_idcode', collect($products)->pluck('item_code'));
                })
                ->when(count($clearance_sale_item_codes) > 0, function($c) use ($clearance_sale_item_codes) {
                    $c->whereNotIn('f_idcode', $clearance_sale_item_codes);
                })
                ->distinct()->pluck('f_brand');

            $filter_count = count($filters);

            $filters['Brand'] = $brands;

            $filters_arr = $filters;
            $filters = [];
            foreach($filters_arr as $key => $filter){
                $filters[$key] = collect($filter)->sortBy(null, SORT_NATURAL)->values();
            }

            return view('frontend.search_results', compact('results', 'blogs', 'products', 'recently_added_arr' ,'filters', 'filter_count', 'brand_filter'));
        }

        $carousel_data = DB::table('fumaco_header')->where('fumaco_status', 1)
            ->select('fumaco_title', 'fumaco_caption', 'fumaco_image1', 'fumaco_image2', 'fumaco_image3', 'text_color', 'btn_position', 'fumaco_url', 'fumaco_btn_name')
            ->orderBy('fumaco_active', 'desc')->get();

        $onsale_carousel_data = DB::table('fumaco_on_sale')
            ->where('status', 1)->where('banner_image', '!=', null)->where('is_clearance_sale', 0)
            ->whereDate('start_date', '<=', Carbon::now()->startOfDay())->whereDate('end_date', '>=', Carbon::now()->endOfDay())
            ->pluck('banner_image');

        $blogs = DB::table('fumaco_blog')->where('blog_featured', 1)
            ->where('blog_enable', 1)->select('slug', 'id', 'blogprimayimage-home', 'blogtitle', 'blog_caption')
            ->limit(3)->get();

        // get best selling items
        $best_selling_query = DB::table('fumaco_items')->where('f_status', 1)->where('f_featured', 1)
            ->when(count($clearance_sale_item_codes) > 0, function($c) use ($clearance_sale_item_codes) {
                $c->whereNotIn('f_idcode', $clearance_sale_item_codes);
            })
            ->select('f_idcode', 'f_default_price', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_qty', 'f_reserved_qty', 'slug', 'f_name_name', 'f_item_name', 'image_alt')->get();

        $best_selling_item_codes = array_column($best_selling_query->toArray(), 'f_idcode');

        $clearance_sale_items = $on_sale_items = [];
        if (count($best_selling_item_codes) > 0) {
            $best_selling_item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $best_selling_item_codes)
                ->select('imgprimayx', 'idcode')->get();
            $best_selling_item_images = collect($best_selling_item_images)->groupBy('idcode')->toArray();

            $product_reviews = $this->getProductRating($best_selling_item_codes);

            $clearance_sale_items = $this->isIncludedInClearanceSale($best_selling_item_codes);

            $on_sale_items = $this->onSaleItems($best_selling_item_codes);
        }

        $onsale_item_codes = $this->onSaleItems([]);
        $on_sale_query = DB::table('fumaco_items')->where('f_status', 1)
            ->whereIn('f_idcode', array_keys($onsale_item_codes))
            ->when(count($clearance_sale_item_codes) > 0, function($c) use ($clearance_sale_item_codes) {
                $c->whereNotIn('f_idcode', $clearance_sale_item_codes);
            })
            ->select('f_idcode', 'f_default_price', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_qty', 'f_reserved_qty', 'slug', 'f_name_name', 'f_item_name', 'image_alt')->get();

        $sale_per_category = [];
        if (!$sale && !Auth::check()) {
            $best_selling_item_categories = array_column($best_selling_query->toArray(), 'f_cat_id');
            $on_sale_item_categories = array_column($on_sale_query->toArray(), 'f_cat_id');
            $item_categories_arr = array_merge($best_selling_item_categories, $on_sale_item_categories);
            $sale_per_category = $this->getSalePerItemCategory($item_categories_arr);
        }

        if (Auth::check()) {
            $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

            $sale = $customer_group_sale ? $customer_group_sale : $sale;
        }

        $best_selling_arr = [];
        foreach($best_selling_query as $row){
            $image = null;
            if (array_key_exists($row->f_idcode, $best_selling_item_images)) {
                $image = $best_selling_item_images[$row->f_idcode][0]->imgprimayx;
            }

            $is_new_item = 0;
            if($row->f_new_item == 1){
                if($row->f_new_item_start <= Carbon::now() and $row->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }

            $on_sale = false;
            $discount_type = $discount_rate = null;
            if (array_key_exists($row->f_idcode, $on_sale_items)) {
                $on_sale = $on_sale_items[$row->f_idcode]['on_sale'];
                $discount_type = $on_sale_items[$row->f_idcode]['discount_type'];
                $discount_rate = $on_sale_items[$row->f_idcode]['discount_rate'];
            }

            $item_detail = [
                'default_price' => $row->f_default_price,
                'category_id' => $row->f_cat_id,
                'item_code' => $row->f_idcode,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
                'stock_uom' => $row->f_stock_uom,
                'on_sale' => $on_sale
            ];

            $is_on_clearance_sale = false;
            if (array_key_exists($row->f_idcode, $clearance_sale_items)) {
                $item_detail['discount_type'] = $clearance_sale_items[$row->f_idcode][0]->discount_type;
                $item_detail['discount_rate'] = $clearance_sale_items[$row->f_idcode][0]->discount_rate;
                $is_on_clearance_sale = true;
            }

            // get item price, discounted price and discount rate
            $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);
            // get product reviews
            $total_reviews = array_key_exists($row->f_idcode, $product_reviews) ? $product_reviews[$row->f_idcode]['total_reviews'] : 0;
            $overall_rating = array_key_exists($row->f_idcode, $product_reviews) ? $product_reviews[$row->f_idcode]['overall_rating'] : 0;

            $best_selling_arr[] = [
                'item_code' => $row->f_idcode,
                'item_name' => $row->f_name_name,
                'alt' => $row->image_alt ? $row->image_alt : $row->f_item_name,
                'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                'on_stock' => ($row->f_qty - $row->f_reserved_qty) > 0 ? 1 : 0,
                'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                'discount_display' => $item_price_data['discount_display'],
                'image' => $image,
                'slug' => $row->slug,
                'is_new_item' => $is_new_item,
                'overall_rating' => $overall_rating,
                'total_reviews' => $total_reviews
            ];
        }

        $clearance_sale_items = $on_sale_items = [];
        if (count(array_keys($onsale_item_codes)) > 0) {
            $onsale_item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', array_keys($onsale_item_codes))
                ->select('imgprimayx', 'idcode')->get();
            $onsale_item_images = collect($onsale_item_images)->groupBy('idcode')->toArray();

            $product_reviews = $this->getProductRating($onsale_item_codes);

            $clearance_sale_items = $this->isIncludedInClearanceSale(array_keys($onsale_item_codes));

            $on_sale_items = $this->onSaleItems(array_keys($onsale_item_codes));
        }

        $on_sale_arr = [];
        foreach($on_sale_query as $row){
            $image = null;
            if (array_key_exists($row->f_idcode, $onsale_item_images)) {
                $image = $onsale_item_images[$row->f_idcode][0]->imgprimayx;
            }
            
            $is_new_item = 0;
            if($row->f_new_item == 1){
                if($row->f_new_item_start <= Carbon::now() and $row->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }

            $on_sale = false;
            $discount_type = $discount_rate = null;
            if (array_key_exists($row->f_idcode, $on_sale_items)) {
                $on_sale = $on_sale_items[$row->f_idcode]['on_sale'];
                $discount_type = $on_sale_items[$row->f_idcode]['discount_type'];
                $discount_rate = $on_sale_items[$row->f_idcode]['discount_rate'];
            }

            $item_detail = [
                'default_price' => $row->f_default_price,
                'category_id' => $row->f_cat_id,
                'item_code' => $row->f_idcode,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
                'stock_uom' => $row->f_stock_uom,
                'on_sale' => $on_sale
            ];

            $is_on_clearance_sale = false;
            if (array_key_exists($row->f_idcode, $clearance_sale_items)) {
                $item_detail['discount_type'] = $clearance_sale_items[$row->f_idcode][0]->discount_type;
                $item_detail['discount_rate'] = $clearance_sale_items[$row->f_idcode][0]->discount_rate;
                $is_on_clearance_sale = true;
            }

            // get item price, discounted price and discount rate
            $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);
            // get product reviews
            $total_reviews = array_key_exists($row->f_idcode, $product_reviews) ? $product_reviews[$row->f_idcode]['total_reviews'] : 0;
            $overall_rating = array_key_exists($row->f_idcode, $product_reviews) ? $product_reviews[$row->f_idcode]['overall_rating'] : 0;
            
            $on_sale_arr[] = [
                'item_code' => $row->f_idcode,
                'item_name' => $row->f_name_name,
                'alt' => $row->image_alt ? $row->image_alt : $row->f_item_name,
                'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                'on_stock' => ($row->f_qty - $row->f_reserved_qty) > 0 ? 1 : 0,
                'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                'discount_display' => $item_price_data['discount_display'],
                'image' => $image,
                'slug' => $row->slug,
                'is_new_item' => $is_new_item,
                'overall_rating' => $overall_rating,
                'total_reviews' => $total_reviews
            ];
        }

        // get image for social media sharing
        $image_for_sharing = null;
        $default_image_for_sharing = DB::table('fumaco_social_image')->where('is_default', 1)->where('page_type', 'main_page')->select('filename')->first();
        if ($default_image_for_sharing) {
            $image_for_sharing = ($default_image_for_sharing->filename) ? asset('/storage/social_images/'. $default_image_for_sharing->filename) : null;
        } 

        $page_meta = DB::table('fumaco_pages')->where('is_homepage', 1)->select('page_title', 'meta_description', 'meta_keywords', 'page_name')->first();

        $brands = DB::table('fumaco_about_partners')->where('xstatus', 1)->orderBy('partners_sort', 'asc')->pluck('image');

        return view('frontend.homepage', compact('carousel_data', 'onsale_carousel_data', 'blogs', 'best_selling_arr', 'on_sale_arr', 'page_meta', 'image_for_sharing', 'brands'));
    }

    public function getAutoCompleteData(Request $request){
        if($request->ajax() and $request->search_term){
            $search_str = $request->search_term;
            $item_keywords = DB::table('fumaco_items as item')
                ->where(function ($q) use ($search_str){
                    return $q->where('f_brand', 'LIKE', "%".$search_str."%")
                        ->orWhere('f_parent_code', 'LIKE', "%".$search_str."%")
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
                        ->orWhere('f_category', 'LIKE', "%".$search_str."%");
                })
                ->where('f_status', 1)
                ->where('item.f_status', 1)
                ->select('item.f_idcode', 'item.f_name_name', 'item.f_default_price', 'item.f_cat_id', 'item.slug as item_slug', 'item.f_stock_uom', 'f_cat_id')
                ->orderBy('f_order_by', 'asc')->limit($request->type == 'desktop' ? 8 : 4)->get();

            // get sitewide sale
            $sale = DB::table('fumaco_on_sale')
                ->whereDate('start_date', '<=', Carbon::now()->toDateString())
                ->whereDate('end_date', '>=', Carbon::today()->toDateString())
                ->where('status', 1)->where('apply_discount_to', 'All Items')->first();

            $search_arr = [];
            $search_arr['screen'] = $request->type;
            $search_arr['results_count'] = count($item_keywords);

            $categories = DB::table('fumaco_categories')->get();
            $category = collect($categories)->groupBy('id');

            $ik_items = array_column($item_keywords->toArray(), 'f_idcode'); 

            $clearance_sale_items = $on_sale_items = [];
            if (count($ik_items) > 0) {
                $ik_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $ik_items)
                    ->select('imgprimayx', 'idcode')->get();
                $ik_images = collect($ik_images)->groupBy('idcode')->toArray();
    
                $product_reviews = $this->getProductRating($ik_items);

                $clearance_sale_items = $this->isIncludedInClearanceSale($ik_items);

                $on_sale_items = $this->onSaleItems($ik_items);
            }   

            $sale_per_category = [];
            if (!$sale && !Auth::check()) {
                $item_categories = array_column($item_keywords->toArray(), 'f_cat_id');
                $sale_per_category = $this->getSalePerItemCategory($item_categories);
            }

            if (Auth::check()) {
                $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

                $sale = $customer_group_sale ? $customer_group_sale : $sale;
            }

            foreach($item_keywords as $item){
                $image = null;
                if (array_key_exists($item->f_idcode, $ik_images)) {
                    $image = $ik_images[$item->f_idcode][0]->imgprimayx;
                }

                $on_sale = false;
                $discount_type = $discount_rate = null;
                if (array_key_exists($item->f_idcode, $on_sale_items)) {
                    $on_sale = $on_sale_items[$item->f_idcode]['on_sale'];
                    $discount_type = $on_sale_items[$item->f_idcode]['discount_type'];
                    $discount_rate = $on_sale_items[$item->f_idcode]['discount_rate'];
                }

                $item_detail = [
                    'default_price' => $item->f_default_price,
                    'category_id' => $item->f_cat_id,
                    'item_code' => $item->f_idcode,
                    'discount_type' => $discount_type,
                    'discount_rate' => $discount_rate,
                    'stock_uom' => $item->f_stock_uom,
                    'on_sale' => $on_sale
                ];

                $is_on_clearance_sale = false;
                if (array_key_exists($item->f_idcode, $clearance_sale_items)) {
                    $item_detail['discount_type'] = $clearance_sale_items[$item->f_idcode][0]->discount_type;
                    $item_detail['discount_rate'] = $clearance_sale_items[$item->f_idcode][0]->discount_rate;
                    $is_on_clearance_sale = true;
                }

                // get item price, discounted price and discount rate
                $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);

                $category_name = isset($category[$item->f_cat_id]) ? $category[$item->f_cat_id][0]->name : null;

                $search_arr[] = [
                    'type' => 'Products',
                    'id' => $item->f_idcode,
                    'name' => $item->f_name_name,
                    'image' => $image,
                    'slug' => $item->item_slug,
                    'category' => $category_name,
                    'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                    'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                    'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                    'discount_display' => $item_price_data['discount_display'],
                ];
            }

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

            foreach($blogs as $blog){
                $search_arr[] = [
                    'type' => 'Blogs',
                    'id' => $blog->id,
                    'name' => $blog->blogtitle,
                    'image' => $blog->blogprimaryimage,
                    'slug' => $blog->slug,
                ];
            }

            return view('frontend.search_autocomplete', compact('search_arr'));
        }
    }

    public function newsletterSubscription(Request $request){
        DB::beginTransaction();
        try{
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255'],
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

            $checker = DB::table('fumaco_subscribe')->where('email', $request->email)->exists();

            if($checker){
                return redirect()->back()->with('error_subscribe', 'Email already subscribed!');
            }

            $insert = [
                'email' => $request->email,
                'status' => 1,
                'ip_logs' => $request->ip()
            ];

            DB::table('fumaco_subscribe')->insert($insert);

            if(Newsletter::isSubscribed($request->email) == 0){
                Newsletter::subscribeOrUpdate($request->email);
            }else{
                Newsletter::subscribe($request->email);
            }

            $featured_items = DB::table('fumaco_items')->where('f_status', 1)->where('f_featured', 1)->limit(4)->get();
            $featured = [];

            $featured_item_codes = collect($featured_items)->pluck('f_idcode');

            $sale = DB::table('fumaco_on_sale')
                ->whereDate('start_date', '<=', Carbon::now()->toDateString())
                ->whereDate('end_date', '>=', Carbon::today()->toDateString())
                ->where('status', 1)->where('apply_discount_to', 'All Items')
                ->select('discount_type', 'discount_rate')->first();

            $product_reviews = $this->getProductRating($featured_item_codes);
            $clearance_sale_items = $this->isIncludedInClearanceSale($featured_item_codes);
            $on_sale_items = $this->onSaleItems($featured_item_codes);

            $item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $featured_item_codes)
                ->select('imgprimayx', 'idcode')->get();
            $item_images = collect($item_images)->groupBy('idcode')->toArray();

            $sale_per_category = [];
            if (!$sale && !Auth::check()) {
                $item_categories = array_column($featured_items->toArray(), 'f_cat_id');
                $sale_per_category = $this->getSalePerItemCategory($item_categories);
            }

            if (Auth::check()) {
                $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

                $sale = $customer_group_sale ? $customer_group_sale : $sale;
            }

            foreach($featured_items as $row){
                $image = null;
                if (array_key_exists($row->f_idcode, $item_images)) {
                    $image = $item_images[$row->f_idcode][0]->imgprimayx;
                }

                $bs_item_name = $row->f_name_name;
                $item_code = $row->f_idcode;

                $is_new_item = 0;
                if($row->f_new_item == 1){
                    if($row->f_new_item_start <= Carbon::now() and $row->f_new_item_end >= Carbon::now()){
                        $is_new_item = 1;
                    }
                }

                $on_sale = false;
                $discount_type = $discount_rate = null;
                if (array_key_exists($row->f_idcode, $on_sale_items)) {
                    $on_sale = $on_sale_items[$row->f_idcode]['on_sale'];
                    $discount_type = $on_sale_items[$row->f_idcode]['discount_type'];
                    $discount_rate = $on_sale_items[$row->f_idcode]['discount_rate'];
                }

                $item_detail = [
                    'default_price' => $row->f_default_price,
                    'category_id' => $row->f_cat_id,
                    'item_code' => $row->f_idcode,
                    'discount_type' => $discount_type,
                    'discount_rate' => $discount_rate,
                    'stock_uom' => $row->f_stock_uom,
                    'on_sale' => $on_sale
                ];

                $is_on_clearance_sale = false;
                if (array_key_exists($row->f_idcode, $clearance_sale_items)) {
                    $item_detail['discount_type'] = $clearance_sale_items[$row->f_idcode][0]->discount_type;
                    $item_detail['discount_rate'] = $clearance_sale_items[$row->f_idcode][0]->discount_rate;
                    $is_on_clearance_sale = true;
                }

                // get item price, discounted price and discount rate
                $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);
                // get product reviews
                $total_reviews = array_key_exists($item_code, $product_reviews) ? $product_reviews[$item_code]['total_reviews'] : 0;
                $overall_rating = array_key_exists($item_code, $product_reviews) ? $product_reviews[$item_code]['overall_rating'] : 0;

                $featured[] = [
                    'id' => $row->id,
                    'item_code' => $row->f_idcode,
                    'item_name' => $row->f_name_name,
                    'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                    'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                    'on_stock' => ($row->f_qty - $row->f_reserved_qty) > 0 ? 1 : 0,
                    'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                    'discount_display' => $item_price_data['discount_display'],
                    'image' => $image,
                    'slug' => $row->slug,
                    'is_new_item' => $is_new_item,
                    'overall_rating' => $overall_rating,
                    'total_reviews' => $total_reviews,
                ];
            }

            try {
                Mail::send('emails.new_subscriber', ['featured' => $featured], function($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Thank you for subscribing - FUMACO');
                });
            } catch (\Swift_TransportException  $e) {
                return redirect()->back()->with('error', 'An error occured. Please try again.');
            }

            // check for failures
            if (Mail::failures()) {
                return redirect()->back()->with('error', 'An error occured. Please try again.');
            }

            DB::commit();

            return redirect('/thankyou');
        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
        }
    }

    public function subscribeThankyou(){
        return view('frontend.subscribe_thankyou');
    }

    public function notifyMe(Request $request){
        DB::beginTransaction();
        try {
            if(!Auth::check()){
                return redirect('/login');
            }

            $checker = DB::table('product_notifications')->where('owner', Auth::user()->username)->where('item_code', $request->item_code)->exists();

            if(!$checker){
                DB::table('product_notifications')->insert([
                    'owner' => Auth::user()->username,
                    'item_code' => $request->item_code,
                    'created_by' => Auth::user()->username
                ]);
            }

            DB::commit();
            return response()->json(['success' => 'You will be notified when this product is available.']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json(['error' => 'An error occured. Please try again later']);
        }
    }

    // returns an array of product category
    public function getProductCategories() {
        $item_categories = DB::table('fumaco_categories')->where('publish', 1)->select('slug', 'id', 'external_link', 'image', 'name', 'new', 'new_tag_start', 'new_tag_end')->get();
        $cat_array = [];

        foreach($item_categories as $category){
            $icon = $category->image;
            if(File::exists(public_path('/assets/site-img/icon/'.explode('.', $category->image)[0].'.webp'))){
                $icon = explode('.', $category->image)[0].'.webp';
            }
            $is_new = 0;
            $start = $end = null;
            if($category->new == 1){
                if($category->new_tag_start && $category->new_tag_end){
                    $start = Carbon::parse($category->new_tag_start)->startOfDay();
                    $end = Carbon::parse($category->new_tag_end)->endOfDay();
                    if(Carbon::now() > $start && Carbon::now() < $end){
                        $is_new = 1;
                    }
                }
            }

            $cat_array[] = [
                'slug' => $category->slug,
                'id' => $category->id,
                'external_link' => $category->external_link,
                'image' => $icon,
                'name' => $category->name,
                'is_new' => $is_new
            ];
        }

        return response()->json($cat_array);
    }

    public function pagesList(Request $request){
        // Policy Pages
        if($request->ajax()){
            $pages = DB::table('fumaco_pages')->select('page_title', 'slug')->where('is_homepage', 0)->get();

            return response()->json($pages);
        }
    }

    public function contactInformation(Request $request){
        if($request->ajax()){
            $contact = DB::table('fumaco_contact')->select('office_phone', 'office_email')->first();
            $contact = $contact ? $contact : [];

            return response()->json($contact);
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
        return DB::table('fumaco_settings')->select('set_value')->first();
    }

    // /user_register
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
                ],
                'username.unique' => 'The email address has already been taken.',
            ]);

            $user = new User;
            $user->username = trim($request->username);
            $user->password = Hash::make($request->password);
            $user->f_name = $request->first_name;
            $user->f_lname = $request->last_name;
            $user->f_email = 'Website Account';
            $user->f_temp_passcode = 'fumaco12345';
            $user->save();

            $token = Str::random(64);
            UserVerify::create([
                'user_id' => $user->id, 
                'token' => $token
            ]);

            $customer_group_id = DB::table('fumaco_users')->where('id', $user->id)->pluck('customer_group')->first();
            $customer_group = DB::table('fumaco_customer_group')->where('id', $customer_group_id)->pluck('customer_group_name')->first();

            if(!Newsletter::hasMember($user->username)){
                Newsletter::subscribe($user->username, ['FNAME' => $user->f_name, 'LNAME' => $user->f_lname]);
                Newsletter::addTags([$customer_group], $user->username);
            }

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
            }else{
                Newsletter::unsubscribe($user->username);
            }

            Mail::send('emails.verify_email', ['token' => $token], function($message) use($request){
                $message->to($request->username);
                $message->subject('Verify email from Fumaco.com');
            });

            DB::commit();
            return redirect('/myprofile/verify/email')->with('email', $request->username);
        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error', 'An error occured. Please try again.');
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
   
            try {
                Mail::send('emails.verify_email', ['token' => $token], function($message) use($email){
                    $message->to($email);
                    $message->subject('Verify email from Fumaco.com');
                });
            } catch (\Swift_TransportException  $e) {

            }
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

                try {
                    Mail::send('emails.welcome', ['username' => trim($user->username), 'password' => $user->password], function($message) use($user){
                        $message->to($user->username);
                        $message->subject('Welcome Email from Fumaco.com');
                    });
                } catch (\Swift_TransportException  $e) {
    
                }
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

        $partners = DB::table('fumaco_about_partners')->where('xstatus', 1)->select('image')->orderBy('partners_sort', 'asc')->get();

        $bg1 = explode('.',$about_data->background_1);
        $bg2 = explode('.',$about_data->background_2);
        $bg3 = explode('.',$about_data->background_3);

        $image_for_sharing = null;
        // get image for social media sharing
        $default_image_for_sharing = DB::table('fumaco_social_image')->where('is_default', 1)->where('page_type', 'main_page')->select('filename')->first();
        if ($default_image_for_sharing) {
            $image_for_sharing = ($default_image_for_sharing->filename) ? asset('/storage/social_images/'. $default_image_for_sharing->filename) : null;
        } 

        return view('frontend.about_page', compact('about_data', 'partners', 'bg1', 'bg2', 'bg3', 'image_for_sharing'));
    }

    public function viewJournalsPage(Request $request) {
        $blog_carousel = DB::table('fumaco_blog')->where('blog_enable', 1)
            ->where('blog_featured', 1)->orderBy('blog_active', 'desc')
            ->select('blog_caption', 'blogprimaryimage', 'blogprimayimage-mob', 'blogprimayimage-tab', 'blogtitle', 'slug', 'id', 'image_alt')->get();

        $blog_counts = DB::table('fumaco_blog')->where('blog_enable', 1)
            ->selectRaw('blogtype, COUNT(id) as count')
            ->groupBy('blogtype')->pluck('count', 'blogtype')
            ->toArray();

        $blog_counts = [
            'all' => array_sum($blog_counts),
            'applications' => (array_key_exists('In Applications', $blog_counts)) ? $blog_counts['In Applications'] : 0,
            'solutions' => (array_key_exists('Solutions', $blog_counts)) ? $blog_counts['Solutions'] : 0,
            'products' => (array_key_exists('Products', $blog_counts)) ? $blog_counts['Products'] : 0
        ];

        $blog_list = DB::table('fumaco_blog')->where('blog_enable', 1)
            ->when($request->type, function ($q) use ($request){
                $q->where('blogtype', $request->type);
            })
            ->select('id', 'blogprimayimage-journal', 'datepublish', 'blogtitle', 'blog_caption', 'blogtype', 'slug', 'image_alt')->get()->toArray();

        $blog_ids = array_column($blog_list, 'id');
        $blog_comments = DB::table('fumaco_comments')->whereIn('blog_id', $blog_ids)->where('blog_status', 1)
            ->selectRaw('count(blog_id) as count, blog_id')->groupBy('blog_id')->pluck('count', 'blog_id')->toArray();

        $blogs_arr = [];
        foreach($blog_list as $blogs){
            $blogs_arr[] = [
                'id' => $blogs->id,
                'comment_count' => (array_key_exists($blogs->id, $blog_comments)) ? $blog_comments[$blogs->id] : 0,
                'image' => $blogs->{'blogprimayimage-journal'},
                'publish_date' => $blogs->datepublish,
                'title' => $blogs->blogtitle,
                'caption' => $blogs->blog_caption,
                'type' => $blogs->blogtype,
                'slug' => $blogs->slug,
                'alt' => $blogs->image_alt ? $blogs->image_alt : $blogs->blogtitle
            ];
        }

        return view('frontend.journals', compact('blog_carousel', 'blog_counts', 'blogs_arr'));
    }

    public function viewBlogPage($slug) {
        $blog = DB::table('fumaco_blog')->where('slug', $slug)->orWhere('id', $slug)
            ->select('blogprimaryimage', 'id', 'blog_caption', 'blogtitle', 'datepublish', 'blogtype', 'blogcontent')->first();

        $blog_comment = DB::table('fumaco_comments')->where('blog_id', $blog->id)->where('blog_type', 1)->where('blog_status', 1)
            ->select('id', 'blog_email', 'blog_name', 'blog_comments', 'blog_date')->get()->toArray();

        $blog_comment_ids = array_column($blog_comment, 'id');
        $blog_replies = DB::table('fumaco_comments')->where('blog_id', $blog->id)->where('blog_type', 2)->whereIn('reply_id', $blog_comment_ids)->where('blog_status', 1)
            ->select('blog_name', 'blog_date', 'blog_comments', 'blog_id')->get();
        $blog_replies = collect($blog_replies)->groupBy('blog_id')->toArray();

        $comment_count = count($blog_comment);

        $comments_arr = [];
        foreach($blog_comment as $comment){
            $replies_arr = [];
            $blog_reply = (array_key_exists($comment->id, $blog_replies)) ? $blog_replies[$comment->id] : [];
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

        $blog_tags = DB::table('fumaco_blog_tag')->where('blog_id', $blog->id)->select('tagname')->first();
        $tags = '';
        if($blog_tags){
            $tags = explode(',', str_replace(array('"','"'), '',trim($blog_tags->tagname, '[]')));
        }

        $id = $blog->id;

        return view('frontend.blogs', compact('blog', 'comments_arr', 'id', 'comment_count', 'blog_tags', 'tags'));
    }

    public function viewContactPage() {
        $fumaco_contact = DB::table('fumaco_contact')->select('id', 'office_title', 'office_address')->get();
        $fumaco_contact_numbers = DB::table('fumaco_contact_numbers')->whereIn('parent', collect($fumaco_contact)->pluck('id'))->get();
        $fumaco_contact_numbers = collect($fumaco_contact_numbers)->groupBy('parent');

        $contact_info = [];
        foreach($fumaco_contact as $contact){
            $info_arr = [];
            if(isset($fumaco_contact_numbers[$contact->id])){
                foreach($fumaco_contact_numbers[$contact->id] as $info){
                    $info_arr[] = [
                        'type' => $info->type,
                        'contact' => $info->contact,
                        'apps' => $info->messaging_apps
                    ];
                }
            }
            $contact_info[] = [
                'title' => $contact->office_title,
                'address' => $contact->office_address,
                'info' => $info_arr
            ];
        }

        $fumaco_map = DB::table('fumaco_map_1')->select('map_url')->first();

        $image_for_sharing = null;
        // get image for social media sharing
        $default_image_for_sharing = DB::table('fumaco_social_image')->where('is_default', 1)->where('page_type', 'main_page')->select('filename')->first();
        if ($default_image_for_sharing) {
            $image_for_sharing = ($default_image_for_sharing->filename) ? asset('/storage/social_images/'. $default_image_for_sharing->filename) : null;
        } 

        return view('frontend.contact', compact('fumaco_contact', 'fumaco_map', 'image_for_sharing', 'contact_info'));
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
                'g-recaptcha-response' => 'recaptcha',
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
                try {
                    Mail::send('emails.new_contact', ['new_contact' => $new_contact, 'client' => 0], function($message) use ($email_recipient) {
                        $message->to($email_recipient);
                        $message->subject('New Contact - FUMACO');
                    });
                } catch (\Swift_TransportException  $e) {
    
                }
            }
            
            try {
                // send email to client 
                Mail::send('emails.new_contact', ['new_contact' => $new_contact, 'client' => 1], function($message) use ($request) {
                    $message->to(trim($request->email));
                    $message->subject('Contact Us - FUMACO');
                });
            } catch (\Swift_TransportException  $e) {

            }

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

        $product_category = DB::table('fumaco_categories')->where('slug', $category_id)->orWhere('id', $category_id)->select('id', 'meta_description', 'meta_keywords', 'slug', 'name', 'banner_img')->first();

        if(!$product_category) {
            return view('error');
        }
        // get requested filters
        $request_data = $request->attr ? array_diff_key($request->attr, array_flip(['brand'])) : [];
        $attribute_name_filter = $request_data ? array_keys($request_data) : [];
        $attribute_value_filter = [];
        $brand_filter = isset($request->attr['brand']) ? $request->attr['brand'] : [];
        foreach($request_data as $data) {
            foreach ($data as $value) {
                $attribute_value_filter[] = $value;
            }
        }

        // get items based on filters
        $filtered_items = DB::table('fumaco_items as a')
            ->join('fumaco_items_attributes as b', 'a.f_idcode', 'b.idcode')
            ->join('fumaco_attributes_per_category as c', 'c.id', 'b.attribute_name_id')
            ->where('a.f_status', 1)
            ->when(count($brand_filter) > 0, function($c) use ($brand_filter) {
                $c->whereIn('a.f_brand', $brand_filter);
            })
            ->when(count($request_data) > 0, function($c) use ($attribute_name_filter, $attribute_value_filter) {
                $c->whereIn('c.slug', $attribute_name_filter)->whereIn('b.attribute_value', $attribute_value_filter);
            })
            ->select('c.slug', 'b.attribute_value', 'a.f_idcode')->get();

        $filtered_items = collect($filtered_items)->groupBy('f_idcode')->map(function($i, $q) use ($attribute_name_filter){
            $diff = array_diff($attribute_name_filter, array_column($i->toArray(), 'slug'));
            if (count($diff) == 0) {
                return array_column($i->toArray(), 'attribute_value');
            }
        });

        $filtered_items = array_keys(array_filter($filtered_items->toArray()));

        $include_bulk_item_codes = [];

        if($filtered_items){
            $include_bulk_item_codes = DB::table('fumaco_items')->where(function($q) use ($filtered_items){
                foreach($filtered_items as $items){
                    $q->orWhere('f_idcode', 'like', '%'.$items.'%');
                }
            })->pluck('f_idcode');
        }
        
        $filtered_items = collect($include_bulk_item_codes);

        // get item attributes based on item category (sidebar)
        $filters = DB::table('fumaco_items as a')
            ->join('fumaco_items_attributes as b', 'a.f_idcode', 'b.idcode')
            ->join('fumaco_attributes_per_category as c', 'c.id', 'b.attribute_name_id')
            ->where('a.f_cat_id', $product_category->id)->where('a.f_status', 1)->where('c.status', 1)
            ->when(count($request_data) > 0 || $brand_filter, function($c) use ($filtered_items) {
                $c->whereIn('a.f_idcode', $filtered_items);
            })
            ->select('c.attribute_name', 'b.attribute_value')
            ->groupBy('c.attribute_name', 'b.attribute_value')->get();

        $filters = collect($filters)->groupBy('attribute_name')->map(function($r, $d){
            return array_unique(array_column($r->toArray(), 'attribute_value'));
        });

        // get distinct brands for filtering
        $brands = DB::table('fumaco_items')->where('f_cat_id', $product_category->id)->where('f_status', 1)->whereNotNull('f_brand')
            ->when(count($request_data) > 0 || $brand_filter, function($c) use ($filtered_items) {
                $c->whereIn('f_idcode', $filtered_items);
            })->distinct()->pluck('f_brand');

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

        $filters_arr = $filters;
        $filters = [];
        foreach($filters_arr as $key => $filter){
            $filters[$key] = collect($filter)->sortBy(null, SORT_NATURAL)->values();
        }

        // get sorting value 
        $sortby = $request->sortby;
        switch ($sortby) {
            case 'Price':
                $sortby = 'f_default_price';
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
        $default_image_for_sharing = DB::table('fumaco_social_image')->where('is_default', 1)->where('category_id', $product_category->id)->select('filename')->first();
        if ($default_image_for_sharing) {
            $image_for_sharing = ($default_image_for_sharing->filename) ? asset('/storage/social_images/'. $default_image_for_sharing->filename) : null;
        } else {
            $default_image_for_sharing = DB::table('fumaco_social_image')->where('is_default', 1)->where('page_type', 'main_page')->select('filename')->first();
            if ($default_image_for_sharing) {
                $image_for_sharing = ($default_image_for_sharing->filename) ? asset('/storage/social_images/'. $default_image_for_sharing->filename) : null;
            }
        }

        // get items based on category id
        $products = DB::table('fumaco_items')->where('f_cat_id', $product_category->id)->where('f_status', 1)
            ->when(count($request_data) > 0 || $brand_filter, function($c) use ($filtered_items) {
                $c->whereIn('f_idcode', $filtered_items);
            })
            ->select('id', 'f_idcode', 'f_default_price', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_qty', 'f_reserved_qty', 'slug', 'f_name_name', 'f_item_name', 'image_alt')->orderBy($sortby, $orderby)->paginate(15);

        // get sitewide sale
        $sale = DB::table('fumaco_on_sale')
            ->whereDate('start_date', '<=', Carbon::now()->toDateString())
            ->whereDate('end_date', '>=', Carbon::today()->toDateString())
            ->where('status', 1)->where('apply_discount_to', 'All Items')
            ->select('discount_type', 'discount_rate')->first();

        $item_codes = array_column($products->items(), 'f_idcode');

        $item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $item_codes)
            ->select('imgprimayx', 'idcode')->get();
        $item_images = collect($item_images)->groupBy('idcode')->toArray();

        $product_reviews = $this->getProductRating($item_codes);
        $clearance_sale_items = $this->isIncludedInClearanceSale($item_codes);
        
        $sale_per_category = [];
        if (!$sale && !Auth::check()) {
            $item_categories = array_column($products->items(), 'f_cat_id');
            $sale_per_category = $this->getSalePerItemCategory($item_categories);
        }

        if (Auth::check()) {
            $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

            $sale = $customer_group_sale ? $customer_group_sale : $sale;
        }

        $on_sale_items = $this->onSaleItems($item_codes);

        $products_arr = [];
        foreach ($products as $product) {
            $image = null;
            if (array_key_exists($product->f_idcode, $item_images)) {
                $image = $item_images[$product->f_idcode][0]->imgprimayx;
            }

            $is_new_item = 0;
            if($product->f_new_item == 1){
                if($product->f_new_item_start <= Carbon::now() and $product->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }

            $on_sale = false;
            $discount_type = $discount_rate = null;
            if (array_key_exists($product->f_idcode, $on_sale_items)) {
                $on_sale = $on_sale_items[$product->f_idcode]['on_sale'];
                $discount_type = $on_sale_items[$product->f_idcode]['discount_type'];
                $discount_rate = $on_sale_items[$product->f_idcode]['discount_rate'];
            }

            $item_detail = [
                'default_price' => $product->f_default_price,
                'category_id' => $product->f_cat_id,
                'item_code' => $product->f_idcode,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
                'stock_uom' => $product->f_stock_uom,
                'on_sale' => $on_sale
            ];

            $is_on_clearance_sale = false;
            if (array_key_exists($product->f_idcode, $clearance_sale_items)) {
                $item_detail['discount_type'] = $clearance_sale_items[$product->f_idcode][0]->discount_type;
                $item_detail['discount_rate'] = $clearance_sale_items[$product->f_idcode][0]->discount_rate;
                $is_on_clearance_sale = true;
            }
            
            // get item price, discounted price and discount rate
            $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);
            // get product reviews
            $total_reviews = array_key_exists($product->f_idcode, $product_reviews) ? $product_reviews[$product->f_idcode]['total_reviews'] : 0;
            $overall_rating = array_key_exists($product->f_idcode, $product_reviews) ? $product_reviews[$product->f_idcode]['overall_rating'] : 0;
        
            $products_arr[] = [
                'id' => $product->id,
                'item_code' => $product->f_idcode,
                'item_name' => $product->f_name_name,
                'alt' => $product->image_alt ? $product->image_alt : $product->f_item_name,
                'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                'on_stock' => ($product->f_qty - $product->f_reserved_qty) > 0 ? 1 : 0,
                'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                'discount_display' => $item_price_data['discount_display'],
                'image' => $image,
                'slug' => $product->slug,
                'is_new_item' => $is_new_item,
                'overall_rating' => $overall_rating,
                'total_reviews' => $total_reviews
            ];
        }

        if($request->ajax()){
            return view('frontend.product_list_card', compact('products', 'products_arr'));
        }

        return view('frontend.product_list', compact('product_category', 'products_arr', 'products', 'filters', 'image_for_sharing', 'category_id'));
    }

    public function viewProduct($slug, Request $request) { // Product Page
        $product_details = DB::table('fumaco_items')->where('slug', $slug)->orWhere('f_idcode', $slug)
            ->select('f_parent_code', 'f_idcode', 'f_default_price', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_qty', 'f_reserved_qty', 'slug', 'f_name_name', 'f_item_name', 'id', 'meta_description', 'keywords', 'f_description', 'f_category', 'f_brand', 'f_caption', 'f_featured_image', 'f_full_description', 'url_title', 'image_alt')
            ->first();
        if (!$product_details) {
            return redirect('/');
        }

        $is_ordered = 0;
        if (Auth::check()) {
            $is_ordered = DB::table('fumaco_order as a')->join('fumaco_order_items as b', 'a.order_number', 'b.order_number')
                ->where('user_email', Auth::user()->username)
                ->where('b.item_code', $product_details->f_idcode)
                ->where(function($q) {
                    $q->orWhere('order_status', 'LIKE', "%completed%")
                        ->orWhere('order_status', 'LIKE', "%delivered%");
                })
                ->count();
        }

        // get items with the same parent item code
        $variant_items = DB::table('fumaco_items')
            ->where('f_status', 1)->whereNotNull('f_parent_code')
            ->where('f_parent_code', $product_details->f_parent_code)->pluck('f_idcode');

        // get attributes of all variant items
        $variant_attributes = DB::table('fumaco_items_attributes as a')
            ->join('fumaco_attributes_per_category as c', 'c.id', 'a.attribute_name_id')
            ->whereIn('idcode', $variant_items)->where('status', 1)->orderBy('a.idx', 'asc')
            ->select('attribute_value', 'attribute_name', 'idcode', 'slug', 'status')->get();
        
        $variant_combs = collect($variant_attributes)->groupBy('idcode');

        $variant_attributes = collect($variant_attributes)->groupBy('attribute_name');

        $variant_combinations = [];
        foreach ($variant_combs as $r => $s) {
            $variant_combinations[$r] = collect($s)->pluck('attribute_value', 'attribute_name');
        }

        $attrib = DB::table('fumaco_items_attributes as a')
            ->join('fumaco_attributes_per_category as c', 'c.id', 'a.attribute_name_id')
            ->where('idcode', explode('-', $product_details->f_idcode)[0]);
        
        $na_check = DB::table('fumaco_categories')->where('id', $product_details->f_cat_id)->select('hide_none')->first();
     
        $attributes = $attrib->orderBy('idx', 'asc')->select('a.attribute_value', 'c.attribute_name', 'c.status')->get();
        $filtered_attributes = collect($attributes)->pluck('attribute_value', 'attribute_name');
        if($na_check->hide_none == 1){
            $filtered_attributes = $attrib->where('a.attribute_value', 'NOT LIKE', '%n/a%')->orderBy('idx', 'asc')->pluck('a.attribute_value', 'c.attribute_name');
        }

        $bundle_items = [];
        if (count($filtered_attributes) <= 0) {
            $bundle_items = DB::table('fumaco_product_bundle_item')->where('parent_item_code', explode("-", $product_details->f_idcode)[0])->orderBy('idx', 'asc')->get();
        }

        $variant_attr_array = $variant_attr_arr = [];
        if (count($variant_items) > 1) {
            foreach ($variant_attributes as $attr => $value) {
                $values = collect($value)->groupBy('attribute_value')->map(function($d, $i) {
                    return array_unique(array_column($d->toArray(), 'idcode'));
                });

                $variant_attr_array[$attr] = $values;
            }

            $attribs = [];
            foreach ($variant_attr_array as $key => $value) {
                $attribs = collect(array_keys($value->toArray()))->sortBy(null, SORT_NATURAL)->values();
                foreach($attribs as $a){
                    $variant_attr_arr[$key][$a] = isset($value[$a]) ? $value[$a] : [];
                }
            }
        }

        // get sitewide sale
        $sale = DB::table('fumaco_on_sale')
            ->whereDate('start_date', '<=', Carbon::now()->toDateString())
            ->whereDate('end_date', '>=', Carbon::today()->toDateString())
            ->where('status', 1)->where('apply_discount_to', 'All Items')
            ->select('discount_type', 'discount_rate')->first();

        $is_new_item = 0;
        if($product_details->f_new_item == 1){
            if($product_details->f_new_item_start <= Carbon::now() and $product_details->f_new_item_end >= Carbon::now()){
                $is_new_item = 1;
            }
        }
        $sale_per_category = [];
        if (!$sale && !Auth::check()) {
            $item_categories =[$product_details->f_cat_id];
            $sale_per_category = $this->getSalePerItemCategory($item_categories);
        }

        if (Auth::check()) {
            $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

            $sale = $customer_group_sale ? $customer_group_sale : $sale;
        }

        $on_sale = false;
        $on_sale_items = $this->onSaleItems([$product_details->f_idcode]);
        $discount_type = $discount_rate = null;
        if (array_key_exists($product_details->f_idcode, $on_sale_items)) {
            $on_sale = $on_sale_items[$product_details->f_idcode]['on_sale'];
            $discount_type = $on_sale_items[$product_details->f_idcode]['discount_type'];
            $discount_rate = $on_sale_items[$product_details->f_idcode]['discount_rate'];
        }

        $item_detail = [
            'default_price' => $product_details->f_default_price,
            'category_id' => $product_details->f_cat_id,
            'item_code' => $product_details->f_idcode,
            'discount_type' => $discount_type,
            'discount_rate' => $discount_rate,
            'stock_uom' => $product_details->f_stock_uom,
            'on_sale' => $on_sale
        ];

        $clearance_sale_items = $this->isIncludedInClearanceSale([$product_details->f_idcode]);

        $is_on_clearance_sale = false;
        if (array_key_exists($product_details->f_idcode, $clearance_sale_items)) {
            $item_detail['discount_type'] = $clearance_sale_items[$product_details->f_idcode][0]->discount_type;
            $item_detail['discount_rate'] = $clearance_sale_items[$product_details->f_idcode][0]->discount_rate;
            $is_on_clearance_sale = true;
        }

        // get item price, discounted price and discount rate
        $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);

        $product_reviews = $this->getProductRating([$product_details->f_idcode]);
        // get product reviews
        $total_reviews = array_key_exists($product_details->f_idcode, $product_reviews) ? $product_reviews[$product_details->f_idcode]['total_reviews'] : 0;
        $overall_rating = array_key_exists($product_details->f_idcode, $product_reviews) ? $product_reviews[$product_details->f_idcode]['overall_rating'] : 0;

        $product_details_array = [
            'id' => $product_details->id,
            'item_code' => $product_details->f_idcode,
            'item_name' => $product_details->f_name_name,
            'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
            'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
            'on_stock' => ($product_details->f_qty - $product_details->f_reserved_qty) > 0 ? 1 : 0,
            'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
            'discount_display' => $item_price_data['discount_display'],
            'slug' => $product_details->slug,
            'is_new_item' => $is_new_item,
            'overall_rating' => $overall_rating,
            'total_reviews' => $total_reviews,
            'bundle_items' => $bundle_items
        ];

        $compare_arr = $variant_attr_array = [];
        $products_to_compare = $variant_attributes_to_compare = $attributes_to_compare = $attribute_names = null;
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

            $products_to_compare_item_codes = array_column($products_to_compare->toArray(), 'item_code'); 

            $products_to_compare_item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $products_to_compare_item_codes)
                ->select('imgprimayx', 'idcode')->get();
            $products_to_compare_item_images = collect($products_to_compare_item_images)->groupBy('idcode')->toArray();
    
            $product_reviews = $this->getProductRating($products_to_compare_item_codes);
            $clearance_sale_items = $this->isIncludedInClearanceSale($products_to_compare_item_codes);

            $products_to_compare = [];
            if (!$request->ajax()) {
                $products_to_compare = DB::table('fumaco_items')->whereIn('f_idcode', $products_to_compare_item_codes)
                    ->select('f_idcode', 'f_default_price', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_qty', 'f_reserved_qty', 'slug', 'f_name_name', 'f_item_name', 'image_alt')->get()->toArray();
            }
            
            $sale_per_category = [];
            if (!$sale && !Auth::check()) {
                $item_categories = array_column($products_to_compare, 'f_cat_id');
                $sale_per_category = $this->getSalePerItemCategory($item_categories);
            }

            if (Auth::check()) {
                $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

                $sale = $customer_group_sale ? $customer_group_sale : $sale;
            }

            $on_sale_items = $this->onSaleItems(collect($products_to_compare)->pluck('f_idcode'));

            foreach($products_to_compare as $item_details){
                $image = null;
                if (array_key_exists($item_details->f_idcode, $products_to_compare_item_images)) {
                    $image = $products_to_compare_item_images[$item_details->f_idcode][0]->imgprimayx;
                }
                
                $is_new_item = 0;
                if($item_details->f_new_item == 1){
                    if($item_details->f_new_item_start <= Carbon::now() and $item_details->f_new_item_end >= Carbon::now()){
                        $is_new_item = 1;
                    }
                }

                $on_sale = false;
                $discount_type = $discount_rate = null;
                if (array_key_exists($item_details->f_idcode, $on_sale_items)) {
                    $on_sale = $on_sale_items[$item_details->f_idcode]['on_sale'];
                    $discount_type = $on_sale_items[$item_details->f_idcode]['discount_type'];
                    $discount_rate = $on_sale_items[$item_details->f_idcode]['discount_rate'];
                }

                $item_detail = [
                    'default_price' => $item_details->f_default_price,
                    'category_id' => $item_details->f_cat_id,
                    'item_code' => $item_details->f_idcode,
                    'discount_type' => $discount_type,
                    'discount_rate' => $discount_rate,
                    'stock_uom' => $item_details->f_stock_uom,
                    'on_sale' => $on_sale
                ];

                $is_on_clearance_sale = false;
                if (array_key_exists($item_details->f_idcode, $clearance_sale_items)) {
                    $item_detail['discount_type'] = $clearance_sale_items[$item_details->f_idcode][0]->discount_type;
                    $item_detail['discount_rate'] = $clearance_sale_items[$item_details->f_idcode][0]->discount_rate;
                    $is_on_clearance_sale = true;
                }

                // get item price, discounted price and discount rate
                $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);
                // get product reviews
                $total_reviews = array_key_exists($item_details->f_idcode, $product_reviews) ? $product_reviews[$item_details->f_idcode]['total_reviews'] : 0;
                $overall_rating = array_key_exists($item_details->f_idcode, $product_reviews) ? $product_reviews[$item_details->f_idcode]['overall_rating'] : 0;
            
                $compare_arr[] = [
                    'item_code' => $item_details->f_idcode,
                    'item_name' => $item_details->f_name_name,
                    'alt' => $item_details->image_alt ? $item_details->image_alt : $item_details->f_item_name,
                    'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                    'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                    'on_stock' => ($item_details->f_qty - $item_details->f_reserved_qty) > 0 ? 1 : 0,
                    'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                    'discount_display' => $item_price_data['discount_display'],
                    'image' => $image,
                    'slug' => $item_details->slug,
                    'is_new_item' => $is_new_item,
                    'overall_rating' => $overall_rating,
                    'total_reviews' => $total_reviews
                ];
            }
        }

        // Recommended Items
        if(!session()->has('recommended_item_codes')){
            session()->put('recommended_item_codes', []);
        }

        $recommended_item_codes = session()->get('recommended_item_codes');
        if(!in_array($product_details->f_idcode, $recommended_item_codes)){
            session()->push('recommended_item_codes', $product_details->f_idcode);
        }

        $recommended_item_codes_query = [];
        if (!$request->ajax()) {
            $recommended_item_codes_query = DB::table('fumaco_items')->whereIn('f_idcode', $recommended_item_codes)
                ->select('f_idcode', 'f_default_price', 'f_new_item', 'f_new_item_start', 'f_new_item_end', 'f_cat_id', 'f_stock_uom', 'f_qty', 'f_reserved_qty', 'slug', 'f_name_name', 'f_item_name', 'id', 'image_alt')->get()->toArray();
        }

        $recommended_item_codes = array_column($recommended_item_codes_query, 'f_idcode'); 

        $recommended_item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $recommended_item_codes)
            ->select('imgprimayx', 'idcode')->get();
        $recommended_item_images = collect($recommended_item_images)->groupBy('idcode')->toArray();
    
        $product_reviews = $this->getProductRating($recommended_item_codes);
        $clearance_sale_items = $this->isIncludedInClearanceSale($recommended_item_codes);
        $sale_per_category = [];
        if (!$sale && !Auth::check()) {
            $item_categories = array_column($recommended_item_codes_query, 'f_cat_id');
            $sale_per_category = $this->getSalePerItemCategory($item_categories);
        }

        if (Auth::check()) {
            $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

            $sale = $customer_group_sale ? $customer_group_sale : $sale;
        }

        $on_sale_items = $this->onSaleItems(collect($recommended_item_codes_query)->pluck('f_idcode'));
        
        $recommended_items = [];
        foreach($recommended_item_codes_query as $row){
            if($row->f_idcode == $product_details->f_idcode){
                continue;
            }

            $image = null;
            if (array_key_exists($row->f_idcode, $recommended_item_images)) {
                $image = $recommended_item_images[$row->f_idcode][0]->imgprimayx;
            }

            $is_new_item = 0;
            if($row->f_new_item == 1){
                if($row->f_new_item_start <= Carbon::now() and $row->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }
            
            $item_code = $row->f_idcode;

            $on_sale = false;
            $discount_type = $discount_rate = null;
            if (array_key_exists($item_code, $on_sale_items)) {
                $on_sale = $on_sale_items[$item_code]['on_sale'];
                $discount_type = $on_sale_items[$item_code]['discount_type'];
                $discount_rate = $on_sale_items[$item_code]['discount_rate'];
            }

            $item_detail = [
                'default_price' => $row->f_default_price,
                'category_id' => $row->f_cat_id,
                'item_code' => $row->f_idcode,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
                'stock_uom' => $row->f_stock_uom,
                'on_sale' => $on_sale
            ];

            $is_on_clearance_sale = false;
            if (array_key_exists($row->f_idcode, $clearance_sale_items)) {
                $item_detail['discount_type'] = $clearance_sale_items[$row->f_idcode][0]->discount_type;
                $item_detail['discount_rate'] = $clearance_sale_items[$row->f_idcode][0]->discount_rate;
                $is_on_clearance_sale = true;
            }

            // get item price, discounted price and discount rate
            $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);
            // get product reviews
            $total_reviews = array_key_exists($item_code, $product_reviews) ? $product_reviews[$item_code]['total_reviews'] : 0;
            $overall_rating = array_key_exists($item_code, $product_reviews) ? $product_reviews[$item_code]['overall_rating'] : 0;
            
            $recommended_items[] = [
                'id' => $row->id,
                'item_code' => $row->f_idcode,
                'item_name' => $row->f_name_name,
                'alt' => $row->image_alt ? $row->image_alt : $row->f_item_name,
                'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                'on_stock' => ($row->f_qty - $row->f_reserved_qty) > 0 ? 1 : 0,
                'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                'discount_display' => $item_price_data['discount_display'],
                'image' => $image,
                'slug' => $row->slug,
                'is_new_item' => $is_new_item,
                'overall_rating' => $overall_rating,
                'total_reviews' => $total_reviews,
            ];
        }

        $product_images = DB::table('fumaco_items_image_v1')->where('idcode', $product_details->f_idcode)->select('imgprimayx', 'idcode', 'imgoriginalx')->get();

        $related_products_query = [];
        if (!$request->ajax()) {
            $related_products_query = DB::table('fumaco_items as a')
                ->join('fumaco_items_relation as b', 'a.f_idcode', 'b.related_item_code')
                ->where('b.item_code', $product_details->f_idcode)->where('a.f_status', 1)
                ->select('a.id', 'a.f_idcode', 'a.f_default_price','a.f_stock_uom', 'a.f_name_name', 'a.f_item_name', 'a.slug', 'a.f_qty', 'a.f_reserved_qty', 'a.f_new_item', 'a.f_new_item_start', 'a.f_new_item_end', 'a.f_category', 'a.f_cat_id', 'a.image_alt')
                ->get()->toArray();
        }

        $related_item_codes = array_column($related_products_query, 'f_idcode');
        $clearance_sale_items = [];
        if (count($related_item_codes) > 0) {
            $related_item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $related_item_codes)
                ->select('imgprimayx', 'idcode')->get();
            $related_item_images = collect($related_item_images)->groupBy('idcode')->toArray();
        
            $product_reviews = $this->getProductRating($related_item_codes);
            $clearance_sale_items = $this->isIncludedInClearanceSale($related_item_codes);
        }

        $sale_per_category = [];
        if (!$sale && !Auth::check()) {
            $item_categories = array_column($related_products_query, 'f_cat_id');
            $sale_per_category = $this->getSalePerItemCategory($item_categories);
        }

        if (Auth::check()) {
            $customer_group_sale = $this->getSalePerCustomerGroup(Auth::user()->customer_group);

            $sale = $customer_group_sale ? $customer_group_sale : $sale;
        }

        $on_sale_items = $this->onSaleItems(collect($related_products_query)->pluck('f_idcode'));

        $related_products = [];
        foreach($related_products_query as $row) {
            $image = null;
            if (array_key_exists($row->f_idcode, $related_item_images)) {
                $image = $related_item_images[$row->f_idcode][0]->imgprimayx;
            }
            
            $is_new_item = 0;
            if($row->f_new_item == 1){
                if($row->f_new_item_start <= Carbon::now() and $row->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }

            $on_sale = false;
            $discount_type = $discount_rate = null;
            if (array_key_exists($row->f_idcode, $on_sale_items)) {
                $on_sale = $on_sale_items[$row->f_idcode]['on_sale'];
                $discount_type = $on_sale_items[$row->f_idcode]['discount_type'];
                $discount_rate = $on_sale_items[$row->f_idcode]['discount_rate'];
            }

            $item_detail = [
                'default_price' => $row->f_default_price,
                'category_id' => $row->f_cat_id,
                'item_code' => $row->f_idcode,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
                'stock_uom' => $row->f_stock_uom,
                'on_sale' => $on_sale
            ];

            $is_on_clearance_sale = false;
            if (array_key_exists($row->f_idcode, $clearance_sale_items)) {
                $item_detail['discount_type'] = $clearance_sale_items[$row->f_idcode][0]->discount_type;
                $item_detail['discount_rate'] = $clearance_sale_items[$row->f_idcode][0]->discount_rate;
                $is_on_clearance_sale = true;
            }

            // get item price, discounted price and discount rate
            $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);
            // get product reviews
            $total_reviews = array_key_exists($row->f_idcode, $product_reviews) ? $product_reviews[$row->f_idcode]['total_reviews'] : 0;
            $overall_rating = array_key_exists($row->f_idcode, $product_reviews) ? $product_reviews[$row->f_idcode]['overall_rating'] : 0;
        
            $related_products[] = [
                'id' => $row->id,
                'item_code' => $row->f_idcode,
                'item_name' => $row->f_name_name,
                'alt' => $row->image_alt ? $row->image_alt : $row->f_item_name,
                'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                'on_stock' => ($row->f_qty - $row->f_reserved_qty) > 0 ? 1 : 0,
                'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                'discount_display' => $item_price_data['discount_display'],
                'image' => $image,
                'slug' => $row->slug,
                'is_new_item' => $is_new_item,
                'overall_rating' => $overall_rating,
                'total_reviews' => $total_reviews,
            ];
        }

        // get product reviews
        $product_reviews = DB::table('fumaco_product_review as a')->join('fumaco_users as b', 'a.user_id', 'b.id')
            ->where('status', '!=', 'pending')->where('item_code', $product_details->f_idcode)->select('a.*', 'b.f_name', 'b.f_lname')
            ->orderBy('a.created_at', 'desc')->paginate(5);

        $view = 'frontend.product_page';
        if ($request->ajax()) {
            $view = 'frontend.product_detail_section';
        }

        // attribute for variant selection
        $attributes_arr = collect($attributes)->where('status', 1)->pluck('attribute_value', 'attribute_name');

        $attributes = collect($attributes)->pluck('attribute_value', 'attribute_name');

        return view($view, compact('attributes_arr', 'variant_combinations', 'product_details', 'product_images', 'attributes', 'variant_attr_arr', 'related_products', 'filtered_attributes', 'products_to_compare', 'variant_attributes_to_compare', 'compare_arr', 'attributes_to_compare', 'variant_attr_array', 'attribute_names', 'product_reviews', 'recommended_items', 'product_details_array', 'is_ordered'));
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
        $completed_statuses = DB::table('order_status')->where('update_stocks', 1)->pluck('status');
        $archived_statuses = collect($completed_statuses)->push('Cancelled');

        $orders = DB::table('fumaco_order')->where('user_email', Auth::user()->username)
            ->whereIn('order_status', $archived_statuses)
            ->select('id', 'order_number', 'order_date', 'order_status', 'estimated_delivery_date', 'date_delivered', 'pickup_date', 'order_subtotal', 'order_shipping', 'order_shipping_amount', 'discount_amount', 'voucher_code', 'grand_total')
            ->orderBy('id', 'desc')->paginate(10);

        $order_numbers = array_column($orders->items(), 'order_number');
        $order_items = DB::table('fumaco_order_items')->whereIn('order_number', $order_numbers)
            ->select('item_code', 'item_name', 'item_qty', 'item_discount', 'item_original_price', 'item_price', 'order_number')->get();
        $order_item_codes = array_column($order_items->toArray(), 'item_code');
        $item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $order_item_codes)
            ->select('imgprimayx', 'idcode')->get();
        $item_images = collect($item_images)->groupBy('idcode')->toArray();

        $order_items = collect($order_items)->groupBy('order_number')->toArray();
        $orders_arr = [];
        foreach($orders as $order){
            $items_arr = [];

            $order_item_list = array_key_exists($order->order_number, $order_items) ? $order_items[$order->order_number] : [];
            foreach($order_item_list as $item){
                $image = null;
                if (array_key_exists($item->item_code, $item_images)) {
                    $image = $item_images[$item->item_code][0]->imgprimayx;
                }

                $items_arr[] = [
                    'image' => $image,
                    'item_code' => $item->item_code,
                    'item_name' => $item->item_name,
                    'qty' => $item->item_qty,
                    'discount' => $item->item_discount,
                    'orig_price' => $item->item_original_price,
                    'price' => $item->item_price,
                ];
            }

            $orders_arr[] = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'date' => date('M d, Y - h:i A', strtotime($order->order_date)),
                'status' => $order->order_status,
                'edd' => $order->estimated_delivery_date,
                'date_delivered' => $order->date_delivered,
                'pickup_date' => $order->pickup_date,
                'items' => $items_arr,
                'subtotal' => $order->order_subtotal,
                'shipping_name' => $order->order_shipping,
                'shipping_fee' => $order->order_shipping_amount,
                'grand_total' => $order->grand_total ? $order->grand_total : (($order->order_shipping_amount + $order->order_subtotal) - $order->discount_amount),
                'voucher_code' => $order->voucher_code,
                'discount_amount' => $order->discount_amount,
            ];
        }

        $new_orders = DB::table('fumaco_order')->where('user_email', Auth::user()->username)
            ->whereNotIn('order_status', $archived_statuses)
            ->select('id', 'pickup_date', 'order_number', 'order_date', 'order_status', 'payment_status', 'estimated_delivery_date', 'date_delivered', 'order_subtotal', 'order_payment_method', 'order_shipping', 'order_shipping_amount', 'discount_amount', 'voucher_code', 'grand_total')
            ->orderBy('id', 'desc')->paginate(10);

        $order_numbers = array_column($new_orders->items(), 'order_number');
        $order_items = DB::table('fumaco_order_items')->whereIn('order_number', $order_numbers)
            ->select('item_code', 'item_name', 'item_qty', 'item_discount', 'item_original_price', 'item_price', 'order_number')->get();
        $order_item_codes = array_column($order_items->toArray(), 'item_code');
        $item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $order_item_codes)
            ->select('imgprimayx', 'idcode')->get();
        $item_images = collect($item_images)->groupBy('idcode')->toArray();

        $order_items = collect($order_items)->groupBy('order_number')->toArray();

        $track_order_detail_query = DB::table('track_order')->whereIn('track_code', $order_numbers)->where('track_active', 1)->select('track_code', 'track_status', 'track_date', 'track_payment_status', 'track_date_update')->get();
        $track_order_arr = $track_order_detail_query ? collect($track_order_detail_query)->groupBy('track_code') : [];
        
        $shipping_methods = array_column($new_orders->items(), 'order_shipping');

        $order_statuses = DB::table('order_status as s')
            ->join('order_status_process as p', 's.order_status_id', 'p.order_status_id')
            ->whereIn('shipping_method', $shipping_methods)
            ->select('s.status', 's.status_description', 'p.order_sequence', 'p.shipping_method')
            ->orderBy('order_sequence', 'asc')->get();

        $payment_statuses = DB::table('fumaco_payment_status')->get();
        $payment_status = collect($payment_statuses)->groupBy('status');

        $order_statuses = collect($order_statuses)->groupBy('shipping_method');

        $new_orders_arr = [];
        foreach($new_orders as $key => $new_order){
            $items_arr = [];

            $order_item_list = array_key_exists($new_order->order_number, $order_items) ? $order_items[$new_order->order_number] : [];

            $track_order_details = isset($track_order_arr[$new_order->order_number]) ? $track_order_arr[$new_order->order_number] : [];

            foreach($order_item_list as $item){
                $image = null;
                if (array_key_exists($item->item_code, $item_images)) {
                    $image = $item_images[$item->item_code][0]->imgprimayx;
                }

                $items_arr[] = [
                    'image' => $image,
                    'item_code' => $item->item_code,
                    'item_name' => $item->item_name,
                    'qty' => $item->item_qty,
                    'discount' => $item->item_discount,
                    'orig_price' => $item->item_original_price,
                    'price' => $item->item_price,
                ];
            }
            
            $order_status = isset($order_statuses[$new_order->order_shipping]) ? $order_statuses[$new_order->order_shipping] : [];

            $status = collect($order_status)->groupBy('status');

            $new_orders_arr[] = [
                'order_id' => $new_order->id,
                'order_number' => $new_order->order_number,
                'order_date' => $new_order->order_date,
                'date' => date('M d, Y h:i A', strtotime($new_order->order_date)),
                'status' => $new_order->order_status,
                'current_order_status_sequence' => isset($status[$new_order->order_status]) ? $status[$new_order->order_status][0]->order_sequence : 0, // 0 = Order Placed
                'current_payment_status_sequence' => isset($payment_status[$new_order->payment_status]) ? $payment_status[$new_order->payment_status][0]->status_sequence : 1, // 1 = Pending for Upload
                'edd' => $new_order->estimated_delivery_date,
                'items' => $items_arr,
                'payment_method' => $new_order->order_payment_method,
                'payment_status' => $new_order->payment_status,
                'subtotal' => $new_order->order_subtotal,
                'shipping_name' => $new_order->order_shipping,
                'shipping_fee' => $new_order->order_shipping_amount,
                'grand_total' => $new_order->grand_total ? $new_order->grand_total : ($new_order->order_shipping_amount + ($new_order->order_subtotal - $new_order->discount_amount)),
                'pickup_date' => $new_order->pickup_date,
                'order_tracker' => $track_order_details,
                'ship_status' => $order_status,
                'voucher_code' => $new_order->voucher_code,
                'discount_amount' => $new_order->discount_amount,
            ];
        }

        return view('frontend.orders', compact('orders', 'orders_arr', 'new_orders', 'new_orders_arr', 'payment_statuses'));
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
        $customer_group = DB::table('fumaco_customer_group')->where('id', Auth::user()->customer_group)->pluck('customer_group_name')->first();
        return view('frontend.profile.account_details', compact('customer_group'));
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

            $mobile = null;
            if($request->mobile_no){
                $mobile = preg_replace("/[^0-9]/", "", $request->mobile_no);
                $mobile = $mobile[0] == 0 ? '63'.substr($mobile, 1) : '63'.$mobile;
                if($mobile[0] == 0){
                    $mobile = '63'.substr($mobile, 1);
                }else if(substr($mobile, 0, 2) != '63' || $mobile[0] == '9'){
                    $mobile = '63'.$mobile;
                }
            }

            DB::table('fumaco_users')->where('id', $id)->update(
                [
                    'f_name' => $request->first_name,
                    'f_lname' => $request->last_name,
                    'username' => $request->email_address,
                    'f_mobilenumber' => $mobile,
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
        $default_billing_address = Auth::user();

        $billing_addresses = DB::table('fumaco_user_add')
            ->where('user_idx', Auth::user()->id)->where('address_class', 'Billing')
            ->select('id', 'xdefault', 'xadd1', 'xadd2', 'xprov', 'xcontactlastname1', 'xcontactname1', 'add_type', 'xcontactnumber1', 'xmobile_number', 'xcontactemail1', 'xcity', 'xbrgy', 'xpostal', 'xcountry')
            ->get();

        $shipping_addresses = DB::table('fumaco_user_add')
            ->where('user_idx', Auth::user()->id)->where('address_class', 'Delivery')
            ->select('id', 'xdefault', 'xadd1', 'xadd2', 'xprov', 'xcontactlastname1', 'xcontactname1', 'add_type', 'xcontactnumber1', 'xmobile_number', 'xcontactemail1', 'xcity', 'xbrgy', 'xpostal', 'xcountry')
            ->get();

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

            $mobile = null;
            if($request->mobile){
                $mobile = preg_replace("/[^0-9]/", "", $request->mobile);
                // $mobile = $mobile[0] == 0 ? '63'.substr($mobile, 1) : '63'.$mobile;
                if($mobile[0] == 0){
                    $mobile = '63'.substr($mobile, 1);
                }else if(substr($mobile, 0, 2) != '63' || $mobile[0] == '9'){
                    $mobile = '63'.$mobile;
                }
            }

            $contact = null;
            if($request->contact){
                $contact = preg_replace("/[^0-9]/", "", $request->contact);
                // $contact = $contact[0] == 0 ? '63'.substr($contact, 1) : '63'.$contact;
                if($contact[0] == 0){
                    $contact = '63'.substr($contact, 1);
                }else if(substr($contact, 0, 2) != '63' || $contact[0] == '9'){
                    $contact = '63'.$contact;
                }
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
                'xmobile_number' => $mobile,
                'xcontactname1' => $request->first_name,
                'xcontactlastname1' => $request->last_name,
                'xcontactnumber1' => $contact,
                'xcontactemail1' => $request->email 
            ];

            DB::table('fumaco_user_add')->where('id', $id)->update($update);

            DB::commit();

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

            $mobile = null;
            if($request->mobile_no){
                $mobile = preg_replace("/[^0-9]/", "", $request->mobile_no);
                // $mobile = $mobile[0] == 0 ? '63'.substr($mobile, 1) : '63'.$mobile;
                if($mobile[0] == 0){
                    $mobile = '63'.substr($mobile, 1);
                }else if(substr($mobile, 0, 2) != '63' || $mobile[0] == '9'){
                    $mobile = '63'.$mobile;
                }
            }
            
            $contact = null;
            if($request->contact_no){
                $contact = preg_replace("/[^0-9]/", "", $request->contact_no);
                // $contact = $contact[0] == 0 ? '63'.substr($contact, 1) : '63'.$contact;
                if($contact[0] == 0){
                    $contact = '63'.substr($contact, 1);
                }else if(substr($contact, 0, 2) != '63' || $contact[0] == '9'){
                    $contact = '63'.$contact;
                }
            }
            
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
                    'xcontactnumber1' => $contact,
                    'xmobile_number' => $mobile,
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

    public function viewOrderTracking($order_number = null) {
        $order_details = DB::table('fumaco_order')->where('order_number', $order_number)->first();

        if($order_number and !$order_details){
            return redirect()->back()->with('error', 'Order Number not found!');
        }

        $track_order_details = DB::table('track_order')->where('track_code', $order_number)->where('track_active', 1)->get();

        $ordered_items = DB::table('fumaco_order_items as o')
            ->join('fumaco_items as i', 'i.f_idcode', 'o.item_code')
            ->where('o.order_number', $order_number)
            ->select('o.*', 'i.f_cat_id')
            ->get();
        $items = $payment_statuses = $status = $order_status = [];
        $payment_status_sequence = $status_sequence = null;
        if($order_details){
            $order_status = DB::table('order_status as s')
                ->join('order_status_process as p', 's.order_status_id', 'p.order_status_id')
                ->where('shipping_method', $order_details->order_shipping)
                ->select('s.status', 's.status_description', 'p.order_sequence')
                ->orderBy('order_sequence', 'asc')
                ->get();

            $arr = collect($ordered_items)->map(function ($q){
                return [
                    'quantity' => $q->item_qty,
					'category_id' => $q->f_cat_id,
					'subtotal' => $q->item_total_price
                ];
            });

            $price_rule = $this->getPriceRules($arr, $order_details->order_date);
            $price_rule = isset($price_rule['price_rule']['Any']) ? $price_rule['price_rule']['Any'] : [];

            $status = collect($order_status)->groupBy('status');
            $status_sequence = isset($status[$order_details->order_status]) ? $status[$order_details->order_status][0]->order_sequence : 0;

            $payment_statuses = DB::table('fumaco_payment_status')->get();
            $payment_status = collect($payment_statuses)->sortByDesc('status_sequence')->groupBy('status');
            $payment_status_sequence = isset($payment_status[$order_details->payment_status]) ? $payment_status[$order_details->payment_status][0]->status_sequence : 1;

            $voucher_details = DB::table('fumaco_voucher')->where('code', $order_details->voucher_code)->first();

            $shipping_discount = DB::table('fumaco_on_sale as p')
                ->join('fumaco_on_sale_shipping_service as c', 'p.id', 'c.sale_id')->where('c.shipping_service', $order_details->order_shipping)
                ->where('status', 1)->where('p.apply_discount_to', 'Per Shipping Service')->whereDate('p.start_date', '<=', $order_details->order_date)->whereDate('p.end_date', '>=', $order_details->order_date)
                ->first();

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
        }

        return view('frontend.track_order', compact('order_details', 'items', 'track_order_details', 'order_status', 'status_sequence', 'payment_statuses', 'payment_status_sequence', 'shipping_discount', 'price_rule', 'voucher_details'));
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

        return null;
    }

    public function uploadDepositSlipForm($token, Request $request) {
        $order_details = DB::table('fumaco_order')->where('deposit_slip_token', $token)->first();

        if(!$order_details) {
            return view('error');
        }

        $is_invalid = false;
        $reason = null;

        $startTime = Carbon::parse($order_details->deposit_slip_token_date_created);
        $endTime = Carbon::now();

        $totalDuration = $endTime->diffInSeconds($startTime);
        if($totalDuration > 86400) {
            $is_invalid = true;
            $reason = 'Link has been expired.';
        }

        if($order_details->deposit_slip_token_used || $order_details->deposit_slip_image) {
            $is_invalid = true;
            $reason = 'Deposit slip for your order <b>'.$order_details->order_number.'</b> has been already uploaded.';
        }

        return view('frontend.upload_deposit_slip', compact('order_details', 'is_invalid', 'reason'));
    }

    public function submitUploadDepositSlip($token, Request $request) {
        $order_details = DB::table('fumaco_order')->where('deposit_slip_token', $token)->first();
        if(!$order_details) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $customer_name = $order_details->order_name . ' ' . $order_details->order_lastname;
        $order_number = $order_details->order_number;
        $date_uploaded = Carbon::now()->format('d-m-y');

        $image_filename = $customer_name . '-' . $order_number . '-' . $date_uploaded;

        if($request->hasFile('image')){
            $image = $request->file('image');

            $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif');
            $extension_error = "Sorry, only JPG, JPEG and PNG files are allowed.";

            $destinationPath = storage_path('/app/public/deposit_slips/');

            $extension = strtolower(pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION));

            $image_name = $image_filename.".".$extension;
            if(!in_array($extension, $allowed_extensions)){
                return redirect()->back()->with('error', $extension_error);
            }

            $image->move($destinationPath, $image_name);

            DB::table('fumaco_order')->where('id', $order_details->id)->update([
                'deposit_slip_image' => $image_name,
                'deposit_slip_date_uploaded' => Carbon::now()->toDateTimeString(),
                'payment_status' => 'Payment For Confirmation',
                'deposit_slip_token_used' => 1,
                'last_modified_by' => $customer_name
            ]);

            DB::table('track_order')->insert([
                'track_code' => $order_number,
                'track_date' => Carbon::now()->toDateTimeString(),
                'track_item' => 'Item Purchase',
                'track_description' => 'Your order is on processing',
                'track_status' => 'Order Placed',
                'track_payment_status' => 'Payment For Confirmation',
                'track_ip' => $order_details->order_ip,
                'track_active' => 1,
                'transaction_member' => $order_details->order_type,
                'last_modified_by' => $customer_name
            ]);

            // send notification to accounting
            $order = ['order_details' => $order_details];

            $email_recipient = DB::table('fumaco_admin_user')->where('user_type', 'Accounting Admin')->pluck('username');
            $recipients = collect($email_recipient)->toArray();
            if (count(array_filter($recipients)) > 0) {
                try {
                    Mail::send('emails.deposit_slip_notif', $order, function($message) use ($recipients) {
                        $message->to($recipients);
                        $message->subject('Awaiting Confirmation - FUMACO');
                    });
                } catch (\Swift_TransportException  $e) {
    
                }
            }
        }
        
        return redirect()->back()->with('success', 'Thank you for uploading your deposit slip / proof of payment, payment confirmation will be sent to you via email and SMS.');
    }

    public function viewShortenLink($code) {
        $findurl = DB::table('fumaco_short_links')->where('code', $code)->first();
        if (!$findurl) {
            return abort(404);
        }
   
        return redirect($findurl->url);
    }

    public function clearanceSalePage(Request $request) {
        $category_query = DB::table('fumaco_on_sale as os')
            ->join('fumaco_on_sale_items as osi', 'os.id', 'osi.sale_id')
            ->join('fumaco_items as i', 'i.f_idcode', 'osi.item_code')
            ->join('fumaco_categories as ic', 'ic.id', 'i.f_cat_id')
            ->where('i.f_status', 1)->where('os.is_clearance_sale', 1)->where('os.status', 1)
            ->orderBy('ic.slug', 'asc')
            ->select('os.start_date', 'os.end_date', 'os.ignore_sale_duration', 'ic.name', 'ic.id', 'banner_image', 'mob_banner_image')
            ->get();
        
        $category_filter = [];
        foreach($category_query as $q){
            if($q->ignore_sale_duration || $q->start_date <= Carbon::now()->startOfDay() && $q->end_date >= Carbon::now()->endOfDay()){
                $category_filter[$q->id] = $q->name;
            }
        }

        $sale_arr = collect($category_query)->first();
        $ignore_sale_duration = $sale_arr->ignore_sale_duration;
        $banner_image = $sale_arr->banner_image;
        if((new \Jenssegers\Agent\Agent())->isMobile()){
            $banner_image = $sale_arr->mob_banner_image ? $sale_arr->mob_banner_image : $sale_arr->banner_image;
        }

        if (count($category_filter) <= 0) {
            return redirect('/');
        }

        // get sorting value 
        $sortby = $request->sortby;
        switch ($sortby) {
            case 'Price':
                $sortby = 'f_default_price';
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
        $default_image_for_sharing = DB::table('fumaco_social_image')->where('is_default', 1)->where('page_type', 'main_page')->select('filename')->first();
        if ($default_image_for_sharing) {
            $image_for_sharing = ($default_image_for_sharing->filename) ? asset('/storage/social_images/'. $default_image_for_sharing->filename) : null;
        }

        $filters = is_array($request->category) ? $request->category : [];

        // get items on clearance sale
        $products = DB::table('fumaco_on_sale as os')
            ->join('fumaco_on_sale_items as osi', 'os.id', 'osi.sale_id')
            ->join('fumaco_items as i', 'i.f_idcode', 'osi.item_code')
            ->where('i.f_status', 1)->where('os.is_clearance_sale', 1)->where('os.status', 1)
            ->when(count($filters) > 0, function($c) use ($filters) {
                $c->whereIn('i.f_cat_id', $filters);
            })
            ->when(!$ignore_sale_duration, function ($q){
                $q->whereDate('os.start_date', '<=', Carbon::now()->startOfDay())->whereDate('os.end_date', '>=', Carbon::now()->endOfDay());
            })
            ->when($ignore_sale_duration, function ($q){
                $q->where('os.ignore_sale_duration', 1);
            })
            ->select('i.id', 'i.f_idcode', 'i.f_default_price', 'i.f_new_item', 'i.f_new_item_start', 'i.f_new_item_end', 'i.f_cat_id', 'osi.discount_type', 'osi.discount_rate', 'i.f_stock_uom', 'i.f_qty', 'i.f_reserved_qty', 'i.slug', 'i.f_name_name', 'i.f_item_name', 'i.image_alt')
            ->orderBy($sortby, $orderby)->paginate(15);

        $item_codes = array_column($products->items(), 'f_idcode');

        $item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', $item_codes)
            ->select('imgprimayx', 'idcode')->get();
        $item_images = collect($item_images)->groupBy('idcode')->toArray();

        $product_reviews = $this->getProductRating($item_codes);

        $products_arr = $sale = $sale_per_category = [];
        foreach ($products as $product) {
            $image = null;
            if (array_key_exists($product->f_idcode, $item_images)) {
                $image = $item_images[$product->f_idcode][0]->imgprimayx;
            }

            $is_new_item = 0;
            if($product->f_new_item == 1){
                if($product->f_new_item_start <= Carbon::now() and $product->f_new_item_end >= Carbon::now()){
                    $is_new_item = 1;
                }
            }

            $item_detail = [
                'default_price' => $product->f_default_price,
                'category_id' => $product->f_cat_id,
                'item_code' => $product->f_idcode,
                'discount_type' => $product->discount_type,
                'discount_rate' => $product->discount_rate,
                'stock_uom' => $product->f_stock_uom,
                'on_sale' => true
            ];

            // get item price, discounted price and discount rate
            $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, 1);
            // get product reviews
            $total_reviews = array_key_exists($product->f_idcode, $product_reviews) ? $product_reviews[$product->f_idcode]['total_reviews'] : 0;
            $overall_rating = array_key_exists($product->f_idcode, $product_reviews) ? $product_reviews[$product->f_idcode]['overall_rating'] : 0;
        
            $products_arr[] = [
                'id' => $product->id,
                'item_code' => $product->f_idcode,
                'item_name' => $product->f_name_name,
                'alt' => $product->image_alt ? $product->image_alt : $product->f_item_name,
                'default_price' => '₱ ' . number_format($item_price_data['item_price'], 2, '.', ','),
                'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                'on_stock' => ($product->f_qty - $product->f_reserved_qty) > 0 ? 1 : 0,
                'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                'discount_display' => $item_price_data['discount_display'],
                'image' => $image,
                'slug' => $product->slug,
                'is_new_item' => $is_new_item,
                'overall_rating' => $overall_rating,
                'total_reviews' => $total_reviews
            ];
        }

        if($request->ajax()){
            return view('frontend.product_list_card', compact('products', 'products_arr'));
        }

        return view('frontend.clearance_sale', compact('products_arr', 'products', 'image_for_sharing', 'category_filter', 'banner_image'));
    }

    public function getJson(Request $request)
    {
        $list = json_decode(file_get_contents(public_path('/json/' . $request->list)), true);
        $list = isset($list['results']) ? $list['results'] : [];

        switch ($request->list) {
            case 'cities.json':
                $parent = 'provCode';
                $code = 'citymunCode';
                break;
            case 'brgy.json':
                $parent = 'citymunCode';
                $code = 'brgyCode';
                break;
            default:
                $parent = null;
                $code = 'provCode';
                break;
        }

        if ($request->list != 'provinces.json') {
            if($request->$parent){
                $list = collect($list)->groupBy($parent);
                $list = isset($list[$request->$parent]) ? $list[$request->$parent] : [];
            }
        }

        if ($request->term) {
            $list = collect($list)->filter(function ($q) use ($request) {
                $search_arr = explode(' ', $request->term);
                $search_arr = array_map('strtolower', $search_arr);

                if (Str::of(strtolower($q[($request->list == 'brgy.json' ? 'brgyDesc' : 'text')]))->contains($search_arr)) {
                    return $q;
                }
            })->values()->all();
        }

        $list = collect($list)->map(function ($q) use ($code, $request) {
            return [
                'id' => $q[($request->list == 'brgy.json' ? 'brgyDesc' : 'text')],
                'text' => $q[($request->list == 'brgy.json' ? 'brgyDesc' : 'text')],
                'code' => $q[$code]
            ];
        });

        return response()->json([
            'results' => $list
        ]);
    }
}