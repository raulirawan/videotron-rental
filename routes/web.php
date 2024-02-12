<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VideotronController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('users', [UserController::class,'index'])->name('users.index');
    Route::post('users/create', [UserController::class,'store'])->name('users.store');
    Route::post('users/update/{id}', [UserController::class,'update'])->name('users.update');
    Route::get('users/delete/{id}', [UserController::class,'delete'])->name('users.delete');

    Route::get('category', [CategoryController::class,'index'])->name('category.index');
    Route::post('category/create', [CategoryController::class,'store'])->name('category.store');
    Route::post('category/update/{id}', [CategoryController::class,'update'])->name('category.update');
    Route::get('category/delete/{id}', [CategoryController::class,'delete'])->name('category.delete');

    Route::get('videotron', [VideotronController::class,'index'])->name('videotron.index');
    Route::post('videotron/create', [VideotronController::class,'store'])->name('videotron.store');
    Route::post('videotron/update/{id}', [VideotronController::class,'update'])->name('videotron.update');
    Route::get('videotron/delete/{id}', [VideotronController::class,'delete'])->name('videotron.delete');


    Route::get('transaction', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('transaction/detail/{id}', [TransactionController::class, 'detail'])->name('transaction.detail');
});



Auth::routes();
