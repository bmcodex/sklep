<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayUController;

/*
|--------------------------------------------------------------------------
| PayU Routes
|--------------------------------------------------------------------------
|
| Routing dla integracji PayU
|
*/

// Utworzenie płatności
Route::post('/payu/create', [PayUController::class, 'createPayment'])->name('payu.create');

// Powiadomienie z PayU (webhook)
Route::post('/payu/notify', [PayUController::class, 'notify'])->name('payu.notify');

// Powrót z PayU
Route::get('/payu/callback', [PayUController::class, 'callback'])->name('payu.callback');
