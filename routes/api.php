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
    ]);

});

Route::post('login', \App\Http\Controllers\Api\V1\Auth\LoginController::class)->name('login');
