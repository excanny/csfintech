<?php
namespace App\Classes;




use App\Classes\AbCrypt;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class Blusalt
{

    /**
     * Get api endpoint
     *
     * @return mixed
     */
    private static function url()
    {
        if (env('APP_ENV') == 'production') {
            return env('BLUSALT_BASE_URL_LIVE') . env('BLUSALT_API_VERSION') . '/';
        }
        return env('BLUSALT_BASE_URL_TEST') . env('BLUSALT_API_VERSION') . '/';
    }

    /**
     * Get headers
     *
     * @return array
     */
    public static function getHeaders()
    {
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json"
        ];

        if (env('APP_ENV') == 'production') {
            $headers[] = "clientid: " . env('BLUSALT_CLIENT_ID_LIVE');
            $headers[] = "appname: " . env('BLUSALT_APP_NAME_LIVE');
            $headers[] = "apikey: " . env('BLUSALT_API_KEY_LIVE');
            $headers[] = "publickey: " . env('BLUSALT_PUBLIC_KEY_LIVE');
        } else {
            $headers[] = "clientid: " . env('BLUSALT_CLIENT_ID_TEST');
            $headers[] = "appname: " . env('BLUSALT_APP_NAME_TEST');
            $headers[] = "apikey: " . env('BLUSALT_API_KEY_TEST');
            $headers[] = "publickey: " . env('BLUSALT_PUBLIC_KEY_TEST');
        }

        return $headers;
    }


    /**
     * VERIFY BVN
     *
     * @param $phone
     * @param $bvn
     * @return array
     */
    public static function verifyBankVerificationNumber($phone, $bvn)
    {
        try {
            $method = 'POST';
            $url = self::url() . 'IdentityVerification/BVN';

            $headers = self::getHeaders();

            $payload = [
                'phone_number' => $phone,
                'bvn_number' => $bvn
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info('========== KYC bvn ==============');
            Log::info(json_encode($res));

            $data = json_decode($res['RESPONSE_BODY']);

            if ($res['HTTP_CODE'] == 200) {

                if ($data->status_code == 200 && $data->status == 'Success') {
                    return [
                        'success' => true,
                        'message' => $data->message,
                        'data' => $data->results
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data->message
                ];
            }

            return [
                'success' => false,
                'message' => $data->message
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => 'Error verifying BVN',
                'error' => $exception->getMessage()
            ];
        }
    }

    public static function verifyImageBankVerificationNumber($phone, $bvn)
    {
        try {
            $method = 'POST';
            $url = self::url() . 'IdentityVerification/iBVN';

            $headers = self::getHeaders();

            $payload = [
                'phone_number' => $phone,
                'bvn_number' => $bvn
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
//            dd($res);

            $data = json_decode($res['RESPONSE_BODY']);

            if ($res['HTTP_CODE'] == 200) {

                if ($data->status_code == 200 && $data->status == 'Success') {
                    return [
                        'success' => true,
                        'message' => $data->message,
                        'data' => $data->results
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data->message
                ];
            }

            return [
                'success' => false,
                'message' => $data->message
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => 'Error verifying BVN',
                'error' => $exception->getMessage()
            ];
        }
    }

    public static function verifyNin($phone, $nin)
    {
        try {
            $method = 'POST';
            $url = self::url() . 'IdentityVerification/NIN';

            $headers = self::getHeaders();

            $payload = [
                'phone_number' => $phone,
                'nin_number' => $nin
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info('========== KYC nin ==============');
            Log::info(json_encode($res));

            $data = json_decode($res['RESPONSE_BODY']);

            if ($res['HTTP_CODE'] == 200) {

                if ($data->status_code == 200 && $data->status == 'Success') {
                    return [
                        'success' => true,
                        'message' => $data->message,
                        'data' => $data->results
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data->message
                ];
            }

            return [
                'success' => false,
                'message' => $data->message
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => 'Error verifying NIN',
                'error' => $exception->getMessage()
            ];
        }
    }

    public static function verifyPvc($pvc_number, $last_name, $phone_number, $state)
    {
        try {
            $method = 'POST';
            $url = self::url() . 'IdentityVerification/PVC';

            $headers = self::getHeaders();

            $payload = [
                'pvc_number' => $pvc_number,
                'last_name' => $last_name,
                'phone_number' => $phone_number,
                'state' => $state
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info('========== KYC pvc ==============');
            Log::info(json_encode($res));

            $data = json_decode($res['RESPONSE_BODY']);
//            dd($data->message);

            if ($res['HTTP_CODE'] == 200) {

                if ($data->status_code == 200 && $data->status == 'Success') {
                    return [
                        'success' => true,
                        'message' => $data->message,
                        'data' => $data->results
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data->message
                ];
            }

            return [
                'success' => false,
                'message' => $data->message
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => 'Error verifying PVC',
                'error' => $exception->getMessage()
            ];
        }
    }

    /** @noinspection PhpComposerExtensionStubsInspection */
    public static function fundsTransfer ($amount, $accountNumber, $bankCode, $accountName, $reference, $narration) {
        try {
            $method = 'POST';
            $url = self::url() . 'Authorize/FundsTransfer/Transfer';

            $headers = self::getHeaders();
//            dd($headers);

            $initial_payload = [
                'amount' => $amount,
                'account_number' => $accountNumber,
                'bank_code' => $bankCode,
                'save_beneficiary' => true,
                'source' => 'wallet',
                'narration' => $narration,
                'metadata' => (object)[],
                'reference_code' => $reference,
                'pin' => '00000000000',
            ];

            $request_string = json_encode($initial_payload);
            Log::info('========== Blusalt transfer payload ==============');
            Log::info($request_string);

            # Key for encryption
            $key = env('APP_ENV') == 'production' ? env('BLUSALT_SECRET_KEY_LIVE') :  env('BLUSALT_SECRET_KEY_TEST');
            $password = substr($key,0,32);

            $iv_size        = openssl_cipher_iv_length('AES-128-CBC');
            $iv             = openssl_random_pseudo_bytes($iv_size);
            $ciphertext     = openssl_encrypt($request_string, 'AES-128-CBC', hex2bin($password), OPENSSL_RAW_DATA, $iv);

            $base64Iv = base64_encode($iv);
            $data = base64_encode($ciphertext);

            $payload = [
                'data' => $data,
                'base64Iv' => $base64Iv,
            ];
//            dd($payload);


            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            $data = json_decode($res['RESPONSE_BODY']);
            Log::info('========== Blusalt transfer ==============');
            Log::info(json_encode($data));

            // decrypt response
            $resp_iv = base64_decode($data->base64Iv);
            $resp_cipher_text = base64_decode($data->data);
            $password = substr($key,0,32);
            $decrypted_data_json = openssl_decrypt($resp_cipher_text,'AES-128-CBC', hex2bin($password), OPENSSL_RAW_DATA, $resp_iv);
            $decrypted_data = json_decode($decrypted_data_json);
            Log::info('========== Blusalt transfer ==============');
            Log::info(json_encode($decrypted_data));

            if ($res['HTTP_CODE'] == 200) {

                if ($decrypted_data->status_code == 200 && $decrypted_data->status == 'success') {
                    return [
                        'success' => true,
                        'status' => 'success',
                        'message' => $decrypted_data->message,
                        'data' => $decrypted_data->results
                    ];
                }

                if ($decrypted_data->status == 'pending') {
                    return [
                        'success' => false,
                        'status' => 'pending',
                        'message' => $decrypted_data->message
                    ];
                }
            }

//            $body = json_decode()

            return [
                'success' => false,
                'status' => 'failed',
                'message' => $decrypted_data->message ?? 'Transfer failed'
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'status' => 'failed',
                'message' => 'Error with transfer',
                'error' => $exception->getMessage()
            ];
        }
    }

}
