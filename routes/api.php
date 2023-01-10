<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt');
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::group(['middleware' => 'jwt', 'prefix' => 'user'], function () {
    Route::get("", [UserController::class, 'index']);
    Route::get("single/{id}", [UserController::class, 'show']);
    Route::post("create", [UserController::class, "store"]);
    Route::put("update/{id}", [UserController::class, 'update']);
    Route::delete("delete/{id}", [UserController::class, 'destroy']);
    Route::post("restore/{id}", [UserController::class, "restore"]);
});

Route::group(['middleware' => 'jwt', 'prefix' => 'product'], function () {
    Route::get("", [ProductController::class, 'index']);
    Route::get("single/{id}", [ProductController::class, 'show']);
    Route::post("create", [ProductController::class, "store"]);
    Route::put("update/{id}", [ProductController::class, 'update']);
    Route::delete("delete/{id}", [ProductController::class, 'destroy']);
    Route::post("restore/{id}", [ProductController::class, "restore"]);
});
