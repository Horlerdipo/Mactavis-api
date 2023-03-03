<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\WishlistController;
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

Route::controller(AuthController::class)->prefix("auth")->group(function () {
    Route::post("register", 'registerUser');
    Route::post("login", "loginUser");
});


Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('addresses', AddressController::class);

    Route::apiResource('wishlists', WishlistController::class)->except([
            'show'
    ]);

    Route::get('/me', function (Request $request) {
        return $request->user();
    });

});

