<?php

namespace App\Http\Controllers\Api;

use App\Classes\General;
use App\Classes\Wallet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Account extends Controller
{


    public function debitWallet()
    {

        $user = auth()->user();
        $business = $user->business;

        $amount = request('amount');
        $businessName = request('business_name');

        // Verify Business Name
        if ( !$business || ( $business->name !== $businessName ) ) {
            return response()->json(['success' => false, 'message' => 'Sorry! This business account isn\'t registered with SageClooud']);
        }

        // Verify Wallet balance
        if ($amount > $business->wallet->balance) {

            return response()->json(['success' => false, 'message' => 'Insufficient fund!']);
        }

        $info = "Wallet debit to reconcile manual funding from business portal";
        $baseInfo = request('narration') . " $info" ?? $info;

        $reference = General::generateReference();

        // Debit and Log Transaction
        // save transaction
        $transaction = $business->transactions()->create([
            'amount'      => $amount,
            'charge'      => 0.0,
            'net_amount'  => $amount,
            'status'      => 'PENDING',
            'type'        => 'TRANSFER',
            'info'        => $baseInfo,
            'channel'     => request()->header('channel') ?? 'OTHERS',
            'reference'   =>  $reference ,
        ]);

        // debit wallet
        if ($transaction) {
            $debit =  Wallet::debit($business, $amount, $baseInfo);
        }

        if ($debit['success']) {

            $transaction->update([ 'wallet_debited' => 1, 'status' => 'SUCCESSFUL' ]);

            return response()->json(['success' => true, 'message' => 'Business wallet debit successful!']);
        }

        return response()->json(['success' => false, 'message' => 'Sorry! We could not debit business wallet']);
    }

    public function requery() {
        $required = 'reference';

        $data = request()->all();

        if (!isset($data[$required])) {
            return response()->json(["success" => false, "message" => "Request is missing key: $required"]);
        }

        $business = auth()->user()->business;

        $transaction = $business->transactions()->where('external_reference', $data['reference'])->first();

        if (is_null($transaction)) {
            return response()->json(["success" => false, "message" => "Transaction not found"]);
        }

        $status = strtolower($transaction->status);

        return response()->json([
            "success" => true,
            "message" => "Transaction found",
            "transaction" => [
                "reference" => $data['reference'],
                "status" => $status,
                "date" => $transaction->created_at
            ]
        ]);
    }
}
