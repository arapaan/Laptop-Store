<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [StripeController::class, 'index'])->name('index');
// Route::post('/checkout/{cart}', [CartController::class, 'checkout'])->name('checkout');
Route::get('/success', [StripeController::class, 'success'])->name('success');