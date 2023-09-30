<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResources([
        'users' => \App\Http\Controllers\Api\V1\UserController::class,
        'categories' => \App\Http\Controllers\Api\V1\CategoryController::class,
        'products' => \App\Http\Controllers\Api\V1\CategoryController::class,
        'orders' => \App\Http\Controllers\Api\V1\OrderController::class,
    ]);

    Route::apiResource('roles', \App\Http\Controllers\Api\V1\RoleController::class)
        ->only('index', 'show');


    Route::get('profile', [\App\Http\Controllers\Api\V1\UserController::class, 'show']);

});

Route::post('login', \App\Http\Controllers\Api\V1\Auth\LoginController::class)->name('login');
