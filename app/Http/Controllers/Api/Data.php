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
use App\Model\CapricornDataPlan;
use App\Model\DataPlan;
use App\Model\Product;
use App\Model\Provider;
use App\Model\ServiceCode;
use App\Model\SmileBundlePlan;
use App\Model\Transaction;
use App\ReQuery;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Data extends Controller
{


    private $business;

    public function dataLookUp( Request $request)
    {
        try {
            $data = $request->all();

            // This guy returns null most times, so it's bypassed.
            $service = ServiceCode::where('provider', 'SONITE')->where('type' , $data['provider'])->first();

            // Get plans
            $plans = DataPlan::where('type', $data['provider'])
                ->select([
                    'type',
                    'code',
                    'description',
                    'amount',
                    'price',
                    'value',
                    'duration'
                ])->get();


            // if no plans were found
            if (count($plans) < 1) {

                // fetch items
                $items = Sonite::dataLookup($data['provider']);

                // if it fails, send error response
                if (!$items['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to get data items at the moment. Please try again later.'
                    ]);
                }

                // Hence it's successful, update record
                foreach ($items['data']->data as $datum) {
                    DataPlan::create([
                        'type' => $data['provider'] == '9MOBILEDATA' ? '9MOBILEDATA' : $datum->type,
                        'code' => $datum->code,
                        'description' => $datum->description,
                        'amount' => $datum->amount,
                        'price' => is_null($datum->price) ? $datum->amount : $datum->price,
                        'value' => $datum->value,
                        'duration' => $datum->duration
                    ]);
                }


                // Get plans
                $plans = DataPlan::where('type', $data['provider'])
                    ->select([
                        'type',
                        'code',
                        'description',
                        'amount',
                        'price',
                        'value',
                        'duration'
                    ])->get();

            }

            //send success response
            return response()->json([
                'success' => true,
                'data' => $plans
            ]);

        }
        catch (\Exception $exception ) {
//            return response()->json(['success' => false, 'message' => $exception->getMessage() . " line: ". $exception->getLine()  ]);
            return response()->json(['success' => false, 'message' => 'Error getting data plans']);
        }
    }


    public function purchase()
    {

        $debited = false;

        try {

            $this->business = auth()->user()->business;

            // validate product.
            $product = $this->business->getProduct('DATA');

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

            $current_provider = Provider::data_current();

            if ( $current_provider->name == 'CAPRICORN') {
                return $this->purchaseWithCapricorn($reference, $data);
            }

            if ( $current_provider->name == 'SHAGO') {
                return $this->purchaseWithShago($reference, $data);
            }


            if ( $current_provider->name == 'SONITE') {
                return $this->purchaseWithSonite($reference, $data);
            }

            return response()->json(['success' => false, 'message' => 'Invalid provider']);

        }catch (\Exception $exception ) {

            Log::info("=============== DATA Subscription Response ==========");
            Log::info($exception->getMessage(). " line: " . $exception->getLine());
//            return response()->json(['success' => false, 'message' => $exception->getMessage() . " line: " . $exception->getLine() ]);
            return response()->json(['success' => false, 'message' => 'Error with purchase']);
        }
    }


    /**
     * Fetch Capricorn Default Providers
     * @return JsonResponse
     */
    public function fetchProviders()
    {
        try {

            $serviceTypes = [
                [
                    'id' => 1,
                    'type' => 'MTNDATA',
                    'name' => 'MTN',
                    'slug' => 'mtn',
                    'narration' => 'MTN Data'
                ],
                [
                    'id' => 2,
                    'type' => 'AIRTELDATA',
                    'name' => 'AIRTEL',
                    'slug' => 'airtel',
                    'narration' => 'AIRTEL Data'
                ],
                [
                    'id' => 3,
                    'type' => 'GLODATA',
                    'name' => 'GLO',
                    'slug' => 'glo',
                    'narration' => 'GLO Data'
                ],
                [
                    'id' => 4,
                    'type' => '9MOBILEDATA',
                    'name' => '9MOBILE',
                    'slug' => 'etisalat',
                    'narration' => '9MOBILE Data'
                ],
//                [
//                    'id' => 5,
//                    'type' => 'SMILE',
//                    'name' => 'SMILE Bundle',
//                    'slug' => 'smile',
//                    'narration' => 'Smile Bundle'
//                ],
//                [
//                    'id' => 6,
//                    'type' => 'SPECTRANET',
//                    'name' => 'SPECTRANET',
//                    'slug' => 'spectranet',
//                    'narration' => 'SPECTRANET Bundle'
//                ]
            ];

            $billers = [];

            foreach ($serviceTypes as $type) {

                // Formulate image since providers does not have image
                $path = "assets/images/billers/capricorn/" . $type['slug'] . ".webp";

                if (is_file(public_path() . "/". $path)) {
                    $image = env('APP_URL') . "/" . $path;
                } else {
                    $image = env('APP_URL') . "/assets/images/billers/capricorn/elect.webp";
                }

                $billers[] = (object) [
                    'type' => $type['type'],
                    'id' => $type['id'],
                    'name' => $type['name'],
                    'narration' => $type['narration'],
                    'image' => $image,
                ];
            }

            return response()->json(['success' => true, 'billers' => $billers ]);

        } catch ( \Exception $exception ) {

            //return error response
            return response()->json(['success' => false, 'message' => 'Error fetching cable providers.']);
        }
    }


    public function spectranetLookUp()
    {
        try {
            $this->business = auth()->user()->business;

            // validate product.
            $product = $this->business->getProduct('DATA');

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            // Check if product is active for this business
            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            // Fetch Bundles
            $items = Capricorn::spectranetPinBundles();

            if ($items['success']) {

                $packages = [];

                // Properly format this data to enable smooth consumption
                // This has same keys as VTU lookup
                foreach ($items['data']->data as $item) {

                    $packages[] = [
                        'type' => 'SPECTRANET',
                        'code' => $item->available,
                        'amount' => $item->amount,
                        'price' => $item->amount,
                        'description' => "Spectranet data - " . $item->description,
                        'duration' => ""
                    ];
                }

                return response()->json([
                    'success' => true,
                    'data' => $packages
                ]);
            }


            return response()->json([
                'success' => false,
                'message' => "Unable to fetch packages. Please try again later."
            ]);

        } catch (\Exception $exception ) {
//            return response()->json(['success' => false, 'message' => $exception->getMessage() . " line: " . $exception->getLine()  ]);
            return response()->json(['success' => false, 'message' => 'Error getting spectranet bundles']);
        }
    }


    public function spectranetPurchase()
    {

        $debited = false;

        try {

            $this->business = auth()->user()->business;

            // validate product.
            $product = $this->business->getProduct('DATA');

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $reference = General::generateReference();
            $data = request()->all();
            $required = [
                'amount'
            ];

            foreach ($required as $req) {
                if (!isset($data[$req]))
                    return response()->json([
                        'success' => false,
                        'message' => "Request is missing key: $req"
                    ]);
            }

            $user = request()->user();

            $amount = (double) $data['amount'] ;

            if ($amount > $user->business->wallet->balance) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }

            if ($amount <= 0) {
                return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
            }

            if ($amount < 50) {
                return response()->json(['success' => false, 'message' => 'Amount entered should be greater than 50']);
            }

            $info = "Spectranet purchase of N $amount from your Wallet";

            // save transaction
            $transaction = $user->business->transactions()->create([

                'business_id' => $user['business_id'],
                'amount'      => $amount,
                'charge'      => 0.0,
                'net_amount'  => $amount,
                'status'      => 'PENDING',
                'type'        => 'DATA',
                'info'        => $info,
                'channel'     => request()->header('channel') ?? 'OTHERS',
                'reference'   =>  $reference
            ]);

            // debit wallet
            $debit = [];

            if ($transaction) {
                $debit =  Wallet::debit($this->business, (float) ($amount - ($amount * 0.01)), $info);
            }

            if ($debit['success']) {
                $debited = true;
                $transaction->update([
                    'wallet_debited' => 1,
                ]);

                $purchase = Capricorn::spectranetPinPurchase($amount, $reference);

                if ($purchase['success'] && $product['status'] == 'success') {

                    // Update transaction
                    $transaction->status = 'SUCCESSFUL';

                    $transaction->save();

                    // Commission Settlement
                    self::settleCommissions($amount, 'Spectranet', 'Spectranet Pin');
                    // End Commission Settlement

                    return response()->json([
                        'success' => true,
                        'status' => 'success',
                        'message' => 'Spectranet pin purchase successful',
                        'pins' => $purchase['data']->data->pins
                    ]);
                }
                elseif ($purchase['success'] && $product['status'] == 'pending'){
                    // Commission Settlement
                    self::settleCommissions($amount, 'Spectranet', 'Spectranet Pin');
                    // End Commission Settlement

                    ReQuery::create([
                        'transaction_id' => $transaction->id,
                        'provider' => 'Capricorn',
                        'status' => 'pending'
                    ]);

                    return response()->json([
                        'success' => true,
                        'status' => 'pending',
                        'message' => 'Spectranet pin purchase successful',
                        'pins' => $purchase['data']->data->pins
                    ]);
                }
                else {
                    $transaction->status = 'FAILED';
                    $transaction->save();

                    // Reverse failed purchase
                    if ($debited) {
                        Wallet::credit($user->business, $amount, 'Spectranet pin purchase reversal');
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



        }catch (\Exception $exception ) {

            Log::info("=============== Spectranet pin purchase Response ==========");
            Log::info($exception->getMessage());
//            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error with purchase']);
        }
    }


    public function smileLookUp()
    {
        try {
            $this->business = auth()->user()->business;

            // validate product.
            $product = $this->business->getProduct('DATA');

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            // Check if product is active for this business
            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $plans = SmileBundlePlan::select([
                    'name',
                    'allowance',
                    'price',
                    'validity',
                    'datacode',
                ])->get();

            if (count($plans) < 1) {


                // fetch items
                $items = Capricorn::retrieveDataBundles('smile');

                // if it fails, send error response
                if (!$items['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to get smile data items at the moment. Please try again later.'
                    ]);
                }


                // Hence it's successful, update record
                foreach ($plans['data']->data as $datum) {
                    SmileBundlePlan::create([
                        'name' => $datum->name,
                        'allowance' => $datum->allowance,
                        'price' => $datum->price,
                        'validity' => $datum->validity,
                        'datacode' => $datum->datacode,
                    ]);
                }


                // Get plans again.
                $plans = SmileBundlePlan::select([
                    'name',
                    'allowance',
                    'price',
                    'validity',
                    'datacode',
                ])->get();

            }


            $packages = [];

            // Properly format this data to enable smooth consumption
            // This has same keys as VTU lookup
            foreach ($plans as $plan) {

                $packages[] = [
                    'type' => 'SMILE',
                    'code' => $plan->datacode,
                    'amount' => $plan->price,
                    'price' => $plan->price,
                    'description' => $plan->name,
                    'duration' => $plan->validity
                ];
            }

            //send success response
            return response()->json([
                'success' => true,
                'data' => $packages
            ]);

        }
        catch (\Exception $exception ) {
            return response()->json(['success' => false, 'message' => $exception->getMessage() ]);
//            return response()->json(['success' => false, 'message' => 'Error getting smile bundles']);
        }
    }

    public function validateSmileCustomer()
    {
        try {
            $data = request()->all();
            $this->business = auth()->user()->business;

//            Log::info('================= electric validate payload =======');
//            Log::info(json_encode($data));

            $required = [
                'phone'
            ];

            foreach ($required as $req) {
                if (!isset($data[$req]))
                    return response()->json([
                        'success' => false,
                        'message' => "Request is missing key: $req"
                    ]);
            }

            $type = 'DATA';

            // validate product.
            $product = $this->business->getProduct($type);

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $resp = Capricorn::validateService('smile', $data['phone']);

            if ( $resp['success'] ) {

                $details = $resp['data']->data->user;

                $customer = (object) [];
                // Add extra fields for actual purchase
                $customer->customerName = $details->name;
                $customer->customerAddress = $details->address ?? null;

                return response()->json(['success' => true, 'customer' => $customer ]);

            }

            return response()->json(['success' => false, 'message' => 'Sorry! we could not validate this customer. Please check number and try again']);
        }
        catch (\Exception $exception ) {

            return response()->json(['success' => false, 'message' => "Error validating customer's details.".$exception->getMessage() ]);
        }
    }

    public function smilePurchase()
    {

        $debited = false;

        try {

            $this->business = auth()->user()->business;

            // validate product.
            $product = $this->business->getProduct('DATA');

            if ( !$product ) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ( $product['status'] != true ) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $reference = General::generateReference();

            $data = request()->all();

            $required = [
               'dataCode', 'phone'
            ];

            foreach ($required as $req) {
                if (!isset($data[$req]))
                    return response()->json([
                        'success' => false,
                        'message' => "Request is missing key: $req"
                    ]);
            }
//            dd($data);

            $plan = SmileBundlePlan::where('datacode', $data['dataCode'])->first();

            if (is_null($plan)) {
                return response()->json(["success" => false, "message" => "Invalid code entered"]);
            }

            $amount = (double) $plan->price;

            $user = request()->user();

            if ($amount > $user->business->wallet->balance) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }

            if ($amount <= 0) {
                return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
            }

            if ($amount < 50) {
                return response()->json(['success' => false, 'message' => 'Amount entered should be greater than 50']);
            }

            $info = "Smile purchase of N $amount from your Wallet";

            // save transaction
            $transaction = $user->business->transactions()->create([

                'business_id' => $user['business_id'],
                'amount'      => $amount,
                'charge'      => 0.0,
                'net_amount'  => $amount,
                'status'      => 'PENDING',
                'type'        => 'DATA',
                'info'        => $info,
                'channel'     => request()->header('channel') ?? 'OTHERS',
                'reference'   =>  $reference
            ]);

            // debit wallet
            $debit = [];

            if ($transaction) {
                $debit =  Wallet::debit($this->business, $amount, $info);
            }

            if ($debit['success']) {
                $debited = true;
                $transaction->update([
                    'wallet_debited' => 1,
                ]);

                $purchase = Capricorn::dataPurchase($amount, $reference, $data['dataCode'], $data['phone'], 'smile');

                if ($purchase['success'] && $purchase['status'] == 'success') {

                    // Update transaction
                    $transaction->status = 'SUCCESSFUL';

                    $transaction->save();

                    // Commission Settlement

                    self::settleCommissions($amount, 'Smile', 'Smile Bundle');

                    // End Commission Settlement

                    return response()->json([
                        'success' => true,
                        'status' => 'success',
                        'message' => 'Smile bundle of '.$amount.' purchase successful for '. $data['phone'],
                    ]);
                }
                elseif ($purchase['success'] && $purchase['status'] == 'pending'){
                    // Commission Settlement

                    self::settleCommissions($amount, 'Smile', 'Smile Bundle');

                    // End Commission Settlement

                    ReQuery::create([
                        'transaction_id' => $transaction->id,
                        'provider' => 'Capricorn',
                        'status' => 'pending'
                    ]);

                    return response()->json([
                        'success' => true,
                        'status' => 'pending',
                        'message' => 'Smile bundle of '.$amount.' purchase successful for '. $data['phone'],
                    ]);
                }
                elseif (!$purchase['success'] && $purchase['status'] == 'failed') {
                    $transaction->status = 'FAILED';
                    $transaction->save();

                    // Reverse failed purchase
                    if ($debited) {
                        Wallet::credit($user->business, $amount, 'Smile pin purchase reversal');
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



        }catch (\Exception $exception ) {

            Log::info("=============== Smile pin purchase Response ==========");
            Log::info($exception->getMessage());
//            return response()->json(['success' => false, 'message' => $exception->getMessage() . "line: " . $exception->getLine() ]);
            return response()->json(['success' => false, 'message' => 'Error with purchase']);
        }
    }


    public function purchaseWithShago($reference, $data)
    {
        $user = request()->user();
        $phone = str_replace(' ', '', $data['phone']);
        $biller = Biller::where('biller_name', $data['network'] == '9MOBILE' ? 'ETISALAT' : $data['network'])->first();
//        $netAmount = $biller->charge != null ? (double) $data['amount'] + $biller->charge :  $data['amount'];
        $plan = DataPlan::where(['code' => $data['code'], 'type' => $data['type']])->first();
        $biller_items = $biller->items()->where([
            'amount' => $plan->amount,
//            'duration' => $plan->duration
        ])->get();
//        dd($biller_items);
        $biller_item = $biller_items[0];

        if (sizeof($biller_items) > 1) {
            foreach ($biller_items as $item) {
                if ($item->allowance == $plan->value)
                    $biller_item = $item;
            }
        }

        $amount =  (double) $biller_item->amount;
//        $item = $biller->items()->where('amount', (int) $data['amount'])->first();


        if ($amount > $user->business->wallet->balance) {
            return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
        }

        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
        }

        if ($amount < 50) {
            return response()->json(['success' => false, 'message' => 'Amount entered should be greater than 50']);
        }

        $info = "{$biller->biller_type} purchase of N $amount from your Wallet to $phone";

        // save transaction
        $transaction = $user->business->transactions()->create([

            'business_id' => $user['business_id'],
            'amount'      => $amount,
            'charge'      => 0.0,
            'net_amount'  => $amount,
            'status'      => 'PENDING',
            'type'        => 'DATA',
            'info'        => $info,
            'channel'     => request()->header('channel') ?? 'OTHERS',
            'reference'   =>  $reference ,
            'external_reference'   =>  $data['reference'] ?? null
        ]);

        // debit wallet
        $debit = [];
        if ($transaction) {
            $debit =  Wallet::debit($user->business, $amount, $info);
        }


        if ($debit['success']) {
            $debited = true;
            $transaction->update([
                'wallet_debited' => 1,
            ]);

            $service = 'BDA';

            // Smile purchase
            if ($biller->biller_name == 'SMILE') {
                $service = 'SMB';
            }

            //spectranet purchase
            if ($biller->biller_name == 'SPECTRANET') {
                $service = 'SPB';
            }
            $purchase = Shago::buyData($service, $phone, $amount, $biller->biller_name, $biller_item->allowance, $biller_item->code, $reference);
            // $purchase = Shago::buyAirtime($service, $phone, $amount, $biller->billername ,  $reference);

            if ($purchase['success'] && $purchase['status'] == 'success') {

                // Update transaction
                $transaction->status = 'SUCCESSFUL';

                $transaction->save();

                // Commission Settlement
                $current_product = $this->business->getProduct('DATA');

                $product_merchant_commission = $current_product['merchant_commission'][$data['network']];
                $product_vas_commission = $current_product['vas_commission'][$data['network']];


                if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                    $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                    $info = "Commission of N $merchant_commission from DATA purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                    $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                    $info = "Commission of N $vas_commission for DATA purchase of N $amount from {$this->business->name}";

                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);

                }
                else {
                    $info = "Commission of $product_merchant_commission from DATA purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                    $info = "Commission of N $product_vas_commission for DATA purchase of N $amount from {$this->business->name}";
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
                    'message' => $data['type'] . ' purchase successful',
                    'reference' => $data['reference'] ?? null
                ]);
            }
            elseif ($purchase['success'] && $purchase['status'] == 'pending'){
                // Commission Settlement
                $current_product = $this->business->getProduct('DATA');

                $product_merchant_commission = $current_product['merchant_commission'][$data['network']];
                $product_vas_commission = $current_product['vas_commission'][$data['network']];


                if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                    $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                    $info = "Commission of N $merchant_commission from DATA purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                    $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                    $info = "Commission of N $vas_commission for DATA purchase of N $amount from {$this->business->name}";

                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);

                }
                else {
                    $info = "Commission of $product_merchant_commission from DATA purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                    $info = "Commission of N $product_vas_commission for DATA purchase of N $amount from {$this->business->name}";
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
                    'message' => $data['type'] . ' purchase successful',
                    'reference' => $data['reference'] ?? null
                ]);
            }
            elseif ($purchase['status'] === 'failed') {
                $transaction->status = 'FAILED';
                $transaction->save();

                // Reverse failed purchase
                if ($debited) {
                    Wallet::credit($user->business, $amount, 'DATA purchase reversal');
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

            // dd($purchase['message']);
            return response()->json(['success' => false, 'message' => $purchase['message']]);
        }
        return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
    }


    public function purchaseWithSonite($reference, $data)
    {

        // remove unnecessary space from phone
        $phone = str_replace(' ', '', $data['phone']);
        $amount = DataPlan::where(['type' => $data['type'], 'code' => $data['code']])->first()->amount;

        // If biller ID is set, use it
        if (isset($data['biller_id'])) {
            $biller = Biller::find($data['biller_id']);
            $netAmount = $biller->charge != null ?  (double) $amount + $$biller->charge :  $amount;
            $type = $biller->biller_type;
            $code = $biller->biller_code;
        } else{

            // else use payload coming from request
            $netAmount = $amount;
            $type = $data['type'];
            $code = $data['code'];
        }

        // parse amount
        $amount =  (double) $amount ;

        // If amount is more than business wallet balance, return error
        if ($amount > $this->business->wallet->balance) {
            return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
        }

        // If amount is negative, return error response
        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
        }

        if ($amount < 50) {
            return response()->json(['success' => false, 'message' => 'Amount entered should be greater than 50']);
        }

        $info = "{$type} purchase of N $amount from your Wallet to $phone";


        // save transaction
        $transaction = $this->business->transactions()->create([
            'amount'      => $amount,
            'charge'      => 0.0,
            'net_amount'  => $netAmount,
            'status'      => 'PENDING',
            'type'        => 'DATA',
            'info'        => $info,
            'channel'     => request()->header('channel') ?? 'OTHERS',
            'reference'   =>  $reference,
            'external_reference'   =>  $data['reference'] ?? null
        ]);

        // debit wallet
        $debit = Wallet::debit($this->business, $amount, $info);

        if ( $debit['success'] ) {

            // $transaction->wallet_debited = $debited;
            //GEt More clarification on this method
            $debited = true;

            $transaction->update([
                'wallet_debited' => 1,
            ]);

            $purchase = Sonite::dataSubscription($data['type'], $code, $phone);
            // $purchase = Shago::buyAirtime($service, $phone, $amount, $biller->billername ,  $reference);

            if ( $purchase['success'] ) {

                // Update transaction
                $transaction->status = 'SUCCESSFUL';

                $transaction->save();

                // Commission Settlement
                $current_product = $this->business->getProduct('DATA');

                $product_merchant_commission = $current_product['merchant_commission'][$data['network']];
                $product_vas_commission = $current_product['vas_commission'][$data['network']];


                if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                    $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                    $info = "Commission of N $merchant_commission from DATA purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                    $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                    $info = "Commission of N $vas_commission for DATA purchase of N $amount from {$this->business->name}";

                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);

                }
                else {
                    $info = "Commission of $product_merchant_commission from DATA purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                    $info = "Commission of N $product_vas_commission for DATA purchase of N $amount from {$this->business->name}";
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
                    'message' => $data['type'] . ' data subscription successful',
                    'reference' => $data['reference'] ?? null
                ]);

            } else {

                $transaction->status = 'FAILED';
                $transaction->save();

                // Reverse failed purchase
                if ( $debited ) {
                    Wallet::credit($this->business, $amount, 'Data subscription purchase reversal of ' . $amount);
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

            // dd($purchase['message']);
            return response()->json(['success' => false, 'message' => $purchase['message']]);
        }

        return response()->json(['success' => false, 'message' => "Error debiting wallet"]);
    }



    public function purchaseWithCapricorn($reference, $data)
    {
        $user = request()->user();
        $phone = str_replace(' ', '', $data['phone']);
//        $netAmount = $biller->charge != null ? (double) $data['amount'] + $biller->charge :  $data['amount'];
        $plan = DataPlan::where(['code' => $data['code'], 'type' => $data['type']])->first();

        $service = strtolower($data['network']);

        $biller_item = CapricornDataPlan::where(['service_type' => $service, 'datacode'=> $plan->code])->first();

        if (is_null($biller_item)) {
            $biller_item = CapricornDataPlan::where(['service_type' => $service, 'price' => $plan->amount])->first();
        }


        $amount =  (double) $biller_item->price;
//        $item = $biller->items()->where('amount', (int) $data['amount'])->first();


        if ($amount > $user->business->wallet->balance) {
            return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
        }

        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
        }

        if ($amount < 50) {
            return response()->json(['success' => false, 'message' => 'Amount entered should be greater than 50']);
        }

        $info = "{$data['network']}DATA purchase of N $amount from your Wallet to $phone";

        // save transaction
        $transaction = $user->business->transactions()->create([

            'business_id' => $user['business_id'],
            'amount'      => $amount,
            'charge'      => 0.0,
            'net_amount'  => $amount,
            'status'      => 'PENDING',
            'type'        => 'DATA',
            'info'        => $info,
            'channel'     => request()->header('channel') ?? 'OTHERS',
            'reference'   =>  $reference ,
            'external_reference'   =>  $data['reference'] ?? null
        ]);

        // debit wallet
        $debit = [];
        if ($transaction) {
            $debit =  Wallet::debit($user->business, $amount, $info);
        }


        if ($debit['success']) {
            $debited = true;
            $transaction->update([
                'wallet_debited' => 1,
            ]);

            $purchase = Capricorn::dataPurchase($amount, $reference, $biller_item->datacode, $phone, $service);
            // $purchase = Shago::buyAirtime($service, $phone, $amount, $biller->billername ,  $reference);

            if ($purchase['success'] && $purchase['status'] == 'success') {

                // Update transaction
                $transaction->status = 'SUCCESSFUL';

                $transaction->save();

                // Commission Settlement
                $current_product = $this->business->getProduct('DATA');

                $product_merchant_commission = $current_product['merchant_commission'][$data['network']];
                $product_vas_commission = $current_product['vas_commission'][$data['network']];


                if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                    $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                    $info = "Commission of N $merchant_commission from DATA purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                    $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                    $info = "Commission of N $vas_commission for DATA purchase of N $amount from {$this->business->name}";

                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);

                }
                else {
                    $info = "Commission of $product_merchant_commission from DATA purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                    $info = "Commission of N $product_vas_commission for DATA purchase of N $amount from {$this->business->name}";
                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $product_vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);
                }
                // End Commission Settlement

                return response()->json(['success' => true, 'status' => 'success', 'message' => $data['type'] . ' purchase successful']);
            }
            elseif ($purchase['success'] && $purchase['status'] == 'pending'){
                // Commission Settlement
                $current_product = $this->business->getProduct('DATA');

                $product_merchant_commission = $current_product['merchant_commission'][$data['network']];
                $product_vas_commission = $current_product['vas_commission'][$data['network']];


                if ($current_product['charge_type'] == Product::$PERCENTAGE) {
                    $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );
                    $info = "Commission of N $merchant_commission from DATA purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

                    $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );
                    $info = "Commission of N $vas_commission for DATA purchase of N $amount from {$this->business->name}";

                    // Record VAS commissions
                    $this->business->commissions()->create([
                        'amount' => $vas_commission,
                        'product' => $current_product['name'],
                        'info' => $info
                    ]);

                }
                else {
                    $info = "Commission of $product_merchant_commission from DATA purchase of N $amount from your Wallet to $phone";
                    $this->business->product_name = str_replace(' ', '-', $current_product['name']);
                    \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

                    $info = "Commission of N $product_vas_commission for DATA purchase of N $amount from {$this->business->name}";
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

                return response()->json(['success' => true, 'status' => 'pending', 'message' => $data['type'] . ' purchase successful']);

            }
            elseif ($purchase['status'] === 'failed') {
                $transaction->status = 'FAILED';
                $transaction->save();


                // Reverse failed purchase
                if ($debited) {
                    Wallet::credit($user->business, $amount, 'DATA purchase reversal');
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

            // dd($purchase['message']);
            return response()->json([
                'success' => false,
                'message' => $purchase['message'],
                'reference' => $data['reference'] ?? null
            ]);
        }
        return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
    }


    public function settleCommissions ($amount, $service, $product='DATA') {
        // Get current product
        $current_product = $this->business->getProduct('DATA');

        // Get commissions for vas and merchant
        $product_merchant_commission = $current_product['merchant_commission'][$service];
        $product_vas_commission = $current_product['vas_commission'][$service];


        // Check charge type
        if ($current_product['charge_type'] == Product::$PERCENTAGE) {
            // Calculate commission for merchant
            $merchant_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_merchant_commission );

            // Prepare Information for commission transaction
            $info = "Commission of N$merchant_commission from $product purchase of N $amount from your Wallet";

            // Append product name to the current business to be used in the wallet credit method
            $this->business->product_name = str_replace(' ', '-', $current_product['name']);

            // Credit commission wallet of business
            \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

            // Calculate commission for vas
            $vas_commission = \App\Classes\Wallet::calculateCommission( $amount, $product_vas_commission );

            // Prepare Information for commission transaction
            $info = "Commission of N$vas_commission for $product purchase of N $amount from {$this->business->name}";

            // Record VAS commissions
            $this->business->commissions()->create([
                'amount' => $vas_commission,
                'product' => $current_product['name'],
                'info' => $info
            ]);

        }
        else {
            // Prepare Information for commission transaction
            $info = "Commission of $product_merchant_commission from $product purchase of N $amount from your Wallet";

            // Append product name to the current business to be used in the wallet credit method
            $this->business->product_name = str_replace(' ', '-', $current_product['name']);

            // Credit commission wallet of business
            \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

            // Prepare Information for commission transaction
            $info = "Commission of N $product_vas_commission for $product purchase of N $amount from {$this->business->name}";

            // Record VAS commissions
            $this->business->commissions()->create([
                'amount' => $product_vas_commission,
                'product' => $current_product['name'],
                'info' => $info
            ]);
        }
    }
}
