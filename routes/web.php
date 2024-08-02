<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['web'])->group(function () {
    Route::get('sign-up', [UserController::class, 'viewSignUp'])->name('sign-up')->middleware('prevent_logged_in_access');
    Route::get('log-in', [UserController::class, 'viewLogIn'])->name('log-in')->middleware('prevent_logged_in_access');



    Route::middleware('auth')->group(function () {
        Route::get('product-page', [UserController::class, 'viewProductPage'])->name('product-page');
    });
    Route::middleware(['auth', 'checkRole'])->group(function () {
        Route::get('user-list', [UserController::class, 'viewUserList'])->name('user-list');
        Route::post('edit-product', [UserController::class, 'editProduct'])->name('edit-product');
    });
    Route::post('manual-register', [UserController::class, 'register'])->name('manual-register');
    Route::post('manual-login', [UserController::class, 'authenticate'])->name('manual-login');
    Route::any('/admin-session', [UserController::class, 'checkAdminSession']);
    Route::any('log-out', [UserController::class, 'logout'])->name('log-out');


    Route::middleware(['auth', 'superadmin'])->group(function () {
        Route::any('delete-product/{id}', [UserController::class, 'deleteProduct']);
        Route::any('delete-user/{id}', [UserController::class, 'deleteUser']);
        Route::post('add-product', [UserController::class, 'addProduct'])->name('add-product');
        Route::any('change-user-role', [UserController::class, 'changeUserRole'])->name('change-user-role');
    });
});
