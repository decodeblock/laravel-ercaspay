<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\PaymentController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PaymentController::class, 'index']);

Route::post('/payment/initiate', [PaymentController::class, 'initiatePayment'])->name('initiate.pay');
Route::post('/checkout/process', [PaymentController::class, 'processCheckout'])->name('checkout.process');

Route::post('process-card-payment', [PaymentController::class, 'processCardPayment'])
    ->name('process.card.payment');
