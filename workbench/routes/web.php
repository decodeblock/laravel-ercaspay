<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\PaymentController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PaymentController::class, 'index']);

Route::post('/payment/initiate', [PaymentController::class, 'initiatePayment'])->name('initiate.pay');
Route::post('/checkout/process', [PaymentController::class, 'processCheckout'])->name('checkout.process');

Route::post('process-card-payment', [PaymentController::class, 'processCardPayment'])->name('process.card.payment');
Route::post('process-ussd-payment', [PaymentController::class, 'processUssdPayment'])->name('process.ussd');

Route::get('/extra', function () {
    return view('extra-endpoints');
});
Route::post('call-extra-endpoint', [PaymentController::class, 'callExtraEndpoint'])->name('call.endpoint');
