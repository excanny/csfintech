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
use App\Model\Wallet as EWALLET;

class Wallet
{

    public static function generateAccountNumber()
    {
        $no = (string) random_int(00000000, 99999999);
        $no = "10$no";

        if ( strlen($no) != 10 ) {
            return self::generateAccountNumber();
        }

        if ( \App\Model\Wallet::where('account_number', $no)->count() > 0 ) {
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
    public static function credit($business, $amount, $info, $isCommission = false, $reference = null)
    {
        try {
          $wallet = $business->wallet;
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

            DB::transaction(function () use ($business, $wallet, $amount, $info, &$response, $isCommission, $reference) {

                if ($isCommission) {
                    $commission_prev_balance = $wallet->commission;

                    $wallet->commission += $amount;
                    $wallet->updated_at = now();
                    $wallet->save();

                    $wallet->commissionTransactions()->create([
                        'business_id' => $business->id,
                        'amount' => $amount,
                        'product' => $business->product_name ?? null,
                        'prev_balance' => $commission_prev_balance,
                        'new_balance' => $wallet->commission,
                        'info' => $info,
                        'reference' => is_null($reference) ? General::generateCommissionReference() : $reference,
                        'type' => 'CREDIT'
                    ]);
                }
                else {
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
                        'reference' => is_null($reference) ? General::generateWalletReference() : $reference,
                    ]);
                }

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
    public static function debit($business, $amount, $info, $isCommission = false, $allow_negative = false, $product = null)
    {
        try {
            $wallet = $business->wallet;

            // Check if wallet is active
            if ($wallet->status !== 'ACTIVE') {
                return [
                    'success' => false,
                    'message' => "Wallet is {$wallet->status}"
                ];
            }

            // validate wallet balance / commission
            // Check if wallet is active
            if ( $isCommission ) {
                if ( $amount > $wallet->commission ) {
                    if ( !$allow_negative ) {
                        return [
                            'success' => false,
                            'message' => "Insufficient Fund"
                        ];
                    }
                }
            } else {
                if ($amount > $wallet->balance) {
                    if ( !$allow_negative) {
                        return [
                            'success' => false,
                            'message' => "Insufficient Fund!"
                        ];
                    }
                }
            }

            $response = [
                'success' => false,
                'message' => 'Error with debit'
            ];

            DB::transaction(function () use ($business, $wallet, $amount, $info, &$response, $isCommission) {

                if ($isCommission) {
                    $prev_bal = $wallet->commission;

                    $wallet->commission -= $amount;
                    $wallet->updated_at = now();
                    $wallet->save();

                    $wallet->commissionTransactions()->create([
                        'business_id' => $business->id,
                        'amount' => $amount,
                        'prev_balance' => $prev_bal,
                        'reference' => General::generateCommissionReference(),
                        'new_balance' => $wallet->commission,
                        'info' => $info,
                        'product' => $business->product_name,
                        'type' => 'DEBIT'
                    ]);

                } else {
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
                }

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

    public static function calculateCommission ( $amount , $commission ) {
       return floatVal(($commission / 100) * $amount);
    }

    public static function logFailedTransaction( $business, $amount, $info, $type )
    {
        $wallet = $business->wallet;

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
