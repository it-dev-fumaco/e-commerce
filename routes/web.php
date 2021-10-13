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

Route::get('/', 'FrontendController@index');

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

Route::post('/user_register', 'FrontendController@userRegistration');
Route::get('/about', 'FrontendController@viewAboutPage');
Route::get('/journals', 'FrontendController@viewJournalsPage');
Route::get('/privacy_policy', 'FrontendController@viewPrivacyPage');
Route::get('/terms_condition', 'FrontendController@viewTermsPage');
Route::get('/blog', 'FrontendController@viewBlogPage');
Route::post('/add_comment', 'FrontendController@addComment');
Route::post('/add_reply', 'FrontendController@addReply');
Route::get('/contact', 'FrontendController@viewContactPage');
Route::post('/add_contact', 'FrontendController@addContact');
Route::get('/products/{id}', 'FrontendController@viewProducts');
Route::post('/products/{id}', 'FrontendController@viewProducts');
Route::get('/product/{item_code}', 'FrontendController@viewProduct');
Route::get('/track_order', 'FrontendController@viewOrderTracking');

Route::get('/categories', 'FrontendController@getProductCategories');
Route::get('/website_settings', 'FrontendController@websiteSettings');

Route::post('/getvariantcode', 'FrontendController@getVariantItemCode');

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
    Route::get('/myprofile/address/{id}/{type}/change_default', 'FrontendController@setDefaultAddress');
    Route::get('/myprofile/address/{type}/new', 'FrontendController@addAddressForm');
    Route::post('/myprofile/address/{type}/save', 'FrontendController@saveAddress');
});

// SHOPPING CART ROUTES
Route::get('/cart', 'CartController@viewCart');
Route::post('/product_actions', 'CartController@productActions');
Route::patch('/updatecart', 'CartController@updateCart');
Route::delete('/removefromcart', 'CartController@removeFromCart');
Route::post('/addshipping', 'CartController@addShippingDetails');
Route::post('/addtowishlist', 'CartController@addToWishlist');
Route::get('/countcartitems', 'CartController@countCartItems');
Route::get('/countwishlist', 'CartController@countWishlist');

// CHECKOUT ROUTES
Route::get('/checkout/review_order', 'CheckoutController@reviewOrder');
Route::get('/checkout/billing', 'CheckoutController@billingForm');
Route::post('/checkout/summary', 'CheckoutController@checkoutSummary');
Route::post('/checkout/set_address', 'CheckoutController@setAddress');
Route::get('/checkout/set_billing_form', 'CheckoutController@setBillingForm');
Route::post('/checkout/set_billing', 'CheckoutController@setBilling');


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
        Route::post('/api_setup/save', 'SettingsController@saveApiCredentials');

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

        Route::get('/category/list', 'CategoryController@viewCategories');
        Route::post('/category/edit/{id}', 'CategoryController@editCategory');
        Route::post('/category/add', 'CategoryController@addCategory');
        Route::get('/category/delete/{id}', 'CategoryController@deleteCategory');
        Route::get('/category/settings/{id}', 'CategoryController@sortItems');
        Route::post('/category/settings/{id}', 'CategoryController@sortItems');
        Route::get('/category/reset/{id}', 'CategoryController@resetOrder');
        Route::post('/category/set_row/{id}', 'CategoryController@changeSort');

        Route::get('/media/add', 'MediaController@add_media_form');
        Route::get('/media/list', 'MediaController@list_media');
        Route::post('/add_media_records', 'MediaController@add_media_record');
        Route::post('/delete_media', 'MediaController@delete_media_record');

        Route::get('/order/order_lists', 'OrderController@order_list');

        // SHIPPING SERVICES ROUTES CMS
        Route::get('/shipping/list', 'ShippingController@viewList');
        Route::get('/shipping/add', 'ShippingController@viewAddForm');
        Route::post('/shipping/save', 'ShippingController@saveShipping');
        Route::post('/shipping/{id}/update', 'ShippingController@updateShipping');
        Route::get('/shipping/{id}/edit', 'ShippingController@viewShipping');
        Route::delete('/shipping/{id}/delete', 'ShippingController@deleteShipping');
    });
});