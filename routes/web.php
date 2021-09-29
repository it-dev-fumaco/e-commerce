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


Route::get('/about', 'FrontendController@viewAboutPage');
Route::get('/journals', 'FrontendController@viewJournalsPage');
Route::get('/blog', 'FrontendController@viewBlogPage');
Route::post('/add_comment', 'FrontendController@addComment');
Route::post('/add_reply', 'FrontendController@addReply');
Route::get('/contact', 'FrontendController@viewContactPage');
Route::post('/add_contact', 'FrontendController@addContact');
Route::get('/products/{id}', 'FrontendController@viewProducts');
Route::get('/product/{item_code}', 'FrontendController@viewProduct');
