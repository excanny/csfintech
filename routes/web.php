<?php

use App\Classes\Capricorn;
use App\Classes\General;
use App\Classes\Shago;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use \App\Classes\Sonite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('/', function () {
//   return view('landing.index');
//});
Route::get('/', function () {

   return view('landing.index');
});
Route::post('/send-mail', 'Landing@sendMail')->name('support.mail');

// Login
//Route::get('login', 'AuthController@login')->name('login');
//Route::post('login', 'AuthController@postLogin');

// Register
//Route::get('register', 'AuthController@register')->name('register');
//Route::post('register', 'AuthController@postRegister');

// Logout
//Route::get('/logout', 'Auth\LoginController')->name('logout');

Auth::routes();


Route::middleware(['auth'])->namespace('Dashboard')->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});

Route::prefix('admin')->middleware(['auth', 'role:SUPER_ADMIN|ADMIN'])->group(function () {

    Route::get('/', 'Admin@index')->name('admin.index');
    Route::post('/filter', 'Admin@filterIndex')->name('admin.index.filter');
    // Profile
    Route::get('settings/profile/view', 'Admin@viewProfile')->name('admin.settings.profile');
    Route::post('settings/profile/update', 'Admin@updateProfile')->name('admin.profile.update');

    // Change Password
    Route::get('settings/change/password/view', 'ChangePassword@index')->name('admin.settings.password');
    Route::post('settings/change/password', 'ChangePassword@store')->name('admin.change.password');

    // Toggle Providers
    Route::get('settings/providers', 'Provider@index')->name('admin.settings.providers')->middleware('permission:authorise');;
    Route::post('settings/switch/providers', 'Provider@switchProvider')->name('admin.switch.providers')->middleware('permission:authorise');;

    //Logs
    Route::get('settings/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('admin.settings.logs')->middleware('permission:authorise');

    //Queries
    Route::get('settings/queries', 'Admin@query')->name('admin.settings.queries')->middleware('permission:authorise');;
//    Route::post('settings/switch/providers', 'Provider@switchProvider')->name('admin.')->middleware('permission:authorise');;

    // Merchants
    Route::get('/merchants/view', 'Admin@viewMerchants')->name('merchants.view');
    Route::get('/merchants/add/view', 'Admin@viewAddMerchant')->name('merchants.view.add');
    Route::post('/merchants/add', 'Admin@addMerchant')->name('merchants.add');

    // Administrators
    Route::get('/administrators/view','Admin@viewAdministrators')->name('administrators.view');
    Route::get('/administrators/add/view','Admin@viewAddAdministrator')->name('administrators.view.add');
    Route::post('/administrators/add', 'Admin@addAdministrator')->name('administrators.add');
    Route::get('/administrators/delete/{id}', 'Admin@removeAdmin')->name('administrators.remove');

    // Admin Permissions
    Route::get('/administrators/permissions/view/{id}','Admin@viewPermissions')->name('permissions.view');
    Route::post('/administrators/permissions/add/{id}','Admin@addPermission')->name('permissions.add');
    Route::get('/administrators/permissions/strip/{id}','Admin@stripPermission')->name('permissions.strip');

    // Business
    Route::get('/business/verify/view','Admin@viewVerifyPage')->name('business.verify.view');
    Route::get('/business/verify/{id}','Admin@verifyBusiness')->name('business.verify');
    Route::get('/business/authorise/view','Admin@viewAuthorisePage')->name('business.authorise.view');
    Route::get('/business/authorise/{id}','Admin@authoriseBusiness')->name('business.authorise');
    Route::get('/business/deactivate/{id}','Admin@deactivateMerchant')->name('business.deactivate');
    Route::get('/business/view/{id}','Admin@viewBusiness')->name('business.view');
    Route::get('/business/view/wallet-transactions/{id}','Wallet@viewBusinessWalletTranx')->name('business.view.wallet.tranx');
    Route::post('/business/filter/wallet-transactions','Wallet@filterWalletTransactions')->name('business.filter.wallet.tranx');
    Route::get('/business/product/tranx','Admin@viewProductTransactions')->name('business.product.tranx');
    Route::post('/business/product/tranx/filter','Admin@filterProductTransactions')->name('business.product.tranx.filter');

    // Documents
    Route::get('/business/documents','Admin@viewDocuments')->name('business.documents');

    // Transactions
    Route::get('/transactions/view','Admin@viewTransactions')->name('transactions.view');
    Route::post('/transactions/filter','Admin@filterTransactions')->name('transactions.filter');
    Route::post('/transactions/status/update','Admin@updateTransactionStatus')->name('transactions.update');

    // Sagepay Transactions
    Route::namespace('SagePay')->group(function () {
        Route::get('/pg/transactions/view','Admin@viewTransactions')->name('sage_pay.transactions.view');
        Route::post('/pg/transactions/filter','Admin@filterTransactions')->name('sage_pay.transactions.filter');
        Route::post('/pg/transactions/status/update','Admin@updateTransactionStatus')->name('sage_pay.transactions.update');
    });

    // Fees
    Route::get('/business/update/fees/{id}','Admin@updateFees')->name('business.update.fees');

    // Wallet
    Route::get('/wallet/requests/view', 'Wallet@viewRequests')->name('view.requests')->middleware('permission:authorise');
    Route::get('/wallet/requests/approve/{id}', 'Wallet@approveRequest')->name('wallet.requests.approve')->middleware('permission:authorise','throttle:1,1');
    Route::get('/wallet/requests/reject/{id}', 'Wallet@rejectRequest')->name('wallet.requests.reject')->middleware('permission:authorise');

    // Disputes
    Route::get('/disputes/view', 'Dispute@index')->name('admin.disputes');
    Route::get('/disputes/messages/{id}', 'Dispute@messages')->name('dispute.messages');
    Route::post('/disputes/reply/{id}', 'Dispute@replyDispute')->name('dispute.reply');
    Route::get('/disputes/close/{id}', 'Dispute@closeDispute')->name('dispute.close');

    // Impersonation
    Route::get('/impersonate/{id}', 'Impersonate@index');
});

Route::get('/impersonate/leave', 'Impersonate@leave')->name('impersonate.leave');


/*********************************** MERCHANT | USER | MERCHANT_ADMIN ***********************************************************/

Route::prefix('merchant')->namespace('Merchant')->middleware(['auth', 'role:MERCHANT|USER|MERCHANT_ADMIN'])->group( function () {
    Route::get('/', 'Dashboard@index')->name('merchant.index');
    Route::post('/filter', 'Dashboard@filterIndex')->name('merchant.index.filter');

    //profile
    Route::get('/profile/view', 'Dashboard@viewProfile')->name('settings.profile');
    Route::post('/profile/update', 'Dashboard@updateUser')->name('profile.update');

    // Documents
    Route::get('/documents/view', 'Compliance@viewDocuments')->name('settings.documents');
    Route::post('/documents/add', 'Compliance@addDocument')->name('settings.documents.add');
    Route::get('/documents/delete', 'Compliance@deleteDocument')->name('settings.documents.delete');
    Route::post('/documents/add/director', 'Compliance@addDirector')->name('settings.add.director');
    Route::post('/documents/add/beneficial', 'Compliance@addBeneficialOwner')->name('settings.add.beneficial');
    Route::get('/documents/delete/compliance', 'Compliance@deleteCompliance')->name('settings.delete.compliance');

    // Change Password
    Route::get('/change/password/view', 'ChangePassword@index')->name('settings.password');
    Route::post('/change/password', 'ChangePassword@store')->name('change.password');

    // Wallet
    Route::get('/wallet/view', 'Wallet@index')->name('wallet.view');
    Route::get('/wallet/request/top-up/view', 'Wallet@viewRequestTopUp')->name('wallet.top-up.form');
    Route::post('/wallet/request/top-up', 'Wallet@requestTopUp')->name('wallet.top-up.request');
    Route::get('/wallet/requests/view', 'Wallet@viewRequests')->name('wallet.view.requests');
    Route::get('/wallet/top-up/view', 'Wallet@openTopUp')->name('wallet.top-up.view');
    Route::get('/wallet/top-up/details', 'Wallet@getTopUpDetails')->name('wallet.top-up.details');
    Route::get('/wallet/top-up/{ref}', 'Wallet@getTopUp')->name('wallet.top-up');
    Route::post('/wallet/filter/transaction', 'Wallet@filterWalletTransactions')->name('wallet.filter.tranx');
        // Capicollect
        Route::post('/wallet/capicollect/topup', 'Wallet@submitTopupToCapicollect')->name('wallet.capicollect.topup');
        Route::get('/wallet/capicollect/reference/verify', 'Wallet@capicollectVerifyReference')->name('wallet.capicollect.verify');

    // Products
    Route::get('/products/view', 'Products@index')->name('products.view');
    Route::get('/product/tranx','Products@viewProductTransactions')->name('product.tranx');
    Route::post('/product/tranx/filter','Products@filterProductTransactions')->name('product.tranx.filter');

    // Transactions
    Route::get('/transactions', 'Dashboard@viewTransactions')->name('merchant.transactions');
    Route::get('/transactions/dispute/{ref}', 'Dashboard@disputeTransaction')->name('transaction.dispute');
    Route::post('/transactions/filter', 'Dashboard@filterTransactions')->name('merchant.transactions.filter');

    // Sagepay Transactions
    Route::namespace('SagePay')->group(function () {
        Route::get('/pg/transactions', 'Dashboard@viewTransactions')->name('sagepay.merchant.transactions');
        Route::post('/pg/transactions/filter', 'Dashboard@filterTransactions')->name('sagepay.merchant.transactions.filter');
        Route::get('/pg/settings', 'Dashboard@showSettings')->name('sagepay.merchant.settings');
        Route::post('/pg/settings/update', 'Dashboard@updateSettings')->name('sagepay.merchant.settings.update');
    });

    // Disputes
    Route::get('/disputes', 'Dispute@index')->name('merchant.disputes');
    Route::post('/disputes/log', 'Dispute@logDispute')->name('merchant.dispute.log');
    Route::get('/dispute/messages/{id}', 'Dispute@viewMessages')->name('merchant.dispute.messages');
    Route::post('/dispute/messages/send/{id}', 'Dispute@replyDispute')->name('merchant.dispute.reply');

});

Route::prefix('merchant')->namespace('Merchant')->middleware(['auth', 'role:MERCHANT|MERCHANT_ADMIN'])->group( function () {
    // Business
    Route::get('/business/view', 'Dashboard@viewBusiness')->name('settings.business');
    Route::post('/business/update/basic', 'Dashboard@updateBusinessBasicInfo')->name('business.update.basic');
    Route::post('/business/update/emails', 'Dashboard@updateBusinessEmails')->name('business.update.emails');
    Route::post('/business/update/notification', 'Dashboard@updateNotification')->name('business.update.notification');
    Route::post('/business/update/internet', 'Dashboard@updateBusinessInternet')->name('business.update.internet');

    // Team
    Route::get('/team/view', 'Dashboard@viewTeam')->name('team.view');
    Route::get('/team/add/user', 'Dashboard@showAddMember')->name('team.view.add');
    Route::post('/team/add/user', 'Dashboard@addMember')->name('team.add.user');
    Route::get('/team/user/role/{id}/{role}', 'Dashboard@changeUserRole')->name('team.user.role');

    // Wallet
    Route::post('/wallet/commission/transfer', 'Wallet@commissionTransfer')->name('wallet.transfer');
});

Route::get('/test/{provider}', function ($provider) {


//    $payload = [
//        'amount' => 1.00,
////        'customer_acc_number' => '0608063125',
//        'info' => "Testing bank transfer endpoint"
//    ];
//

//    $dataCode = 101;
    $phone = '08168441395';
    $network = $provider;
//    $bvn = '22297004288';
//
//    $resp = \App\Classes\Blusalt::fundsTransfer('50000', '3066476912', '011', 'EJIOGU UGOCHUKWU LAWRENCE', $ref, 'jksdj');
    $resp = \App\Classes\Shago::validateCustomer('VDA', $phone, $network);
//    $resp = \App\Classes\Shago::re_query($ref);
    return response()->json($resp);

//    dd($resp);

});

Route::get('/test', function () {
//    $fees = \App\Model\Fee::all();
//    foreach ($fees as $fee) {
//        $products = $fee->products;
//        $all_products = $products;
////        $new_products = ['SMS', 'KYC', '']
////        array_push($all_products, [
////
////        ]);
//
//        dd($all_products);
//        $fee->update([
//            'products' => $products
//        ]);
//    }
    $response = Capricorn::reQuery('UKETMJ8CZTMHICUT');
    dd($response);
});

//Route::get('/home', 'HomeController@index')->name('home');
