<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (\Illuminate\Http\Request $request) {
    return $request->user();
});


Route::group(['prefix' => '/v1'], function () {

    // authorize merchant
    Route::post('/merchant/authorization', 'Api\Users@authorizeMerchant');

     Route::group(['middleware' => 'auth:api'], function() {

         Route::group(['middleware' => 'businessIsActive'], function() {
             /*******************  AIRTIME *******************/
             Route::post('/airtime', 'Api\Airtime@purchase');

             /******************* DATA ************************/
             Route::get('/internet/data/fetch-providers', 'Api\Data@fetchProviders');
             Route::post('/internet/data', 'Api\Data@purchase');
             Route::get('/internet/data/lookup', 'Api\Data@dataLookUp');
             Route::get('/internet/data/spectranet/lookup', 'Api\Data@spectranetLookUp');
             Route::post('/internet/data/spectranet', 'Api\Data@spectranetPurchase');
             Route::get('/internet/data/smile/lookup', 'Api\Data@smileLookUp');
             Route::post('/internet/data/smile', 'Api\Data@smilePurchase');

             /************* CABLE-TV ***************************/
             //, 'middleware' => ['auth:api', 'verify.account'], 'namespace' => 'Api'

             Route::group(['prefix' => 'cable-tv'], function () {

                 Route::get('/fetch-billers', 'Api\CableTv@fetchBillers');
                 Route::post('/validate-customer', 'Api\CableTv@validateCustomer');
                 Route::post('/purchase', 'Api\CableTv@purchase');

             });

             /*********** ELECTRICITY *********  */
             Route::group(['prefix' => 'electricity'], function () {
                 Route::get('/fetch-billers', 'Api\Electricity@fetchBillers');
                 Route::post('/validate-customer', 'Api\Electricity@validateCustomer');
                 Route::post('/purchase', 'Api\Electricity@purchase');
             });

            /*************** TRANSFER *******************************/
             Route::group(['prefix' => 'transfer'], function () {
                 Route::get('/get-transfer-data', 'Api\Transfer@fundTransferData');
                 Route::post('/verify-bank-account', 'Api\Transfer@verifyBankAccount');
                 Route::post('/fund-transfer', 'Api\Transfer@fundTransfer');
             });

             /*************** CUSTOMER *******************************/
             Route::group(['prefix' => 'customer'], function () {
                 Route::post('/debit-wallet', 'Api\Account@debitWallet');
             });

             Route::post('transaction/requery', 'Api\Account@requery');
         });
     });
});

Route::group(['prefix' => '/v2'], function () {

    // authorize merchant
    Route::post('/merchant/authorization', 'Api\Users@authorizeMerchant');

    Route::group(['middleware' => 'auth:api'], function() {

        Route::group(['middleware' => 'businessIsActive'], function() {

            //============================================================================
            // Replication of some v1 endpoints to compliment v2 to avoid breakage or service disruption
            //

            /*******************  AIRTIME *******************/
//            Route::post('/airtime', 'Api\Airtime@purchase')->middleware('throttle:1,1');
            Route::post('/airtime', 'Api\Airtime@purchase');

            /******************* DATA ************************/
            Route::get('/internet/data/fetch-providers', 'Api\Data@fetchProviders');
//            Route::post('/internet/data', 'Api\Data@purchase')->middleware('throttle:1,1');
            Route::post('/internet/data', 'Api\Data@purchase');
            Route::get('/internet/data/lookup', 'Api\Data@dataLookUp');
            Route::get('/internet/data/spectranet/lookup', 'Api\Data@spectranetLookUp');
//            Route::post('/internet/data/spectranet', 'Api\Data@spectranetPurchase')->middleware('throttle:1,1');
            Route::post('/internet/data/spectranet', 'Api\Data@spectranetPurchase');
            Route::get('/internet/data/smile/lookup', 'Api\Data@smileLookUp');
            Route::post('/internet/data/smile/validate', 'Api\Data@validateSmileCustomer');
//            Route::post('/internet/data/smile', 'Api\Data@smilePurchase')->middleware('throttle:1,1');
            Route::post('/internet/data/smile', 'Api\Data@smilePurchase');


            /*************** TRANSFER *******************************/
            Route::group(['prefix' => 'transfer'], function () {
                Route::get('/get-transfer-data', 'Api\Transfer@fundTransferData');
                Route::post('/verify-bank-account', 'Api\Transfer@verifyBankAccount');
//                Route::post('/fund-transfer', 'Api\Transfer@fundTransfer')->middleware('throttle:1,1');
                Route::post('/fund-transfer', 'Api\Transfer@fundTransfer');
//                Route::post('/fund-transfer2', 'Api\Transfer@bluSaltFundTransfer')->middleware('throttle:1,1');
                Route::post('/fund-transfer2', 'Api\Transfer@bluSaltFundTransfer');
            });

            /*************** CUSTOMER *******************************/
            Route::group(['prefix' => 'customer'], function () {
                Route::post('/debit-wallet', 'Api\Account@debitWallet');
            });


            //=============================================================================
            //
            // New Capricorn Implementations
            //
            /************* CABLE-TV ***************************/
            Route::group(['prefix' => 'cable-tv'], function () {

                Route::get('/fetch-providers', 'Api\CableTv@fetchProvidersWithCapricorn');
                Route::get('/fetch-billers', 'Api\CableTv@fetchBillersWithCapricorn');
                Route::post('/validate-customer', 'Api\CableTv@validateCustomer');
                //Route::post('/purchase', 'Api\CableTv@purchaseWithCapricorn')->middleware('throttle:1,1');
                Route::post('/purchase', 'Api\CableTv@purchaseWithCapricorn');

            });

            /*********** ELECTRICITY *********  */
            Route::group(['prefix' => 'electricity'], function () {
                Route::get('/fetch-billers', 'Api\Electricity@fetchBillersWithCapricorn');
//                Route::post('/validate-customer', 'Api\Electricity@validateCustomerWithCapricorn');
                Route::post('/validate-customer', 'Api\Electricity@validateCustomer');
                Route::post('/purchase', 'Api\Electricity@purchase');
            });


            /************** KYC ******************/
            Route::group(['prefix' => 'kyc'], function () {
                Route::post('/verify-bvn', 'Api\Kyc@verifyBvn');
                Route::post('/verify-bvn-image', 'Api\Kyc@verifyBvnWithImage');
                Route::post('/verify-nin', 'Api\Kyc@verifyNin');
                Route::post('/verify-pvc', 'Api\Kyc@verifyPvc');
            });


            /************** SAGE-PAY ******************/
            Route::group(['prefix' => 'pay'], function () {
                Route::post('/initialize', 'Api\SagePay@createSession');
            });
            Route::post('transaction/requery', 'Api\Account@requery');
        });
    });

    /*************** CRM *******************************/
    Route::post('/crm/fetch-user-details', 'Api\Crm@fetchUserdetails');
});
