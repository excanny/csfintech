<?php
namespace App\Classes;




use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UBA
{

    /**
     * Get api endpoint
     *
     * @return mixed
     */
    private static function url()
    {
        if (env('APP_ENV') == 'production') {
            return env('UBA_GATEWAY_BASE_URL_LIVE') . env('UBA_GATEWAY_MERCHANT_ID_LIVE') . '/';
        }
        return env('UBA_GATEWAY_BASE_URL_TEST') . env('UBA_GATEWAY_MERCHANT_ID_TEST') . '/';
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

        $encoded_credentials = base64_encode(env('UBA_GATEWAY_MERCHANT_LOGIN_ID_TEST'). ':'. env('UBA_GATEWAY_API_PASSWORD_TEST'));

        $headers[] = "Authorization: Basic ". $encoded_credentials;
        return $headers;
    }

    public static function createSession () {
        try {
            $method = 'POST';
            $url = self::url() . 'session';

            $headers = self::getHeaders();

            $payload = (object)[];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            $data = json_decode($res['RESPONSE_BODY']);

            Log::info("=============payment gateway");
            Log::info(json_encode($res));

            //LiveResponse
            if ($res['HTTP_CODE'] == 200) {

                if ($data->status == 'ok') {
                    return [
                        'success' => true,
                        'message' => 'Session created successfully',
                        'data' => [
                            "session" => $data->session,
                            "version" => $data->version
                        ]
                    ];
                }

                if ($data->result == 'ERROR') {
                    return [
                        'success' => false,
                        'message' => 'Error initializing payment',
                        'error' => $data->error->cause
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Error initializing payment',
                'error' => $data->error->cause ?? null
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => 'Error exception initializing payment',
                'error' => $exception->getMessage()
            ];
        }
    }

    public static function updateSession ($session_id, $amount) {
        try {
            $method = 'PUT';
            $url = self::url() . 'session/'.$session_id;

            $headers = self::getHeaders();


            $payload = (object)[
                "order" => [
                    "amount" => $amount,
                    "currency" => "NGN"
                ]
            ];
            Log::info("=============payment gateway update payload");
            Log::info(json_encode($payload));
            Log::info(json_encode($url));

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            Log::info("=============payment gateway update session");
            Log::info(json_encode($res));

            $data = json_decode($res['RESPONSE_BODY']);

            if ($res['HTTP_CODE'] == 200) {

                if ($data->session && $data->session->updateStatus == 'SUCCESS') {
                    return [
                        'success' => true,
                        'message' => 'Session Updated successfully',
                        'data' => $data->session
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Error Updating payment',
                    'error' => $data->error->cause ?? null
                ];
            }

            return [
                'success' => false,
                'message' => 'Error Updating payment',
                'error' => $data->error->cause ?? null
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => 'Error exception initializing payment',
                'error' => $exception->getMessage()
            ];
        }
    }


    public static function initiateAuthentication ($session_id, $order_id, $transaction_id, $correlation_id) {
        try {
            $method = 'PUT';
            $url = self::url() . 'order/'. $order_id .'/transaction/'. $transaction_id;

            $headers = self::getHeaders();

            $payload = (object)[
                "apiOperation" => "INITIATE_AUTHENTICATION",
                "order" => [
                    "currency" => "NGN"
                ],
                "session" => [
                    "id" => $session_id
                ],
                "authentication" => [
                    "acceptVersions" =>"3DS1,3DS2",
                    "channel" => "PAYER_BROWSER",
                    "purpose" => "PAYMENT_TRANSACTION"
                ],
                "correlationId" => (string) $correlation_id
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            $data = json_decode($res['RESPONSE_BODY']);


            if ($res['HTTP_CODE'] == 201) {

                if ($data->response && $data->response->gatewayRecommendation == 'PROCEED'
                    && $data->result == 'SUCCESS') {
                    return [
                        'success' => true,
                        'message' => 'Authentication initiated successfully',
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Error initiating authentication',
                    'error' => $data->error->explanation ?? null
                ];
            }

            return [
                'success' => false,
                'message' => 'Error initiating authentication',
                'error' => $data->error->explanation ?? null
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => 'Error initiating authentication',
                'error' => $exception->getMessage()
            ];
        }
    }


    public static function authenticatePayer ($transaction, $browser_details) {
        try {
            $method = 'PUT';
            $url = self::url() . 'order/'. $transaction->external_reference .'/transaction/'. $transaction->reference;

            $headers = self::getHeaders();

            $payload = (object)[
                "apiOperation" => "AUTHENTICATE_PAYER",
                "order" => [
                    "amount" => $transaction->amount,
                    "currency" => "NGN"
                ],
                "session" => [
                    "id" => $transaction->session_id
                ],
                "authentication" => [
                    "redirectResponseUrl" => env('PAYMENT_URL').'/'.$transaction->access_code.'/close-payment'
                ],
                "correlationId" => (string) $transaction->id,
                "device" => [
                    "browser" => $transaction->browser,
                    "ipAddress" => $transaction->ip_address,
                    "browserDetails" => $browser_details
                ]
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            $data = json_decode($res['RESPONSE_BODY']);

            if ($res['HTTP_CODE'] == 201) {

                if ($data->response && $data->response->gatewayRecommendation == 'PROCEED'
                    && $data->result == 'PENDING') {
                    return [
                        'success' => true,
                        'message' => 'Authentication payer operation in progress',
                        'data' => [
                            "redirectHtml" => $data->authentication->redirectHtml
                        ]
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Error Authenticating payer',
                    'error' => $data->error->explanation ?? null
                ];
            }

            return [
                'success' => false,
                'message' => 'Error Authenticating payer',
                'error' => $data->error->explanation ?? null
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => 'Error Authenticating payer',
                'error' => $exception->getMessage()
            ];
        }
    }


    public static function pay ($transaction) {
        try {
            $method = 'PUT';
            $url = self::url() . 'order/'. $transaction->external_reference .'/transaction/'. $transaction->pay_reference;

            $headers = self::getHeaders();

            $payload = (object)[
                "apiOperation" => "PAY",
                "order" => [
                    "amount" => $transaction->amount,
                    "currency" => "NGN",
                    "reference" => $transaction->external_reference
                ],
                "transaction" => [
                    "reference" => $transaction->pay_reference
                ],
                "session" => [
                    "id" => $transaction->session_id
                ]
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            $data = json_decode($res['RESPONSE_BODY']);

            if ($res['HTTP_CODE'] == 201) {

                if ($data->response && $data->response->gatewayCode == 'APPROVED'
                    && $data->response->acquirerMessage == 'Approved' && $data->result == 'SUCCESS') {
                    return [
                        'success' => true,
                        'message' => 'Payment Successful'
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Error With Payment',
                    'error' => $data->error->explanation ?? null
                ];
            }

            return [
                'success' => false,
                'message' => 'Error With Payment',
                'error' => $data->error->explanation ?? null
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => 'Error Exception With Payment',
                'error' => $exception->getMessage()
            ];
        }
    }

}
