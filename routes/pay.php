<?php

use Illuminate\Support\Facades\Route;

/*************************************** SAGEPAY ROUTES **********************************************/
Route::namespace('SagePay')->group(function () {
    Route::get('/{access_code?}', 'Payment@index')->name('sagepay.index');
    Route::post('/init-auth-payer', 'Payment@initAuthPayer')->name('sagepay.init.auth');
    Route::get('/{access_code?}/success', 'Payment@showSuccess')->name('sagepay.success');


    // Webhook
    Route::post('/{access_code?}/close-payment', 'Payment@closePayment')->name('sagepay.close');
});
