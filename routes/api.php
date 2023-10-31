<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuoteController;
use App\Http\Controllers\AuthController;
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

Route::post("/auth/token", [AuthController::class,"login"]);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get("/quotes", [QuoteController::class,"index"]);
    Route::get("/random_quotes/{amount}", [QuoteController::class,"randomQuotes"]);
    Route::delete("/quote/{quote}", [QuoteController::class,"destroy"]);

});
