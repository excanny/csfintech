<?php

use Illuminate\Support\Facades\Route;

/*************************************** MERCHANT ROUTES **********************************************/

/************** USER ************************/
Route::get('/sign-in', 'Merchant\User@viewLogin')->name('merchant.login');
Route::post('/sign-in', 'Merchant\User@login');

Route::get('/logout', function () {
    auth()->logout();
    return redirect()->route('merchant.login');
});


/************** MERCHANT ********************/

Route::middleware(['auth', 'role:MERCHANT|USER'])->group( function () {
    Route::get('/', 'Merchant\Dashboard@index')->name('dashboard.index');

    //profile
    Route::get('/profile/view', 'Merchant\Dashboard@viewProfile')->name('settings.profile');
    Route::post('/profile/update', 'Merchant\Dashboard@updateUser')->name('profile.update');

    // Change Password
    Route::get('/change/password/view', 'Merchant\ChangePassword@index')->name('settings.password');
    Route::post('/change/password', 'Merchant\ChangePassword@store')->name('change.password');

    // Business
    Route::get('/business/view', 'Merchant\Dashboard@viewBusiness')->name('settings.business');
    Route::post('/business/update/basic', 'Merchant\Dashboard@updateBusinessBasicInfo')->name('business.update.basic');
    Route::post('/business/update/emails', 'Merchant\Dashboard@updateBusinessEmails')->name('business.update.emails');
    Route::post('/business/update/internet', 'Merchant\Dashboard@updateBusinessInternet')->name('business.update.internet');

    // Team
    Route::get('/team/view', 'Merchant\Dashboard@viewTeam')->name('team.view');
    Route::get('/team/add/user', 'Merchant\Dashboard@showAddMember')->name('team.view.add');
    Route::post('/team/add/user', 'Merchant\Dashboard@addMember')->name('team.add.user');

    // Wallet
    Route::get('/wallet/view', 'Merchant\Wallet@index')->name('wallet.view');
    Route::get('/wallet/request/top-up/view', 'Merchant\Wallet@viewRequestTopUp')->name('wallet.top-up.form');
    Route::post('/wallet/request/top-up', 'Merchant\Wallet@requestTopUp')->name('wallet.top-up.request');
    Route::get('/wallet/requests/view', 'Merchant\Wallet@viewRequests')->name('wallet.view.requests');

    // Products
    Route::get('/products/view', 'Merchant\Products@index')->name('products.view');

    // Transactions
    Route::get('/transactions', 'Merchant\Dashboard@viewTransactions')->name('merchant.transactions');
    Route::get('/transactions/dispute/{ref}', 'Merchant\Dashboard@disputeTransaction')->name('transaction.dispute');

    // Disputes
    Route::get('/disputes', 'Merchant\Dispute@index')->name('merchant.disputes');
    Route::post('/disputes/log', 'Merchant\Dispute@logDispute')->name('merchant.dispute.log');
    Route::get('/dispute/messages/{id}', 'Merchant\Dispute@viewMessages')->name('merchant.dispute.messages');
    Route::post('/dispute/messages/send/{id}', 'Merchant\Dispute@replyDispute')->name('merchant.dispute.reply');
});
