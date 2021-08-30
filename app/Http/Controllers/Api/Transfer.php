<?php
/**
 * Created by Canaan Etai.
 * Date: 5/12/19
 * Time: 6:42 PM
 */

namespace App\Http\Controllers\Api;


use App\Classes\Blusalt;
use App\Helpers\NotificationHelper;
use App\Model\Bank;
use App\Classes\ETranzact;
use App\Classes\General;
use App\Classes\ISW;
use App\Classes\Kyc;
use App\Classes\Paystack;
use App\Classes\Security;
use App\Classes\Wallet;
use App\Http\Controllers\Controller;
use App\Http\Requests\BankAccountVerificationRequest;
use App\Http\Requests\CardFundTransfer;

use App\Model\IswBank;
use App\Model\Product;
use App\Model\Transaction;
use App\ReQuery;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class Transfer extends Controller
{

    public function fundTransferData()
    {
        try {
            $banks = [];
            $business = auth()->user()->business;
            $type = 'TRANSFER';

            // validate product.
            $product = $business->getProduct($type);

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }
            $wallet = $business->wallet()->select(['account_number', 'status', 'balance'])->first();

            switch (env('TRANSFER_PROVIDER')) {
                case 'INTERSWITCH':
                    $banks = IswBank::orderBy('bank_name', 'asc')->select(['cbn_code', 'bank_name'])->get();
                    break;

                case 'PAYSTACK':
//                    $banks = Bank::orderBy('name', 'asc')->select(['code as cbn_code', 'name as bank_name'])->get();
//                    break;

                case 'ETRANZACT':
//                    ETranzact::updateBankList();
                    $banks = json_decode(file_get_contents(storage_path('bank_list.json')));
                    $banks = collect($banks);
                    $banks = $banks->sortBy('bank_name')->values()->all();
                    break;

            }

            return response()->json(['success' => true, 'banks' => $banks, 'wallet' => $wallet]);
        }
        catch (\Exception $exception ) {
//            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error getting required data for transfer']);
        }
    }


    public function verifyBankAccount(BankAccountVerificationRequest $request)
    {
        try {

            $account = [];

            switch (env('TRANSFER_PROVIDER')) {
                case 'INTERSWITCH':
                    $account = ISW::nameEnquiry(request('bank_code'), request('account_number'));
                    break;

                case 'PAYSTACK':
                    return $this->paystackVerifyBankAccount($request);

                case 'ETRANZACT':
                    return $this->eTranzactVerifyBankAccount($request);

            }

            return response()->json($account);
        }
        catch (\Exception $exception ) {
            return response()->json(['success' => false, 'message' => $exception->getMessage() ]);
            return response()->json(['success' => false, 'message' => 'Error verifying account number']);
        }
    }


    public function fundTransfer(BankAccountVerificationRequest $request)
    {
        try {

            $amount = (double) request('amount');
            if ( $amount > 5000000 ) {
                return response()->json(['success' => false, 'message' => 'Invalid amount!. Maximum transfer amount is N5000,000']);
            }
            $ext_reference = request('reference');

            if (isset($ext_reference)) {
                $transaction = Transaction::where('external_reference', $ext_reference)->first();
                if ( !is_null($transaction) && $transaction->business_id == auth()->user()->business->id) {
                    return response()->json(['success' => false, 'message' => 'Duplicate reference']);
                }
            }


            switch (env('TRANSFER_PROVIDER')) {
                case 'INTERSWITCH':
                    return $this->iswFundTransfer();

                case 'PAYSTACK':
                    return $this->paystackFundTransfer();

                case 'ETRANZACT':
                    return $this->eTranzactFundTransfer();

            }

            return response()->json(['success' => false, 'message' => 'Fund transfer failed']);
        }
        catch (\Exception $exception ) {
            return response()->json(['success' => false, 'message' => 'Error with fund transfer']);
        }
    }


    public function iswFundTransfer()
    {
        $debited = false;
        $user = auth()->user();

        $business = auth()->user()->business;
        $type = 'TRANSFER';

        // validate product.
        $product = $business->getProduct($type);

        if ( !$product ) {
            return response()->json(['success' => false, 'message' => 'Invalid Product']);
        }

        if ( $product['status'] != true ) {
            return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
        }

        $amount = (double) request('amount');
        $charge = \App\Classes\Wallet::transferCharge($amount, $product);
        $netAmount = $amount + $charge;
        $reference = ISW::transferPrefix() . time();


        $info = 'Transfer N'. $amount . ' from your Wallet to '. request('account_name') . '('. request('account_number'). ') with transfer charge of ' . $charge;

        $transaction = $business->transactions()->create([
            'amount' => $amount, 'charge' => $charge, 'net_amount' => $netAmount,
            'reference' => $reference, 'status' => 'PENDING',
            'info' => $info . ' | ' . request('narration'), 'type' => 'TRANSFER',
            'channel' => request()->header('channel') ?? 'OTHERS',
            'wallet_debited' => $debited,
            'external_reference'   =>  request('reference') ?? null
        ]);

        try {

            // validate kyc
//            if ( Kyc::dailyLimitExceeded($user, $amount) ) {
//                return response()->json(['success' => false, 'message' => 'Daily transaction limit exceeded, you can still transfer '. Kyc::dailyAvailable($user)]);
//            }

            if ( $amount <= 0 ) {
                return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
            }

            if ( $amount > $business->wallet->balance ) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }

            // debit wallet
            $debit = \App\Classes\Wallet::debit($business, $netAmount, $info);

            if ( $debit['success'] ) {
                $debited = true;
                $transaction->wallet_debited = true;
                $transaction->save();

                $transfer = ISW::transfer(
                    $amount * 100,
                    request('account_number'),
                    request('account_name'),
                    '00',
                    request('bank_code'),
                    $reference,
                    request('narration')
                );

                if ( $transfer['success'] ) {
                    // validate response
                    $responseCode = $transfer['data']->responseCode ?? '9999999999';
                    $success = in_array($responseCode, \App\Classes\ISW::$transferCodeSucess);

                    if ( !$success ) {
//                        if ( $debited ) {
//                            Wallet::credit($user, $netAmount, $transaction->type . ' reversal');
//                        }

                        // save transaction
//                        $transaction->status = $transfer['data']->responseCodeGrouping;
                        $transaction->status = 'PENDING';
                        $transaction->wallet_debited = $debited;
                        $transaction->save();



                        return response()->json(['success' => true, 'message' => 'Wallet fund transfer in progress']);
                    }



                    // save transaction
                    $transaction->status = $transfer['data']->responseCodeGrouping;
                    $transaction->wallet_debited = $debited;
                    $transaction->save();

                    // Commission Settlement
                    if ($amount <= 5000)
                        $product_merchant_commission = $product['merchant_commission'][Product::$transfer_band_1] ?? 0;
                    elseif ($amount <= 50000)
                        $product_merchant_commission = $product['merchant_commission'][Product::$transfer_band_2] ?? 0;
                    else
                        $product_merchant_commission = $product['merchant_commission'][Product::$transfer_band_3] ?? 0;



                    if ($product['charge_type'] == Product::$PERCENTAGE) {
                        $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                        $info = "Commission of N $vas_commission for transfer of N$netAmount from the wallet of {$business->name} to "
                            . request('account_name') . '('. request('account_number'). ') with transfer charge of ' . $charge;

                        Log::info(json_encode($product_merchant_commission));
                        // Record VAS commissions
                        $business->commissions()->create([
                            'amount' => $vas_commission,
                            'product' => $product['name'],
                            'info' => $info
                        ]);

                    }
                    else {
                        $info = "Commission of N $product_merchant_commission for transfer of N$netAmount from the wallet of {$business->name} to "
                            . request('account_name') . '('. request('account_number'). ') with transfer charge of ' . $charge;
                        // Record VAS commissions
                        $business->commissions()->create([
                            'amount' => $product_merchant_commission,
                            'product' => $product['name'],
                            'info' => $info
                        ]);
                    }
                    // End Commission Settlement

//                    $user->transactions()->create([
//                        'amount' => $amount, 'charge' => $charge, 'net_amount' => $netAmount,
//                        'reference' => $reference, 'status' => $transfer['data']->responseCodeGrouping,
//                        'info' => $info . ' | ' . request('narration'), 'type' => 'TRANSFER',
//                        'channel' => request()->header('channel') ?? 'OTHERS',
//                        'wallet_debited' => $debited
//                    ]);


                    return response()->json(['success' => true, 'message' => 'Wallet fund transfer successful']);
                }

                // save transaction
                $transaction->status = 'FAILED';
                $transaction->wallet_debited = $debited;
                $transaction->info = $transfer['data']->message ?? 'Wallet fund transfer';
                $transaction->save();

                return response()->json(['success' => false, 'message' => 'Fund transfer failed!']);
//                return response()->json(['success' => false, 'message' => $transfer['data']->message]);
            }

            // save transaction
            $transaction->wallet_debited = $debited;
            $transaction->info = $debit['message'] ?? 'Error debiting your wallet';
            $transaction->save();

            if ( $debited ) {
                Wallet::credit($user->business, $netAmount, $transaction->type . ' reversal');
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
        }
        catch (\Exception $exception ) {
            // save transaction
            $transaction->wallet_debited = $debited;
            $transaction->status = 'PENDING';
            $transaction->save();

//            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error exception with bank transfer']);
        }
    }


    public function paystackVerifyBankAccount(BankAccountVerificationRequest $request)
    {
        try {
            $account =  Paystack::resolveAccount($request->account_number, $request->bank_code);

            if ( isset($account['success']) && $account['success'] ) {
                return response()->json(['success' => true, 'data' => $account['data'] ]);
            }

            return response()->json(['success' => false, 'message' => $account['message'] ]);
        }
        catch (\Exception $exception ) {
//            return response()->json(['success' => false, 'message' => $exception->getMessage() ]);
            return response()->json(['success' => false, 'message' => 'Error verifying account number']);
        }
    }


    public function paystackFundTransfer()
    {
        try {
            $debited = false;
            $user = auth()->user();

            $business = auth()->user()->business;
            $type = 'TRANSFER';

            // validate product.
            $product = $business->getProduct($type);

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }


            $amount = (double) request('amount');
            $charge = \App\Classes\Wallet::transferCharge($amount, $product);
            $netAmount = $amount + $charge;

            if ( $amount <= 0 ) {
                return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
            }

            if ( $amount > $business->wallet->balance ) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }


            $info = 'Transfer N'. $amount . ' from your Wallet to '. request('account_name') . '('. request('account_number'). ') with transfer charge of ' . $charge;

            $reference = General::generateReference();

            $transaction = $business->transactions()->create([
                'amount' => $amount, 'charge' => $charge, 'net_amount' => $netAmount,
                'status' => 'PENDING',
                'reference' => $reference,
                'info' => $info . ' | ' . request('narration'), 'type' => 'TRANSFER',
                'channel' => request()->header('channel') ?? 'OTHERS',
                'wallet_debited' => $debited,
                'external_reference'   =>  request('reference') ?? null
            ]);


            // debit wallet
            $debit = \App\Classes\Wallet::debit($business, $netAmount, $info);

            if ( $debit['success'] ) {
                $debited = true;
                $transaction->wallet_debited = true;
                $transaction->save();

                $recipient = Paystack::createTransferRecipient(request('account_number'), request('account_name'), request('bank_code'));
                Log::info('======== RECIPIENT PAYSTACK==');
                Log::info(json_encode($recipient));
                if ( $recipient['success'] && $recipient['data']['status']) {

                    $transfer = Paystack::transferFunds($amount * 100, $recipient['data']['data']['recipient_code'], request('narration'));

                    if ( $transfer['success'] && $transfer['data']['status']) {
                        $data = $transfer['data']['data'];

                        if ( $data['status'] == 'failed' ) {
                            if ( $debited ) {
                                Wallet::credit($user, $netAmount, $transaction->type . ' reversal');
                            }

                            // save transaction
                            $transaction->reference = $data['reference'];
                            $transaction->status = 'FAILED';
                            $transaction->wallet_debited = $debited;
                            $transaction->save();

                            return response()->json(['success' => true, 'message' => 'Wallet fund transfer in progress']);
                        }

                        // save transaction
                        $transaction->reference = $data['reference'];
                        $transaction->status = 'SUCCESSFUL';
                        $transaction->wallet_debited = $debited;
                        $transaction->save();


                        // Commission Settlement
                        if ($amount <= 5000)
                            $product_merchant_commission = $product['merchant_commission'][Product::$transfer_band_1] ?? 0;
                        elseif ($amount <= 50000)
                            $product_merchant_commission = $product['merchant_commission'][Product::$transfer_band_2] ?? 0;
                        else
                            $product_merchant_commission = $product['merchant_commission'][Product::$transfer_band_3] ?? 0;



                        if ($product['charge_type'] == Product::$PERCENTAGE) {
                            $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                            $info = "Commission of N $vas_commission for transfer of N$netAmount from the wallet of {$business->name} to "
                                . request('account_name') . '('. request('account_number'). ') with transfer charge of ' . $charge;

                            Log::info(json_encode($product_merchant_commission));
                            // Record VAS commissions
                            $business->commissions()->create([
                                'amount' => $vas_commission,
                                'product' => $product['name'],
                                'info' => $info
                            ]);

                        }
                        else {
                            $info = "Commission of N $product_merchant_commission for transfer of N$netAmount from the wallet of {$business->name} to "
                                . request('account_name') . '('. request('account_number'). ') with transfer charge of ' . $charge;
                            // Record VAS commissions
                            $business->commissions()->create([
                                'amount' => $product_merchant_commission,
                                'product' => $product['name'],
                                'info' => $info
                            ]);
                        }
                        // End Commission Settlement

                        return response()->json(['success' => true, 'message' => 'Wallet fund transfer successful']);
                    }

                    if ( $debited ) {
                        Wallet::credit($user, $netAmount, $transaction->type . ' reversal');
                    }

                    // save transaction
                    $transaction->status = 'FAILED';
                    $transaction->wallet_debited = false;
                    $transaction->save();


                    return response()->json(['success' => false, 'message' => 'Fund transfer failed!']);
                }

                if ( $debited ) {
                    Wallet::credit($user->business, $netAmount, $transaction->type . ' reversal');
                }

                // save transaction
                $transaction->status = 'FAILED';
                $transaction->wallet_debited = false;
                $transaction->save();

                return response()->json(['success' => false, 'message' => 'Fund transfer failed!']);
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
        }
        catch (\Exception $exception ) {
//            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error exception with bank transfer']);
        }
    }


    public function eTranzactVerifyBankAccount(BankAccountVerificationRequest $request)
    {
        try {
            $account =  ETranzact::validateAccount($request->account_number, $request->bank_code);

            if ( $account['success'] ) {
                return response()->json(['success' => true, 'account_name' => trim($account['data']['message'])]);
            }

            return response()->json(['success' => false, 'message' => $account['message'] ]);
        }
        catch (\Exception $exception ) {
            Log::info('============= Etranzact Exception ===============');
            Log::info($exception->getMessage());
//            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error verifying account number']);
        }
    }


    public function eTranzactFundTransfer()
    {
        try {
            $debited = false;
            $user = auth()->user();

            $business = auth()->user()->business;
            $type = 'TRANSFER';

            // validate product.
            $product = $business->getProduct($type);

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $bank = null;
            foreach ( json_decode(file_get_contents(storage_path('bank_list.json'))) as $b ) {
                if ( $b->cbn_code == request('bank_code') ) {
                    $bank = $b;
                }
            }

            if (is_null($bank) ) {
                return response()->json(['success' => false, 'message' => 'Invalid bank selected!']);
            }


            $amount = (double) request('amount');
            $charge = \App\Classes\Wallet::transferCharge($amount, $product);
            $netAmount = $amount + $charge;
            $reference = General::generateReference();

            // validate kyc
//            if ( Kyc::dailyLimitExceeded($user, $amount) ) {
//                return response()->json(['success' => false, 'message' => 'Daily transaction limit exceeded, you can still transfer '. Kyc::dailyAvailable($user)]);
//            }

            if ( $amount <= 0 ) {
                return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
            }

            if ( $amount > $user->business->wallet->balance ) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }


            $info = 'Transfer N'. $amount . ' from your Wallet to '. request('account_name') . '('. request('account_number'). ') @ ' . $bank->bank_name . ' with transfer charge of ' . $charge;

            $transaction = $user->business->transactions()->create([
                'amount' => $amount, 'charge' => $charge, 'net_amount' => $netAmount,
                'status' => 'PENDING',
                'info' => $info . ' | ' . request('narration'), 'type' => 'TRANSFER',
                'channel' => request()->header('channel') ?? 'OTHERS',
                'wallet_debited' => $debited,
                'reference' => $reference,
                'external_reference'   =>  request('reference') ?? null
            ]);

            // debit wallet
            $debit = \App\Classes\Wallet::debit($user->business, $netAmount, $info);

            if ( $debit['success'] ) {
                $debited = true;
                $transaction->wallet_debited = true;
                $transaction->save();

                $transfer = ETranzact::transfer($amount, request('account_number'), request('bank_code'), request('account_name'), $reference, request('narration'));

                if ( $transfer['success'] ) {

                    $data = $transfer['data'];

                    if ($data['error'] == -1 || $data['error'] == 31){
                        ReQuery::create([
                            'transaction_id' => $transaction->id,
                            'provider' => 'ETranzact',
                            'status' => 'pending'
                        ]);
                    }

                    // save transaction
                    $transaction->status = ($data['error'] == -1 || $data['error'] == 31) ? 'PENDING' : 'SUCCESSFUL';
                    $transaction->wallet_debited = $debited;
                    $transaction->save();

                    // Commission Settlement
                    if ($amount <= 5000)
                        $product_vas_commission = $product['vas_commission'][Product::$transfer_band_1] ?? 0;
                    elseif ($amount <= 50000)
                        $product_vas_commission = $product['vas_commission'][Product::$transfer_band_2] ?? 0;
                    else
                        $product_vas_commission = $product['vas_commission'][Product::$transfer_band_3] ?? 0;


                    if ($product['is_flat']) {
                        // Commission Settlement
                        $info = "Commission of N $charge for transfer of N$netAmount from the wallet of {$business->name} to "
                            . request('account_name') . '('. request('account_number'). ') with transfer charge of ' . $charge;
                        // Record VAS commissions
                        $business->commissions()->create([
                            'amount' => $charge,
                            'product' => $product['name'],
                            'info' => $info
                        ]);
                    }
                    else{
                        if ($product['charge_type'] == Product::$PERCENTAGE) {
                            $product_vas_commission_perc = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                            $info = "Commission of N $product_vas_commission_perc for transfer of N$amount from the wallet of {$business->name} to "
                                . request('account_name') . '('. request('account_number'). ') with transfer charge of ' . $charge;

                            // Record VAS commissions
                            $business->commissions()->create([
                                'amount' => $product_vas_commission_perc,
                                'product' => $product['name'],
                                'info' => $info
                            ]);

                        }
                        else {
                            $info = "Commission of N $product_vas_commission for transfer of N$amount from the wallet of {$business->name} to "
                                . request('account_name') . '('. request('account_number'). ') with transfer charge of ' . $charge;
                            // Record VAS commissions
                            $business->commissions()->create([
                                'amount' => $product_vas_commission,
                                'product' => $product['name'],
                                'info' => $info
                            ]);
                        }
                    }

                    // End Commission Settlement

                    return response()->json(['success' => true, 'status' => ($data['error'] == -1 || $data['error'] == 31) ? 'pending' : 'success', 'message' => 'Fund transfer successful']);
                }

                if ( $debited ) {
                    Wallet::credit($user->business, $netAmount, $transaction->type . ' reversal');
                }

                // save transaction
                $transaction->status = 'FAILED';
                $transaction->wallet_debited = false;
                $transaction->save();

                $details = [
                    'subject' => 'Failed Transaction Notification 🔊',
                    'greeting' => 'Hello 👋🏾 Support Admin!' ,
                    'body' => "A failed transaction just occurred on - {$transaction->business->name} with the below details; {$transaction->info}",
                    'moreBody' => "Transaction reference: {$transaction->reference}",
                    'thanks' => 'Sagecloud Automated Notification',
                    'actionText' => 'See Now!',
                    'actionURL' => url('/login')
                ];
                NotificationHelper::notifyAdmin($details);

                return response()->json(['success' => false, 'message' => 'Fund transfer failed!']);
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
        }
        catch (\Exception $exception ) {
//            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error exception with bank transfer']);
        }
    }


    public function bluSaltFundTransfer()
    {
        try {
            $debited = false;
            $user = auth()->user();

            $business = auth()->user()->business;
            $type = 'TRANSFER';

            // validate product.
            $product = $business->getProduct($type);

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $bank = null;
            foreach ( json_decode(file_get_contents(storage_path('bank_list.json'))) as $b ) {
                if ( $b->cbn_code == request('bank_code') ) {
                    $bank = $b;
                }
            }

            if (is_null($bank) ) {
                return response()->json(['success' => false, 'message' => 'Invalid bank selected!']);
            }


            $amount = (double) request('amount');
            $charge = \App\Classes\Wallet::transferCharge($amount, $product);
            $netAmount = $amount + $charge;
            $reference = General::generateReference();


            if ( $amount <= 0 ) {
                return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
            }

            if ( $amount > $user->business->wallet->balance ) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }


            $info = 'Transfer N'. $netAmount . ' from your Wallet to '. request('account_name') . '('. request('account_number'). ') @ ' . $bank->bank_name . ' with transfer charge of ' . $charge;

            $transaction = $user->business->transactions()->create([
                'amount' => $amount, 'charge' => $charge, 'net_amount' => $netAmount,
                'status' => 'PENDING',
                'info' => $info . ' | ' . request('narration'), 'type' => 'TRANSFER',
                'channel' => request()->header('channel') ?? 'OTHERS',
                'wallet_debited' => $debited,
                'reference' => $reference,
                'external_reference'   =>  request('reference') ?? null
            ]);

            // debit wallet
            $debit = \App\Classes\Wallet::debit($user->business, $netAmount, $info);

            if ( $debit['success'] ) {
                $debited = true;
                $transaction->wallet_debited = true;
                $transaction->save();

                $transfer = Blusalt::fundsTransfer($amount*100, request('account_number'), request('bank_code'), request('account_name'), $reference, request('narration'));

                if ( $transfer['success'] && $transfer['status'] == 'success' ) {

                    $data = $transfer['data'];

                    // save transaction
                    $transaction->status = 'SUCCESSFUL';
                    $transaction->wallet_debited = $debited;
                    $transaction->save();

                    // Commission Settlement
                    $info = "Commission of N $charge for transfer of N$netAmount from the wallet of {$business->name} to "
                        . request('account_name') . '('. request('account_number'). ') with transfer charge of ' . $charge;
                    // Record VAS commissions
                    $business->commissions()->create([
                        'amount' => $charge,
                        'product' => $product['name'],
                        'info' => $info
                    ]);
                    // End Commission Settlement

                    return response()->json(['success' => true, 'message' => 'Fund transfer successful']);
                }

                if ( !$transfer['success'] && $transfer['status'] == 'failed' ) {
                    if ( $debited ) {
                        Wallet::credit($user->business, $netAmount, $transaction->type . ' reversal');
                    }

                    // save transaction
                    $transaction->status = 'FAILED';
                    $transaction->wallet_debited = false;
                    $transaction->save();
                    return response()->json(['success' => false, 'message' => 'Fund transfer failed']);
                }

                return response()->json(['success' => false, 'message' => 'Fund transfer is processing']);
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
        }
        catch (\Exception $exception ) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error exception with bank transfer']);
        }
    }
}
