<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ReviewController;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'users'], function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/all', [UserController::class, 'details'])->middleware('auth:sanctum');
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

Route::group(['prefix' => '/admins'], function () {
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/login', [AdminController::class, 'login']);
    Route::delete('/logout', [AdminController::class, 'logout'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'shoe', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/add', [ShoeController::class, 'addShoe']);
    Route::put('/update/{id}', [ShoeController::class, 'updateShoe']);
    Route::delete('/delete/{id}', [ShoeController::class, 'deleteShoe']);
    Route::post('/getall', [ShoeController::class, 'getShoes']);
    Route::get('/get/{id}', [ShoeController::class, 'getShoe']);
});

Route::group(['prefix' => 'order', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/add', [OrderController::class, 'addOrder']);
    Route::put('/update', [OrderController::class, 'updateOrder']);
    Route::delete('/delete/{id}', [OrderController::class, 'deleteOrder']);
    Route::get('/get', [OrderController::class, 'getOrders']);
    Route::get('/get/{id}', [OrderController::class, 'getOrder']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
});
Route::group(['prefix' => 'review', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/add', [ReviewController::class, 'addReview']);
});