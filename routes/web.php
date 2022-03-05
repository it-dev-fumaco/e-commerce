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

// https://www.fumaco.com/products/category/luminaires/1469 - Troofers and Luminaires
Route::get('/products/category/luminaires/1469', function() {
    return redirect('/products/troffer-and-luminaires');
});
// https://www.fumaco.com/products/category/emergency-exit-lighting/1621 - Emergeny Light
Route::get('/products/category/emergency-exit-lighting/1621', function() {
    return redirect('/products/emergency-light-and-exit-light');
});
// https://www.fumaco.com/products/category/downlights-and-uplights/5527 - Downlinghts
Route::get('/products/category/downlights-and-uplights/5527', function() {
    return redirect('/products/downlights');
});
// https://www.fumaco.com/products/category/lighting-components/208 - Led Bulb
Route::get('/products/category/lighting-components/208', function() {
    return redirect('/products/led-lamps-and-bulbs');
});


Route::namespace('Auth')->group(function(){
    //Login Routes
    Route::get('/login','LoginController@viewLoginPage')->name('login');
    Route::post('/login','LoginController@login');
    Route::get('/logout','LoginController@logout')->name('logout');

    //Forgot Password Routes
    Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::get('/password/request_reset','ForgotPasswordController@resetOptions')->name('password.reset_options');
    Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/otp_form','ForgotPasswordController@OTPform')->name('password.otp_form');
    Route::post('/password/verify_otp','ForgotPasswordController@verifyOTP');

    //Reset Password Routes
    Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');

    // facebook login
    Route::post('/facebook/login', 'LoginController@loginFbSdk')->name('facebook.login');

    // google login
    Route::get('auth/google', 'LoginController@loginUsingGoogle')->name('google.login');
    Route::get('auth/google/callback', 'LoginController@callbackFromGoogle')->name('google.callback');

     // linkedin login
     Route::get('auth/linkedin', 'LoginController@loginUsingLinkedin')->name('linkedin.login');
     Route::get('auth/linkedin/callback', 'LoginController@callbackFromLinkedin')->name('linkedin.callback');

     Route::post('/data_deletion_request', 'LoginController@fbDataDeletionCallback');
     Route::get('/data_deletion_status', function(Request $request) {
         return $request->all();
     });
});

Route::get('/signup', 'FrontendController@signupForm');
Route::post('/user_register', 'FrontendController@userRegistration');
Route::get('/about', 'FrontendController@viewAboutPage');
Route::get('/journals', 'FrontendController@viewJournalsPage');
Route::get('/terms_condition', 'FrontendController@viewTermsPage');
Route::get('/blog/{slug}', 'FrontendController@viewBlogPage')->name('blogs');
Route::post('/add_comment', 'BlogController@addComment');
Route::get('/contact', 'FrontendController@viewContactPage')->name('contact');
Route::post('/add_contact', 'FrontendController@addContact');
Route::get('/products/{slug}', 'FrontendController@viewProducts');
Route::post('/products/{slug}', 'FrontendController@viewProducts');
Route::get('/product/{slug}', 'FrontendController@viewProduct');
Route::get('/track_order/{order_number?}', 'FrontendController@viewOrderTracking')->name('track_order');
Route::get('/categories', 'FrontendController@getProductCategories');
Route::get('/website_settings', 'FrontendController@websiteSettings');
Route::post('/getvariantcode', 'FrontendController@getVariantItemCode');
Route::post('/subscribe', 'FrontendController@newsletterSubscription');
Route::get('/thankyou', 'FrontendController@subscribeThankyou');
Route::get('/search', 'FrontendController@getAutoCompleteData');

Route::get('/policy_pages', 'FrontendController@pagesList');
Route::get('/pages/{slug}', 'FrontendController@viewPage')->name('pages');

Route::get('/myprofile/verify/email', 'FrontendController@emailVerify');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/mywishlist', 'FrontendController@viewWishlist');
    Route::delete('/mywishlist/{id}/delete', 'FrontendController@deleteWishlist');
    Route::get('/myorders', 'FrontendController@viewOrders');
    Route::get('/myorder/{order_id}', 'FrontendController@viewOrder');
    Route::post('/myorder/cancel/{id}', 'OrderController@cancelOrder');
    Route::get('/myprofile/account_details', 'FrontendController@viewAccountDetails');
    Route::post('/myprofile/account_details/{id}/update', 'FrontendController@updateAccountDetails');
    Route::get('/myprofile/change_password', 'FrontendController@viewChangePassword');
    Route::post('/myprofile/change_password/{id}/update', 'FrontendController@updatePassword');
    Route::get('/myprofile/address', 'FrontendController@viewAddresses');
    Route::delete('/myprofile/address/{id}/{type}/delete', 'FrontendController@deleteAddress');
    Route::post('/myprofile/address/{id}/{type}/update', 'FrontendController@updateAddress');
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
Route::get('/checkout/apply_voucher/{code}', 'CheckoutController@applyVoucher');
Route::get('/eghlform/{order_no}', 'CheckoutController@viewPaymentForm');
Route::post('/order/save', 'CheckoutController@saveOrder');

Route::get('/checkout/success/{id}', 'CheckoutController@orderSuccess');
Route::post('/checkout/success/{id}', 'CheckoutController@orderSuccess');
Route::get('/checkout/failed', 'CheckoutController@orderFailed');
Route::post('/checkout/failed', 'CheckoutController@orderFailed');
Route::post('/checkout/callback', 'CheckoutController@paymentCallback');

// product reviews
Route::post('/submit_review', 'ProductReviewController@submitProductReview');

Route::get('/verify_email/{token}', 'FrontendController@verifyAccount')->name('account.verify');
Route::get('/resend_verification/{email}', 'FrontendController@resendVerification');

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
        Route::get('/send_abandoned_cart_email/{transaction_id}', 'DashboardController@sendAbandonedCartEmail');

        Route::get('/pages/home', 'HomeCRUDController@home_crud');
        Route::post('/add_carousel', 'HomeCRUDController@add_header_carousel');
        Route::post('/set_active', 'HomeCRUDController@set_header_active');
        Route::post('/remove_active', 'HomeCRUDController@remove_header_active');
        Route::post('/delete_header', 'HomeCRUDController@remove_header');

        Route::get('/api_setup/erp', 'SettingsController@erpApiSetup');
        Route::get('/api_setup/payment', 'SettingsController@paymentApiSetup');
        Route::get('/api_setup/google', 'SettingsController@googleApiSetup');
        Route::get('/api_setup/sms', 'SettingsController@smsApiSetup');
        Route::post('/api_setup/save', 'SettingsController@saveApiCredentials');

        Route::get('/email_setup', 'SettingsController@emailSetup');
        Route::post('/email_setup/save', 'SettingsController@saveEmailSetup');
        Route::post('/email_recipients/save', 'SettingsController@saveEmailRecipients');

        Route::get('/product/settings', 'ProductController@viewCategoryAttr');
        Route::post('/attribute_status/{cat_id}/update', 'ProductController@updateCategoryAttr');
    

        Route::get('/product/list', 'ProductController@viewList');
        Route::get('/product/add/{type}', 'ProductController@viewAddForm');
        Route::post('/product/save', 'ProductController@saveItem');
        Route::get('/product/reviews', 'ProductReviewController@viewList');
        Route::get('/product/toggle/{id}', 'ProductReviewController@toggleStatus');

        Route::get('/product/search', 'ProductController@searchItem');
        Route::get('/product/{id}/edit', 'ProductController@viewProduct');
        Route::get('/product/{id}/edit_bundle', 'ProductController@viewProduct');
        Route::get('/product/images/{id}', 'ProductController@uploadImagesForm');
        Route::post('/add_product_images', 'ProductController@uploadImages');
        Route::get('/delete_product_image/{id}/{social?}', 'ProductController@deleteProductImage');

        // Price list routes
        Route::get('/price_list', 'PriceListController@viewPriceList');
        Route::post('/price_list/create', 'PriceListController@savePriceList');
        Route::get('/get_price_list', 'PriceListController@getErpPriceList');
        Route::delete('/price_list/delete/{id}', 'PriceListController@deletePriceList');
        Route::get('/item_prices/{pricelist_id}', 'PriceListController@viewItemPrices');
        Route::get('/sync_price_list', 'PriceListController@syncItemPrices');
        
        Route::get('/select_related_products/{category_id}', 'ProductController@selectProductsRelated');
        Route::post('/product/{parent_code}/save_related_products', 'ProductController@saveRelatedProducts');
        Route::delete('/product/remove_related/{id}', 'ProductController@removeRelatedProduct');
        
        Route::post('/product/{id}/update', 'ProductController@updateItem');
        Route::post('/product/{item_code}/disable', 'ProductController@disableItem');
        Route::post('/product/{item_code}/enable', 'ProductController@enableItem');
        Route::delete('/product/{item_code}/delete', 'ProductController@deleteItem');
        Route::get('/product/{id}/featured', 'ProductController@featureItem');
        Route::get('/is_new_item/{id}', 'ProductController@isNewItem');
        Route::post('/product/{item_code}/enable_on_sale', 'ProductController@setProductOnSale');
        Route::post('/product/{item_code}/disable_on_sale', 'ProductController@disableProductOnSale');
        Route::get('/product/{item_code}/{item_type}', 'ProductController@getItemDetails');
        Route::get('/products/compare/list', 'ProductController@viewProductsToCompare');
        Route::get('/products/compare/add', 'ProductController@addProductsToCompare');
        Route::get('/products/compare/{compare_id}/edit', 'ProductController@editProductsToCompare');
        Route::get('/products/compare/{compare_id}/delete', 'ProductController@deleteProductsToCompare');
        Route::post('/products/compare/save', 'ProductController@saveProductsToCompare');
        Route::post('/products/compare/set_status', 'ProductController@statusProductsToCompare');

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
        Route::get('/order/print/{order_id}', 'OrderController@printOrder');
        Route::post('/order/status_update', 'OrderController@statusUpdate');
        
        Route::get('/order/status_list', 'OrderController@statusList');
        Route::get('/order/status/add_form', 'OrderController@addStatusForm');
        Route::post('/order/status/add', 'OrderController@addStatus');
        Route::get('/order/status/{id}/edit_form', 'OrderController@editStatusForm');
        Route::post('/order/status/{id}/edit', 'OrderController@editStatus');
        Route::get('/order/status/{id}/delete', 'OrderController@deleteStatus');
        Route::post('/order/cancel/{id}', 'OrderController@cancelOrder');

        Route::get('/order/sequence_list', 'OrderController@sequenceList');
        Route::get('/order/sequence_list/add_form', 'OrderController@addSequenceForm');
        Route::post('/order/sequence_list/add', 'OrderController@addSequence');
        Route::get('/order/sequence_list/{shipping}/delete', 'OrderController@deleteSequence');

        Route::get('/items_on_cart', 'OrderController@viewItemOnCart');
        Route::get('/items_on_cart_by_location', 'OrderController@viewItemOnCartByLocation');
        Route::get('/items_on_cart_by_item', 'OrderController@viewItemOnCartByItem');
        Route::get('/abandoned_items_on_cart', 'OrderController@viewAbandonedItemOnCart');

        Route::get('/order/payment_status', 'OrderController@checkPaymentStatus');
        Route::post('/order/payment_status', 'OrderController@checkPaymentStatus');
        
        Route::get('/customer/list', 'CustomerController@viewCustomers');
        Route::get('/customer/profile/{id}', 'CustomerController@viewCustomerProfile');
        Route::get('/customer/address/{address_type}/{user_id}', 'CustomerController@getCustomerAddress');
        Route::get('/customer/orders/{user_id}', 'CustomerController@getCustomerOrders');
        Route::get('/customer/order/{id}', 'CustomerController@viewOrderDetails');

        Route::post('/customer/profile/{id}/change_customer_group', 'CustomerController@changeCustomerGroup');

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
        Route::get('/pages/list', 'PagesController@viewPages');
        Route::get('/pages/contact', 'PagesController@viewContact');
        Route::get('/pages/contact/edit/{id}', 'PagesController@editContactForm');
        Route::get('/pages/contact/add_form', 'PagesController@addContactForm');
        Route::post('/pages/contact/add', 'PagesController@addContact');
        Route::get('/pages/contact/delete/{id}', 'PagesController@deleteContact');
        Route::post('/pages/contact/update/{id}', 'PagesController@editContact');
        Route::get('/pages/about/sponsor/list', 'PagesController@viewSponsors');
        Route::get('/pages/edit/{page_id}', 'PagesController@editForm');
        Route::post('/edit/{id}', 'PagesController@editPage');
        Route::get('/pages/about', 'PagesController@viewAbout');
        Route::post('/edit/page/about_us', 'PagesController@editAbout');
        Route::post('/edit/page/about_us/image', 'PagesController@aboutBackground');
        Route::post('/edit/page/about_us/sponsor/add', 'PagesController@addSponsor');
        Route::get('/edit/page/about_us/sponsor/delete/{id}', 'PagesController@deleteSponsor');
        Route::post('/edit/page/about_us/sponsor/sort/{id}', 'PagesController@updateSort');
        Route::get('/edit/page/about_us/sponsor/reset/{id}', 'PagesController@resetSort');

        Route::get('/search/list', 'PagesController@searchList');


        Route::get('/marketing/on_sale/list', 'ProductController@onSaleList');
        Route::get('/marketing/voucher/list', 'ProductController@voucherList');
        Route::get('/marketing/on_sale/addForm', 'ProductController@addOnsaleForm');
        Route::post('/marketing/on_sale/add', 'ProductController@addOnsale');
        Route::get('/marketing/on_sale/{id}/edit_form', 'ProductController@editOnsaleForm');
        Route::post('/marketing/on_sale/{id}/edit', 'ProductController@editOnsale');
        Route::get('/marketing/on_sale/{id}/delete', 'ProductController@removeOnsale');
        Route::post('/marketing/on_sale/set_status', 'ProductController@setOnSaleStatus');
        Route::post('/marketing/voucher/add', 'ProductController@addVoucher');
        Route::get('/marketing/voucher/add_voucher', 'ProductController@addVoucherForm');
        Route::get('/marketing/voucher/{id}/edit_form', 'ProductController@editVoucherForm');
        Route::post('/marketing/voucher/{id}/edit', 'ProductController@editVoucher');
        Route::get('/marketing/voucher/{id}/delete', 'ProductController@removeVoucher');

        Route::get('/marketing/social/images', 'SocialImagesController@viewList');
        Route::post('/marketing/social/create', 'SocialImagesController@uploadImage');
        Route::delete('/marketing/social/delete/{id}', 'SocialImagesController@deleteImage');
        Route::get('/marketing/social/default/{id}', 'SocialImagesController@setDefault');

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