<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\LaundryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StatusTokoController;
use App\Http\Controllers\TransactionController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'users'], function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/detail', [UserController::class, 'details'])->middleware('auth:sanctum');
    Route::get('/all', [UserController::class, 'getAllUsers']);
    Route::post('/update-profile-picture', [UserController::class, 'updateProfilePicture'])->middleware('auth:sanctum');
    Route::delete('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/send-otp', [OtpController::class, 'sendOtp'])->middleware('auth:sanctum');
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->middleware('auth:sanctum');

    Route::group(['prefix' => 'update', 'middleware' => 'auth:sanctum'], function() {
        Route::post('/username', [UserController::class, 'updateUsername']);
        Route::post('/email', [UserController::class, 'updateEmail']);
        Route::post('/phone', [UserController::class, 'updatePhone']);
        Route::post('/password', [UserController::class, 'updatePassword']);
        Route::post('/profile-picture', [UserController::class, 'updateProfilePicture']);
        Route::post('/all', [UserController::class, 'updateUser']);
    });
});

Route::group(['prefix' => 'admins'], function () {
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/login', [AdminController::class, 'login']);
    Route::delete('/logout', [AdminController::class, 'logout'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'shoe', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/add', [ShoeController::class, 'addShoe']);
    Route::post('/update/{id}', [ShoeController::class, 'updateShoe']);
    Route::delete('/delete/{id}', [ShoeController::class, 'deleteShoe']);
    Route::get('/getall', [ShoeController::class, 'getShoes']);
    Route::get('/get/{id}', [ShoeController::class, 'getShoe']);
    Route::get('/getshoe/{order_id}', [ShoeController::class, 'getShoesByOrderId']);
});

Route::group(['prefix' => 'order', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/add', [OrderController::class, 'addOrder']);
    Route::post('/update', [OrderController::class, 'updateOrder']);
    Route::delete('/delete/{id}', [OrderController::class, 'deleteOrder']);
    Route::get('/getall', [OrderController::class, 'getOrders']);
    Route::get('/get/{id}', [OrderController::class, 'getOrder']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/status/{status}', [OrderController::class, 'getOrdersByStatus']);  
    Route::get('/status/user/{status}', [OrderController::class, 'getOrdersByStatusUser']);
    Route::get('/charts', [OrderController::class, 'getChart']);
    Route::get('/chart/{orderType}', [OrderController::class, 'getChartByOrderType']);
});

Route::group(['prefix' => 'review', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/add', [ReviewController::class, 'addReview']);
    Route::get('/average/{id}', [ReviewController::class, 'getAverageRating']);
    Route::get('/all/{id}', [ReviewController::class, 'getReviews']);
});

Route::group(['prefix' => 'laundry', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/add', [LaundryController::class, 'addLaundry']);
    Route::get('/getall', [LaundryController::class, 'getLaundries']);
    Route::get('/get/{id}', [LaundryController::class, 'getLaundry']);
    Route::put('/update/{id}', [LaundryController::class, 'updateLaundry']);
    Route::delete('/delete/{id}', [LaundryController::class, 'deleteLaundry']);
});

Route::group(['prefix' => 'brand', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/add', [BrandController::class, 'addBrand']);
    Route::put('/update', [BrandController::class, 'updateBrand']);
    Route::delete('/delete/{id}', [BrandController::class, 'deleteBrand']);
    Route::get('/getall', [BrandController::class, 'getBrands']);
    Route::get('/get/{id}', [BrandController::class, 'getBrand']);
    Route::get('/user/{userId}', [BrandController::class, 'getBrandsByUserId']);
});

Route::group(['prefix' => 'kabupaten', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/add', [KabupatenController::class, 'addKabupaten']);
    Route::put('/update', [KabupatenController::class, 'updateKabupaten']);
    Route::delete('/delete/{id}', [KabupatenController::class, 'deleteKabupaten']);
    Route::get('/getall', [KabupatenController::class, 'getKabupatens']);
    Route::get('/get/{id}', [KabupatenController::class, 'getKabupaten']);
});

Route::group(['prefix' => 'kecamatan', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/add', [KecamatanController::class, 'addKecamatan']);
    Route::put('/update', [KecamatanController::class, 'updateKecamatan']);
    Route::delete('/delete/{id}', [KecamatanController::class, 'deleteKecamatan']);
    Route::get('/getall', [KecamatanController::class, 'getKecamatans']);
    Route::get('/get/{id}', [KecamatanController::class, 'getKecamatan']);
    Route::get('/get-kecamatan-kabupatenid/{kabupaten_id}', [KecamatanController::class, 'getKecamatanByKabupatenId']);
    Route::get('/laundry/{laundry_id}', [KecamatanController::class, 'getKecamatansByLaundryId']); 
});

Route::group(['prefix' => 'payment', 'middleware' => 'auth:sanctum'], function() {
    Route::post('/create', [PaymentController::class, 'createPayment']);
    Route::post('/update', [PaymentController::class, 'updatePaymentStatus']);
    Route::delete('/expire/{id}', [PaymentController::class, 'expirePayment']);
    Route::get('/get', [PaymentController::class, 'getInvoiceUser']);
    Route::get('/all-payment-histories', [PaymentController::class, 'getAllPaymentHistories']);
});

Route::group(['prefix' => 'store-status', 'middleware' => 'auth:sanctum'], function() { 
    Route::post('/status-toko', [StatusTokoController::class, 'store']);
    Route::get('/status-toko/{id}', [StatusTokoController::class, 'show']);
    Route::put('/status-toko/{id}', [StatusTokoController::class, 'update']);
});

Route::group(['prefix' => 'transactions', 'middleware' => 'auth:sanctum'], function() { 
    Route::post('invoice-status', [TransactionController::class, 'invoiceStatus']);
    Route::get('get-transaction', [TransactionController::class, 'getTransaction']);
    Route::get('all', [TransactionController::class, 'getAllTransaction']);
    Route::delete('delete-transaction', [TransactionController::class, 'deleteTransaction']);
});

