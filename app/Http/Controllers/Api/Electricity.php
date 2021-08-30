<?php

namespace App\Http\Controllers\Api;


use App\Classes\Capricorn;
use App\Classes\General;
use App\Classes\Shago;
use App\Classes\Wallet;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Model\Biller;
use App\Model\Product;
use App\Model\Provider;
use App\Model\Transaction;
use App\ReQuery;
use Illuminate\Support\Facades\Log;


class Electricity extends Controller
{


    private $business;


    public function fetchBillers()
    {
        try {

            $billers = Biller::with('items')
                ->where('biller_type', 'ELECTRICITY')->get();

            foreach ($billers as $biller) {
                if ( isset($biller->logo_url) ) {
                    $biller->image = env('APP_URL') . "/" . $biller->logo_url;
                }
            }

            return response()->json(['success' => true, 'billers' => $billers ]);
        }
        catch (\Exception $exception ) {

            return response()->json(['success' => false, 'message' => 'Error fetching electricity bills']);
        }
    }


    private function filterType($type)
    {
        $type = explode('_', $type);
        array_pop($type);
        return implode('_', $type);
    }


    public function fetchBillersWithCapricorn()
    {
        try {
//            $data = request()->all();
            $this->business = auth()->user()->business;

            $type = 'ELECTRICITY';

            // validate product.
            $product = $this->business->getProduct($type);

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $resp = Capricorn::electricityLookUp();

            if (!$resp['success']){
                return response()->json(['success' => false, 'message' => 'Failed to fetch billers']);
            }

            $billers = [];

            foreach ($resp['data']->data->providers as $biller) {

                // Formulate image since providers does not have image
                $slug = $this->filterType($biller->service_type);
                $path = "assets/images/billers/capricorn/" . $slug . ".webp";

                if (is_file(public_path() . "/". $path)) {
                    $image = env('APP_URL') . "/" . $path;
                } else {
                    $image = env('APP_URL') . "/assets/images/billers/capricorn/elect.webp";
                }

                $billers[] = (object) [
                    'type' => $biller->service_type,
                    'id' => $biller->biller_id,
                    'narration' => $biller->name,
                    'product_id' => $biller->product_id,
                    'short_name' => $biller->shortname,
                    'image' => $image,
                ];
            }

            return response()->json(['success' => true, 'billers' => $billers ]);
        }
        catch (\Exception $exception ) {

//            return response()->json(['success' => false, 'message' => $exception->getMessage(), 'line' => $exception->getLine() ]);
            return response()->json(['success' => false, 'message' => 'Error fetching electricity data']);
        }
    }

    public function getFullName ($product, $type, $disco) {
        foreach ($product[$type] as $key => $item) {
            $short_name = str_replace(' ', '', rtrim($key,')'));
            $short_name = explode('(', $short_name);
            if ($short_name[1] == $disco) {
                return $key;
            }
        }
        return null;
    }

    public function validateCustomer()
    {
        try {


            $data = request()->all();

            $required = [
                'type', 'account_number'
            ];

            foreach ($required as $req) {
                if (!isset($data[$req]))
                    return response()->json([
                        'success' => false,
                        'message' => "Request is missing key: $req"
                    ]);
            }

            $disco = '';
            if ($data['type'] == 'ikeja_electric_prepaid' || $data['type'] == 'ikeja_electric_postpaid')
                $disco = 'IKEDC';
            if ($data['type'] == 'ibadan_electric_prepaid' || $data['type'] == 'ibadan_electric_postpaid')
                $disco = 'IBEDC';
            if ($data['type'] == 'eko_electric_prepaid' || $data['type'] == 'eko_electric_postpaid')
                $disco = 'EKEDC';
            if ($data['type'] == 'kedco_electric_prepaid' || $data['type'] == 'kedco_electric_postpaid')
                $disco = 'KEDC';
            if ($data['type'] == 'abuja_electric_prepaid' || $data['type'] == 'abuja_electric_postpaid')
                $disco = 'AEDC';
            if ($data['type'] == 'jos_electric_prepaid' || $data['type'] == 'jos_electric_postpaid')
                $disco = 'JEDC';
            if ($data['type'] == 'kaduna_electric_prepaid' || $data['type'] == 'kaduna_electric_postpaid')
                $disco = 'KAEDC';
            if ($data['type'] == 'enugu_electric_prepaid' || $data['type'] == 'enugu_electric_postpaid')
                $disco = 'EEDC';
            if ($data['type'] == 'portharcourt_electric_prepaid' || $data['type'] == 'portharcourt_electric_postpaid')
                $disco = 'PHEDC';

            $biller = Biller::where('short_name', $disco)->first();


            $customer = Shago::validateMeter($biller->short_name, $data['account_number'], $biller->type);


            if ( $customer['success'] ) {

                // Add extra fields for actual purchase
                $customer['data']->billerName = $biller->short_name;
                $customer['data']->billerNarration = $biller->narration;
                $customer['data']->billerType = $biller->type;
//                $customer['data']->customerName = $customer['data']->customerName . '.  Address: ' . $customer['data']->customerAddress;
//                $customer['data']->customerAddress = $customer['data']->customerAddress;

                return response()->json(['success' => true, 'customer' => $customer['data']]);

            }

            return response()->json(['success' => false, 'message' => $customer['message']]);
        }
        catch (\Exception $exception ) {

            return response()->json(['success' => false, 'message' => 'Error exception with customer validation', 'error' => $exception->getMessage()]);
        }
    }


    public function validateCustomerWithCapricorn()
    {
        try {
            $data = request()->all();
            $this->business = auth()->user()->business;

//            Log::info('================= electric validate payload =======');
//            Log::info(json_encode($data));

            $required = [
                'type', 'account_number'
            ];

            foreach ($required as $req) {
                if (!isset($data[$req]))
                    return response()->json([
                        'success' => false,
                        'message' => "Request is missing key: $req"
                    ]);
            }

            $type = 'ELECTRICITY';

            // validate product.
            $product = $this->business->getProduct($type);

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $resp = Capricorn::validateService($data['type'], $data['account_number']);

            if ( $resp['success'] ) {

                $details = $resp['data']->data->user;

                $customer = (object) [];
                // Add extra fields for actual purchase
                $customer->billerName = $details->short_name ?? null;
                $customer->billerNarration = $details->narration ?? null;
                $customer->billerType = $details->rawOutput->customerType ?? null;
                $customer->customerName = $details->name;
                $customer->customerAddress = $details->address ?? null;

                return response()->json(['success' => true, 'customer' => $customer ]);

            }

            return response()->json(['success' => false, 'message' => 'Sorry! we could not validate this customer. Please check account number and try again']);
        }
        catch (\Exception $exception ) {

            return response()->json(['success' => false, 'message' => "Error validating customer's details.".$exception->getMessage() ]);
        }
    }


    public function purchase () {

        try {

            $this->business = auth()->user()->business;

            $data = request()->all();
            // validate product.
            $product = $this->business->getProduct('ELECTRICITY');

            if ($data['amount'] > 0 && $data['amount'] < 100) {
                return response()->json(['success' => false, 'message' => 'Minimum amount is 100']);
            }

            $required = [
                'type', 'amount', 'phone', 'account_number'
            ];

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            foreach ($required as $req) {
                if (!isset($data[$req]))
                    return response()->json([
                        'success' => false,
                        'message' => "Request is missing key: $req"
                    ]);
            }

            $reference = General::generateReference();
            $data = request()->all();


            if (isset($data['reference'])) {
                $transaction = Transaction::where('external_reference', $data['reference'])->first();
                if ( !is_null($transaction) && $transaction->business_id == $this->business->id) {
                    return response()->json(['success' => false, 'message' => 'Duplicate reference']);
                }
            }

            $current_provider = Provider::electricity_current();

            if($current_provider->name == 'SHAGO'){

                //ABUJA

                if ($data['type'] == 'abuja_electric_prepaid') {
                    $data['disco'] = 'AEDC';
                    $data['biller_type'] = 'PREPAID';
                }

                if ($data['type'] == 'abuja_electric_postpaid') {
                    $data['disco'] = 'AEDC';
                    $data['biller_type'] = 'POSTPAID';
                }


                //KADUNA

                if ($data['type'] == 'kaduna_electric_prepaid') {
                    $data['disco'] = 'KAEDC';
                    $data['biller_type'] = 'PREPAID';
                }

                if ($data['type'] == 'kaduna_electric_postpaid') {
                    $data['disco'] = 'KAEDC';
                    $data['biller_type'] = 'POSTPAID';
                }

                //JOS

                if ($data['type'] == 'jos_electric_prepaid') {
                    $data['disco'] = 'JEDC';
                    $data['biller_type'] = 'PREPAID';
                }

                if ($data['type'] == 'jos_electric_postpaid') {
                    $data['disco'] = 'JEDC';
                    $data['biller_type'] = 'POSTPAID';
                }

                //IKEJA

                if ($data['type'] == 'ikeja_electric_prepaid') {
                    $data['disco'] = 'IKEDC';
                    $data['biller_type'] = 'PREPAID';
                }

                if ($data['type'] == 'ikeja_electric_postpaid') {
                    $data['disco'] = 'IKEDC';
                    $data['biller_type'] = 'POSTPAID';
                }

                //EKO

                if ($data['type'] == 'eko_electric_prepaid') {
                    $data['disco'] = 'EKEDC';
                    $data['biller_type'] = 'PREPAID';
                }

                if ($data['type'] == 'eko_electric_postpaid') {
                    $data['disco'] = 'EKEDC';
                    $data['biller_type'] = 'POSTPAID';
                }


                //KANO

                if ($data['type'] == 'kedco_electric_prepaid') {
                    $data['disco'] = 'KEDC';
                    $data['biller_type'] = 'PREPAID';
                }

                if ($data['type'] == 'kedco_electric_postpaid') {
                    $data['disco'] = 'KEDC';
                    $data['biller_type'] = 'POSTPAID';
                }

                //PH

                if ($data['type'] == 'portharcourt_electric_prepaid') {
                    $data['disco'] = 'PHEDC';
                    $data['biller_type'] = 'PREPAID';
                }

                if ($data['type'] == 'portharcourt_electric_postpaid') {
                    $data['disco'] = 'PHEDC';
                    $data['biller_type'] = 'POSTPAID';
                }

                //IBADAN

                if ($data['type'] == 'ibadan_electric_prepaid') {
                    $data['disco'] = 'IBEDC';
                    $data['biller_type'] = 'PREPAID';
                }

                if ($data['type'] == 'ibadan_electric_postpaid') {
                    $data['disco'] = 'IBEDC';
                    $data['biller_type'] = 'POSTPAID';
                }

                //ENUGU

                if ($data['type'] == 'enugu_electric_prepaid') {
                    $data['disco'] = 'EEDC';
                    $data['biller_type'] = 'PREPAID';
                }

                if ($data['type'] == 'enugu_electric_postpaid') {
                    $data['disco'] = 'EEDC';
                    $data['biller_type'] = 'POSTPAID';
                }


                return $this->purchaseWithShago($data, $reference);

            }

            if($current_provider->name == 'CAPRICORN'){

                return $this->purchaseWithCapricorn($data);

            }


        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage().$exception->getLine()]);
//            return response()->json(['success' => false, 'message' => 'Error with purchase']);
        }

    }


    /**
     * Electricity purchase & Data subscription
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchaseWithShago($data,  $reference)
    {
        $debited = false;
        try {

            $this->business = auth()->user()->business;

//            $data = request()->all();

            if (isset($data['reference'])) {
                $transaction = Transaction::where('external_reference', $data['reference'])->first();
                if ( !is_null($transaction) && $transaction->business_id == $this->business->id) {
                    return response()->json(['success' => false, 'message' => 'Duplicate reference']);
                }
            }

            // validate product.
            $product = $this->business->getProduct('ELECTRICITY');

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $meterNumber = $data['account_number'];

            $referenceId = General::generateReference();

            $biller = Biller::where('short_name', $data['disco'])->where('type', $data['biller_type'])->first();

            $amount =  (double) $data['amount'];

            if ( $amount > $this->business->wallet->balance ) {

                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }

            if ( $amount <= 0 ) {

                return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
            }

            if ($amount < 100) {
                return response()->json(['success' => false, 'message' => 'Amount entered should be greater than 100']);
            }

            $info = "ELECTRICITY purchase of N$amount to ({$data['type']}) from your Wallet to {$data['account_number']}";

            // save transaction
            $transaction = $this->business->transactions()->create([
                'amount' => $amount,
                'charge' => 0.0,
                'net_amount' => $amount,
                'status' => 'PENDING',
                'type' => 'ELECTRICITY',
                'info' => $info,
                'channel' => request()->header('channel') ?? 'OTHERS',
                'reference' => $reference,
                'external_reference'   =>  $data['reference'] ?? null
            ]);


            // debit wallet
            if($transaction){

                $debit = \App\Classes\Wallet::debit($this->business, $amount, $info);
            }


            if ( $debit['success'] ) {
                $debited = true;

                // Update transaction
                $transaction->update([
                    'wallet_debited' => 1,
                ]);

                $purchase = Shago::electricityPurchase($biller->short_name,  $meterNumber, $biller->type, $amount, $data['phone'], $this->business->name, $reference);

                if ( $purchase['success'] && $purchase['status'] == 'success' ) {

                    $token = $purchase['data']->token ?? null;
                    $unit = $purchase['data']->unit ?? null;
                    $desc = $transaction->info;

                    $params = [
                        'token' => $purchase['data']->token ?? null,
                        'unit' => $purchase['data']->unit ?? null,
                        'amount' => $data['amount'],
                        'transId' => $purchase['data']->transId
                    ];

                    // Update transaction
                    $transaction->info = $desc." Token:{$token}, Unit: {$unit}";
                    $transaction->status = 'SUCCESSFUL';
                    $transaction->save();

                    // Commission Settlement
                    $current_product = $this->business->getProduct($product['name']);
                    $full_name = $this->getFullName($product, 'merchant_commission', $data['disco']);

                    $product_merchant_commission = $current_product['merchant_commission'][$full_name];
                    $product_vas_commission = $current_product['vas_commission'][$full_name];


                    if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                        $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                        $info = "Commission of N $merchant_commission for {$biller['biller_type']} purchase of N$amount from your Wallet to $meterNumber";
                        $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                        \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                        $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                        $info = "Commission of N $vas_commission for {$biller['biller_type']} purchase of N$amount from {$this->business->name} to $meterNumber";

                        // Record VAS commissions
                        $this->business->commissions()->create([
                            'amount' => $vas_commission,
                            'product' => $current_product['name'],
                            'info' => $info
                        ]);

                    }
                    else {
                        $info = "Commission of N $product_merchant_commission for {$biller['biller_type']} purchase of N$amount from your Wallet to $meterNumber";
                        \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                        $info = "Commission of N $product_vas_commission for {$biller['biller_type']} purchase of N$amount from {$this->business->name} to $meterNumber";
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
                        'message' => 'ELECTRICITY purchase successful',
                        'data' => $params,
                        'reference' => $data['reference'] ?? null
                    ]);
                }
                elseif ( $purchase['success'] && $purchase['status'] == 'pending' ){
                    $params = [
                        'token' => $purchase['data']->token ?? null,
                        'unit' => $purchase['data']->unit ?? null,
                        'amount' => $data['amount'],
                        'transId' => $purchase['data']->transId
                    ];

                    // Commission Settlement
                    $current_product = $this->business->getProduct($product['name']);
                    $full_name = $this->getFullName($product, 'merchant_commission', $data['disco']);

                    $product_merchant_commission = $current_product['merchant_commission'][$full_name];
                    $product_vas_commission = $current_product['vas_commission'][$full_name];


                    if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                        $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                        $info = "Commission of N $merchant_commission for {$biller['biller_type']} purchase of N$amount from your Wallet to $meterNumber";
                        $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                        \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                        $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                        $info = "Commission of N $vas_commission for {$biller['biller_type']} purchase of N$amount from {$this->business->name} to $meterNumber";

                        // Record VAS commissions
                        $this->business->commissions()->create([
                            'amount' => $vas_commission,
                            'product' => $current_product['name'],
                            'info' => $info
                        ]);

                    }
                    else {
                        $info = "Commission of N $product_merchant_commission for {$biller['biller_type']} purchase of N$amount from your Wallet to $meterNumber";
                        \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                        $info = "Commission of N $product_vas_commission for {$biller['biller_type']} purchase of N$amount from {$this->business->name} to $meterNumber";
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
                        'message' => 'ELECTRICITY purchase successful',
                        'data' => $params,
                        'reference' => $data['reference'] ?? null
                    ]);
                }
                elseif ($purchase['status'] == 'failed' ) {

                    $transaction->status = 'FAILED';
                    $transaction->save();

                    if ( $debited ) {
                        Wallet::credit($this->business, $amount, 'Electricity purchase reversal');
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
                }

                return response()->json(['success' => false, 'message' => $purchase['message']]);
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
        }
        catch (\Exception $exception ) {
//             return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error with '. request('type').' purchase']);
        }
    }


    public function purchaseWithCapricorn($data)
    {
        $debited = false;

        try {

//            $data = request()->all();
            $this->business = auth()->user()->business;

            if (isset($data['reference'])) {
                $transaction = Transaction::where('external_reference', $data['reference'])->first();
                if ( !is_null($transaction) && $transaction->business_id == $this->business->id) {
                    return response()->json(['success' => false, 'message' => 'Duplicate reference']);
                }
            }

            $required = [
                'type', 'amount', 'phone', 'account_number'
            ];

            foreach ($required as $req) {
                if (!isset($data[$req]))
                    return response()->json([
                        'success' => false,
                        'message' => "Request is missing key: $req"
                    ]);
            }

            $type = 'ELECTRICITY';

            // validate product.
            $product = $this->business->getProduct($type);

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $reference = General::generateReference();

            $amount =  (double) $data['amount'];

            if ( $amount > $this->business->wallet->balance ) {

                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }

            if ( $amount <= 0 ) {

                return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
            }

            if ($amount < 100) {
                return response()->json(['success' => false, 'message' => 'Amount entered should be greater than 100']);
            }

            $info = $type." purchase of N$amount to ({$data['type']}) from your Wallet to {$data['account_number']}";

            // save transaction
            $transaction = $this->business->transactions()->create([
                'amount' => $amount,
                'charge' => 0.0,
                'net_amount' => $amount,
                'status' => 'PENDING',
                'type' => 'ELECTRICITY',
                'info' => $info,
                'channel' => request()->header('channel') ?? 'OTHERS',
                'reference' => $reference,
                'external_reference'   =>  $data['reference'] ?? null
            ]);


            if($transaction){
                $debit = \App\Classes\Wallet::debit($this->business, $amount, $info);
            }


            if ( $debit['success'] ) {
                $debited = true;

                // Update transaction
                $transaction->wallet_debited = 1;
                $transaction->save();


                $purchase = Capricorn::electricityPurchase($data['type'], $amount, $data['phone'], $data['account_number'], $reference);

                if ( $purchase['success'] && $purchase['status'] == 'success' ) {

                    $token = $purchase['data']->data->tokenCode ?? null;
                    $unit = $purchase['data']->data->amountOfPower ?? null;
                    $desc = $transaction->info;

                    $params = [
                        'token' => $purchase['data']->data->tokenCode ?? null,
                        'unit' => $purchase['data']->data->amountOfPower ?? null,
                        'amount' => $data['amount'],
                        'transId' => $purchase['data']->data->transactionReference
                    ];


                    // Update transaction
                    $transaction->info = $desc." Token:{$token}, Unit: {$unit}";
                    $transaction->status = 'SUCCESSFUL';
                    $transaction->save();

                    // Initialize disco name
                    $disco = '';
                    if ($data['type'] == 'ikeja_electric_prepaid' || $data['type'] == 'ikeja_electric_postpaid')
                        $disco = 'IKEDC';
                    if ($data['type'] == 'ibadan_electric_prepaid' || $data['type'] == 'ibadan_electric_postpaid')
                        $disco = 'IBEDC';
                    if ($data['type'] == 'eko_electric_prepaid' || $data['type'] == 'eko_electric_postpaid')
                        $disco = 'EKEDC';
                    if ($data['type'] == 'kedco_electric_prepaid' || $data['type'] == 'kedco_electric_postpaid')
                        $disco = 'KEDC';
                    if ($data['type'] == 'abuja_electric_prepaid' || $data['type'] == 'abuja_electric_postpaid')
                        $disco = 'AEDC';
                    if ($data['type'] == 'jos_electric_prepaid' || $data['type'] == 'jos_electric_postpaid')
                        $disco = 'JEDC';
                    if ($data['type'] == 'kaduna_electric_prepaid' || $data['type'] == 'kaduna_electric_postpaid')
                        $disco = 'KAEDC';
                    if ($data['type'] == 'enugu_electric_prepaid' || $data['type'] == 'enugu_electric_postpaid')
                        $disco = 'EEDC';
                    if ($data['type'] == 'portharcourt_electric_prepaid' || $data['type'] == 'portharcourt_electric_postpaid')
                        $disco = 'PHEDC';

                    // Commission Settlement
                    $current_product = $this->business->getProduct($product['name']);
                    $full_name = $this->getFullName($product, 'merchant_commission', $disco);

                    $product_merchant_commission = $current_product['merchant_commission'][$full_name];
                    $product_vas_commission = $current_product['vas_commission'][$full_name];


                    if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                        $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                        $info = "Commission of N $merchant_commission for {$type} purchase of N$amount from your Wallet to {$data['account_number']}";
                        $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                        \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                        $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                        $info = "Commission of N $vas_commission for $type purchase of N$amount from {$this->business->name} to {$data['account_number']}";

                        // Record VAS commissions
                        $this->business->commissions()->create([
                            'amount' => $vas_commission,
                            'product' => $current_product['name'],
                            'info' => $info
                        ]);

                    }
                    else {
                        $info = "Commission of N $product_merchant_commission for {$type} purchase of N$amount from your Wallet to {$data['account_number']}";
                        \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                        $info = "Commission of N $product_vas_commission for {$type} purchase of N$amount from {$this->business->name} to {$data['account_number']}";
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
                        'message' => $type . ' purchase successful',
                        'data' => $params,
                        'reference' => $data['reference'] ?? null
                    ]);

                }
                elseif ( $purchase['success'] && $purchase['status'] == 'pending' ){
                    $params = [
                        'token' => $purchase['data']->data->tokenCode ?? null,
                        'unit' => $purchase['data']->data->amountOfPower ?? null,
                        'amount' => $data['amount'],
                        'transId' => $purchase['data']->data->transactionReference
                    ];


                    // Initialize disco name
                    $disco = '';
                    if ($data['type'] == 'ikeja_electric_prepaid' || $data['type'] == 'ikeja_electric_postpaid')
                        $disco = 'IKEDC';
                    if ($data['type'] == 'ibadan_electric_prepaid' || $data['type'] == 'ibadan_electric_postpaid')
                        $disco = 'IBEDC';
                    if ($data['type'] == 'eko_electric_prepaid' || $data['type'] == 'eko_electric_postpaid')
                        $disco = 'EKEDC';
                    if ($data['type'] == 'kedco_electric_prepaid' || $data['type'] == 'kedco_electric_postpaid')
                        $disco = 'KEDC';
                    if ($data['type'] == 'abuja_electric_prepaid' || $data['type'] == 'abuja_electric_postpaid')
                        $disco = 'AEDC';
                    if ($data['type'] == 'jos_electric_prepaid' || $data['type'] == 'jos_electric_postpaid')
                        $disco = 'JEDC';
                    if ($data['type'] == 'kaduna_electric_prepaid' || $data['type'] == 'kaduna_electric_postpaid')
                        $disco = 'KAEDC';
                    if ($data['type'] == 'enugu_electric_prepaid' || $data['type'] == 'enugu_electric_postpaid')
                        $disco = 'EEDC';
                    if ($data['type'] == 'portharcourt_electric_prepaid' || $data['type'] == 'portharcourt_electric_postpaid')
                        $disco = 'PHEDC';

                    // Commission Settlement
                    $current_product = $this->business->getProduct($product['name']);
                    $full_name = $this->getFullName($product, 'merchant_commission', $disco);

                    $product_merchant_commission = $current_product['merchant_commission'][$full_name];
                    $product_vas_commission = $current_product['vas_commission'][$full_name];


                    if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                        $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                        $info = "Commission of N $merchant_commission for {$type} purchase of N$amount from your Wallet to {$data['account_number']}";
                        $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                        \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                        $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                        $info = "Commission of N $vas_commission for $type purchase of N$amount from {$this->business->name} to {$data['account_number']}";

                        // Record VAS commissions
                        $this->business->commissions()->create([
                            'amount' => $vas_commission,
                            'product' => $current_product['name'],
                            'info' => $info
                        ]);

                    }
                    else {
                        $info = "Commission of N $product_merchant_commission for {$type} purchase of N$amount from your Wallet to {$data['account_number']}";
                        \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                        $info = "Commission of N $product_vas_commission for {$type} purchase of N$amount from {$this->business->name} to {$data['account_number']}";
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
                        'message' => $type . ' purchase successful',
                        'data' => $params,
                        'reference' => $data['reference'] ?? null
                    ]);
                }
                else {

                    $transaction->status = 'FAILED';
                    $transaction->save();

                    if ( $debited ) {
                        Wallet::credit($this->business, $amount, 'Electricity purchase reversal');
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
                }

                return response()->json(['success' => false, 'message' => $purchase['message']]);
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
        }
        catch (\Exception $exception ) {
//             return response()->json(['success' => false, 'message' => $exception->getMessage() . " line: " . $exception->getLine() ]);
            return response()->json(['success' => false, 'message' => 'Error with '. request('type').' purchase']);
        }
    }
}
