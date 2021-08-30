<?php
/**
 * Created by Canaan Etai.
 * Date: 1/25/20
 * Time: 9:36 AM
 */

namespace App\Classes;

use App\Http\Controllers\HelperController;
use App\Model\Product;
use App\Notifications\NotifyUser;
use Illuminate\Support\Facades\DB;

class SagePayWallet
{

    public static function generateAccountNumber()
    {
        $no = (string) random_int(00000000, 99999999);
        $no = "10$no";

        if ( strlen($no) != 10 ) {
            return self::generateAccountNumber();
        }

        if ( \App\Model\SagePayWallet::where('account_number', $no)->count() > 0 ) {
            return self::generateAccountNumber();
        }

        return $no;
    }


    /**
     * Credit Wallet
     *
     * @param $business
     * @param $amount
     * @param $info
     * @param bool $isCommission
     * @param null $reference
     * @return array
     */
    public static function credit($business, $amount, $info, $reference = null)
    {
        try {
          $wallet = $business->sage_pay_wallet;
            // Check if wallet is active
            if ($wallet->status !== 'ACTIVE') {
                return [
                    'success' => false,
                    'message' => "Wallet is {$wallet->status}"
                ];
            }

            $response = [
                'success' => false,
                'message' => 'Error crediting wallet'
            ];

            DB::transaction(function () use ($business, $wallet, $amount, $info, &$response, $reference) {

                $prev_bal = $wallet->balance;

                $wallet->balance += $amount;
                $wallet->updated_at = now();
                $wallet->save();

                $wallet->transactions()->create([
                    'business_id' => $business->id,
                    'amount' => $amount,
                    'type' => 'CREDIT',
                    'prev_balance' => $prev_bal,
                    'new_balance' => $wallet->balance,
                    'info' => $info,
                    'reference' => is_null($reference) ? General::generateSagePayWalletReference() : $reference,
                ]);

                $response = [
                    'success' => true,
                    'message' => 'Wallet credited successfully.'
                ];
            });

            return $response;

        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }


    /**
     * Debit Wallet
     *
     * @param $business
     * @param $amount
     * @param $info
     * @param bool $isCommission
     * @param bool $allow_negative
     * @param null $product
     * @return array
     */
    public static function debit($business, $amount, $info, $allow_negative = false, $product = null)
    {
        try {
            $wallet = $business->sage_pay_wallet;

            // Check if wallet is active
            if ($wallet->status !== 'ACTIVE') {
                return [
                    'success' => false,
                    'message' => "Wallet is {$wallet->status}"
                ];
            }

            // validate wallet balance / commission
            if ($amount > $wallet->balance) {
                if ( !$allow_negative) {
                    return [
                        'success' => false,
                        'message' => "Insufficient Fund!"
                    ];
                }
            }

            $response = [
                'success' => false,
                'message' => 'Error with debit'
            ];

            DB::transaction(function () use ($business, $wallet, $amount, $info, &$response) {

                $prev_bal = $wallet->balance;

                $wallet->balance -= $amount;
                $wallet->updated_at = now();
                $wallet->save();

                $wallet->transactions()->create([
                    'business_id' => $business->id,
                    'amount' => $amount,
                    'type' => 'DEBIT',
                    'prev_balance' => $prev_bal,
                    'reference' => General::generateWalletReference(),
                    'new_balance' => $wallet->balance,
                    'info' => $info
                ]);

                $response = [
                    'success' => true,
                    'message' => 'Wallet debited successfully.'
                ];
            });

            return $response;

        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public static function transferCharge( $amount, $product )
    {
        if ($product['is_flat']) {
            $charge = floatval($product['flat_charge']);
        }
        else {
            $band1 = $product['merchant_commission'][Product::$transfer_band_1];
            $band2 = $product['merchant_commission'][Product::$transfer_band_2];
            $band3 = $product['merchant_commission'][Product::$transfer_band_3];

            if( $amount <= 5000 ) {
                $charge = $product['charge_type'] == Product::$FIXED ? $band1 :
                    floatVal(($band1 / 100) * $amount);
            }
            elseif ( $amount <= 50000 ) {
                $charge = $product['charge_type'] == Product::$FIXED ? $band2 :
                    floatVal(($band2 / 100) * $amount);
            }
            else {
                $charge = $product['charge_type'] == Product::$FIXED ? $band3 :
                    floatVal(($band3 / 100) * $amount);
            }
        }

        return $charge;
    }

    public static function gateWayCharge ( $charge , $amount, $cap ) {
       $final_charge = floatVal(($charge / 100) * $amount) ;

       // Charge should not be more than the cap
       return $final_charge <= $cap ? $final_charge : $cap;
    }

    public static function logFailedTransaction( $business, $amount, $info, $type )
    {
        $wallet = $business->sage_pay_wallet;

        $wallet->transactions()->create([
            'business_id' => $business->id,
            'amount' => $amount,
            'reference' => General::generateWalletReference(),
            'type' => $type,
            'prev_balance' => $wallet->balance,
            'new_balance' => $wallet->balance,
            'info' => $info
        ]);
    }
}
