<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SslCommerzPaymentController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/pay', [SslCommerzPaymentController::class, 'apiindex'])->name('payment');

Route::prefix('v1')->group(function () {

    Route::get('sliders', [ApiController::class, 'sliders']);

    Route::get('shops', [ApiController::class, 'shops']);
    Route::get('all-products', [ApiController::class, 'all_products']);
    Route::get('products-by-brand', [ApiController::class, 'products_by_brand']);
    Route::get('all-brands', [ApiController::class, 'all_brands']);
    Route::get('new-arrivals',[ApiController::class, 'new_arrivals']);

    Route::get('shops/{id}',[ApiController::class, 'shop_products']);

    Route::get('featured-products',[ApiController::class, 'featured_products']);

    Route::get('similar-products/{shopId}',[ApiController::class, 'similar_products']);

    Route::get('search/{query}',[ApiController::class, 'search_products']);

    Route::get('tag/{tagName}',[ApiController::class, 'tag_search']);

    Route::get('wishlist',[ApiController::class, 'wishlist']);

    Route::post('wishlist',[ApiController::class, 'add_wishlist']);

    Route::post('wishlist/delete',[ApiController::class, 'remove_wishlist']);

    Route::post('filter-product',[ApiController::class, 'filter_product']);

    Route::get('random-product',[ApiController::class, 'random_products']);

    Route::get('sale',[ApiController::class, 'sale_products']);

    Route::get('deal',[ApiController::class, 'deal_products']);
    Route::get('order-details',[ApiController::class, 'order_details']);
    Route::post('my-account', [ApiController::class, 'my_account']);
	
    Route::post('my-account/update-account',[ApiController::class, 'update_account']);

    Route::post('my-account/update-address',[ApiController::class, 'update_address']);

    Route::get('filter-attributes',[ApiController::class, 'filter_attributes']);

    Route::post('login',[ApiController::class, 'login']);

    Route::post('social-login',[ApiController::class, 'social_login']);

    Route::post('logout',[ApiController::class, 'logout']);

    Route::post('register',[ApiController::class, 'register']);

    Route::post('cash-on-delivery',[ApiController::class, 'cash_on_delivery']);

    Route::post('online-payment',[ApiController::class, 'online_payment']);

    Route::post('product/rate',[ApiController::class, 'rate_product']);

	Route::post('/update-coupon-code', [ApiController::class, 'apply_coupon']);
	
	Route::get('app-version',[ApiController::class, 'app_version']);
	
	Route::post('/request-for-restock', [ApiController::class, 'request_for_restock']);
	
	Route::get('/user-coupon-list', [ApiController::class, 'user_coupon_list']);
	
	Route::get('/product-details', [ApiController::class, 'product_details']);
	

});


