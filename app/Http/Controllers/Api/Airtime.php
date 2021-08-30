<?php

namespace App\Http\Controllers\Api;


use App\Classes\Capricorn;
use App\Classes\General;
use App\Classes\Shago;
use App\Classes\Wallet;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Model\Biller;
use App\Classes\Sonite;
use App\Model\Product;
use App\Model\Provider;
use App\Model\ServiceCode;
use App\Model\Transaction;
use App\ReQuery;
use Illuminate\Support\Facades\Log;


class Airtime extends Controller
{

    private $business;

    /**
     *
     * Airtime purchase
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public function purchase()
    {
        try {
            $this->business = auth()->user()->business;

            // validate product.
            $product = $this->business->getProduct('AIRTIME');

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }


            $reference = General::generateReference();
            $data = request()->all();
            if (isset($data['reference'])) {
                $transaction = Transaction::where('external_reference', $data['reference'])->first();
                if ( !is_null($transaction) && $transaction->business_id == $this->business->id) {
                    return response()->json(['success' => false, 'message' => 'Duplicate reference']);
                }
            }

            $current_provider = Provider::airtime_current();

            if ($current_provider->name == 'SHAGO') {
                return $this->purchaseWithShago($reference, $data);
            }

            if ($current_provider->name == 'CAPRICORN') {
                return $this->purchaseWithCapricorn($reference, $data);
            }

            //No Check for pending
            if ($current_provider->name == 'SONITE') {
                return $this->purchaseWithSonite($reference, $data);
            }

            return response()->json(['success' => false, 'message' => 'Invalid provider']);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage().$exception->getLine()]);
//            return response()->json(['success' => false, 'message' => 'Error with purchase']);
        }
    }


    public function purchaseWithShago($reference, $data)
    {
        $debited = false;

        $phone = str_replace(' ', '', $data['phone']);

        $amount =  (double) $data['amount'];
        $netAmount = $amount;

        if ($amount > $this->business->wallet->balance) {
            return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
        }

        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
        }

        if ($amount < 50) {
            return response()->json(['success' => false, 'message' => 'Amount entered should be greater than 50']);
        }

        $info = "{$data['network']} AIRTIME purchase of N $amount from your Wallet to $phone";

        // save transaction
        $transaction = $this->business->transactions()->create([
            'amount'      => $amount,
            'charge'      => 0.0,
            'net_amount'  => $netAmount,
            'status'      => 'PENDING',
            'type'        => 'AIRTIME',
            'info'        => $info,
            'channel'     => request()->header('channel') ?? 'OTHERS',
            'reference'   =>  $reference ,
            'external_reference'   =>  $data['reference'] ?? null ,
        ]);


        // debit wallet
        $debit =  Wallet::debit($this->business, $amount, $info);


        if ($debit['success']) {

            $debited = true;
            $transaction->update(['wallet_debited' => 1]);

            $service = "QAB";
            $purchase = Shago::buyAirtime($service, $phone, $amount, $data['network'], $reference);

            if ( $purchase['success'] && $purchase['status'] == 'success') {
                // Update transaction
                $transaction->status = 'SUCCESSFUL';
                $transaction->save();

                // Commission Settlement
                $current_product = $this->business->getProduct('AIRTIME');

                $product_merchant_commission = $current_product['merchant_commission'][$data['network']];
                $product_vas_commission = $current_product['vas_commission'][$data['network']];


                if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                    $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                    $info = "Commission of N $merchant_commission from AIRTIME purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                    $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                    $info = "Commission of N $vas_commission for AIRTIME purchase of N $amount from {$this->business->name}";

                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);

                }
                else {
                    $info = "Commission of $product_merchant_commission from AIRTIME purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                    $info = "Commission of N $product_vas_commission for AIRTIME purchase of N $amount from {$this->business->name}";
                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $product_vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);
                }
                // End Commission Settlement

                return response()->json([
                    'success' => true,
                    'status' => 'success',
                    'message' => 'AIRTIME purchase successful',
                    'reference' => $data['reference'] ?? null
                ]);

            }
            elseif ($purchase['success'] && $purchase['status'] == 'pending'){
                // Commission Settlement
                $current_product = $this->business->getProduct('AIRTIME');

                $product_merchant_commission = $current_product['merchant_commission'][$data['network']];
                $product_vas_commission = $current_product['vas_commission'][$data['network']];


                if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                    $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                    $info = "Commission of N $merchant_commission from AIRTIME purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                    $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                    $info = "Commission of N $vas_commission for AIRTIME purchase of N $amount from {$this->business->name}";

                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);

                }
                else {
                    $info = "Commission of $product_merchant_commission from AIRTIME purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                    $info = "Commission of N $product_vas_commission for AIRTIME purchase of N $amount from {$this->business->name}";
                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $product_vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);
                }
                // End Commission Settlement

                ReQuery::create([
                    'transaction_id' => $transaction->id,
                    'provider' => 'Shago',
                    'status' => 'pending'
                ]);

                return response()->json([
                    'success' => true,
                    'status' => 'pending',
                    'message' => 'AIRTIME purchase successful',
                    'reference' => $data['reference'] ?? null
                ]);

            }
            elseif ( $purchase['status'] === 'failed') {
                // Update transaction
                $transaction->status = 'FAILED';
                $transaction->save();

                // Reverse failed purchase
                if ($debited) {
                    Wallet::credit($this->business, $amount, 'AIRTIME purchase reversal');
                }

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
                return response()->json(['success' => false, 'message' => 'AIRTIME purchase failed. Please try again']);
            }

            return response()->json(['success' => false, 'message' => $purchase['message']]);
        }

        return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
    }


    public function purchaseWithSonite($reference, $data)
    {
        $debited = false;

        $phone = str_replace(' ', '', $data['phone']);

        $amount =  (double) $data['amount'] ;
        $netAmount = $amount;


        if ( $amount > $this->business->wallet->balance ) {
            return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
        }

        if ( $amount <= 0 ) {
            return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
        }

        if ($amount < 50) {
            return response()->json(['success' => false, 'message' => 'Amount entered should be greater than 50']);
        }

        $info = "{$data['network']} AIRTIME purchase of N $amount from your Wallet to $phone";

        // save transaction
        $transaction = $this->business->transactions()->create([
            'amount'      => $amount,
            'charge'      => 0.0,
            'net_amount'  => $netAmount,
            'status'      => 'PENDING',
            'type'        => 'AIRTIME',
            'info'        => $info,
            'channel'     => request()->header('channel') ?? 'OTHERS',
            'reference'   =>  $reference ,
            'external_reference'   =>  $data['reference'] ?? null ,
        ]);
//        Log::info(json_encode($transaction));

        // debit wallet
        $debit =  Wallet::debit($this->business, $amount, $info);

        if ( $debit['success'] ) {
            $debited = true;

            $transaction->update(['wallet_debited' => 1]);

            $service = $data['service'];

            $purchase = Sonite::vtuPurchase($phone, $service, $amount);


            if ( $purchase['success'] ) {

                // Update transaction
                $transaction->status = 'SUCCESSFUL';
                $transaction->save();

                // Commission Settlement
                $current_product = $this->business->getProduct('AIRTIME');

                $product_merchant_commission = $current_product['merchant_commission'][$data['network']];
                $product_vas_commission = $current_product['vas_commission'][$data['network']];


                if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                    $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                    $info = "Commission of N $merchant_commission from AIRTIME purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                    $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                    $info = "Commission of N $vas_commission for AIRTIME purchase of N $amount from {$this->business->name}";

                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);

                }
                else {
                    $info = "Commission of $product_merchant_commission from AIRTIME purchase of N $amount from your Wallet to $phone";
                    \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                    $info = "Commission of N $product_vas_commission for AIRTIME purchase of N $amount from {$this->business->name}";
                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $product_vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);
                }
                // End Commission Settlement



                return response()->json([
                    'success' => true,
                    'status' => 'success',
                    'message' => 'AIRTIME purchase successful',
                    'reference' => $data['reference'] ?? null
                ]);

            } else {

                $transaction->status = 'FAILED';
                $transaction->save();

                // Reverse failed purchase
                if ( $debited ) {
                    Wallet::credit($this->business, $amount, 'AIRTIME purchase reversal');
                }
            }
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
            // dd($purchase['message']);
            return response()->json(['success' => false, 'message' => $purchase['message']]);
        }

        return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
    }


    public function purchaseWithCapricorn($reference, $data)
    {
        $debited = false;

        $phone = str_replace(' ', '', $data['phone']);

        $amount =  (double) $data['amount'] ;

        $netAmount = $amount;


        if ( $amount > $this->business->wallet->balance ) {
            return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
        }

        if ( $amount <= 0 ) {
            return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
        }

        if ($amount < 50) {
            return response()->json(['success' => false, 'message' => 'Amount entered should be greater than 50']);
        }

        $info = "{$data['network']} AIRTIME purchase of N $amount from your Wallet to $phone";

        // save transaction
        $transaction = $this->business->transactions()->create([
            'amount'      => $amount,
            'charge'      => 0.0,
            'net_amount'  => $netAmount,
            'status'      => 'PENDING',
            'type'        => 'AIRTIME',
            'info'        => $info,
            'channel'     => request()->header('channel') ?? 'OTHERS',
            'reference'   =>  $reference ,
            'external_reference'   =>  $data['reference'] ?? null ,
        ]);
//        Log::info(json_encode($transaction));

        // debit wallet
        $debit =  Wallet::debit($this->business, $amount, $info);

        if ( $debit['success'] ) {
            $debited = true;

            $transaction->update(['wallet_debited' => 1]);

            $service = '';

            if ($data['service'] == 'MTNVTU')
                $service = 'mtn';
            if ($data['service'] == 'AIRTELVTU')
                $service = 'airtel';
            if ($data['service'] == 'GLOVTU')
                $service = 'glo';
            if ($data['service'] == '9MOBILEVTU')
                $service = '9mobile';

            $purchase = Capricorn::vtuPurchase($amount, $reference, $phone, $service);


            if ( $purchase['success'] && $purchase['status'] == 'success' ) {

                // Update transaction
                $transaction->status = 'SUCCESSFUL';
                $transaction->save();

                // Commission Settlement
                $current_product = $this->business->getProduct('AIRTIME');

                $product_merchant_commission = $current_product['merchant_commission'][$data['network']];
                $product_vas_commission = $current_product['vas_commission'][$data['network']];


                if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                    $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                    $info = "Commission of N $merchant_commission from AIRTIME purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                    $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                    $info = "Commission of N $vas_commission for AIRTIME purchase of N $amount from {$this->business->name}";

                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);

                }
                else {
                    $info = "Commission of $product_merchant_commission from AIRTIME purchase of N $amount from your Wallet to $phone";
                    \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                    $info = "Commission of N $product_vas_commission for AIRTIME purchase of N $amount from {$this->business->name}";
                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $product_vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);
                }
                // End Commission Settlement



                return response()->json([
                    'success' => true,
                    'status' => 'success',
                    'message' => 'AIRTIME purchase successful',
                    'reference' => $data['reference'] ?? null
                ]);

            }
            elseif ($purchase['success'] && $purchase['status'] == 'pending'){

                // Commission Settlement
                $current_product = $this->business->getProduct('AIRTIME');

                $product_merchant_commission = $current_product['merchant_commission'][$data['network']];
                $product_vas_commission = $current_product['vas_commission'][$data['network']];


                if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                    $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                    $info = "Commission of N $merchant_commission from AIRTIME purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                    $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                    $info = "Commission of N $vas_commission for AIRTIME purchase of N $amount from {$this->business->name}";

                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);

                }
                else {
                    $info = "Commission of $product_merchant_commission from AIRTIME purchase of N $amount from your Wallet to $phone";
                    \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                    $info = "Commission of N $product_vas_commission for AIRTIME purchase of N $amount from {$this->business->name}";
                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $product_vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);
                }
                // End Commission Settlement

                ReQuery::create([
                    'transaction_id' => $transaction->id,
                    'provider' => 'Capricorn',
                    'status' => 'pending'
                ]);

                return response()->json([
                    'success' => true,
                    'status' => 'pending',
                    'message' => 'AIRTIME purchase successful',
                    'reference' => $data['reference'] ?? null
                ]);
            }
            elseif ($purchase['status'] == 'failed') {

                $transaction->status = 'FAILED';
                $transaction->save();

                // Reverse failed purchase
                if ( $debited ) {
                    Wallet::credit($this->business, $amount, 'AIRTIME purchase reversal');
                }
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
                return response()->json(['success' => false, 'message' => 'AIRTIME purchase failed. Please try again']);
            }

            // dd($purchase['message']);
            return response()->json(['success' => false, 'message' => $purchase['message']]);
        }

        return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
    }
}
