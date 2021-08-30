<?php

namespace App\Helpers;

use App\Model\Transaction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class Helper
{

    public static function generatereference(string $string)
    {
        return strtoupper($string);
    }

    public static function getPaymentKey()
    {
        if (App::environment('local')) {
            return [
                'public' => env('PAYSTACK_TEST_PUBLIC_KEY'),
                'secret' => env('PAYSTACK_TEST_SECRET_KEY')
            ];
        }

        return [
            'public' => env('PAYSTACK_LIVE_PUBLIC_KEY'),
            'secret' => env('PAYSTACK_LIVE_SECRET_KEY')
        ];
    }

    public static function getTransaction($transaction_id)
    {
        return Transaction::find($transaction_id);
    }
}
