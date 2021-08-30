<?php

namespace App\Http\Controllers\Api;


use App\Classes\Blusalt;
use App\Classes\General;
use App\Classes\SagePayWallet;
use App\Classes\UBA;
use App\Classes\Wallet;
use App\Http\Controllers\Controller;
use App\Model\SagePayTransaction;
use Illuminate\Http\JsonResponse;

class SagePay extends Controller
{

    private $business;

    public function createSession () {
        $data = request()->all();
        $required = [
            'email',
            'amount',
            'reference',
            'phone'
        ];

        foreach ($required as $req) {
            if (!isset($data[$req]))
                return response()->json([
                    'success' => false,
                    'message' => "Request is missing key: $req"
                ]);
        }

        $business = auth()->user()->business;

        // Check if external reference exists
        $transaction = SagePayTransaction::where('external_reference', $data['reference'])->first();
        if ( !is_null($transaction) && $transaction->business_id == $business->id) {
            return response()->json(['success' => false, 'message' => 'Duplicate reference']);
        }

        if (!isset($data['callback_url']) && (is_null($business->sage_pay_settings) || is_null($business->sage_pay_settings->callback_url))) {
            return response()->json([
                'success' => false,
                'message' => 'You need to have a callback URL in your payload or in your business gateway settings to proceed'
            ]);
        }

        // Make API call to create session
        $response = UBA::createSession();

        if (!$response['success']) {
            return response()->json($response);
        }

        $product = $business->getProduct('PAYMENT GATEWAY');
        $charge = SagePayWallet::gateWayCharge($product['charge'], (float) $data['amount'], $product['cap']);
        $net_amount = (float)$data['amount'] - $charge;
//        dd($charge, $net_amount);

        $access_code = General::generateAccessCode();
        $reference = General::generateSagePayReference();
        $ip_address = request()->ip();
        $info = "Payment of N{$data['amount']} to {$business->name} from email {$data['email']} with a charge of N$charge";

        $transaction = $business->sage_pay_transactions()->create([
            'access_code' => $access_code,
            'session_id' => $response['data']['session']->id,
            'version' => $response['data']['version'],
            'ip_address' => $ip_address,
            'customer_email' => $data['email'],
            'customer_phone' => $data['phone'],
            'amount' => $data['amount'],
            'net_amount' => $net_amount,
            'charge' => $charge,
            'status' => SagePayTransaction::$PENDING,
            'reference' => $reference,
            'external_reference' => $data['reference'],
            'info' => $info,
            'callback_url' => $data['callback_url'] ?? null
        ]);

        // Update current session
//        $update = UBA::updateSession($transaction->session_id, $data['amount']);
//
//        if (!$update['success']) {
//            $transaction->status = SagePayTransaction::$FAILED;
//            $transaction->save();
//
//            return response()->json([
//                "success" => false,
//                "message" => "Error updating payment",
//                "error" => $update["error"]
//            ]);
//        }

        $access_code = $transaction->access_code;
        $payment_url = env('PAYMENT_URL').'/'.$access_code;

        return response()->json([
            "success" => true,
            "message" => "Payment URL created successfully",
            "data" => [
                "payment_url" => $payment_url,
                "access_code" => $access_code,
                "reference" => $data['reference']
            ]
        ]);
    }

}
