<?php
namespace App\Classes;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use \GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Model\Biller;

/**
 * @author Michel Kalavanda
 * @version 1.0.0
 * @copyright CAPITAL SAGE - 2020
 */
class Sonite
{
    public static  $soniteAuth;
    public static $soniteApiKey;

    /**
     * Get api endpoint
     *
     * @return mixed
     */
    private static function url()
    {
        if ( env('APP_ENV') == 'production' ) {
            return  env('SONITE_BASE_URI');
        }
        return  env('SONITE_BASE_URI');
    }


    /**
     * Get headers
     *
     * @return array
     */
    public static function getHeaders()
    {
        $headers = [
            'Content-Type: application/json',
        ];

        if ( env('APP_ENV') == 'production' ) {
            $headers[] = 'Accept: application/json';
            $headers[] = 'Authorization: Bearer ' . env('SONITE_AUTH_KEY');
            $headers[] = 'x-api-key: ' . env('SONITE_X_API');
        }
        else {
            $headers[] = 'Accept: application/json';
            $headers[] = 'Authorization: Bearer ' . env('SONITE_AUTH_KEY');
            $headers[] = 'x-api-key: ' . env('SONITE_X_API');
        }

        return $headers;
    }


    /**
     * GET DATA PLANS FROM SONITE
     *
     * service
     * MTNDATA
     * AIRTELDATA
     * GLODATA
     * 9MOBILEDATA or ETISALATDATA
     *
     * @param $service
     * @return array
     */
    public static function dataLookup($service)
    {
        try {
            $method = 'POST';

            $url = self::url() . '/api/data/lookup';

            $payload = [
                'service' => $service
            ];

            $headers = self::getHeaders();

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info('======== SONITE RESPONSE ==========');
            Log::info(json_encode($res));

            if ( $res['HTTP_CODE'] == 200 ) {
                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->status == 1 ) {
                    return [
                        'success' => true,
                        'data' => $data
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data->message
                ];
            }

            // error in transaction.
            return [
                'success'   => false,
                'message' => 'Could not fetch packages, please try again.'
            ];


        } catch (\Exception $e) {

            Log::info("Exception Message", [$e->getMessage()]);
            return [
                'success'   => false,
                'message'   => 'Exception occured calling Sonite Service on DATA lookup',
                'error'   => $e->getMessage()
            ];
        }
    }


    /**
     * BUY DATA FROM SONITE
     * @param $service
     * @param $code
     * @param $phone
     * @return array
     */
    public static function dataSubscription($service, $code, $phone){

        try{
            $method = 'POST';
            $url = self::url() . '/api/data/purchase';

            $headers = self::getHeaders();

            $payload = [
                'service' => $service,
                "code" =>  $code,
                "phoneNumber" =>  $phone,
                "pin" =>  env('SONITE_ACC_PIN'),
                "key" =>  \hash('sha256', env('SONITE_API_PASS'))
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info('======== SONITE RESPONSE ==========');
            Log::info(json_encode($res));

            if ( $res['HTTP_CODE'] == 200 ) {
                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->status == 1 ) {
                    return [
                        'success' => true,
                        'data' => $data
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data->message
                ];
            }

            // error in transaction.
            return [
                'success'   => false,
                'message' => 'Could not purchase data, please try again.'
            ];

        }catch (\Exception $e) {

            Log::info("Exception Message", [$e->getMessage()]);
            return [
                'success'   => false,
                'message'   => 'Exception occured calling Sonite Service on DATA subscription',
                'error'   => $e->getMessage()
            ];
        }
    }


    /**
     * BUY AIRTIME - SONITE
     *
     * service
     * MTNVTU
     * AIRTELVTU
     * GLOVTU
     * 9MOBILEVTU or ETISALATVTU
     *
     * @param $phone
     * @param $service
     * @param $amount
     * @return array
     */
    public static function vtuPurchase($phone, $service, $amount )
    {
        try {
            $method = 'POST';
            $url = self::url() . '/api/product/1';

            $headers = self::getHeaders();

            $payload = [
                'service' => $service,
                "phoneNumber" =>  $phone,
                "amount" => $amount,
                "pin" =>  env('SONITE_ACC_PIN'),
                "key" =>  \hash('sha256', env('SONITE_API_PASS'))
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info('======== SONITE RESPONSE ==========');
            Log::info(json_encode($res));

            //dd(json_decode($res['RESPONSE_BODY']));

            if ( $res['HTTP_CODE'] == 200 ) {
                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->status == 1 ) {
                    return [
                        'success' => true,
                        'data' => $data
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data->message
                ];
            }

            // error in transaction.
            return [
                'success'   => false,
                'message' => 'Could not purchase VTU, please try again.'
            ];

        }catch (\Exception $e) {

            Log::info("Exception Message", [$e->getMessage()]);
            return [
                'success'   => false,
                'message'   => 'Exception occured calling Sonite VTU service',
                'error'   => $e->getMessage()
            ];
        }
    }

}
