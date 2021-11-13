<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'FrontendController@index')->name('website');

Route::namespace('Auth')->group(function(){
    //Login Routes
    Route::get('/login','LoginController@viewLoginPage')->name('login');
    Route::post('/login','LoginController@login');
    Route::get('/logout','LoginController@logout')->name('logout');

    //Forgot Password Routes
    Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');

    //Reset Password Routes
    Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');
});

Route::get('/signup', 'FrontendController@signupForm');
Route::post('/user_register', 'FrontendController@userRegistration');
Route::get('/about', 'FrontendController@viewAboutPage');
Route::get('/journals', 'FrontendController@viewJournalsPage');
Route::get('/terms_condition', 'FrontendController@viewTermsPage');
Route::get('/blog/{slug}', 'FrontendController@viewBlogPage');
Route::post('/add_comment', 'BlogController@addComment');
Route::get('/contact', 'FrontendController@viewContactPage')->name('contact');
Route::post('/add_contact', 'FrontendController@addContact');
Route::get('/products/{slug}', 'FrontendController@viewProducts');
Route::post('/products/{slug}', 'FrontendController@viewProducts');
Route::get('/product/{slug}', 'FrontendController@viewProduct');
Route::get('/track_order', 'FrontendController@viewOrderTracking')->name('track_order');
Route::get('/categories', 'FrontendController@getProductCategories');
Route::get('/website_settings', 'FrontendController@websiteSettings');
Route::post('/getvariantcode', 'FrontendController@getVariantItemCode');
Route::post('/subscribe', 'FrontendController@newsletterSubscription');
Route::get('/thankyou', 'FrontendController@subscribeThankyou');

Route::get('/policy_pages', 'FrontendController@pagesList');
Route::get('/pages/{slug}', 'FrontendController@viewPage')->name('pages');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/mywishlist', 'FrontendController@viewWishlist');
    Route::delete('/mywishlist/{id}/delete', 'FrontendController@deleteWishlist');
    Route::get('/myorders', 'FrontendController@viewOrders');
    Route::get('/myorder/{order_id}', 'FrontendController@viewOrder');
    Route::get('/myprofile/account_details', 'FrontendController@viewAccountDetails');
    Route::post('/myprofile/account_details/{id}/update', 'FrontendController@updateAccountDetails');
    Route::get('/myprofile/change_password', 'FrontendController@viewChangePassword');
    Route::post('/myprofile/change_password/{id}/update', 'FrontendController@updatePassword');
    Route::get('/myprofile/address', 'FrontendController@viewAddresses');
    Route::delete('/myprofile/address/{id}/{type}/delete', 'FrontendController@deleteAddress');
    Route::get('/myprofile/address/{id}/{type}/change_default/{summary?}', 'FrontendController@setDefaultAddress');
    Route::get('/myprofile/address/{type}/new', 'FrontendController@addAddressForm');
    Route::post('/myprofile/address/{type}/save', 'FrontendController@saveAddress');
});

// SHOPPING CART ROUTES
Route::get('/cart', 'CartController@viewCart');
Route::post('/product_actions', 'CartController@productActions');
Route::patch('/updatecart', 'CartController@updateCart');
Route::delete('/removefromcart', 'CartController@removeFromCart');
Route::post('/addtowishlist', 'CartController@addToWishlist');
Route::get('/countcartitems', 'CartController@countCartItems');
Route::get('/countwishlist', 'CartController@countWishlist');
Route::get('/setdetails', 'CartController@setShippingBillingDetails');
Route::post('/setdetails', 'CartController@setShippingBillingDetails');

// CHECKOUT ROUTES
Route::get('/checkout/review_order', 'CheckoutController@reviewOrder');
Route::get('/checkout/billing/{item_code_buy?}/{qty_buy?}', 'CheckoutController@billingForm');
Route::get('/checkout/summary', 'CheckoutController@checkoutSummary');
Route::post('/checkout/set_address', 'CheckoutController@setAddress');
Route::post('/checkout/update_shipping', 'CheckoutController@updateShipping');
Route::post('/checkout/update_billing', 'CheckoutController@updateBilling');
Route::get('/checkout/set_billing_form/{item_code_buy?}/{qty_buy?}', 'CheckoutController@setBillingForm');
Route::post('/checkout/set_billing', 'CheckoutController@setBilling');
Route::get('/eghlform/{order_no}', 'CheckoutController@viewPaymentForm');
Route::post('/order/save', 'CheckoutController@saveOrder');

Route::get('/checkout/success/{id}', 'CheckoutController@orderSuccess');
Route::post('/checkout/success/{id}', 'CheckoutController@orderSuccess');
Route::get('/checkout/failed', 'CheckoutController@orderFailed');
Route::post('/checkout/failed', 'CheckoutController@orderFailed');
Route::post('/checkout/callback', 'CheckoutController@paymentCallback');

Auth::routes();

// CMS Routes
Route::get('/admin', function () {
    return redirect('/admin/login');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', 'Admin\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login_user', 'Admin\Auth\LoginController@login');
    Route::get('/logout', 'Admin\Auth\LoginController@logout');

    Route::group(['middleware' => 'auth:admin'], function(){
        Route::get('/dashboard', 'DashboardController@index');

        Route::get('/pages/home', 'HomeCRUDController@home_crud');
        Route::post('/add_carousel', 'HomeCRUDController@add_header_carousel');
        Route::post('/set_active', 'HomeCRUDController@set_header_active');
        Route::post('/remove_active', 'HomeCRUDController@remove_header_active');
        Route::post('/delete_header', 'HomeCRUDController@remove_header');

        Route::get('/api_setup/erp', 'SettingsController@erpApiSetup');
        Route::get('/api_setup/payment', 'SettingsController@paymentApiSetup');
        Route::get('/api_setup/google', 'SettingsController@googleApiSetup');
        Route::post('/api_setup/save', 'SettingsController@saveApiCredentials');

        Route::get('/email_setup', 'SettingsController@emailSetup');
        Route::post('/email_setup/save', 'SettingsController@saveEmailSetup');
        Route::post('/email_recipients/save', 'SettingsController@saveEmailRecipients');

        Route::get('/product/settings', 'ProductController@viewCategoryAttr');
        Route::post('/attribute_status/{cat_id}/update', 'ProductController@updateCategoryAttr');
        

        Route::get('/product/list', 'ProductController@viewList');
        Route::get('/product/add', 'ProductController@viewAddForm');
        Route::post('/product/save', 'ProductController@saveItem');

        Route::get('/product/search', 'ProductController@searchItem');
        Route::get('/product/{item_code}', 'ProductController@getItemDetails');
        Route::get('/product/{id}/edit', 'ProductController@viewProduct');
        Route::get('/product/images/{id}', 'ProductController@uploadImagesForm');
        Route::post('/add_product_images', 'ProductController@uploadImages');
        Route::post('/delete_product_image', 'ProductController@deleteProductImage');

        Route::get('/select_related_products/{category_id}', 'ProductController@selectProductsRelated');
        Route::post('/product/{parent_code}/save_related_products', 'ProductController@saveRelatedProducts');
        Route::delete('/product/remove_related/{id}', 'ProductController@removeRelatedProduct');
        
    
        Route::post('/product/{id}/update', 'ProductController@updateItem');
        Route::post('/product/{item_code}/disable', 'ProductController@disableItem');
        Route::post('/product/{item_code}/enable', 'ProductController@enableItem');
        Route::delete('/product/{item_code}/delete', 'ProductController@deleteItem');
        Route::get('/product/{id}/featured', 'ProductController@featureItem');
        Route::post('/product/{item_code}/enable_on_sale', 'ProductController@setProductOnSale');
        Route::post('/product/{item_code}/disable_on_sale', 'ProductController@disableProductOnSale');

        Route::get('/category/list', 'CategoryController@viewCategories');
        Route::post('/category/edit/{id}', 'CategoryController@editCategory');
        Route::post('/category/add', 'CategoryController@addCategory');
        Route::get('/category/delete/{id}', 'CategoryController@deleteCategory');
        Route::get('/category/settings/{id}', 'CategoryController@sortItems');
        Route::post('/category/settings/{id}', 'CategoryController@sortItems');
        Route::get('/category/reset/{id}', 'CategoryController@resetOrder');
        Route::post('/category/set_row/{id}', 'CategoryController@changeSort');
        Route::post('/category/publish', 'CategoryController@publishCategory');

        Route::get('/media/add', 'MediaController@add_media_form');
        Route::get('/media/list', 'MediaController@list_media');
        Route::post('/add_media_records', 'MediaController@add_media_record');
        Route::post('/delete_media', 'MediaController@delete_media_record');

        Route::get('/order/order_lists/', 'OrderController@orderList');
        Route::get('/order/cancelled/', 'OrderController@cancelledOrders');
        Route::get('/order/delivered/', 'OrderController@deliveredOrders');
        Route::post('/order/status_update', 'OrderController@statusUpdate');

        Route::get('/order/payment_status', 'OrderController@checkPaymentStatus');
        Route::post('/order/payment_status', 'OrderController@checkPaymentStatus');
        
        Route::get('/customer/list', 'CustomerController@viewCustomers');

        Route::get('/blog/list', 'BlogController@viewBlogs');
        Route::get('/blog/new', 'BlogController@newBlog');
        Route::post('/blog/add', 'BlogController@addBlog');
        Route::post('/blog/publish', 'BlogController@publishBlog');
        Route::post('/blog/feature', 'BlogController@featuredBlog');
        Route::get('/blog/edit/form/{id}', 'BlogController@editBlogForm');
        Route::post('/blog/edit/{id}', 'BlogController@editBlog');
        Route::post('/blog/images/edit/{id}', 'BlogController@editBlogImages');
        Route::get('/blog/delete/{id}', 'BlogController@deleteBlog');
        Route::get('/blog/delete/{id}', 'BlogController@deleteBlog');
        Route::get('/blog/images/img-delete/{id}/{image}', 'BlogController@deleteBlogImage');

        Route::get('/blog/comments', 'BlogController@viewComments');
        Route::post('/blog/comment/approve', 'BlogController@commentStatus');
        Route::get('/blog/comment/delete/{id}', 'BlogController@deleteComment');

        Route::get('/blog/subscribers', 'BlogController@viewSubscribers');
        Route::post('/subscribe/change_status', 'BlogController@subscriberChangeStatus');

        Route::get('/user_management/list', 'UserManagementController@viewAdmin');
        Route::get('/user_management/add', 'UserManagementController@addAdminForm');
        Route::post('/user_management/add_admin', 'UserManagementController@addAdmin');
        Route::post('/user_management/edit', 'UserManagementController@editAdmin');
        Route::post('/user_management/change_status', 'UserManagementController@adminChangeStatus');
        Route::get('/user_management/change_pass', 'UserManagementController@adminPasswordForm');
        Route::post('/user_management/change_password/{id}', 'UserManagementController@adminChangePassword');
        Route::post('/user_management/change_user_password/', 'UserManagementController@userChangePassword');

        Route::get('/pages/list', 'PagesController@viewPages');
        Route::get('/pages/about', 'PagesController@viewAbout');
        Route::get('/pages/edit/{page_id}', 'PagesController@editForm');
        Route::post('/edit/{id}', 'PagesController@editPage');
        Route::post('/edit/page/about_us', 'PagesController@editAbout');
        Route::post('/edit/page/about_us/image', 'PagesController@aboutBackground');
        Route::post('/edit/page/about_us/sponsor/add', 'PagesController@addSponsor');
        Route::get('/edit/page/about_us/sponsor/delete/{id}', 'PagesController@deleteSponsor');

        // SHIPPING SERVICES ROUTES CMS
        Route::get('/shipping/list', 'ShippingController@viewList');
        Route::get('/shipping/add', 'ShippingController@viewAddForm');
        Route::post('/shipping/save', 'ShippingController@saveShipping');
        Route::post('/shipping/{id}/update', 'ShippingController@updateShipping');
        Route::get('/shipping/{id}/edit', 'ShippingController@viewShipping');
        Route::delete('/shipping/{id}/delete', 'ShippingController@deleteShipping');

        Route::get('/holiday/list', 'ShippingController@viewHolidays');
        Route::post('/holiday/new', 'ShippingController@addHoliday');
        Route::get('/holiday/add_form', 'ShippingController@addHolidayForm');
        Route::post('/holiday/edit', 'ShippingController@editHoliday');
        Route::get('/holiday/delete/{id}', 'ShippingController@deleteHoliday');

        // STORE ROUTES CMS
        Route::get('/store/list', 'StoreController@viewList');
        Route::get('/store/add', 'StoreController@viewAddForm');
        Route::post('/store/save', 'StoreController@saveStore');
        Route::post('/store/{id}/update', 'StoreController@updateStore');
        Route::get('/store/{id}/edit', 'StoreController@viewStore');
        Route::delete('/store/{id}/delete', 'StoreController@deleteStore');
    });
});