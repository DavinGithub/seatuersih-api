<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'users'], function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/all', [UserController::class, 'details'])->middleware('auth:sanctum');
    Route::post('/update-profile-picture', [UserController::class, 'updateProfilePicture'])->middleware('auth:sanctum');
    Route::delete('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

    Route::group(['prefix' => 'update', 'middleware' => 'auth:sanctum'], function() {
        Route::post('/username', [UserController::class, 'updateUsername']);
        Route::post('/email', [UserController::class, 'updateEmail']);
        Route::post('/phone', [UserController::class, 'updatePhone']);
        Route::post('/password', [UserController::class, 'updatePassword']);
        Route::post('/profile-picture', [UserController::class, 'updateProfilePicture']);
    });
});

Route::group(['prefix' => '/admins'], function () {
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/login', [AdminController::class, 'login']);
    Route::delete('/logout', [AdminController::class, 'logout'])->middleware('auth:sanctum');
});
