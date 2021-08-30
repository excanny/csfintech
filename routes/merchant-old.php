<?php

use Illuminate\Support\Facades\Route;

/*************************************** MERCHANT ROUTES **********************************************/

/************** USER ************************/
//Route::get('/sign-in', 'User@viewLogin')->name('merchant.login');
//Route::post('/sign-in', 'User@login');
//
//Route::get('/logout', function () {
//    auth()->logout();
//    return redirect()->route('merchant.login');
//});


/************** MERCHANT ********************/

Route::prefix('merchant')->namespace('Merchant')->middleware(['auth', 'role:MERCHANT|USER'])->group( function () {
    Route::get('/', 'Dashboard@index')->name('merchant.index');

    //profile
    Route::get('/profile/view', 'Dashboard@viewProfile')->name('settings.profile');
    Route::post('/profile/update', 'Dashboard@updateUser')->name('profile.update');

    // Change Password
    Route::get('/change/password/view', 'ChangePassword@index')->name('settings.password');
    Route::post('/change/password', 'ChangePassword@store')->name('change.password');

    // Business
    Route::get('/business/view', 'Dashboard@viewBusiness')->name('settings.business');
    Route::post('/business/update/basic', 'Dashboard@updateBusinessBasicInfo')->name('business.update.basic');
    Route::post('/business/update/emails', 'Dashboard@updateBusinessEmails')->name('business.update.emails');
    Route::post('/business/update/internet', 'Dashboard@updateBusinessInternet')->name('business.update.internet');

    // Team
    Route::get('/team/view', 'Dashboard@viewTeam')->name('team.view');
    Route::get('/team/add/user', 'Dashboard@showAddMember')->name('team.view.add');
    Route::post('/team/add/user', 'Dashboard@addMember')->name('team.add.user');

    // Wallet
    Route::get('/wallet/view', 'Wallet@index')->name('wallet.view');
    Route::get('/wallet/request/top-up/view', 'Wallet@viewRequestTopUp')->name('wallet.top-up.form');
    Route::post('/wallet/request/top-up', 'Wallet@requestTopUp')->name('wallet.top-up.request');
    Route::get('/wallet/requests/view', 'Wallet@viewRequests')->name('wallet.view.requests');

    // Products
    Route::get('/products/view', 'Products@index')->name('products.view');

    // Transactions
    Route::get('/transactions', 'Dashboard@viewTransactions')->name('merchant.transactions');
});
