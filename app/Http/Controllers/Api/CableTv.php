<?php

namespace App\Http\Controllers\Api;


use App\Classes\Capricorn;
use App\Classes\General;
use App\Classes\Shago;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Model\Biller;
use App\Model\BillerItem;
use App\Model\CableTvBouquet;
use App\Model\Product;
use App\Model\Provider;
use App\Model\Transaction;
use App\ReQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class CableTv extends Controller
{

    private $business;

    public function fetchBillers()
    {
        try {
            // get request parameters
            $query = request()->query();

            // if biller ID is set, fetch biller items instead
            if (isset($query['biller_id']) && !is_null($query['biller_id'])) {
                $plans = BillerItem::with('biller')->where('biller_id', $query['biller_id'])->get();

                return response()->json(['success' => true, 'plans' => $plans]);
            }

            // else fetch billers and proceed
            $billers = Biller::with('items')
                ->where('biller_type', 'CABLE-TV')->get();

            foreach ($billers as $biller) {
                if (isset($biller->logo_url)) {
                    $biller->image = env('APP_URL') . "/" . $biller->logo_url;
                }
            }

            return response()->json(['success' => true, 'billers' => $billers]);
        } catch (\Exception $exception) {

            return response()->json(['success' => false, 'message' => 'Error fetching cable tv data', 'error' => $exception->getMessage()]);
        }
    }


    /**
     * Fetch Capricorn Default Providers
     * @return JsonResponse
     */
    public function fetchProvidersWithCapricorn()
    {
        try {

            $serviceTypes = [
                [
                    'id' => 1,
                    'type' => 'gotv',
                    'name' => 'GOTV',
                    'narration' => 'Gotv Cable'
                ],
                [
                    'id' => 2,
                    'type' => 'dstv',
                    'name' => 'DSTV',
                    'narration' => 'Dstv Cable'
                ],
                [
                    'id' => 3,
                    'type' => 'startimes',
                    'name' => 'STARTIMES',
                    'narration' => 'Startimes Cable'
                ]
            ];

            $billers = [];

            foreach ($serviceTypes as $type) {

                // Formulate image since providers does not have image
                $path = "assets/images/billers/capricorn/" . $type['type'] . ".webp";

                if (is_file(public_path() . "/" . $path)) {
                    $image = env('APP_URL') . "/" . $path;
                } else {
                    $image = env('APP_URL') . "/assets/images/billers/capricorn/elect.webp";
                }

                $billers[] = (object)[
                    'type' => $type['type'],
                    'id' => $type['id'],
                    'name' => $type['name'],
                    'narration' => $type['narration'],
                    'image' => $image,
                ];
            }

            return response()->json(['success' => true, 'billers' => $billers]);

        } catch (\Exception $exception) {

            return response()->json(['success' => false, 'message' => 'Error fetching cable providers.']);
        }
    }


    public function fetchBillersWithCapricorn()
    {
        try {
            $data = request()->all();
            $this->business = auth()->user()->business;

            $required = [];

            $service_types = ['dstv', 'gotv', 'startimes'];

            foreach ($required as $req) {
                if (!isset($data[$req]))
                    return response()->json([
                        'success' => false,
                        'message' => "Request is missing key: $req"
                    ]);
            }
            $index = array_search($data['type'], $service_types);
            if ($index === false)
                return response()->json([
                    'success' => false,
                    'message' => "Service type {$data['type']} is not valid"
                ]);

            $type = 'CABLE TV';

            // validate product.
            $product = $this->business->getProduct($type);

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ($product['status'] != true) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $plans = CableTvBouquet::where('type', $data['type'])->select([
                'id',
                'type',
                'code',
                'name',
                'description',
                'price'
            ])->get();

            if (count($plans) > 0) {

                //adjust to conform with end users
                foreach ($plans as $plan) {
                    $plan->amount = $plan->price;
                }

                return response()->json([
                    'success' => true,
                    'plans' => $plans
                ]);
            } else {
                $resp = Capricorn::cableTvLookUp($data['type']);
                if (!$resp['success'])
                    return response()->json(['success' => false, 'message' => 'Failed to fetch cable tv data']);

                $billers = $resp['data'];

                foreach ($billers->data as $biller) {
                    $biller->price = $biller->availablePricingOptions[0]->price . '';
                    unset($biller->availablePricingOptions);

                    CableTvBouquet::create([
                        'type' => $data['type'],
                        'code' => $biller->code,
                        'name' => $biller->name,
                        'description' => $biller->description,
                        'price' => $biller->price
                    ]);
                }

                $plans = CableTvBouquet::where('type', $data['type'])->select([
                    'id',
                    'type',
                    'code',
                    'name',
                    'description',
                    'price'
                ])->get();

                //adjust to conform with end users
                foreach ($plans as $plan) {
                    $plan->amount = $plan->price;
                }

                return response()->json(['success' => true, 'plans' => $plans]);
            }
        } catch (\Exception $exception) {
//            return response()->json(['success' => false, 'message' => $exception->getMessage() ]);
            return response()->json(['success' => false, 'message' => 'Error fetching cable tv data', 'error'=>$exception->getMessage()]);
        }
    }

    /**
     * Cable TV subscription
     *
     * @return JsonResponse
     */
    public function purchase()
    {
        $debited = false;

        try {

            $user = request()->user();
            $data = request()->all();
            $business = $user->business;

            $type = 'CABLE TV';

            // validate product.
            $product = $business->getProduct($type);

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ($product['status'] != true) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            //$item_type =  $data['type'];
            $biller_id = $data['biller_id'];
            $item_id = $data['item_id'];

            $reference = General::generateReference();

            if (isset($data['reference'])) {
                $transaction = Transaction::where('external_reference', $data['reference'])->first();
                if (!is_null($transaction) && $transaction->business_id == $business->id) {
                    return response()->json(['success' => false, 'message' => 'Duplicate reference']);
                }
            }

            $biller = Biller::find($biller_id);

            //$item = $biller->biller_name ==  $item_type ? $biller->items()->where('title', $data['name'])->first(): null;
            $item = $biller->items()->find($item_id);

            if (is_null($item)) {
                return response()->json(['success' => false, 'message' => 'Bouquet selected is not valid. Please try again later']);
            }

            $provider = Provider::cableTv_current()->name;

            if ($provider !== 'SHAGO') {
                $provider = 'SHAGO'; //set shago as utility payment channel by default
            }

            if ($biller->biller_name == 'DSTV' && $provider == 'SHAGO') {

                $amount = (double)$item->amount;
                $charge = (double)$biller->charge;

            } elseif ($biller->biller_name == 'GOTV' && $provider == 'SHAGO') {

                $amount = (double)$item->amount;
                $charge = $biller->charge;

            } else {

                $amount = $provider == 'SHAGO' ? $item->amount : (double)$item->amount / 100;
                $charge = $provider == 'SHAGO' ? $biller->charge : $biller->charge / 100;
            }

            $netAmount = $amount + $charge;

            if ($netAmount <= 0) {
                return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
            }


            if ($netAmount > $business->wallet->balance) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }


            $info = "$biller->biller_name subscription of N$netAmount ({$item->name}) from your Wallet to smartcard number " . $data['smartCardNo'];

            // save transaction
            $transaction = $business->transactions()->create([
                'amount' => $netAmount,
                'charge' => 0.0,
                'net_amount' => $amount,
                'status' => 'PENDING',
                'type' => 'CABLE-TV',
                'info' => $info,
                'channel' => request()->header('channel') ?? 'OTHERS',
                'reference' => $reference,
                'external_reference' => $data['reference'] ?? null
            ]);

            // debit wallet
            if ($transaction) {
                $debit = \App\Classes\Wallet::debit($business, $amount, $info);
            }


            if ($debit['success']) {
                $debited = true;

                // Update transaction
                $transaction->update([
                    'wallet_debited' => 1,
                ]);

                if ($provider == 'SHAGO') {

                    $purchase = Shago::cableTvPurchase($data['smartCardNo'], $biller->biller_name, $item, $reference);
                    if ($purchase['success'] && $purchase['status'] == 'success') {

                        // Update transaction
                        $transaction->status = 'SUCCESSFUL';
                        $transaction->save();

                        // Commission Settlement
                        $product_merchant_commission = $product['merchant_commission'][$biller->biller_name];
                        $product_vas_commission = $product['vas_commission'][$biller->biller_name];


                        if ($product['charge_type'] == Product::$PERCENTAGE) {
                            $merchant_commission = \App\Classes\Wallet::calculateCommission($amount, $product_merchant_commission);
                            $info = "Commission of N $merchant_commission for subscription of N$amount from your Wallet to smartcard number {$data['smartCardNo']}";
                            $business->product_name = str_replace(' ', '-', $product['name']);
                            \App\Classes\Wallet::credit($business, $merchant_commission, $info, true);

                            $vas_commission = \App\Classes\Wallet::calculateCommission($amount, $product_vas_commission);
                            $info = "Commission of N $vas_commission for for subscription of N$amount from {$business->name} to smartcard number {$data['smartCardNo']}";
                            $business->commissions()->create([
                                'amount' => $vas_commission,
                                'product' => 'CABLE-TV',
                                'info' => $info
                            ]);

                        } else {
                            $info = "Commission of N $product_merchant_commission for subscription of N$amount from your Wallet to smartcard number {$data['smartCardNo']}";
                            $business->product_name = str_replace(' ', '-', $product['name']);
                            \App\Classes\Wallet::credit($business, $product_merchant_commission, $info, true);

                            $info = "Commission of N $product_vas_commission for for subscription of N$amount from {$business->name} to smartcard number {$data['smartCardNo']}";
                            $business->commissions()->create([
                                'amount' => $product_vas_commission,
                                'product' => 'CABLE-TV',
                                'info' => $info
                            ]);
                        }
                        // End Commission Settlement

                        return response()->json([
                            'success' => true,
                            'status' => 'success',
                            'message' => $item->name . ' purchase successful',
                            'reference' => $data['reference'] ?? null
                        ]);
                    } elseif ($purchase['success'] && $purchase['status'] == 'pending') {
                        // Commission Settlement
                        $product_merchant_commission = $product['merchant_commission'][$biller->biller_name];
                        $product_vas_commission = $product['vas_commission'][$biller->biller_name];


                        if ($product['charge_type'] == Product::$PERCENTAGE) {
                            $merchant_commission = \App\Classes\Wallet::calculateCommission($amount, $product_merchant_commission);
                            $info = "Commission of N $merchant_commission for subscription of N$amount from your Wallet to smartcard number {$data['smartCardNo']}";
                            $business->product_name = str_replace(' ', '-', $product['name']);
                            \App\Classes\Wallet::credit($business, $merchant_commission, $info, true);

                            $vas_commission = \App\Classes\Wallet::calculateCommission($amount, $product_vas_commission);
                            $info = "Commission of N $vas_commission for for subscription of N$amount from {$business->name} to smartcard number {$data['smartCardNo']}";
                            $business->commissions()->create([
                                'amount' => $vas_commission,
                                'product' => 'CABLE-TV',
                                'info' => $info
                            ]);

                        } else {
                            $info = "Commission of N $product_merchant_commission for subscription of N$amount from your Wallet to smartcard number {$data['smartCardNo']}";
                            $business->product_name = str_replace(' ', '-', $product['name']);
                            \App\Classes\Wallet::credit($business, $product_merchant_commission, $info, true);

                            $info = "Commission of N $product_vas_commission for for subscription of N$amount from {$business->name} to smartcard number {$data['smartCardNo']}";
                            $business->commissions()->create([
                                'amount' => $product_vas_commission,
                                'product' => 'CABLE-TV',
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
                            'message' => $item->name . ' purchase successful',
                            'reference' => $data['reference'] ?? null
                        ]);
                    } elseif ($purchase['status'] == 'failed') {
                        // Update transaction
                        $transaction->status = 'FAILED';
                        $transaction->save();

                        // refund wallet if debited
                        if ($debited) {
                            $info = 'Refund of ' . $amount . ' for ' . $type . ' subscription from your Wallet to ' . request('number');
                            \App\Classes\Wallet::credit($business, $amount, $info);
                        }
                        $details = [
                            'subject' => 'Failed Transaction Notification 🔊',
                            'greeting' => 'Hello 👋🏾 Support Admin!',
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
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
        } catch (\Exception $exception) {
//              return response()->json(['success' => false, 'message' => $exception->getMessage()]);
//            Log::info($exception->getMessage());
            return response()->json(['success' => false, 'message' => 'Error with Cable-TV subscription']);
        }
    }

    public function purchaseWithCapricorn()
    {
        $debited = false;

        try {

            $data = request()->all();
            $this->business = auth()->user()->business;

            if (isset($data['reference'])) {
                $transaction = Transaction::where('external_reference', $data['reference'])->first();
                if (!is_null($transaction) && $transaction->business_id == $this->business->id) {
                    return response()->json(['success' => false, 'message' => 'Duplicate reference']);
                }
            }

            $required = [
                'code', 'smartCardNo', 'type'
            ];

            foreach ($required as $req) {
                if (!isset($data[$req]))
                    return response()->json([
                        'success' => false,
                        'message' => "Request is missing key: $req"
                    ]);
            }

            $type = 'CABLE TV';

            // validate product.
            $product = $this->business->getProduct($type);

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Invalid Product']);
            }

            if ($product['status'] != true) {
                return response()->json(['success' => false, 'message' => $product['name'] . ' service is not active for this business']);
            }

            $reference = General::generateReference();
            $amount = (double)CableTvBouquet::where(['type' => $data['type'], 'code' => $data['code']])->first()->price;
            $service_type = $data['type'];

            $bouquet = [
                'code' => $data['code'],
                'price' => $amount
            ];


//            if ( $amount <= 0 ) {
//                return response()->json(['success' => false, 'message' => 'Invalid Amount entered!']);
//            }


            if ($amount > $this->business->wallet->balance) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }


            $info = "$service_type subscription of N$amount  from your Wallet to smartcard number " . $data['smartCardNo'];

            // save transaction
            $transaction = $this->business->transactions()->create([
                'amount' => $amount,
                'charge' => 0.0,
                'net_amount' => $amount,
                'status' => 'PENDING',
                'type' => 'CABLE-TV',
                'info' => $info,
                'channel' => request()->header('channel') ?? 'OTHERS',
                'reference' => $reference,
                'external_reference' => $data['reference'] ?? null
            ]);

            // debit wallet
            $debit = [];
            if ($transaction) {
                $debit = \App\Classes\Wallet::debit($this->business, $amount, $info);
            }


            if ($debit['success']) {
                $debited = true;

                // Update transaction
                $transaction->update([
                    'wallet_debited' => 1,
                ]);

                $purchase = Capricorn::cableTvPurchase($service_type, $bouquet, $data['smartCardNo'], $reference);
                if ($purchase['success'] && $purchase['status'] == 'success') {

                    // Update transaction
                    $transaction->status = 'SUCCESSFUL';
                    $transaction->save();

                    // Commission Settlement
                    $service = '';
                    if ($service_type == 'dstv')
                        $service = 'DSTV';
                    if ($service_type == 'gotv')
                        $service = 'GOTV';
                    if ($service_type == 'startimes')
                        $service = 'Startimes';

                    $this->settleCommission($data, $amount, $service);

                    // End Commission Settlement

                    return response()->json([
                        'success' => true,
                        'status' => 'success',
                        'message' => $service . ' purchase successful',
                        'reference' => $data['reference'] ?? null
                    ]);
                } elseif ($purchase['success'] && $purchase['status'] == 'pending') {

                    // Commission Settlement
                    $service = '';
                    if ($service_type == 'dstv')
                        $service = 'DSTV';
                    if ($service_type == 'gotv')
                        $service = 'GOTV';
                    if ($service_type == 'startimes')
                        $service = 'Startimes';

                    $this->settleCommission($data, $amount, $service);

                    // End Commission Settlement

                    ReQuery::create([
                        'transaction_id' => $transaction->id,
                        'provider' => 'Capricorn',
                        'status' => 'pending'
                    ]);

                    return response()->json([
                        'success' => true,
                        'status' => 'pending',
                        'message' => $service . ' purchase successful',
                        'reference' => $data['reference'] ?? null
                    ]);
                } elseif ($purchase['success'] === false) {
                    // Update transaction
                    $transaction->status = 'FAILED';
                    $transaction->save();


                    if ($debited) {
                        // refund wallet if debited
                        $info = 'Refund of ' . $amount . ' for ' . $type . ' subscription from your Wallet to ' . request('number');
                        \App\Classes\Wallet::credit($this->business, $amount, $info);
                    }
                    $details = [
                        'subject' => 'Failed Transaction Notification 🔊',
                        'greeting' => 'Hello 👋🏾 Support Admin!',
                        'body' => "A failed transaction just occurred on - {$transaction->business->name} with the below details; {$transaction->info}",
                        'moreBody' => "Transaction reference: {$transaction->reference}",
                        'thanks' => 'Sagecloud Automated Notification',
                        'actionText' => 'See Now!',
                        'actionURL' => url('/login')
                    ];
                    NotificationHelper::notifyAdmin($details);
                }
                return response()->json([
                    'success' => false,
                    'message' => $purchase['message']
                ]);
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);
        } catch (\Exception $exception) {
//              return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            Log::info($exception->getMessage());
            return response()->json(['success' => false, 'message' => 'Error with Cable-TV subscription']);
        }
    }


    public function validateCustomer()
    {
        try {


            $data = request()->all();

            $biller = Biller::find(request('biller_id'));

            $smartCardNumber = $data['smartCardNo'];

//            if ( env('PROVIDER') == 'SHAGO' ) {

            $customer = Shago::cableTvLookup($smartCardNumber, $biller->biller_name);

            if ($customer['success']) {
                $data = [
                    'success' => $customer['success'],
                    'fullName' => $customer['data']->customerName
                ];

                $customer = $data;
            }
//            }


            if ($customer['success']) {

                return response()->json(['success' => true, 'customer' => $customer]);
            }

            return response()->json(['success' => false, 'message' => $customer['message']]);
        } catch (\Exception $exception) {

            return response()->json(['success' => false, 'message' => 'Error exception with customer validation']);
        }
    }


    public function settleCommission($data, $amount, $service, $product = 'CABLE TV')
    {
        // Get current product
        $current_product = $this->business->getProduct('CABLE TV');

        // Get commissions for vas and merchant
        $product_merchant_commission = $current_product['merchant_commission'][$service];
        $product_vas_commission = $current_product['vas_commission'][$service];


        // Check charge type
        if ($current_product['charge_type'] == Product::$PERCENTAGE) {
            // Calculate commission for merchant
            $merchant_commission = \App\Classes\Wallet::calculateCommission($amount, $product_merchant_commission);

            // Prepare Information for commission transaction
            $info = "Commission of N $merchant_commission for subscription of N$amount from your Wallet to smartcard number {$data['smartCardNo']}";

            // Append product name to the current business to be used in the wallet credit method
            $this->business->product_name = str_replace(' ', '-', $current_product['name']);

            // Credit commission wallet of business
            \App\Classes\Wallet::credit($this->business, $merchant_commission, $info, true);

            // Calculate commission for vas
            $vas_commission = \App\Classes\Wallet::calculateCommission($amount, $product_vas_commission);

            // Prepare Information for commission transaction
            $info = "Commission of N $vas_commission for for subscription of N$amount from {$this->business->name} to smartcard number {$data['smartCardNo']}";

            // Record VAS commissions
            $this->business->commissions()->create([
                'amount' => $vas_commission,
                'product' => 'CABLE-TV',
                'info' => $info
            ]);

        } else {
            // Prepare Information for commission transaction
            $info = "Commission of N $product_merchant_commission for subscription of N$amount from your Wallet to smartcard number {$data['smartCardNo']}";

            // Append product name to the current business to be used in the wallet credit method
            $this->business->product_name = str_replace(' ', '-', $current_product['name']);

            // Credit commission wallet of business
            \App\Classes\Wallet::credit($this->business, $product_merchant_commission, $info, true);

            // Prepare Information for commission transaction
            $info = "Commission of N $product_vas_commission for for subscription of N$amount from {$this->business->name} to smartcard number {$data['smartCardNo']}";

            // Record VAS commissions
            $this->business->commissions()->create([
                'amount' => $product_vas_commission,
                'product' => 'CABLE-TV',
                'info' => $info
            ]);
        }
    }
}
