<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiUserController;
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

Route::post('signup', [ApiUserController::class, 'register'])->name('manual-register');
Route::post('login', [ApiUserController::class, 'authenticateapi'])->name('manual-login');
Route::post('logout', [ApiUserController::class, 'logout'])->name('manual-logout');

// Protected Routes (Require Authentication)
Route::middleware('auth:api')->group(function () {
    Route::get('user-list', [ApiUserController::class, 'viewUserList'])->name('user-list')->middleware('checkRole');
    Route::post('edit-product', [ApiUserController::class, 'editProduct'])->name('edit-product')->middleware('checkRole');
    Route::get('product-page', [ApiUserController::class, 'viewProductPage'])->name('product-page');
    Route::post('change-user-role', [ApiUserController::class, 'changeUserRole'])->name('change-user-role'); 
    Route::any('delete-product', [ApiUserController::class, 'deleteProduct'])->middleware('superadmin');
    Route::post('add-product', [ApiUserController::class, 'addProduct'])->name('add-product')->middleware('superadmin');
    Route::any('delete-user', [ApiUserController::class, 'deleteUser'])->middleware('superadmin');
});
Route::middleware(['superadmin'])->group(function () {
    // Route::any('delete-product', [ApiUserController::class, 'deleteProduct'])->middleware('superadmin');

    
    Route::any('change-user-role', [ApiUserController::class, 'changeUserRole'])->name('change-user-role');
});
