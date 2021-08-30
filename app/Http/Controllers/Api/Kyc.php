<?php

namespace App\Http\Controllers\Api;


use App\Classes\Blusalt;
use App\Classes\General;
use App\Classes\Wallet;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class Kyc extends Controller
{

    private $business;

    /**
     *
     * BVN Verification
     * @return JsonResponse
     */
    public function verifyBvn()
    {
        try {
            $this->business = auth()->user()->business;

            $reference = General::generateReference();
            $data = request()->all();

            $required = [
                'bvn', 'phone'
            ];
            foreach ($required as $r) {
                if (!isset($data[$r])) {
                    return response()->json(["success" => false, "message" => "Request is missing key: $r"]);
                }
            }

            $debited = false;

            $phone = str_replace(' ', '', $data['phone']);
            $bvn = str_replace(' ', '', $data['bvn']);

            $cost = (double) \App\Model\Kyc::getKycCost( \App\Model\Kyc::$bvn );

            if ( $cost > $this->business->wallet->balance ) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }

            $info = "KYC BVN verification of $bvn with charge of N $cost from your Wallet";

            // save transaction
            $transaction = $this->business->transactions()->create([
                'amount'      => $cost,
                'charge'      => 0.0,
                'net_amount'  => $cost,
                'status'      => 'PENDING',
                'type'        => 'OTHERS',
                'info'        => $info,
                'channel'     => request()->header('channel') ?? 'OTHERS',
                'reference'   =>  $reference ,
            ]);


            // debit wallet
            $debit =  Wallet::debit($this->business, $cost, $info);

            if ($debit['success']) {

                $debited = true;
                $transaction->update(['wallet_debited' => 1]);

                $response = Blusalt::verifyBankVerificationNumber($phone, $bvn);

                if ( $response['success'] ) {

                    // Update transaction
                    $transaction->status = 'SUCCESSFUL';
                    $transaction->save();

                    return response()->json([
                        'success' => true,
                        'message' => $response['message'],
                        'data' => $response['data']
                    ]);

                } else {
                    // Update transaction
                    $transaction->status = 'FAILED';
                    $transaction->save();

                    // Reverse failed purchase
                    if ($debited) {
                        Wallet::credit($this->business, $cost, "KYC BVN verification of $bvn reversal");
                    }
                }

                return response()->json(['success' => false, 'message' => $response['message'], 'error' => $response['error']]);
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);

        } catch (\Exception $exception) {
//            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error with BVN verification']);
        }
    }


    public function verifyBvnWithImage()
    {
        try {
            $this->business = auth()->user()->business;

            $reference = General::generateReference();
            $data = request()->all();

            $required = [
                'bvn', 'phone'
            ];

            foreach ($required as $r) {
                if (!isset($data[$r])) {
                    return response()->json(["success" => false, "message" => "Request is missing key: $r"]);
                }
            }

            $debited = false;

            $phone = str_replace(' ', '', $data['phone']);
            $bvn = str_replace(' ', '', $data['bvn']);

            $cost = (double) \App\Model\Kyc::getKycCost( \App\Model\Kyc::$ibvn );

            if ( $cost > $this->business->wallet->balance ) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }

            $info = "KYC BVN with image verification of $bvn with charge of N $cost from your Wallet";

            // save transaction
            $transaction = $this->business->transactions()->create([
                'amount'      => $cost,
                'charge'      => 0.0,
                'net_amount'  => $cost,
                'status'      => 'PENDING',
                'type'        => 'OTHERS',
                'info'        => $info,
                'channel'     => request()->header('channel') ?? 'OTHERS',
                'reference'   =>  $reference ,
            ]);


            // debit wallet
            $debit =  Wallet::debit($this->business, $cost, $info);

            if ($debit['success']) {

                $debited = true;
                $transaction->update(['wallet_debited' => 1]);

                $response = Blusalt::verifyImageBankVerificationNumber($phone, $bvn);

                if ( $response['success'] ) {

                    // Update transaction
                    $transaction->status = 'SUCCESSFUL';
                    $transaction->save();

                    return response()->json([
                        'success' => true,
                        'message' => $response['message'],
                        'data' => $response['data']
                    ]);

                } else {
                    // Update transaction
                    $transaction->status = 'FAILED';
                    $transaction->save();

                    // Reverse failed purchase
                    if ($debited) {
                        Wallet::credit($this->business, $cost, "KYC BVN with image verification of $bvn reversal");
                    }
                }

                return response()->json(['success' => false, 'message' => $response['message']]);
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error with BVN verification']);
        }
    }


    public function verifyNin()
    {
        try {
            $this->business = auth()->user()->business;

            $reference = General::generateReference();
            $data = request()->all();
            $required = [
                'nin', 'phone'
            ];

            foreach ($required as $r) {
                if (!isset($data[$r])) {
                    return response()->json(["success" => false, "message" => "Request is missing key: $r"]);
                }
            }

            $debited = false;

            $phone = str_replace(' ', '', $data['phone']);
            $nin = str_replace(' ', '', $data['nin']);

            $cost = (double) \App\Model\Kyc::getKycCost( \App\Model\Kyc::$nin );

            if ( $cost > $this->business->wallet->balance ) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }

            $info = "KYC NIN verification of $nin with charge of N $cost from your Wallet";

            // save transaction
            $transaction = $this->business->transactions()->create([
                'amount'      => $cost,
                'charge'      => 0.0,
                'net_amount'  => $cost,
                'status'      => 'PENDING',
                'type'        => 'OTHERS',
                'info'        => $info,
                'channel'     => request()->header('channel') ?? 'OTHERS',
                'reference'   =>  $reference ,
            ]);


            // debit wallet
            $debit =  Wallet::debit($this->business, $cost, $info);

            if ($debit['success']) {

                $debited = true;
                $transaction->update(['wallet_debited' => 1]);

                $response = Blusalt::verifyNin($phone, $nin);

                if ( $response['success'] ) {

                    // Update transaction
                    $transaction->status = 'SUCCESSFUL';
                    $transaction->save();

                    return response()->json([
                        'success' => true,
                        'message' => $response['message'],
                        'data' => $response['data']
                    ]);

                } else {
                    // Update transaction
                    $transaction->status = 'FAILED';
                    $transaction->save();

                    // Reverse failed purchase
                    if ($debited) {
                        Wallet::credit($this->business, $cost, "KYC BVN verification of $nin reversal");
                    }
                }

                return response()->json(['success' => false, 'message' => $response['message'], 'error' => $response['error']]);
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);

        } catch (\Exception $exception) {
//            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error with NIN verification']);
        }
    }


    public function verifyPvc()
    {
        try {
            $this->business = auth()->user()->business;

            $reference = General::generateReference();
            $data = request()->all();

            $required = [
                'pvc_number', 'last_name', 'phone', 'state'
            ];

            foreach ($required as $r) {
                if (!isset($data[$r])) {
                    return response()->json(["success" => false, "message" => "Request is missing key: $r"]);
                }
            }

            $debited = false;

            $phone = str_replace(' ', '', $data['phone']);
            $pvc_number = str_replace(' ', '', $data['pvc_number']);

            $cost = (double) \App\Model\Kyc::getKycCost( \App\Model\Kyc::$pvc );

            if ( $cost > $this->business->wallet->balance ) {
                return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
            }

            $info = "KYC PVC verification of $pvc_number with charge of N $cost from your Wallet";

            // save transaction
            $transaction = $this->business->transactions()->create([
                'amount'      => $cost,
                'charge'      => 0.0,
                'net_amount'  => $cost,
                'status'      => 'PENDING',
                'type'        => 'OTHERS',
                'info'        => $info,
                'channel'     => request()->header('channel') ?? 'OTHERS',
                'reference'   =>  $reference ,
            ]);


            // debit wallet
            $debit =  Wallet::debit($this->business, $cost, $info);

            if ($debit['success']) {

                $debited = true;
                $transaction->update(['wallet_debited' => 1]);

                $response = Blusalt::verifyPvc($pvc_number, $data['last_name'], $phone, $data['state']);

                if ( $response['success'] ) {

                    // Update transaction
                    $transaction->status = 'SUCCESSFUL';
                    $transaction->save();

                    return response()->json([
                        'success' => true,
                        'message' => $response['message'],
                        'data' => $response['data']
                    ]);

                } else {
                    // Update transaction
                    $transaction->status = 'FAILED';
                    $transaction->save();

                    // Reverse failed purchase
                    if ($debited) {
                        Wallet::credit($this->business, $cost, "KYC PVC verification of $pvc_number reversal");
                    }
                }

                return response()->json(['success' => false, 'message' => $response['message'], 'error' => $response['error'] ?? '']);
            }

            return response()->json(['success' => false, 'message' => "Error debiting your wallet"]);

        } catch (\Exception $exception) {
//            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error with PVC verification']);
        }
    }
}
