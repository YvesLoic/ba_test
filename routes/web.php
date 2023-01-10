<?php

use App\Http\Controllers\GuestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get(
    '/',
    [GuestController::class, 'index']
);
// Route::get(
//     '/{id}',
//     [GuestController::class, 'show']
// )->name('detail');

Auth::routes();


Route::middleware(['auth', 'userIs:owner'])->group(
    function () {

        /**
         * Accueil du dashboard
         */
        Route::get(
            '/home',
            [HomeController::class, 'index']
        )->name('home');

        /**
         * Routes pour le module des users
         */
        Route::prefix('users')->group(
            function () {
                route::get(
                    'index',
                    [UserController::class, 'index']
                )->name('user_index');
                route::get(
                    'create',
                    [UserController::class, 'create']
                )->name('user_create');
                route::get(
                    '/{id}/show',
                    [UserController::class, 'show']
                )->name('user_show');
                route::post(
                    'store',
                    [UserController::class, 'store']
                )->name('user_store');
                route::get(
                    '/{id}/edit',
                    [UserController::class, 'edit']
                )->name('user_edit');
                route::put(
                    '/{id}/update',
                    [UserController::class, 'update']
                )->name('user_update');
                route::get(
                    '/{id}/delete',
                    [UserController::class, 'destroy']
                )->name('user_delete');
            }
        );

        /**
         * Routes pour le module des produits
         */
        Route::prefix('products')->group(
            function () {
                route::get(
                    'index',
                    [ProductController::class, 'index']
                )->name('product_index');
                route::get(
                    'create',
                    [ProductController::class, 'create']
                )->name('product_create');
                route::get(
                    '/{id}/show',
                    [ProductController::class, 'show']
                )->name('product_show');
                route::get(
                    '/{id}/restore',
                    [ProductController::class, 'restore']
                )->name('product_restore');
                route::post(
                    'store',
                    [ProductController::class, 'store']
                )->name('product_store');
                route::get(
                    '/{id}/edit',
                    [ProductController::class, 'edit']
                )->name('product_edit');
                route::put(
                    '/{id}/update',
                    [ProductController::class, 'update']
                )->name('product_update');
                route::get(
                    '/{id}/delete',
                    [ProductController::class, 'destroy']
                )->name('product_delete');
            }
        );
    }
);
