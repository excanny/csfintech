<?php
/**
 * Created by Canaan Etai.
 * Date: 1/29/20
 * Time: 7:16 AM
 */

namespace App\Classes;




use Illuminate\Support\Facades\Log;

class Shago
{

    /**
     * Get api endpoint
     *
     * @return mixed
     */
    private static function url()
    {
        if ( env('APP_ENV') == 'production' ) {
            return env('SHAGO_LIVE_API');
        }
        return env('SHAGO_TEST_API');
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
            $headers[] = 'hashKey: ' . env('SHAGO_KEY');
        }
        else {
            $headers[] = "email: kolaoyafajo@gmail.com";
            $headers[] = "password: kolawole1";
        }

        return $headers;
    }


    /**
     * Validate customer.
     *
     * @param $serviceCode
     * @param $phone
     * @param $network
     * @return array|\Illuminate\Http\JsonResponse
     */
    public static function validateCustomer($serviceCode, $phone, $network)
    {
        try {

            $method = 'POST';
            $url = self::url();
            $headers = self::getHeaders();
            $payload = [
                'serviceCode' => $serviceCode,
                'phone' => $phone,
                'network' => $network
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info(json_encode("=========== SHAGO VALIDATE RESPONSE"));
            Log::info(json_encode($res));
            $data = json_decode($res['RESPONSE_BODY']);

//            dd($data);

            if ( $res['HTTP_CODE'] == 200 ) {
//                $customer = $data->Customers[0];
//
//                if ( $customer->responseCode == '90000' ) {
//                    return [
//                        'success' => true,
//                        'data' => $data
//                    ];
//                }
//
//                return [
//                    'success' => false,
//                    'message' => $customer->responseDescription
//                ];
                if ( $data->status == '200' ) {
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
                'message' => $data->error->message
            ];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception with customer validation',
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Fetch data plans
     *
     * @param $serviceCode
     * @param $phone
     * @param $network
     * @return array
     */
    public static function getPackages($serviceCode, $phone, $network)
    {
        try {
            $method = 'POST';
            $url = self::url();

            $headers = self::getHeaders();

            $payload = [
                'serviceCode' => $serviceCode,
                'network' => $network
            ];
            if ( $serviceCode == 'SMV' ) {
                $payload['account'] = $phone;
            }
            else {
                $payload['phone'] = $phone;
            }

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            if ( $res['HTTP_CODE'] == 200 ) {
                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->status == '200' ) {
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
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception getting packages',
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Purchase internet data
     *
     * @param $serviceCode
     * @param $phone
     * @param $network
     * @param $amount
     * @param $bundle
     * @param $package
     * @param $reference
     * @return array
     */
    public static function buyData($serviceCode, $phone,  $amount,$network, $bundle, $package, $reference)
    {
        try {
            $method = 'POST';
            $url = self::url();

            $headers = self::getHeaders();

            $payload = [
                'serviceCode' => $serviceCode,
                'amount' => $amount,
                'bundle' => $bundle,
                'package' => $package,
                'request_id' => $reference
            ];

            // smile
            if ( $serviceCode == 'SMB' ) {

                $payload['account'] = $phone;
                $payload['type'] = 'SMILE_BUNDLE';
                $payload['productsCode'] = $package;

            }elseif( $serviceCode == 'BDA' ) {
                $payload['phone'] = $phone;
                $payload['network'] = $network;
                $payload['productsCode'] = $package;
            }
            elseif ($serviceCode == 'SPB') {
                $payload['pinNo'] = '1';
                $payload['type'] = 'SPECTRANET';
                $payload['productsCode'] = $package;
            }
            else {
                $payload['phone'] = $phone;
                $payload['network'] = $network;
            }

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info(json_encode("=========== SHAGO DATA PAYLOAD"));
            Log::info(json_encode($payload));
            Log::info(json_encode("=========== SHAGO DATA RESPONSE"));
            Log::info(json_encode($res));

            if ( $res['HTTP_CODE'] == 200 ) {

                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->status == '200' ) {
                    return [
                        'success' => true,
                        'status' => 'success',
                        'data' => $data
                    ];
                }
                elseif ($data->status == '400') {
                    return [
                        'success' => true,
                        'status' => 'pending',
                        'message' => $data->message
                    ];
                }
                return [
                    'success'   => false,
                    'status' => 'failed',
                    'message' => $data->message
                ];
            }

            // error in transaction.
            return [
                'success'   => false,
                'message' => 'Could not purchase data at this time, please try again.'
            ];
        }
        catch (\Exception $exception ) {
//            return $exception->getMessage();
            return [
                'success'   => false,
                'message'   => 'Error exception with DATA purchase',
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Purchase airtime
     *
     * @param $serviceCode
     * @param $phone
     * @param $amount
     * @param $network
     * @param $reference
     * @return array
     */
    public static function buyAirtime($serviceCode, $phone, $amount, $network, $reference)
    {
        try {
            $method = 'POST';
            $url = self::url();

            $headers = self::getHeaders();

            $payload = [
                'serviceCode' => $serviceCode,
                'phone' => $phone,
                'amount' => $amount,
                'vend_type' => 'VTU',
                'network' => $network,
                'request_id' => $reference
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info('=========== SHAGO AIRTIME RESPONSE =============');
            Log::info(json_encode($res));
            if ( $res['HTTP_CODE'] == 200 ) {
                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->status == '200' ) {
                    return [
                        'success' => true,
                        'status' => 'success',
                        'data' => $data
                    ];
                }
                elseif ($data->status == '400') {
                    return [
                        'success' => true,
                        'status' => 'pending',
                        'data' => $data->message
                    ];
                }
                return [
                    'success'   => false,
                    'status' => 'failed',
                    'message' => $data->message
                ];
            }

            // error in transaction.
            return [
                'success'   => false,
                'status' => 'failed',
                'message' => 'Could not purchase airtime at this time, please try again.'
            ];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'status' => 'failed',
                'message'   => 'Error exception with Airtime purchase',
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Validate cableTV account
     *
     * @param $smartCardNo
     * @param $type
     * @return array
     */
    public static function cableTvLookup($smartCardNo, $type)
    {
        try {
            $method = 'POST';
            $url = self::url();

            $headers = self::getHeaders();

            $payload = [
                'serviceCode' => 'GDS',
                'smartCardNo' => $smartCardNo,
                'type' => $type
            ];

            Log::info('==========SHAGO VALIDATE PAYLOAD ===========');
            Log::info(json_encode($payload));
            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info('==========SHAGO VALIDATE RESPONSE ===========');
            Log::info(json_encode($res));

            if ( $res['HTTP_CODE'] == 200 ) {
                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->status == '200' ) {
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
                'message' => 'Could not fetch bouquets at this time, please try again.'
            ];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception getting bouquets',
                'error'   => $exception->getMessage()
            ];
        }
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
            $url = self::url();

            $headers = self::getHeaders();

            $payload = [
                'serviceCode' => $service,
                'phone' => '08150972145',
                'network' => 'MTN'
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

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
     * Purchase bouquet
     *
     * @param $smartCardNo
     * @param $type
     * @param $item
     * @param $reference
     * @return array
     */
    public static function cableTvPurchase($smartCardNo, $type, $item, $reference)
    {
        try {
            $method = 'POST';
            $url = self::url();

            $headers = self::getHeaders();


            $payload = [
                'serviceCode' => 'GDB',
                'smartCardNo' => $smartCardNo,
                'type' => $type,
                'customerName'=> $smartCardNo,
                'packagename' => $type == 'STARTIMES' ? 'STARTIMES' : $item->paymentitemname,
                'amount' => $type == 'STARTIMES' ? request('amount') : $item->amount,
                'request_id' => $reference
            ];

            if ( $type != 'STARTIMES' )  {
                $payload['productsCode'] = $item->code;
                $payload['period'] = '1';
                $payload['hasAddon'] = '0';
            }

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info("===== SHAGO RESPONSE =======");
            Log::info($res);

            if ( $res['HTTP_CODE'] == 200 ) {

                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->status == '200' ) {
                    return [
                        'success' => true,
                        'status' => 'success',
                        'data' => $data
                    ];
                }
                elseif ($data->status == '400') {
                    return [
                        'success' => true,
                        'status' => 'pending',
                        'data' => $data->message
                    ];
                }
                return [
                    'success'   => false,
                    'status' => 'failed',
                    'message' => $data->message
                ];
            }

            // error in transaction.
            return [
                'success'   => false,
                'status' => 'failed',
                'message' => "Could not purchase $type bouquet at this time, please try again."
            ];
        }
        catch (\Exception $exception ) {
//           return $exception->getMessage();
            return [
                'success'   => false,
                'status' => 'failed',
                'message'   => "Error exception with $type purchase",
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Validate meter/account number
     *
     * @param $serviceCode
     * @param $disco
     * @param $meterNo
     * @param $type
     * @return array|\Illuminate\Http\JsonResponse
     */
    public static function validateMeter($disco, $meterNo, $type)
    {
        try {
            $method = 'POST';
            $url = self::url();

            $headers = self::getHeaders();

            $payload = [
                'serviceCode' => 'AOV',
                'disco' => $disco,
                'meterNo' => $meterNo,
                'type' => $type
            ];

            Log::info('========= elect vali. payload');
            Log::info(json_encode($payload));

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            if ( $res['HTTP_CODE'] == 200 ) {
                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->status == '200' ) {
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
                'message' => 'Could not validate meter at this time, please try again.'
            ];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception validating meter',
                'error'   => $exception->getMessage()
            ];
        }
    }


    public static function electricityPurchase($disco, $meterNo, $type, $amount, $phone, $name, $reference)
    {
        try {
            $method = 'POST';
            $url = self::url();

            $headers = self::getHeaders();

            $payload = [
                'serviceCode' => 'AOB',
                'disco' => $disco,
                'meterNo'=> $meterNo,
                'type' => $type,
                'amount' => $amount,
                'phonenumber' => $phone,
                'name' => $name,
                'address' => '.',
                'request_id' => $reference
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info('==========SHAGO ELECTRICITY RESPONSE ===========');
            Log::info($res);

            if ( $res['HTTP_CODE'] == 200 ) {


                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->status == '200' ) {
                    return [
                        'success' => true,
                        'status' => 'success',
                        'data' => $data
                    ];
                }
                elseif ($data->status == '400') {
                    return [
                        'success' => true,
                        'status' => 'pending',
                        'data' => $data->message
                    ];
                }
                return [
                    'success'   => false,
                    'status' => 'failed',
                    'message' => $data->message
                ];
            }

            // error in transaction.
            return [
                'success'   => false,
                'status' => 'failed',
                'message' => "Could not purchase $disco at this time, please try again."
            ];
        }
        catch (\Exception $exception ) {
//        return $exception->getMessage();
            return [
                'success'   => false,
                'status' => 'failed',
                'message'   => "Error exception with $disco purchase",
                'error'   => $exception->getMessage()
            ];
        }
    }

    public static function re_query($reference)
    {
        try {
            $method = 'POST';
            $url = self::url();

            $headers = self::getHeaders();

            $payload = [
                'serviceCode' => 'QUB',
                'reference' => $reference
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info('==========RE-QUERY RESPONSE ===========');
            Log::info(json_encode($res));

            if ( $res['HTTP_CODE'] == 200 ) {

                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->status == '300' ) {

                    return [
                        'success'   => false,
                        'status' => 'failed',
                        'message' => $data->message
                    ];
                }
                elseif ($data->status == '400') {
                    return [
                        'success' => true,
                        'status' => 'pending',
                        'data' => $data->message
                    ];
                }

                return [
                    'success' => true,
                    'status' => 'success',
                    'data' => $data
                ];
            }

            // error in transaction.
            return [
                'success'   => false,
                'message' => "Could not make a re-query at this time, please try again."
            ];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => "Error exception with re-query",
                'error'   => $exception->getMessage()
            ];
        }
    }
}
