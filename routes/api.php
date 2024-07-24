<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use \App\Http\Controllers\PaymentController;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\ApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/webhook', [PaymentController::class, 'webhook'])->name('webhook');
Route::post('/tds2_ret_url', [PaymentController::class, 'tds2_ret_url'])->name('tds2_ret_url');

Route::group(['middleware' => ['guest'], 'prefix' => 'auth'], function () {
    Route::post('register/send', [AuthController::class, 'send']);
    Route::post('register/verify', [AuthController::class, 'verify']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => 'auth.api', 'prefix' => 'auth'], function () {
    Route::get('user', [AuthController::class, 'user']);
    // Route::post('user', [AuthController::class, 'getUser']);
    

    // Route::get('')
});

Route::get('categories', [ApiController::class, 'categories']);
Route::get('gachas/{cat_id?}', [ApiController::class, 'gachas']);
Route::get('gachas/detail/{id}', [ApiController::class, 'gacha_detail']);

Route::group(['middleware' => ['auth.api']], function() {
    // gacha
    // Route::post('gacha/start', [ApiController::class, 'gacha_start']);
    Route::post('gacha/start', [ApiController::class, 'startPost']);
    Route::get('gacha/result/{token}', [ApiController::class, 'gacha_result']);
    Route::post('gacha/result/exchange', [ApiController::class, 'result_exchange']);
    Route::get('gacha/end/{token}', [ApiController::class, 'gacha_end']);

    // point
    Route::get('points', [ApiController::class, 'points']);
    Route::get('point/purchase/{id}', [ApiController::class, 'toPurchase']);
    Route::get('point/success', [ApiController::class, 'purchaseSuccess'])->name('point.purchase.success');
    Route::get('point', [ApiController::class, 'purchaseCancel'])->name('point.purchase.cancel');
    Route::post('create-payment-intent', [ApiController::class, 'createPaymentIntent']);
    // Route::post('point/purchase/register', [ApiController::class, 'purchase_register']);
    // Route::post('point/purchase/process', [ApiController::class, 'purchase_process']);

    // product
    Route::get('products/{status}', [ApiController::class, 'products']);
    // Route::get('products', [ApiController::class, 'products']);
    Route::post('products/exchange', [ApiController::class, 'product_point_exchange']);
    Route::post('products/delivery', [ApiController::class, 'product_delivery_post']);

    // coupon
    Route::get('coupons', [ApiController::class, 'coupons']);
    Route::post('coupons', [ApiController::class, 'coupon_post']);

    // profile
    Route::get('profile', [ApiController::class, 'profile']);
    Route::post('profile', [ApiController::class, 'profile_post']);
    Route::put('profile', [ApiController::class, 'updateProfile']);
    Route::put('profile/password', [ApiController::class, 'updateProfilePassword']);
    // 

    // Route::post('gacha/start', [ApiController::class, 'startPost']);

    Route::post('notify', [ApiController::class, 'sendNotification']);

    
    

});
