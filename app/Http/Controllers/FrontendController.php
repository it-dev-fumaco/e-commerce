<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;

class FrontendController extends Controller
{
    public function index() {
        $carousel_data = DB::table('fumaco_header')->where('fumaco_status', 1)
            ->orderBy('fumaco_active', 'desc')->get();

        $blogs = DB::table('fumaco_blog')->where('blog_featured', 1)
            ->where('blog_enable', 1)->take(3)->get();
        $display = DB::table('fumaco_items')->where('f_status', 1);
        $best_selling = Clone $display->take(4)->get();
        $on_sale = Clone $display->where('f_onsale', 1)->take(4)->get();
        $best_selling_arr = [];
        $on_sale_arr = [];

        foreach($best_selling as $bs){
            $bs_img = DB::table('fumaco_items_image_v1')->where('idcode', $bs->f_idcode)->first();

            $bs_item_name = $bs->f_name_name;
            // if (strlen($bs_item_name) > 114) {
            //     // truncate string
            //     $stringCut = substr($bs_item_name, 0, 114);
            //     $endPoint = strrpos($stringCut, ' ');
            //     //if the string doesn't contain any space then it will cut without word basis.
            //     $bs_item_name = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            //     $bs_item_name .= '...';
            // }

            $best_selling_arr[] = [
                'item_code' => $bs->f_idcode,
                'item_name' => $bs_item_name,
                'orig_price' => $bs->f_original_price,
                'new_price' => $bs->f_price,
                'bs_img' => ($bs_img) ? $bs_img->imgprimayx : 'test.jpg'
            ];
        }

        foreach($on_sale as $os){
            $os_img = DB::table('fumaco_items_image_v1')->where('idcode', $os->f_idcode)->first();

            $os_item_name = $os->f_name_name;
            // if (strlen($os_item_name) > 114) {
            //     // truncate string
            //     $stringCut = substr($os_item_name, 0, 114);
            //     $endPoint = strrpos($stringCut, ' ');
            //     //if the string doesn't contain any space then it will cut without word basis.
            //     $os_item_name = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            //     $os_item_name .= '...';
            // }

            $on_sale_arr[] = [
                'item_code' => $os->f_idcode,
                'item_name' => $os_item_name,
                'orig_price' => $os->f_original_price,
                'new_price' => $os->f_price,
                'os_img' => ($os_img) ? $os_img->imgprimayx : 'test.jpg'
            ];
        }

        return view('frontend.homepage', compact('carousel_data', 'blogs', 'best_selling_arr', 'on_sale_arr'));
    }

    // returns an array of product category
    public function getProductCategories() {
        $item_categories = DB::table('fumaco_categories')->get();

        return response()->json($item_categories);
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

            $insert = DB::table('fumaco_users')->insert($new_user);
            DB::commit();
            return redirect()->back()->with('success', 'New record created successfully. You can login now!');
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

    public function viewPrivacyPage(){
        return view('frontend.privacy');
    }

    public function viewTermsPage(){
        return view('frontend.terms_conditions');
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
                'type' => $blogs->blogtype
            ];
        }

        return view('frontend.journals', compact('blog_carousel', 'blog_count', 'app_count', 'soln_count', 'prod_count', 'blog_list', 'blogs_arr'));
    }

    public function viewBlogPage(Request $request) {
        $blog = DB::table('fumaco_blog')->where('id', $request->id)->first();

        $blog_comment = DB::table('fumaco_comments')->where('blog_id', $request->id)->where('blog_type', 1)->where('blog_status', 1)->get();

        $comment_count = DB::table('fumaco_comments')->where('blog_id', $request->id)->where('blog_status', 1)->get();

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
            $new_contact = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->comment,
                'ip_address' => $request->ip(),
                'xstatus' => 'Sent'
            ];

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'subject' => ['required', 'string', 'max:255'],
                'message' => ['required', 'string', 'max:255'],
                'ip_address' => ['required', 'string', 'max:255'],
                'xstatus' => ['required', 'string', 'max:255'],
                'g-recaptcha-response' => 'recaptcha'
            ]);

            $insert = DB::table('fumaco_contact_list')->insert($new_contact);
            DB::commit();
            return redirect()->back()->with('message', 'Thank you for contacting us!. We have recieved your message.');
        }catch(Exception $e){
            DB::rollback();
        }
    }

    public function viewProducts($category_id) {
        $product_category = DB::table('fumaco_categories')->where('id', $category_id)->first();

        $products = DB::table('fumaco_items')->where('f_cat_id', $category_id)
            ->where('f_status', 1)->orderBy('f_order_by', 'asc')->paginate(15);

        $products_arr = [];
        foreach ($products as $product) {
            $item_image = DB::table('fumaco_items_image_v1')->where('idcode', $product->f_idcode)->first();

            $item_name = strip_tags($product->f_name_name);
            // if (strlen($item_name) > 150) {
            //     // truncate string
            //     $stringCut = substr($item_name, 0, 150);
            //     $endPoint = strrpos($stringCut, ' ');
            //     //if the string doesn't contain any space then it will cut without word basis.
            //     $item_name = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            //     $item_name .= '...';
            // }

            $products_arr[] = [
                'item_code' => $product->f_idcode,
                'item_name' => $item_name,
                'image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg',
                'price' => $product->f_original_price,
                'discounted_price' => number_format(str_replace(",","",$product->f_price), 2),
                'is_discounted' => $product->f_discount_trigger
            ];
        }

        return view('frontend.product_list', compact('product_category', 'products_arr', 'products'));
    }

    public function viewProduct($item_code) {
        $product_details = DB::table('fumaco_items')->where('f_idcode', $item_code)->first();
        if (!$product_details) {
            return view('error');
        }

        $product_images = DB::table('fumaco_items_image_v1')->where('idcode', $item_code)->get();

        $attributes = DB::table('fumaco_items_attributes')->where('idcode', $item_code)->orderBy('idx', 'asc')->get();

        return view('frontend.product_page', compact('product_details', 'product_images', 'attributes'));
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
                'image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg',
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
        $orders = DB::table('track_order')
            ->where('transaction_member', Auth::user()->id)
            ->orderBy('track_date_update', 'desc')->paginate(10);

        return view('frontend.orders', compact('orders'));
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
                'image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg',
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
            ->where('user_idx', Auth::user()->id)->where('address_class', 'Shipping')->get();

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
            $address_class = ($type == 'billing') ? 'Billing' : 'Shipping';
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

            $address_class = ($type == 'billing') ? 'Billing' : 'Shipping';            

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
                    'xcontactemail1' => $request->email_address
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
        $order_details = DB::table('track_order')->where('track_code', $request->id)->get();

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
                'image' => ($item_image) ? $item_image->imgprimayx : 'test.jpg',
                'quantity' => $item->item_qty,
                'price' => $item->item_price,
                'amount' => $item->item_total_price
            ];
        }

        return view('frontend.track_order', compact('order_details', 'items'));
    }
}
