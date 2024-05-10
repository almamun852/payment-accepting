<?php

use App\Http\Controllers\Api\V1\TransactionController;
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


Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
    Route::post('/make-mock-response', [TransactionController::class, 'makeMockResponse'])->name('makeMockResponse');
    Route::post('/payment', [TransactionController::class, 'payment'])->name('payment');
    Route::post('/payment-update', [TransactionController::class, 'paymentUpdate'])->name('paymentUpdate');
});
