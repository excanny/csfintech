<?php
/**
 * Created by Canaan Etai.
 * Date: 1/29/20
 * Time: 7:16 AM
 */

namespace App\Classes;



use App\Model\commissionTransaction;
use App\Model\SagePayTransaction;
use App\Model\SagePayWalletTransaction;
use App\Model\User;
use App\Model\WalletTransaction;
use Illuminate\Support\Str;
use App\Model\Transaction;

class General
{

    public static function generateReference()
    {
        $reference = strtoupper(Str::random());

        if ( is_null(Transaction::where('reference', $reference)->first()) ) {
            return $reference;
        }

        return self::generateReference();
    }

    public static function generateWalletReference()
    {
        $reference = strtoupper(Str::random());

        if ( is_null(WalletTransaction::where('reference', $reference)->first()) ) {
            return $reference;
        }

        return self::generateWalletReference();
    }

    public static function generateCommissionReference()
    {
        $reference = strtoupper(Str::random());

        if ( is_null(CommissionTransaction::where('reference', $reference)->first()) ) {
            return $reference;
        }

        return self::generateCommissionReference();
    }

    public static function generateAppId()
    {
        $id = random_int(0000000000000000, 9999999999999999);

        if ( is_null(User::where('vas_app_id', $id)->first()) ) {
            return $id;
        }

        return self::generateAppId();
    }


    public static function getEtranzactPin($pin)
    {
        ini_set('error_reporting', '0');
        if ( env('APP_ENV') == 'production' ) {
            $key = env('ET_SECRET_KEY_LIVE');
//            $pin = env('ET_PIN_LIVE');
        }
        else {
            $key = env('ET_SECRET_KEY_TEST');
//            $pin = env('ET_PIN_TEST');
        }

        $master_key = substr($key, 0, 16);
        $pin = self::pkcs5_pad($pin, 16);

        $cipher = \mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        \mcrypt_generic_init($cipher, $master_key, $master_key);
        $encrypted = \mcrypt_generic($cipher, $pin);

        ini_set('error_reporting', '-1');
        return base64_encode($encrypted);
    }

    private static function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }


    /// SagePay

    public static function generateAccessCode()
    {
        $access_code = Str::random();

        if ( is_null(SagePayTransaction::where('access_code', $access_code)->first()) ) {
            return $access_code;
        }

        return self::generateAccessCode();
    }

    public static function generateSagePayReference()
    {
        $reference = strtoupper(Str::random(20));

        if ( is_null(SagePayTransaction::where('reference', $reference)->first()) ) {
            return $reference;
        }

        return self::generateReference();
    }

    public static function generateSagePayWalletReference()
    {
        $reference = strtoupper(Str::random());

        if ( is_null(SagePayWalletTransaction::where('reference', $reference)->first()) ) {
            return $reference;
        }

        return self::generateSagePayWalletReference();
    }
}
