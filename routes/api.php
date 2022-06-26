<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PaymentController;

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

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'admin', 'cors', 'scope:admin']], function () {
    Route::get('/dashboard', [AdminController::class, 'index']);

    Route::post('/create-product', [AdminController::class, 'storeProduct']);
    Route::post('/activate-product', [AdminController::class, 'activateProduct']);
    Route::post('/update-product', [AdminController::class, 'updateProduct']);
    Route::post('/delete-product', [AdminController::class, 'deleteProduct']);

    Route::post('/update-user', [AdminController::class, 'updateUser']);

    Route::post('/create-category', [AdminController::class, 'storeCategory']);
    Route::post('/delete-category', [AdminController::class, 'destroyCategory']);

    Route::get('/order/{id}', [AdminController::class, 'getOrder']);
    Route::get('/orders', [AdminController::class, 'getOrders']);
    Route::post('/update-order', [AdminController::class, 'updateOrderStatus']);
});

Route::group(['middleware' => 'cors'], function() {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::post('/register', [PassportAuthController::class, 'register'])->name('api.register');
    Route::post('/login', [PassportAuthController::class, 'login'])->name('api.login');
    Route::get('/profile', [UserController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);

    Route::post('/post-payment', [PaymentController::class, 'postPayment']);
});

Route::group(['middleware' => ['auth:api']], function() {
    Route::get('/logout', [PassportAuthController::class, 'logout']);
});

Route::get('{any?}', function() {
    return view('welcome');
})->where('any?', '.*')->name('welcome');
