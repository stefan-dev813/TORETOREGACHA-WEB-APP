<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\Auth\LoginController;
use \App\Http\Controllers\Auth\RegisterController;

use \App\Http\Controllers\Admin\AdminController;
use \App\Http\Controllers\Admin\CategoryController;

use \App\Http\Controllers\Admin\GachaController;
use \App\Http\Controllers\Admin\LostProductController;

use \App\Http\Controllers\Admin\DpController;

use \App\Http\Controllers\User\UserController;
use \App\Http\Controllers\Admin\DeliveryController;
use \App\Http\Controllers\Admin\SettingController;


use \App\Http\Controllers\PaymentController;
use \App\Http\Controllers\TestController;

use \App\Http\Controllers\Admin\CouponController;
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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

Route::get('/', [HomeController::class, 'index'])->name('main');
Route::get('/dp', [HomeController::class, 'dp'])->name('main.dp');

//Route::get('/how_to_use', [HomeController::class, 'how_to_use'])->name('main.how_to_use');
Route::get('/privacy_police', [HomeController::class, 'privacy_police'])->name('main.privacy_police');
Route::get('/terms_conditions', [HomeController::class, 'terms_conditions'])->name('main.terms_conditions');
//Route::get('/contact_us', [HomeController::class, 'contact_us'])->name('main.contact_us');
Route::get('/notation_commercial', [HomeController::class, 'notation_commercial'])->name('main.notation_commercial');

Route::get('/status_estimate', [HomeController::class, 'status_estimate'])->name('main.status_estimate');

Route::get('register', [RegisterController::class, 'create'])->name('register'); 
Route::post('/register/send_phone', [RegisterController::class, 'send'])->name('register.phone.send');
Route::post('/register/verify_phone', [RegisterController::class, 'verify'])->name('register.phone.verify');
Route::post('/register/send', [RegisterController::class, 'send_email'])->name('register.email.send');
Route::post('/register/verify', [RegisterController::class, 'verify_email'])->name('register.email.verify');

Route::post('/register/register', [RegisterController::class, 'register'])->name('register.phone.register');

Route::post('register', [RegisterController::class, 'store']);

Route::get('login', [LoginController::class, 'create'])->name('login');

Route::post('login', [LoginController::class, 'store'])->name('login_post');
Route::get('logout', [LoginController::class, 'destroy'])->name('logoutGet'); 

 
Route::get('maintenance', [HomeController::class, 'maintenance'])->name('maintenance'); 
Route::get('/gacha/{id}', [UserController::class, 'gacha'])->name('user.gacha');

Route::get('/detail/gacha/{id}', [UserController::class, 'gacha_ionic']);

Route::group(['prefix'=>'user', 'middleware' => 'user-access:user'], function(){
    Route::get('/', [UserController::class, 'index'])->name('user');
    Route::get('/dp_detail/{id}', [UserController::class, 'dp_detail'])->name('user.dp.detail');
    Route::post('/dp_detail/post', [UserController::class, 'dp_detail_post'])->name('user.dp.detail.post');
    Route::get('/dp/success', [UserController::class, 'dp_detail_success'])->name('user.dp.detail.success');


    Route::get('/dpexchange', [UserController::class, 'dpexchange'])->name('dpexchange');
    
    Route::get('/gacha/start', [UserController::class, 'startPost'])->name('user.gacha.start_post');
    Route::get('/gacha/result/{token}', [UserController::class, 'result'])->name('user.gacha.result'); 

    Route::post('/gacha/result/exchange', [UserController::class, 'result_exchange'])->name('user.gacha.result.exchange');
    Route::get('/gacha/end/{token}', [UserController::class, 'gacha_end'])->name('user.gacha.end');
    
    Route::get('/products', [UserController::class, 'products'])->name('user.products');
    Route::post('/products/point/exchange', [UserController::class, 'product_point_exchange'])->name('user.products.point.exchange');
    Route::post('/products/delivery/post', [UserController::class, 'product_delivery_post'])->name('user.delivery.post');
    


    
    Route::get('/products/wait', [UserController::class, 'delivery_wait'])->name('user.products.wait');
    Route::get('/products/delivered', [UserController::class, 'delivered'])->name('user.products.delivered');


    Route::get('/point', [UserController::class, 'point'])->name('user.point'); 
    Route::get('/point/purchase/{id}', [PaymentController::class, 'purchase'])->name('user.point.purchase');
    Route::post('/point/purchase_card', [PaymentController::class, 'purchase_card'])->name('user.point.purchase_card');
    Route::post('/point/purchase_process', [PaymentController::class, 'purchase_process'])->name('user.point.purchase_process');
    Route::get('/point/purchase_success', [UserController::class, 'purchase_success'])->name('purchase_success');
    Route::post('/point/purchase_paypay/', [PaymentController::class, 'purchase_paypay'])->name('user.point.purchase_paypay');
    Route::post('/point/purchase_applepay/', [PaymentController::class, 'purchase_applepay'])->name('user.point.purchase_applepay');
    Route::get('/point/purchase_info/{order_id}/{access_id}/{pay_type}', [PaymentController::class, 'purchase_info'])->name('purchase_info');

    Route::get('/favorite', [UserController::class, 'favorite'])->name('user.favorite');
    Route::post('/favorite/add', [UserController::class, 'favorite_add'])->name('user.favorite.add');

    Route::get('/address', [UserController::class, 'address'])->name('user.address');
    Route::post('/address/post', [UserController::class, 'address_post'])->name('user.address.post');

    Route::get('/coupon', [UserController::class, 'coupon'])->name('user.coupon');
    Route::post('/coupon/post', [UserController::class, 'coupon_post'])->name('user.coupon.post');

    Route::put('/invitation/confirm', [UserController::class, 'confirm_invitation'])->name('user.invitation.update');
});

Route::group(['prefix'=>'admin', 'middleware' => 'user-access:admin'], function(){
    Route::get('/', [AdminController::class, 'index'])->name('admin'); 

    Route::get('/category', [CategoryController::class, 'category'])->name('admin.category');
    Route::get('/category/create',[CategoryController::class,'category_create'])->name('admin.category.create');
    Route::post('/category/store',[CategoryController::class,'category_store'])->name('admin.category.store');
    Route::post('/category/sorting_store', [CategoryController::class, 'sorting_store'])->name('admin.category.sorting.store'); 
    Route::delete('/category/destroy/{id}', [CategoryController::class, 'category_destroy'])->name('admin.category.destroy');
    

    Route::get('/point', [AdminController::class, 'point_list'])->name('admin.point');
    Route::get('/point/create', [AdminController::class, 'point_create'])->name('admin.point.create'); 
    Route::get('/point/edit/{id}', [AdminController::class, 'point_edit'])->name('admin.point.edit');
    Route::post('/point/store', [AdminController::class, 'point_store'])->name('admin.point.store');
    Route::post('/point/update', [AdminController::class, 'point_update'])->name('admin.point.update');
    Route::delete('/point/destroy/{id}',[AdminController::class,'point_destroy'])->name('admin.point.destroy');

    Route::get('/delivery', [DeliveryController::class, 'admin'])->name('admin.delivery');
    Route::post('/delivery/product_data', [DeliveryController::class, 'getProductData'])->name('admin.delivery.product_data');
    Route::post('/delivery/post', [DeliveryController::class, 'deliver_post'])->name('admin.delivery.post');
    Route::get('/delivery/completed', [DeliveryController::class, 'completed'])->name('admin.delivery.completed');
    Route::post('/delivery/un_delivery', [DeliveryController::class, 'unDeliver_post'])->name('admin.delivery.un_delivery');
    Route::get('/delivery/csv', [DeliveryController::class, 'csv_delivery'])->name('admin.delivery.csv');
    Route::post('/delivery/csv_post', [DeliveryController::class, 'csv_delivery_post'])->name('admin.delivery.csv_post');
    
    Route::post('/gacha/to_public', [GachaController::class, 'to_public'])->name('admin.gacha.to_public'); 
    Route::post('/gacha/gacha_limit', [GachaController::class, 'gacha_limit'])->name('admin.gacha.gacha_limit'); 
    Route::delete('/gacha/destroy/{id}', [GachaController::class, 'destroy'])->name('admin.gacha.destroy'); 
    
    Route::get('/gacha/sorting', [GachaController::class, 'sorting'])->name('admin.gacha.sorting');
    Route::post('/gacha/sorting/store', [GachaController::class, 'sorting_store'])->name('admin.gacha.sorting.store'); 

    Route::get('/coupon', [CouponController::class, 'index'])->name('admin.coupon');
    Route::get('/coupon/create', [CouponController::class, 'create'])->name('admin.coupon.create');
    Route::get('/coupon/edit/{id}', [CouponController::class, 'edit'])->name('admin.coupon.edit');
    Route::post('/coupon/store', [CouponController::class, 'store'])->name('admin.coupon.store');
    Route::delete('/coupon/delete/{id}', [CouponController::class, 'delete'])->name('admin.coupon.delete');

    Route::get('/banner', [AdminController::class, 'banner'])->name('admin.banner');
    Route::post('/banner/store', [AdminController::class, 'banner_store'])->name('admin.banner.store');

    Route::get('/dp', [DpController::class, 'index'])->name('admin.dp');
    Route::get('/dp/create', [DpController::class, 'create'])->name('admin.dp.create');
    Route::get('/dp/edit/{id}', [DpController::class, 'edit'])->name('admin.dp.edit');
    Route::post('/dp/store', [DpController::class, 'store'])->name('admin.dp.store');
    Route::post('/dp/update', [DpController::class, 'update'])->name('admin.dp.update');
    Route::delete('/dp/destroy/{id}',[DpController::class,'destroy'])->name('admin.dp.destroy');

    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings/maintenance_store', [SettingController::class, 'maintenance_store'])->name('admin.settings.maintenance_store');
    Route::post('/settings/payment_store', [SettingController::class, 'payment_store'])->name('admin.settings.payment_store');

    // user management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/purchage_log/{id}', [AdminController::class, 'purchase_log'])->name('admin.users.purchase_log');
    Route::get('/users/gacha_log/{id}', [AdminController::class, 'gacha_log'])->name('admin.users.gacha_log');
    Route::get('/fix_gacha_log', [AdminController::class, 'fix_gacha_log']);

    // Test Payment
    Route::get('/test/point', [UserController::class, 'point'])->name('test.user.point');
    Route::get('/test/point/purchase/{id}', [PaymentController::class, 'purchase'])->name('test.user.point.purchase');
    Route::post('/test/point/purchase_process', [PaymentController::class, 'purchase_process'])->name('test.user.point.purchase_process');
    Route::get('/test/point/purchase_success', [UserController::class, 'purchase_success'])->name('test.purchase_success');
});

Route::group(['prefix'=>'admin', 'middleware' => 'user-access:staff'], function(){
    Route::get('/gacha', [GachaController::class, 'index'])->name('admin.gacha');
    Route::get('/gacha/create', [GachaController::class, 'create'])->name('admin.gacha.create');
    Route::post('/gacha/store', [GachaController::class, 'store'])->name('admin.gacha.store');
    Route::get('/gacha/edit/{id}', [GachaController::class, 'edit'])->name('admin.gacha.edit');
    Route::post('/gacha/update', [GachaController::class, 'update'])->name('admin.gacha.update'); 

    
    

    Route::post('/gacha/product_last/create', [GachaController::class, 'product_last_create'])->name('admin.gacha.product_last.create');
    Route::delete('/gacha/product_last/destroy/{id}',[GachaController::class,'product_last_destroy'])->name('admin.gacha.product_last.destroy');
    Route::post('/gacha/product/create', [GachaController::class, 'product_create'])->name('admin.gacha.product.create');

    Route::get('/lost_product', [LostProductController::class, 'index'])->name('admin.lost_product');
    Route::post('/lost_product/create', [LostProductController::class, 'create'])->name('admin.lost_product.create');
});


require __DIR__.'/auth.php';
