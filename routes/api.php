<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\VideotronController;
use App\Http\Controllers\API\TransactionController;

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


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('videotron', [VideotronController::class, 'fetch'])->name('api.videotron.fetch');
Route::get('category', [CategoryController::class, 'fetch'])->name('api.category.fetch');
Route::get('setting', [SettingController::class, 'fetch']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('update/profile', [AuthController::class, 'updateProfile']);
    Route::post('update/password', [AuthController::class, 'updatePassword']);
    Route::get('transaction', [TransactionController::class, 'fetch']);
    Route::post('transaction/store', [TransactionController::class, 'store'])->name('api.transaction.store');
    Route::post('transaction/payment/{transactionPaymentId}', [TransactionController::class, 'payment'])->name('api.transaction.payment');

    Route::post('logout', [AuthController::class, 'logout']);
});
