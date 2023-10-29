<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuoteController;
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

Route::get('/{any}', function () {
    return view('index');
})->where('any', '^(?!auth|request).*$');


Route::post("/auth", [AuthController::class, "login"]);

Route::middleware(['auth:sanctum'])->group(function () {
    // Logout and Session
    Route::get("/auth/session", function (Request $request) {return $request->user(); });
    Route::post("/auth/logout", [AuthController::class, "logout"]);

    //Quotes
    Route::get("/request/quotes/", [QuoteController::class, "index"]);
    Route::get("/request/random_quotes/{amount}", [QuoteController::class, "randomQuotes"]);
    Route::post("/request/quote", [QuoteController::class, "store"]);
    Route::delete("/request/quote/{quote}", [QuoteController::class, "destroy"]);
});

