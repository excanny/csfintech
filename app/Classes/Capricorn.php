<?php
namespace App\Classes;




use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class Capricorn
{

    /**
     * Get api endpoint
     *
     * @return mixed
     */
    private static function url()
    {
        if ( env('APP_ENV') == 'production' ) {
            return env('CAPRICORN_BASE_URL_LIVE');
        }
        return env('CAPRICORN_BASE_URL_TEST');
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
            $headers[] = "Authorization: Api-key ". env('CAPRICORN_API_KEY_LIVE');
        }
        else {
            $headers[] = "Authorization: Api-key ". env('CAPRICORN_API_KEY_TEST');
        }

        return $headers;
    }


    /**
     * Retrieve Provider Bouquets
     *
     * @param $service_type
     * @return array
     */
    public static function cableTvLookUp($service_type)
    {
        try {
            $method = 'POST';
            $url = self::url().'services/multichoice/list';

            $headers = self::getHeaders();

            $payload = [
                'service_type' => $service_type
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));


            if ( $res['HTTP_CODE'] == 200 ) {
                $data = json_decode($res['RESPONSE_BODY']);
//                dd($data);

                if ( $data->status == 'success' ) {
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
     * Purchase bouquet
     *
     * @param $service_type
     * @param $bouquet
     * @param $smartCardNo
     * @param $ref
     * @return array
     */
    public static function cableTvPurchase($service_type, $bouquet, $smartCardNo, $ref)
    {
        try {
            $method = 'POST';
            $url = self::url().'services/multichoice/request';

            $headers = self::getHeaders();
            $headers[] = 'Baxi-date: '.now()->toRfc1123String();

            if ( env('APP_ENV') == 'production' ) {
                $agentId = env('CAPRICORN_USER_SECRET_LIVE');
            }else {
                $agentId = env('CAPRICORN_USER_SECRET_TEST');
            }



            if ($service_type != 'startimes') {
                $payload = new \stdClass();
                $payload->total_amount = $bouquet['price'];
                $payload->smartcard_number = $smartCardNo;
                $payload->product_monthsPaidFor = '1';
                $payload->product_code = $bouquet['code'];
                $payload->agentReference = $ref;
                $payload->agentId = $agentId;
                $payload->service_type = $service_type;
            } else {
                $payload = [
                    'total_amount' => $bouquet['price'],
                    'smartcard_number' => $smartCardNo,
                    'product_monthsPaidFor'=> '1',
                    'product_code' => $bouquet['code'],
                    'agentReference' => $ref,
                    'agentId' => $agentId,
                    'service_type' => $service_type
                ];
            }
                $payload = json_encode($payload);

            Log::info('======= CAPRICORN CABLETV PAYLOAD =========');
            Log::info(json_encode($payload));


            $res = HttpClient::send($headers, $method, $url, $payload);
            Log::info('======= CAPRICORN CABLETV RESPONSE =========');
            Log::info(json_encode($res));

            // Decode response body
            $data = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {
                // Create response for success and pending
                if ( $data->code == 200 ) {
                    if ( $data->status == 'success' ) {
                        return [
                            'success' => true,
                            'status' => 'success',
                            'data' => $data
                        ];
                    }
                    elseif ( $data->status == 'pending' ) {
                        return [
                            'success' => true,
                            'status' => 'pending',
                            'data' => $data
                        ];
                    }
                }

                // Create response for other instances
                return [
                    'success'   => false,
                    'status' => 'failed',
                    'message' => $data->message
                ];
            }

            // Return response if code is not 200
            return [
                'success'   => false,
                'status' => 'failed',
                'message' => isset($data->message) ? $data->message : "Could not purchase $service_type bouquet at this time, please try again."
            ];
        }
        catch (\Exception $exception ) {
//           return $exception->getMessage();
            return [
                'success'   => false,
                'status' => 'failed',
                'message'   => "Error exception with $service_type purchase",
                'error'   => $exception->getMessage() . " line: " . $exception->getLine()
            ];
        }
    }


    /**
     * Validate meter/account number
     *
     * @param $serviceType
     * @param $accountNumber
     * @return array|JsonResponse
     */
    public static function validateService( $serviceType, $accountNumber )
    {
        try {
            $method = 'POST';
            $url = self::url().'services/namefinder/query';

            $headers = self::getHeaders();

            $payload = [
                'service_type' => $serviceType,
                'account_number' => $accountNumber,
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            // Decode response body
            $data = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {
                // Create response for success and pending
                if ( $data->code == 200 ) {
                    if ( $data->status == 'success' ) {
                        return [
                            'success' => true,
                            'data' => $data
                        ];
                    }
                    elseif ( $data->status == 'pending' ) {
                        return [
                            'pending' => true,
                            'data' => $data
                        ];
                    }
                }

                // Create response for other instances
                return [
                    'success'   => false,
                    'message' => $data->message
                ];
            }

            // Return response if code is not 200
            return [
                'success'   => false,
                'message' => isset($data->message) ? $data->message : 'Could not validate number at this time, please try again.'
            ];
        }
        // Catch other exceptions
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception validating meter',
                'error'   => $exception->getMessage()
            ];
        }
    }



    public static function electricityLookUp()
    {
        try {
            $method = 'GET';
            $url = self::url().'services/electricity/billers';

            $headers = self::getHeaders();

            $payload = [];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

//            Log::info('==========electric lookup=======');
//            Log::info(json_encode($res));

            if ( $res['HTTP_CODE'] == 200 ) {
                $data = json_decode($res['RESPONSE_BODY']);


                if ( $data->status == 'success' ) {
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
                'message' => 'Could not fetch billers at this time, please try again.'
            ];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception getting billers',
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Purchase Electricity
     *
     * @param $serviceType
     * @param $amount
     * @param $phone
     * @param $accountNumber
     * @param $ref
     * @return array
     */
    public static function electricityPurchase($serviceType, $amount, $phone, $accountNumber, $ref)
    {
        try {
            $method = 'POST';
            $url = self::url().'services/electricity/request';

            $headers = self::getHeaders();
            $headers[] = 'Baxi-date: '.now()->toRfc1123String();

            if ( env('APP_ENV') == 'production' ) {
                $agentId = env('CAPRICORN_USER_SECRET_LIVE');
            }else {
                $agentId = env('CAPRICORN_USER_SECRET_TEST');
            }

            $payload = [
                'account_number'=> $accountNumber,
                'service_type' => $serviceType,
                'amount' => $amount,
                'phone' => $phone,
                'agentReference' => $ref,
                'agentId' => $agentId
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info('======= CAPRICORN RESPONSE =========');
            Log::info(json_encode($res));

//            dd($res);

            // Decode response body
            $data = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {
                // Create response for success and pending
                if ( $data->code == 200 ) {
                    if ( $data->status == 'success' ) {
                        return [
                            'success' => true,
                            'status' => 'success',
                            'data' => $data
                        ];
                    }
                    return [
                        'success' => true,
                        'status' => 'pending',
                        'data' => $data
                    ];
                }

                // Create response for other instances
                return [
                    'success'   => false,
                    'status' => 'failed',
                    'message' => $data->message
                ];
            }

            // Return response if code is not 200
            return [
                'success'   => false,
                'status' => 'failed',
                'message' => isset($data->message) ? $data->message : "Could not purchase $serviceType at this time, please try again."
            ];
        }
        catch (\Exception $exception ) {
//        return $exception->getMessage();
            return [
                'success'   => false,
                'status' => 'failed',
                'message'   => "Error exception with $serviceType purchase",
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Fetch Spectranet Pin Bundles
     *
     * @param string $serviceType
     * @return array
     */
    public static function spectranetPinBundles($serviceType='spectranet')
    {
        try {
            $method = 'POST';
            $url = self::url().'services/epin/bundles';

            $headers = self::getHeaders();
            $headers[] = 'Baxi-date: '.now()->toRfc1123String();

            $payload = [
                'service_type' => $serviceType,
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            // Decode response body
            $data = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {
                // Create response for success and pending
                if ( $data->code == 200 ) {
                    return [
                        'success' => true,
                        'data' => $data
                    ];
                }

                // Create response for other instances
                return [
                    'success'   => false,
                    'message' => $data->message
                ];
            }

            // Return response if code is not 200
            return [
                'success'   => false,
                'message' => isset($data->message) ? $data->message : "Error retrieving bundles, please try again."
            ];
        }
        catch (\Exception $exception ) {
//        return $exception->getMessage();
            return [
                'success'   => false,
                'message'   => "Error exception retrieving $serviceType bundles",
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Purchase Spectranet Pin
     *
     * @param $amount
     * @param $ref
     * @return array
     */
    public static function spectranetPinPurchase($amount, $ref)
    {
        try {
            $method = 'POST';
            $url = self::url().'services/epin/request';

            $headers = self::getHeaders();
            $headers[] = 'Baxi-date: '.now()->toRfc1123String();

            if ( env('APP_ENV') == 'production' ) {
                $agentId = env('CAPRICORN_USER_SECRET_LIVE');
            }else {
                $agentId = env('CAPRICORN_USER_SECRET_TEST');
            }

            $payload = [
                'service_type' => 'spectranet',
                'amount' => $amount,
                'pinValue' => $amount,
                'numberOfPins' => 1,
                'agentReference' => $ref,
                'agentId' => $agentId
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));


            // Decode response body
            $data = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {
                // Create response for success and pending
                if ( $data->code == 200 ) {
                    if ( $data->status == 'success' ) {
                        return [
                            'success' => true,
                            'status' => 'success',
                            'data' => $data
                        ];
                    }
                    elseif ( $data->status == 'pending' ) {
                        return [
                            'success' => true,
                            'status' => 'pending',
                            'data' => $data
                        ];
                    }
                }

                // Create response for other instances
                return [
                    'success'   => false,
                    'status' => 'failed',
                    'message' => isset($data->errors->pinValue->message) ? $data->errors->pinValue->message : $data->message
                ];
            }

            // Return response if code is not 200
            return [
                'success'   => false,
                'status' => 'failed',
                'message' => isset($data->errors->pinValue->message) ? $data->errors->pinValue->message
                    : "Could not purchase spectranet pin at this time, please try again."
            ];
        }
        catch (\Exception $exception ) {
//        return $exception->getMessage();
            return [
                'success'   => false,
                'status' => 'failed',
                'message'   => "Error exception with spectranet pin purchase",
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Retrieve Data Bundles
     *
     * @param $service_type
     * @return array
     */
    public static function retrieveDataBundles( $service_type )
    {
        try {
            $method = 'POST';
            $url = self::url().'services/databundle/bundles';

            $headers = self::getHeaders();
            $headers[] = 'Baxi-date: '.now()->toRfc1123String();

            $payload = [
                'service_type' => $service_type,
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            // Decode response body
            $data = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {
                // Create response for success and pending
                if ( $data->code == 200 ) {
                    return [
                        'success' => true,
                        'data' => $data
                    ];
                }

                // Create response for other instances
                return [
                    'success'   => false,
                    'message' => $data->message
                ];
            }

            // Return response if code is not 200
            return [
                'success'   => false,
                'message' => isset($data->message) ? $data->message : "Error retrieving $service_type data bundles, please try again."
            ];
        }
        catch (\Exception $exception ) {
//        return $exception->getMessage();
            return [
                'success'   => false,
                'message'   => "Error exception retrieving $service_type data bundles",
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Purchase Smile Bundle
     *
     * @param $amount
     * @param $ref
     * @param $dataCode
     * @param $phone
     * @return array
     */
    public static function dataPurchase($amount, $ref, $dataCode, $phone, $service_type)
    {
        try {
            $method = 'POST';
            $url = self::url().'services/databundle/request';

            $headers = self::getHeaders();
            $headers[] = 'Baxi-date: '.now()->toRfc1123String();

            if ( env('APP_ENV') == 'production' ) {
                $agentId = env('CAPRICORN_USER_SECRET_LIVE');
            }else {
                $agentId = env('CAPRICORN_USER_SECRET_TEST');
            }

            $payload = [
                'service_type' => $service_type,
                'amount' => $amount,
                'phone' => $phone,
                'datacode' => $dataCode,
                'agentReference' => $ref,
                'agentId' => $agentId
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info("=======CAPRICORN DATA RESP=============");
            Log::info(json_encode($res));

            // Decode response body
            $data = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {
                // Create response for success and pending
                if ( $data->code == 200 ) {
                    if ( $data->status == 'success' ) {
                        return [
                            'success' => true,
                            'status' => 'success',
                            'data' => $data
                        ];
                    }
                    elseif ( $data->status == 'pending' ) {
                        return [
                            'success' => true,
                            'status' => 'pending',
                            'data' => $data,
                            'message' => $data->message ?? 'Purchase is processing'
                        ];
                    }
                }

                // Create response for other instances
                return [
                    'success' => false,
                    'status' => 'failed',
                    'message' => $data->message
                ];
            }

            // Return response if code is not 200
            return [
                'success'   => false,
                'status' => 'failed',
                'message' => isset($data->message) ? $data->message : "Could not purchase data bundle at this time, please try again."
            ];
        }
        catch (\Exception $exception ) {
//        return $exception->getMessage();
            return [
                'success'   => false,
                'status' => 'failed',
                'message'   => "Error exception with data bundle purchase",
                'error'   => $exception->getMessage()
            ];
        }
    }


    public static function vtuPurchase($amount, $ref, $phone, $service)
    {
        try {
            $method = 'POST';
            $url = self::url().'services/airtime/request';

            $headers = self::getHeaders();
            $headers[] = 'Baxi-date: '.now()->toRfc1123String();

            if ( env('APP_ENV') == 'production' ) {
                $agentId = env('CAPRICORN_USER_SECRET_LIVE');
            }else {
                $agentId = env('CAPRICORN_USER_SECRET_TEST');
            }

            $payload = [
                'service_type' => $service,
                'plan' => 'prepaid',
                'amount' => $amount,
                'phone' => $phone,
                'agentReference' => $ref,
                'agentId' => $agentId
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info("=======CAPRICORN VTU RESP=============");
            Log::info(json_encode($res));

            // Decode response body
            $data = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {
                // Create response for success and pending
                if ( $data->code == 200 ) {
                    if ( $data->status == 'success' ) {
                        return [
                            'success' => true,
                            'status' => 'success',
                            'data' => $data
                        ];
                    }
                    elseif ( $data->status == 'pending' ) {
                        return [
                            'success' => true,
                            'status' => 'pending',
                            'data' => $data
                        ];
                    }
                }

                // Create response for other instances
                return [
                    'success'   => false,
                    'status' => 'failed',
                    'message' => $data->message
                ];
            }

            // Return response if code is not 200
            return [
                'success'   => false,
                'status' => 'failed',
                'message' => isset($data->message) ? $data->message : "Could not purchase airtime at this time, please try again."
            ];
        }
        catch (\Exception $exception ) {
//        return $exception->getMessage();
            return [
                'success'   => false,
                'status' => 'failed',
                'message'   => "Error exception with airtime purchase",
                'error'   => $exception->getMessage()
            ];
        }
    }

    public static function reQuery($reference)
    {
        try {
            $method = 'GET';
            $url = self::url().'superagent/transaction/requery';

            $headers = self::getHeaders();

            $payload = [
                'agentReference' => $reference,
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            // Decode response body
            $data = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {
                // Create response for success and pending
                if ( $data->code == 200 ) {
                    return [
                        'success' => true,
                        'data' => $data
                    ];
                }

                // Create response for other instances
                return [
                    'success'   => false,
                    'message' => $data->message
                ];
            }

            // Return response if code is not 200
            return [
                'success'   => false,
                'message' => isset($data->message) ? $data->message : "Error retrieving, please try again."
            ];
        }
        catch (\Exception $exception ) {
//        return $exception->getMessage();
            return [
                'success'   => false,
                'message'   => "Error exception retrieving",
                'error'   => $exception->getMessage()
            ];
        }
    }


}
