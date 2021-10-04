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
Route::get('/product/{item_code}', 'FrontendController@viewProduct');
Route::get('/track_order', 'FrontendController@viewOrderTracking');

Route::get('/categories', 'FrontendController@getProductCategories');
Route::get('/website_settings', 'FrontendController@websiteSettings');

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

// CHECKOUT ROUTES
Route::get('/checkout/review_order', 'CheckoutController@reviewOrder');
Route::get('/checkout/billing', 'CheckoutController@billingForm');


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
        Route::post('/api_setup/save', 'SettingsController@saveApiCredentials');
    });
});