<?php

use App\Http\Controllers\API\CurrencyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ExchangeController;

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

Route::prefix('exchange')->group(function () {
    Route::controller(ExchangeController::class)->group(function () {
        Route::post('download', 'download');
    });
});
Route::prefix('currency')->group(function () {
    Route::controller(CurrencyController::class)->group(function () {
        Route::post('get', 'get');
    });
});
