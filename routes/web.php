<?php

use App\Http\Controllers\account;
use App\Http\Controllers\Admin;
use App\Http\Controllers\AppoinmentController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\checkout;
use App\Http\Controllers\index;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\product;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SubCategoriesController;
use App\Http\Controllers\User;
use App\Http\Controllers\VendorPaymentsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

Route::get('/', [index::class, 'index']);
#Suja 7/6
Route::get('/stocks/least-available', [StockController::class, 'getLeastAvailableStocks']);

Route::get('/about-us', function () {
    Session::put('redirectAfterLogin', '/about-us');
    $app_name = config('app.name');
    return view('about')->with(['title'=>'About us | '.$app_name, 'css' => 'about.scss']);
});

Route::get('/services', function () {
    Session::put('redirectAfterLogin', '/services');
    $app_name = config('app.name');
    return view('services')->with(['title'=>'Hospital Booking | '.$app_name, 'css' => 'services.scss']);
})->name('adbooking1');

Route::get('/cart', [CartController::class, 'index']);
Route::get('/appointment', [AppoinmentController::class, 'index']);
Route::get('/shop', [ProductsController::class, 'index']);
Route::get('/category/{sub}/{sub_name}', [SubCategoriesController::class, 'subcategory']);
Route::get('/search/{search}', [ProductsController::class, 'search']);
Route::get('/product/{id}/{name}', [product::class, 'index']);
Route::get('/checkout', [checkout::class, 'index']);
Route::get('/account', [account::class, 'index']);
Route::get('/account/my-details', [account::class, 'details']);
Route::get('/account/address-book', [account::class, 'address']);
Route::get('/account/orders', [account::class, 'orders']);
Route::get('/account/change-password', [account::class, 'changePassword']);
Route::post('/book/hospital/{date}', [AppoinmentController::class, 'bookAppointment']);
Route::post('/checkout', [checkout::class, 'buyNow']);
Route::post('/get-total', [checkout::class, 'getTotal']);
Route::post('/confirm-checkout', [checkout::class, 'confirmCheckout']);

Route::post('/product/{sku}', [product::class, 'varient']);

Auth::routes();

Route::post('/sendOtp', [User::class, 'sendOtp']);
Route::post('/verifyOtp', [User::class, 'verifyOtp']);
Route::post('/cart', [CartController::class, 'store']);
Route::post('/account-update', [account::class, 'updateDetails']);
Route::post('/password-update', [account::class, 'updatePassword']);
Route::post('/get-district', [account::class, 'getDistrict']);
Route::post('/get-city', [account::class, 'getCity']);

Route::post('/update-category', [CategoriesController::class, 'updateCategory']);
Route::post('/create-category', [CategoriesController::class, 'addCategory']);
Route::post('/delete-category', [CategoriesController::class, 'deleteCategory']);
Route::post('/update-subcategory', [SubCategoriesController::class, 'updateCategory']);
Route::post('/create-subcategory', [SubCategoriesController::class, 'addCategory']);
Route::post('/delete-subcategory', [SubCategoriesController::class, 'deleteCategory']);
Route::post('/get-order', [OrdersController::class, 'getOrder']);
Route::post('/delete-order', [OrdersController::class, 'deleteOrder']);
Route::post('/update-status/{courier_name}/{track_code}/{track_link}', [OrdersController::class, 'updateOrder']);
Route::post('/update-status', [OrdersController::class, 'updateOrder']);
Route::post('/vendor-register', [VendorPaymentsController::class, 'registerVendor']);
Route::post('/vendor-pay', [VendorPaymentsController::class, 'payVendor']);
Route::post('/delete-user', [User::class, 'delete']);
Route::post('/book', [AppoinmentController::class, 'book']);
Route::post('/create-product', [ProductsController::class, 'store']);
Route::post('/update-product', [ProductsController::class, 'update']);
Route::post('/delete-product', [ProductsController::class, 'delete']);
Route::post('/delete-varient', [ProductsController::class, 'deleteVarient']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



// Admin dashboard
Route::get('/web-admin', [Admin::class, 'index']);
Route::get('/web-admin/categories', [CategoriesController::class, 'index']);
Route::get('/web-admin/sub-categories', [SubCategoriesController::class, 'index']);
Route::get('/web-admin/products', [ProductsController::class, 'admin']);
Route::get('/web-admin/orders', [OrdersController::class, 'index']);
Route::get('/web-admin/appointment', [AppoinmentController::class, 'admin']);
Route::get('/web-admin/users', [User::class, 'index']);
Route::get('/web-admin/payments', [VendorPaymentsController::class, 'index']);
Route::get('/web-admin/vendor-register', [VendorPaymentsController::class, 'register']);
Route::get('/web-admin/vendors', [VendorPaymentsController::class, 'list']);
Route::get('/vendor-register/{id}', [VendorPaymentsController::class, 'verifyPage']);
Route::post('/vendor-verify', [VendorPaymentsController::class, 'verify']);
Route::get('/print-orders', [OrdersController::class, 'printingOrders']);
Route::post('/print-orders', [OrdersController::class, 'printOrders']);

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
