<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ConcaveController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SslCommerzPaymentController;


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


Route::get('/', [ConcaveController::class, 'home']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard/{slug?}', [ConcaveController::class, 'dashboard'])->name('dashboard');
Route::middleware(['auth:sanctum', 'verified'])->post('/dashboard/password/change', [ConcaveController::class, 'change_password'])->name('change_password');
Route::middleware(['auth:sanctum', 'verified'])->post('/dashboard/account/change', [ConcaveController::class, 'change_account_detals'])->name('change_account_detals');



//Static Page
Route::get('/about', [ConcaveController::class, 'about'])->name('about.page');
Route::get('/contact', [ConcaveController::class, 'contact'])->name('contact.page');
Route::post('/contact/submit', [ConcaveController::class, 'contact_submit']);
Route::post('/feedback', [ConcaveController::class, 'feedback']);
Route::post('/feedback-api', [ApiController::class, 'feedback']);




Route::post('/footer_contact', [ConcaveController::class, 'contact_submit2']);

Route::post('/newslatter/submit', [ConcaveController::class, 'newslatter_submit']);
Route::post('/filter/submit', [ProductController::class, 'filter_submit']);
Route::get('/get-modal-data', [ProductController::class, 'modal_data'])->name('get-modal-data');

Route::get('/fetured/{cat_id}', [ConcaveController::class, 'fetured']);

//Product
Route::match(['get', 'post'],'/shop', [ProductController::class, 'index'])->name('shop.page');
Route::match(['get', 'post'],'/category/{categoryslug}', [ProductController::class, 'category'])->name('shop.category');
Route::match(['get', 'post'],'/deals', [ProductController::class, 'deals'])->name('shop.deals');
Route::get('/shop/{slug}', [ProductController::class, 'show'])->name('shop.show');
Route::get('/deals', [ProductController::class, 'deals_page']);

Route::get('/categories', [ProductController::class, 'category_page']);


//Cart
Route::post('/add-to-cart', [ProductController::class, 'addToCart'])->name('add-to-cart');
Route::post('/request-for-restock', [ProductController::class, 'request_for_restock'])->name('request_for_restock');
Route::post('/remove-from-cart', [ProductController::class, 'removeCartItem'])->name('remove-form-cart');
Route::post('/remove-from-compare', [ProductController::class, 'removeComparetItem'])->name('remove-form-compare');

//Checkout 
Route::get('/cart', [CheckoutController::class, 'index'])->name('cart');
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/pay', [SslCommerzPaymentController::class, 'checkout_submit']);
Route::post('/pay-again', [SslCommerzPaymentController::class, 'pay_again']);


//Wishlist
Route::post('/add-or-remove-wishlist', [ProductController::class, 'addOrRemoveWishList'])->name('add-or-remove-wishlist');
Route::post('/add-to-compare', [ProductController::class, 'add_to_compare'])->name('add-to-compare');
Route::get('/compare', [ConcaveController::class, 'compare'])->name('compare.list');

Route::get('/all-product', [ConcaveController::class, 'all_product'])->name('all.product');

//SOCIAL LOGIN
Route::get('login/{provider}', [ConcaveController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback', [ConcaveController::class, 'handleProviderCallback']);

//Custom Registration and Login
Route::post('/generate_otp', [ConcaveController::class, 'generate_otp'])->name('generate_otp');

Route::post('/register_web', [ConcaveController::class, 'register_web'])->name('register_web');

Route::post('/web_login', [ConcaveController::class, 'web_login'])->name('web_login');

//Review
Route::post('/review-submit', [ProductController::class, 'review_submit']);

//Blog
Route::get('/blog', [ConcaveController::class, 'blog_page'])->name('blog.page');
Route::get('blog/{slug}', [BlogController::class, 'show'])->name('blog');
//Route::get('blog/{slug}/comment', [BlogController::class, 'comment'])->name('blog.comment');
Route::post('/comment', [BlogController::class, 'comment']);
Route::post('/category-wise-blog', [BlogController::class, 'category_wise']);
Route::post('/update-coupon-code', [ConcaveController::class, 'apply_coupon']);
Route::post('/remove-coupon', [ConcaveController::class, 'remove_coupon']);

//SslCommerzPayment
Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);
Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);

//Policies Pages
Route::get('/other-policy', function(){
    $page = \DB::table('pages')->first();
    return Inertia::render('Policies/OtherPolicy', [
        'data'      => $page->delivery_returns,
    ]);
});

Route::get('/privacy-policy', function(){
    $page = \DB::table('pages')->first();
    return Inertia::render('Policies/PrivacyPolicy', [
        'data'      => $page->privacy_policy,
    ]);
});

Route::get('/terms-and-conditions', function(){
    $page = \DB::table('pages')->first();
    return Inertia::render('Policies/TermsConditions', [
        'data'      => $page->terms_conditions,
    ]);
});




