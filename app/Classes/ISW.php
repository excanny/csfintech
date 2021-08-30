<?php
namespace App\Classes;


use App\IswBank;
use App\IswLoanProvider;
use App\Model\Biller;
use App\Repository\InterSwitchExtended;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class ISW
{

    public static $signatureMethod = 'SHA1';
    public static $isCashOut = false;
    public static $transferCodeSucess = ['90000', '90010', '90011', '90016', '90080', '90009'];


    /**
     * Get resource
     *
     * @return string
     */
    public static function url() : string
    {
        if ( App::environment() == 'local' ) {
            return env('ISW_URL_TEST');
        }

        return env('ISW_URL_LIVE');
    }

    /**
     * Get resource
     *
     * @return string
     */
    public static function credit_url() : string
    {
        if ( App::environment() == 'local' ) {
            return env('ISW_CREDIT_SCORE_TEST');
        }

        return env('ISW_CREDIT_SCORE_LIVE');
    }

    public static function loan_lending_url_v1() : string
    {
        if ( App::environment() === 'local' ) {
            return env('ISW_URL_LOAN_TEST');
        }

        return env('ISW_URL_LOAN_LIVE');
    }

    public static function loan_lending_url_v2() : string
    {
        if ( App::environment() === 'local' ) {
            return env('ISW_URL_LOAN_TEST_V2');
        }

        return env('ISW_URL_LOAN_LIVE_V2');
    }

    public static function channelCode() : string
    {
        if ( App::environment() === 'local' ) {
            return env('CHANNEL_CODE_TEST');
        }

        return env('CHANNEL_CODE_LIVE');
    }

    /**
     * Get guzzle client
     *
     * @return Client
     */
    public static function client() : Client
    {
        return new Client(['verify' => false]);
    }

    /**
     * Get resource
     *
     * @return string
     */
    public static function url_v1() : string
    {
        if ( App::environment() == 'local' ) {
            return env('ISW_URL1_TEST');
        }

        return env('ISW_URL1_LIVE');
    }

    /**
     * Get client ID
     *
     * @return string
     */
    public static function clientID() : string
    {
        if ( self::$isCashOut ) {
            return env('ISW_CLIENT_ID_CASHOUT');
        }

        if ( App::environment() == 'local' ) {
            return env('ISW_CLIENT_ID_TEST');
        }

        return env('ISW_CLIENT_ID_LIVE');
    }

    /**
     * Get client Secret
     *
     * @return string
     */
    public static function clientSecret() : string
    {
        if ( self::$isCashOut ) {
            return env('ISW_CLIENT_SECRET_CASHOUT');
        }

        if ( App::environment() == 'local' ) {
            return env('ISW_CLIENT_SECRET_TEST');
        }

        return env('ISW_CLIENT_SECRET_LIVE');
    }

    /**
     * Get client Secret
     *
     * @return string
     */
    public static function terminalID() : string
    {
        if ( self::$isCashOut ) {
            return env('ISW_TERMINAL_ID_CASHOUT');
        }

        if ( App::environment() == 'local' ) {
            return env('ISW_TERMINAL_ID_TEST');
        }

        return env('ISW_TERMINAL_ID_LIVE');
    }

    /**
     * Get Entity Code
     *
     * @return string
     */
    public static function entityCode() : string
    {
        if ( App::environment() == 'local' ) {
            return env('ISW_ENTITY_CODE_TEST');
        }

        return env('ISW_ENTITY_CODE');
    }

    /**
     * Get Transfer Prefix Code
     *
     * @return string
     */
    public static function transferPrefix() : string
    {
        if ( App::environment() == 'local' ) {
            return env('ISW_TRANSFER_CODE_PREFIX_TEST');
        }

        return env('ISW_TRANSFER_CODE_PREFIX');
    }

    /**
     * Get Nonce
     *
     * @return string
     * @throws \Exception
     */
    public static function nonce() : string
    {
        $uuid = (string) Uuid::uuid4();
        return str_replace("-", "", $uuid);
    }


    /**
     * Get signature
     *
     * @param string $method
     * @param $url
     * @return string
     * @throws \Exception
     */
    public static function signature(string $method, $url, $nonce) : string
    {
        $client_id = self::clientID();
        $client_secret = self::clientSecret();
        $timestamp = time();

        $cipher = "$method&$url&$timestamp&$nonce&$client_id&$client_secret";
        $signature = hash(self::$signatureMethod, $cipher, true);
        $signature = base64_encode($signature);

        return $signature;
    }

    /**
     * Get ISW authorization
     *
     * @return string
     */
    public static function authorization(): string
    {
        return 'InterswitchAuth ' . base64_encode(self::clientID());
    }

    /**
     * Create new InterSwith Instance
     * for all interSwitch loan calls.
     * @return InterSwitchExtended
     */
    public static function interSwitchInstance()
    {
        if (App::environment() === 'local') {
            $environment = 'DEVELOPMENT';
            $ID = env('ISW_CLIENT_ID_TEST');
            $SECRET = env('ISW_CLIENT_SECRET_TEST');
        }else {
            $ID = env('LOAN_CLIENT_ID');
            $SECRET = env('LOAN_CLIENT_SECRET');
            $environment = 'PRODUCTION';
        }

        return new InterSwitchExtended($ID, $SECRET, $environment);
    }

    /**
     * Get mac
     *
     * @param $amount
     * @return string
     */
    public static function mac($amount): string
    {
        $data = $amount . "566CA$amount" . "566ACNG";

        return strtoupper(hash('SHA512', $data));
    }


    /**
     * Verify account number
     *
     * @param $bank_code
     * @param $account_number
     * @return array
     */
    public static function nameEnquiry($bank_code, $account_number)
    {
        try {
            $method = 'GET';
            $url = ISW::url_v1() . '/nameenquiry/banks/accounts/names';
            $encodedUrl = urlencode($url);

            $headers = self::getHeaders($method, $encodedUrl);
            $headers[] = "bankCode: $bank_code";
            $headers[] = "accountId: $account_number";

            $res = HttpClient::send($headers, $method, $url, null);

            if ( $res['HTTP_CODE'] == 200 ) {
                return [
                    'success' => true,
                    'account_name' => json_decode($res['RESPONSE_BODY'])->accountName
                ];
            }

            // error in transaction.
            $errorResponse = json_decode($res['RESPONSE_BODY']);

            return [
                'success'   => false,
                'message' => $errorResponse->error->message
            ];
        }
        catch (TransferException $exception ) {
            return [
                'success'   => false,
                'message'   => 'Could not verify account',
                'error'   => $exception->getMessage()
            ];
        }
    }

    public static function getToken()
    {
        try {
            $method = 'POST';
            $nonce = ISW::nonce();
            $url = 'https://sandbox.interswitchng.com/api/v1/payments/token';


            $authorization = ISW::authorization();

            $headers = [
                'Content-Type'      => 'application/x-www-form-urlencoded',
                'Authorization'     => $authorization,
            ];

            $res = self::client()->request($method, $url, ['headers'   => $headers,
                'json' => [
                    'scope' => 'profile',
                    'grant_type' => 'client_credentials'
                ]]);

            if ( $res->getStatusCode() == 200 ) {
                return [
                    'success'   => true,
                    'a'  => json_decode($res->getBody()->getContents(), true)['accountName']
                ];
            }

            return [
                'success'   => false,
                'account_name'  => "Could not verify account"
            ];
        }
        catch (TransferException $exception ) {
//            $content = $exception->getResponse()->getBody()->getContents();
//            if ( (Str::contains($content, '{"errors":')) ) {
//                dd(json_decode($content, true));
//            }
            return [
                'success'   => false,
//                'message'   => 'Could not verify account'
                'message'   => is_null($exception->getResponse() ) ? $exception->getMessage() : $exception->getResponse()->getBody()->getContents()
            ];
        }
    }

    public static function getHeaders($method, $encodedUrl)
    {
        $nonce = self::nonce();
        $timestamp = now()->getTimestamp();
        $signature = self::signature($method, $encodedUrl, $nonce);
        $authorization = self::authorization();
        $signatureMethod = self::$signatureMethod;
        $terminalID = self::terminalID();
        return [
            'Content-Type: application/json',
            "Authorization: $authorization",
            "Signature: $signature",
            "Timestamp: $timestamp",
            "Nonce: $nonce",
            "SignatureMethod: $signatureMethod",
            "TerminalID: $terminalID"
        ];
    }

    /**
     * Make a transfer
     *
     * @param $amount
     * @param $account_number
     * @param $account_name
     * @param $account_type
     * @param $bank_code
     * @param null $naration
     * @return array
     */
    public static function transfer($amount, $account_number, $account_name, $account_type, $bank_code, $reference, $naration = null)
    {
        try {
            $method = 'POST';
            $url = ISW::url() . '/payments/transfers';
            $encodedUrl = urlencode($url);

            $headers = self::getHeaders($method, $encodedUrl);

            $names = explode(' ', $account_name);

            $payload = [
                'mac' => self::mac($amount),
                'beneficiary' => [
                    'lastname' => $names[sizeof($names) - 1],
                    'othernames' => $names[0]
                ],
                'initiatingEntityCode' => self::entityCode(),
                'initiation' => [
                    'amount' => (string) $amount,
                    'channel' => '7',
                    'currencyCode' => '566',
                    'paymentMethodCode' => 'CA'
                ],
                'sender' => [
                    'email' => 'info@kolomoni.ng',
                    'lastname' => 'CreditAssist'. $naration !== null ? ' | ' . $naration : '',
                    'othernames' => 'Ltd',
                    'phone' => '08165873186'
                ],
                'termination' => [
                    'accountReceivable' => [
                        'accountNumber' => $account_number,
                        'accountType' => $account_type
                    ],
                    'amount' => $amount,
                    'countryCode' => 'NG',
                    'currencyCode' => '566',
                    'entityCode' => $bank_code,
                    'paymentMethodCode' => 'AC'
                ],
                'transferCode' => $reference
            ];


            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            if ( $res['HTTP_CODE'] == 200 ) {
                $data = json_decode($res['RESPONSE_BODY']);

                if ( $data->responseCodeGrouping == 'SUCCESS' ) {
                    $data->responseCodeGrouping = 'SUCCESSFUL';
                }

                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            // error in transaction.
            $errorResponse = json_decode($res['RESPONSE_BODY']);

            return [
                'success'   => false,
                'data' => $errorResponse->error
            ];
        }
        catch (TransferException $exception ) {
            return [
                'success'   => false,
                'message'   => 'Server with transfer',
                'error'     => $exception->getMessage()
            ];
        }
    }


    /**
     * Bill payment advice
     *
     * @param $amount
     * @param $paymentCode
     * @param $customerId
     * @param $email
     * @return array
     */
    public static function billPaymentAdvice($amount, $paymentCode, $customerId, $email)
    {
        try {
            $method = 'POST';
            $url = self::url() . '/payments/advices';
            $encodedUrl = urlencode($url);
            $timestamp = time();

            $headers = self::getHeaders($method, $encodedUrl);

            $payload = [
                'TerminalId'        => self::terminalID(),
                'paymentCode'       => $paymentCode,
                'customerId'        => $customerId,
                'customerMobile'    => $customerId,
                'customerEmail'     => $email,
                'amount'            => $amount,
                'requestReference'  => self::transferPrefix() . $timestamp
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            $data = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {

                if ( $data->responseCodeGrouping == 'SUCCESS' ) {
                    $data->responseCodeGrouping = 'SUCCESSFUL';
                }

                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            // error in transaction.
            return [
                'success'   => false,
                'error' => $data->error
            ];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception with bill payment advice',
                'error'   => $exception->getMessage()
            ];
        }
    }


    public static function validateCustomer($customerId, $paymentCode)
    {
        try {
            $method = 'POST';
            $url = self::url() . '/customers/validations';
            $encodedUrl = urlencode($url);

            $headers = self::getHeaders($method, $encodedUrl);

            $payload = [
                'customers' => [
                    [
                        'customerId' => $customerId,
                        'paymentCode' => $paymentCode
                    ]
                ]
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));
            $data = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {
                $customer = $data->Customers[0];

                if ( $customer->responseCode == '90000' ) {
                    return [
                        'success' => true,
                        'data' => $customer
                    ];
                }

                return [
                    'success' => false,
                    'message' => $customer->responseDescription
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
     * CashOut pay inquiry
     *
     * @param $amount
     * @return array
     */
    public static function payInquiry($amount)
    {
        try {
            self::$isCashOut = true;

            $method = 'POST';
            $url = self::url() . '/transactions/info@kolomoni.ng/inquiry';
            $encodedUrl = urlencode($url);

            $headers = self::getHeaders($method, $encodedUrl);

            $pageFlowValues = 'BankId:'.env('ISW_BANK_ID').
                '|DestinationAccountNumber:' . env('ISW_ACCOUNT_NUMBER') .
                "|DestinationAccountType:00|Amount:$amount|ReciepientPhone:" .
                env('PHONE') . 'ReciepientName:' . env('ISW_RECIPIENT_NAME');

            $payload = [
                'paymentCode'       => env('ISW_TRANSFER_PAYMENT_CODE'),
                'customerId'        => env('ISW_CUSTOMER_ID'),
                'customerMobile'    => env('PHONE'),
                'customerEmail'     => '',
                'amount'            => $amount,
                'pageFlowValues'    => $pageFlowValues
            ];


            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            $responseData = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {
                 return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            // error in transaction.;
            return [
                'success'   => false,
                'data' => $responseData->error
            ];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => "Error exception with payInquiry",
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Cashout transaction
     *
     * @param $reference
     * @param $amount
     * @param $pinData
     * @param $secureData
     * @param $cardBin
     * @param $phone
     * @return array
     * @throws \Exception
     */
    public static function cashOut($reference, $amount, $pinData, $secureData, $cardBin, $phone)
    {
        try {
            self::$isCashOut = true;

            $method = 'POST';
            $url = self::url() . '/transactions';
            $encodedUrl = urlencode($url);

            $headers = self::getHeaders($method, $encodedUrl);

            $payload = [
                'amount'        => $amount,
                'pinData'       => $pinData,
                'secureData'    => $secureData,
                'msisdn'        => $phone,
                'transactionRef'=> $reference,
                'cardBin'       => $cardBin
            ];

            $res = HttpClient::send($headers, $method, $url, json_encode($payload));

            $responseData = json_decode($res['RESPONSE_BODY']);

            if ( $res['HTTP_CODE'] == 200 ) {

                if ( $responseData->ResponseCodeGrouping == 'SUCCESS' ) {
                    $responseData->ResponseCodeGrouping = 'SUCCESSFUL';
                }

                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            // error in transaction.;
            return [
                'success'   => false,
                'data' => $responseData->error
            ];
        }
        catch (TransferException $exception) {
//            $content = $exception->getResponse()->getBody()->getContents();
//            if ( (Str::contains($content, '{"errors":')) ) {
//                dd(json_decode($content, true));
//            }
//            Log::debug($exception->getMessage());
            return [
                'success'   => false,
                'message'   => "Cashout error"
            ];
        }
    }


    public static function getTransaction($reference, $isCashout = false)
    {
        try {
            self::$isCashOut = $isCashout;

            $method = 'GET';
            $url = self::url() . "/transactions?requestreference=$reference";
            $encodedUrl = urlencode($url);
            $headers = self::getHeaders($method, $encodedUrl);

            $res = HttpClient::send($headers, $method, $url, null);
            if ( $res['HTTP_CODE'] == 200 ) {
                return [
                    'success' => true,
                    'data' => json_decode($res['RESPONSE_BODY'])
                ];
            }

            // error in transaction.
            $errorResponse = json_decode($res['RESPONSE_BODY']);

            return [
                'success'   => false,
                'message'  => $errorResponse->error->message,
                'error'  => $errorResponse->error,
            ];
        }
        catch (\Exception $exception) {

            return [
                'success'   => false,
                'message'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Get list of loan providers for
     * a particular channel using channel code.
     * @param $channelCode
     * @return array
     */
    public static function getProviders($channelCode = null)
    {

        $response = [];

        try {

            if (is_null($channelCode)) {
                $channelCode = ISW::channelCode();
            }

            // Compose url
            $url = self::loan_lending_url_v1() . "/offers/providers?channelCode=$channelCode";
            $authorization = self::interSwitchInstance()->getAccessToken();

            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => 'Bearer ' . $authorization
            ];

            $res = self::client()->request('GET', $url, [
                'headers'   => $headers,
            ]);

            if ( $res->getStatusCode() == 200 ) {
                $data = json_decode($res->getBody()->getContents(), true);
                if (isset($data['providers']) && count($data['providers']) > 0) {
                    $response = $data;
                }
            }

        }

        catch (\Exception $exception ) {
            $response =  [ 'success'   => false, 'message'   => $exception->getMessage()];
        }

        //return response of processing
        return $response;
    }


    /**
     * Encrypt Card Details
     * @param $payload
     * @return array|string
     */
    public static function getAuthData($payload)
    {
        try {

            $pan = $payload['card_number'];
            $cvv = $payload['cvv'];
            $expiryDate = $payload['expiry_date'];
            $pin = $payload['pin'];

           return self::interSwitchInstance()->getAuthData( $pan, $expiryDate, $cvv, $pin );
        }

        catch (\Exception $exception ) {

            return [
                'success'   => false,
                'message'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Update database records with Loan Providers
     * @param null $channelCode
     * @return string
     */
    public static function updateLoanProviders( $channelCode = null)
    {

        if (is_null($channelCode)) {
            $channelCode = ISW::channelCode();
        }

        // Get list of providers
        $providersResponse = self::getProviders();

        if (isset($providersResponse['providers']) && count($providersResponse['providers']) > 0) {
            //wipe existing provider records and insert new records
            IswLoanProvider::query()->truncate();

            DB::transaction(function () use($providersResponse, $channelCode) {
                foreach ($providersResponse['providers'] as $provider) {
                    IswLoanProvider::updateOrCreate(
                        [
                            'p_id' => $provider['id'],
                            'code' => $provider['code'],
                            'channel_code' => $channelCode,
                            'name' => $provider['name'],
                            'domain_code' => $provider['domainCode'],
                            'description' => $provider['description'],
                            'clientId' => $provider['clientId'],
                            'displayOrder' => $provider['displayOrder'],
                            'providerType' => $provider['providerType'],
                            'settleDirectPayment' => $provider['settleDirectPayment'],
                            'insecure' => $provider['insecure'],
                            'active' => $provider['active'] ? 1 : 0,
                            'auditableId' => $provider['auditableId']
                        ]
                    );
                }
            });

            $message  =  "Loan providers updated successfully using channel code: {$channelCode}";
            // Log success response
            Log::info($message);

            return $message;
        }


        return "There was an error getting and updating loan providers. Please refresh to try again";
    }


    /**
     * Get loan offers from a particular provider
     * for customer. Takes array parameter
     * CustomerId
     * channelCode
     * providerCode
     * Amount : optional
     * @param $customerId
     * @param $channelCode
     * @param $providerCode
     * @param null $amount
     * @param $serviceType
     * @return array
     */
    public static function getLoanOffers($customerId, $channelCode, $providerCode, $amount = null, $serviceType = 'MONEY')
    {

        $payload = [
            'customerId' => $customerId,
            'channelCode' => $channelCode,
            'providerCode' => $providerCode,
            'amount' => $amount,
            'serviceType' => $serviceType
        ];

        $payloadUrl = '';
        foreach ($payload as $key => $value){
            if (!is_null($payload[$key])) {
                $payloadUrl .= strlen($payloadUrl) === 0 ? "?$key=$value" : "&$key=$value";
            }
        }

        try {

            $url = self::loan_lending_url_v1() . "/offers$payloadUrl";
            $authorization = self::interSwitchInstance()->getAccessToken();

            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => 'Bearer ' . $authorization
            ];


            $res = self::client()->request('GET', $url, [
                'headers'   => $headers,
            ]);

            if ( $res->getStatusCode() == 200 ) {
                return json_decode($res->getBody()->getContents(), true);
            }

            return [];
        }
        catch (\Exception $exception ) {

            //return [];
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }


    /**
     * Get loan credit methods
     * and debit methods that have been saved against
     * a customer id
     * @param $customerId
     * @param $channelCode
     * @return array
     */
    public static function getPaymentMethod($customerId, $channelCode)
    {
        try {
            $url = self::loan_lending_url_v1() . "/users/$customerId/payment-methods?channelCode=$channelCode";
            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => self::interSwitchInstance()->getAccessToken()
            ];


            $res = self::client()->request('GET', $url, [
                'headers'   => $headers,
            ]);

            if ( $res->getStatusCode() == 200 ) {
                $data = json_decode($res->getBody()->getContents(), true);
                return [
                    'success'   => true,
                    'data'  => $data
                ];
            }

            return [
                'success'   => false,
                'message'  => "getting payment methods could not be completed"
            ];
        }
        catch (\Exception $exception ) {

            return [
                'success'   => false,
                'message'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Get Pre-Authorization key to submit customer details
     * which might include card and other personal information
     * @param $customerId
     * @return array|mixed
     */
    public static function getPreAuthorization($customerId)
    {
        try {
            $payload = [
                'customerId' => $customerId,
                'channelCode' => self::channelCode()
            ];

            $method = 'POST';
            $nonce = ISW::nonce();
            $url = self::loan_lending_url_v1() . "/pre-authorization";
            $encodedUrl = urlencode($url);
            $timestamp = time();
            $signature = ISW::signature($method, $encodedUrl, $nonce);
            $authorization = 'Bearer ' . self::interSwitchInstance()->getAccessToken();

            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => $authorization,
                'Signature'         => $signature,
                'Timestamp'         => $timestamp,
                'Nonce'             => $nonce,
                'SignatureMethod'   => ISW::$signatureMethod,
                'TerminalID'        => ISW::terminalID(),
            ];


            $res = self::client()->request($method, $url, [
                'headers'   => $headers,
                'json'      => $payload
            ]);

            if ( $res->getStatusCode() == 200 ) {
                return json_decode($res->getBody()->getContents(), true);
            }

            return [];

        } catch (\Exception $exception) {

            return [
                'success' => false,
                'message' => $exception->getMessage() . $exception->getLine()
            ];
        }
    }

    /**
     * Accept loan offer
     *
     * @param $offerId
     * @param $payload
     * @param $preAuthorization
     * @return array
     */
    public static function acceptOffer($offerId, $payload, $preAuthorization = null)
    {
        try {
            $method = 'POST';
            $nonce = ISW::nonce();
            $url = self::loan_lending_url_v1() . "/offers/$offerId/accept";
            $encodedUrl = urlencode($url);
            $timestamp = time();
            $signature = ISW::signature($method, $encodedUrl, $nonce);
            $authorization = 'Bearer ' . self::interSwitchInstance()->getAccessToken();
            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => $authorization,
                'Signature'         => $signature,
                'Timestamp'         => $timestamp,
                'Nonce'             => $nonce,
                'SignatureMethod'   => ISW::$signatureMethod,
                'TerminalID'        => ISW::terminalID(),
            ];

            if (!is_null($preAuthorization)) {
                $headers['Preauthorization'] = $preAuthorization;
            }

            $res = self::client()->request($method, $url, [
                'headers'   => $headers,
                'json'      => $payload
            ]);

            if ( $res->getStatusCode() == 200 ) {
                return json_decode($res->getBody()->getContents(), true);
            }

            return [];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => $exception->getMessage()
            ];
        }
    }


    /**
     * @param $customerId
     * @param $startDate
     * format: YYYY-MM-DD
     * @param $endDate
     * format: YYYY-MM-DD
     * @param $channelCode
     * @param $pageNumber
     * @param $pageSize
     * @return array
     */
    public static function getLoanHistory($customerId, $startDate, $endDate, $channelCode, $pageNumber, $pageSize)
    {
        try {
            $url = self::loan_lending_url_v1() . "/loans/$customerId?startDate=$startDate&endDate=$endDate&pageNumber=$pageNumber&pageSize=$pageSize&channelCode=$channelCode";

            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => self::interSwitchInstance()->getAccessToken()
            ];


            $res = self::client()->request('GET', $url, [
                'headers'   => $headers,
            ]);

            if ( $res->getStatusCode() == 200 ) {
                $data = json_decode($res->getBody()->getContents(), true);
                return [
                    'success'   => true,
                    'data'  => $data
                ];
            }

            return [
                'success'   => false,
                'message'  => "loan history request could not be completed"
            ];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => $exception->getMessage()
            ];
        }
    }

    /**
     * Get customer status
     * @param $customerId
     * @param $channelCode
     * @param bool $strict
     * @return array
     */
    public static function getCustomerStatus($customerId, $channelCode, $strict = false)
    {
        try {
            $url = self::loan_lending_url_v1() . "/users/$customerId/status?channelCode=$channelCode";

            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => self::interSwitchInstance()->getAccessToken()
            ];


            $res = self::client()->request('GET', $url, [
                'headers'   => $headers,
            ]);

            if ( $res->getStatusCode() == 200 ) {
                $data = json_decode($res->getBody()->getContents(), true);
                return [
                    'success'   => true,
                    'data'  => $data
                ];
            }

            return [
                'success'   => false,
                'message'  => "customer status request could not be completed"
            ];
        }
        catch (\Exception $exception ) {

            return [
                'success'   => false,
                'message'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Get status of a particular loan
     * @param $customerId
     * @param $loanId
     * @param $providerCode
     * @param $channelCode
     * @return array
     */
    public static function getCustomerLoanStatus($customerId, $loanId, $providerCode, $channelCode)
    {
        try {
            $url = self::loan_lending_url_v1() . "/loans/$loanId/status?customerId=$customerId&providerCode=$providerCode&channelCode=$channelCode";

            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => self::interSwitchInstance()->getAccessToken()
            ];


            $res = self::client()->request('GET', $url, [
                'headers'   => $headers,
            ]);

            if ( $res->getStatusCode() == 200 ) {
                $data = json_decode($res->getBody()->getContents(), true);
                return [
                    'success'   => true,
                    'data'  => $data
                ];
            }

            return [
                'success'   => false,
                'message'  => "Loan status request could not be completed"
            ];
        }
        catch (\Exception $exception ) {

            return [
                'success'   => false,
                'message'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Get token with validation
     * @param $customerId
     * @param $authData
     * @param $providerCode
     * @param $transactionRef
     * @return array
     */
    public static function getTokenWithValidation( $customerId, $authData, $providerCode, $transactionRef)
    {
        try {
            $method = 'POST';
            $nonce = ISW::nonce();
            $url = self::loan_lending_url_v1() . "/payments/channel/token";
            $encodedUrl = urlencode($url);
            $timestamp = time();
            $signature = ISW::signature($method, $encodedUrl, $nonce);
            $authorization = self::interSwitchInstance()->getAccessToken();
            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => $authorization,
                'Signature'         => $signature,
                'Timestamp'         => $timestamp,
                'Nonce'             => $nonce,
                'SignatureMethod'   => ISW::$signatureMethod,
                'TerminalID'        => ISW::terminalID(),
            ];

            $payload = [
                'customerId' => $customerId,
                'authData' => $authData,
                'providerCode' => $providerCode,
                'transactionRef' => $transactionRef
            ];

            $res = self::client()->request($method, $url, [
                'headers'   => $headers,
                'json'      => $payload
            ]);

            if ( $res->getStatusCode() == 200 ) {
                $data = json_decode($res->getBody()->getContents(), true);
                if ( $data['responseCodeGrouping'] == 'SUCCESS' ) {
                    $data['responseCodeGrouping'] = 'SUCCESSFUL';
                }

                return [
                    'success'   => true,
                    'data'  => $data
                ];
            }

            return [
                'success'   => false,
                'message'  => "Get token with validation could not be completed"
            ];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Get token with validation
     * @param $customerId
     * @param $authData
     * @param $providerCode
     * @param $transactionRef
     * @return array
     */
    public static function getTokenWithOtp( $customerId, $authData, $providerCode, $otp, $transactionRef = null)
    {
        try {
            $method = 'POST';
            $nonce = ISW::nonce();
            $url = self::loan_lending_url_v1() . "/payments/channel/token";
            $encodedUrl = urlencode($url);
            $timestamp = time();
            $signature = ISW::signature($method, $encodedUrl, $nonce);
            $authorization = self::authorization();
            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => $authorization,
                'Signature'         => $signature,
                'Timestamp'         => $timestamp,
                'Nonce'             => $nonce,
                'SignatureMethod'   => ISW::$signatureMethod,
                'TerminalID'        => ISW::terminalID(),
            ];

            $payload = [
                'customerId' => $customerId,
                'authData' => $authData,
                'providerCode' => $providerCode,
                'otp' => $otp,
            ];

            if (!is_null($transactionRef)) {
                $payload['transactionRef'] = $transactionRef;
            }

            $res = self::client()->request($method, $url, [
                'headers'   => $headers,
                'json'      => $payload
            ]);

            if ( $res->getStatusCode() == 200 ) {
                $data = json_decode($res->getBody()->getContents(), true);
                if ( $data['responseCodeGrouping'] == 'SUCCESS' ) {
                    $data['responseCodeGrouping'] = 'SUCCESSFUL';
                }

                return [
                    'success'   => true,
                    'data'  => $data
                ];
            }

            return [
                'success'   => false,
                'message'  => "Get token with otp could not be completed"
            ];
        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Get customer credit score
     * This is not needed for this integration,
     * But it's kept for reference purpose
     * @param $phone
     * @return array
     */
    public static function getCreditScore( $phone )
    {
        try {
            $url = ISW::credit_url() . "/credit-score?msisdn=$phone";

            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => self::authorization()
            ];


            $res = self::client()->request('GET', $url, [
                'headers'   => $headers,
            ]);

            if ( $res->getStatusCode() == 200 ) {
                $data = json_decode($res->getBody()->getContents(), true);
                return [
                    'success'   => true,
                    'data'  => $data
                ];
            }

            return [
                'success'   => false,
                'message'  => "customer status request could not be completed"
            ];
        }
        catch (TransferException | \Exception $exception ) {
            dd($exception->getMessage());
            return [
                'success'   => false,
                'message'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Get customer credit score history
     * This is not needed for this integration,
     * But it's kept for reference purpose
     * @param $phone
     * @return array
     */
    public static function getCreditScoreHistory( $phone )
    {
        try {
            $url = ISW::credit_url() . "/credit-score/history?msisdn=$phone";

            $headers = [
                'Content-Type'      => 'application/json',
                'Authorization'     => self::authorization()
            ];


            $res = self::client()->request('GET', $url, [
                'headers'   => $headers,
            ]);

            if ( $res->getStatusCode() == 200 ) {
                $data = json_decode($res->getBody()->getContents(), true);
                return [
                    'success'   => true,
                    'data'  => $data
                ];
            }

            return [
                'success'   => false,
                'message'  => "customer status request could not be completed"
            ];
        }
        catch (\Exception $exception ) {
//            dd($exception->getMessage());
            return [
                'success'   => false,
                'message'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Get a list of all ISW billers.
     *
     * @return array
     * @throws \Exception
     */
    public static function getBillers()
    {
        $method = 'GET';
        $nonce = ISW::nonce();
        $url = ISW::url() . '/billers';
        $encodedUrl = urlencode($url);
        $timestamp = time();
        $signature = ISW::signature($method, $encodedUrl, $nonce);


        $authorization = ISW::authorization();

        $headers = [
            'Content-Type'      => 'application/json',
            'Authorization'     => $authorization,
            'Signature'         => $signature,
            'Timestamp'         => $timestamp,
            'Nonce'             => $nonce,
            'SignatureMethod'   => ISW::$signatureMethod,
            'TerminalID'        => ISW::terminalID()
        ];

        $res = self::client()->request($method, $url, [
            'headers' => $headers
        ]);

        if ( $res->getStatusCode() == 200 ) {
            $data = json_decode($res->getBody()->getContents(), true);


            return ['success' => true, 'data' => $data];
        }

        return ['success' => false, 'message' => 'Error getting billers'];
    }

    /**
     * Get paymentitems of biller
     *
     * @param $biller_id
     * @return array
     * @throws \Exception
     */
    public static function getBiller( $biller_id )
    {
        $method = 'GET';
        $nonce = ISW::nonce();
        $url = ISW::url() . "/billers/$biller_id/paymentitems";
        $encodedUrl = urlencode($url);
        $timestamp = time();
        $signature = ISW::signature($method, $encodedUrl, $nonce);


        $authorization = ISW::authorization();

        $headers = [
            'Content-Type'      => 'application/json',
            'Authorization'     => $authorization,
            'Signature'         => $signature,
            'Timestamp'         => $timestamp,
            'Nonce'             => $nonce,
            'SignatureMethod'   => ISW::$signatureMethod,
            'TerminalID'        => ISW::terminalID()
        ];

        $res = self::client()->request($method, $url, [
            'headers' => $headers
        ]);

        if ( $res->getStatusCode() == 200 ) {
            $data = json_decode($res->getBody()->getContents(), true);


            return ['success' => true, 'data' => $data];
        }

        return ['success' => false, 'message' => 'Error getting billers'];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getBillerCategories()
    {
        $method = 'GET';
        $nonce = ISW::nonce();
        $url = ISW::url() . '/categorys';
        $encodedUrl = urlencode($url);
        $timestamp = time();
        $signature = ISW::signature($method, $encodedUrl, $nonce);


        $authorization = ISW::authorization();

        $headers = [
            'Content-Type'      => 'application/json',
            'Authorization'     => $authorization,
            'Signature'         => $signature,
            'Timestamp'         => $timestamp,
            'Nonce'             => $nonce,
            'SignatureMethod'   => ISW::$signatureMethod,
            'TerminalID'        => ISW::terminalID()
        ];

        $res = self::client()->request($method, $url, [
            'headers' => $headers
        ]);

        if ( $res->getStatusCode() == 200 ) {
            $data = json_decode($res->getBody()->getContents(), true);


            return ['success' => true, 'data' => $data];
        }

        return ['success' => false, 'message' => 'Error getting categories'];
    }

    /**
     * Get category
     *
     * @param $category_id
     * @return array
     * @throws \Exception
     */
    public static function getBillersByCategory( $category_id )
    {
        $method = 'GET';
        $nonce = ISW::nonce();
        $url = ISW::url() . "/categorys/$category_id/billers";
        $encodedUrl = urlencode($url);
        $timestamp = time();
        $signature = ISW::signature($method, $encodedUrl, $nonce);


        $authorization = ISW::authorization();

        $headers = [
            'Content-Type'      => 'application/json',
            'Authorization'     => $authorization,
            'Signature'         => $signature,
            'Timestamp'         => $timestamp,
            'Nonce'             => $nonce,
            'SignatureMethod'   => ISW::$signatureMethod,
            'TerminalID'        => ISW::terminalID()
        ];

        $res = self::client()->request($method, $url, [
            'headers' => $headers
        ]);

        if ( $res->getStatusCode() == 200 ) {
            $data = json_decode($res->getBody()->getContents(), true);


            return ['success' => true, 'data' => $data];
        }

        return ['success' => false, 'message' => 'Error getting billers'];
    }

    /**
     * Update ISW Banks
     *
     * @throws \Exception
     */
    public static function updateBanks()
    {
        Log::info('Updating Bank codes');

        $method = 'GET';
        $nonce = ISW::nonce();
        $url = ISW::url() . '/configuration/fundstransferbanks';
        $encodedUrl = urlencode($url);
        $timestamp = time();
        $signature = ISW::signature($method, $encodedUrl, $nonce);


        $authorization = ISW::authorization();

        $headers = [
            'Content-Type'      => 'application/json',
            'Authorization'     => $authorization,
            'Signature'         => $signature,
            'Timestamp'         => $timestamp,
            'Nonce'             => $nonce,
            'SignatureMethod'   => ISW::$signatureMethod,
            'TerminalID'        => ISW::terminalID()
        ];

        $res = self::client()->request($method, $url, [
            'headers' => $headers
        ]);

        if ( $res->getStatusCode() == 200 ) {
            $banks = json_decode($res->getBody()->getContents(), true);

            DB::transaction(function () use($banks) {
                foreach ($banks['banks'] as $bank) {
                    IswBank::updateOrCreate(
                        ['b_id' => $bank['id'], 'cbn_code' => $bank['cbnCode']],
                        ['bank_name' => $bank['bankName'], 'bank_code' => $bank['bankCode']]
                    );
                }
            });
        }

        Log::info('Bank codes updated successfully');
    }

    /**
     * Update ISW Banks
     *
     * @throws \Exception
     */
    public static function updateBillers()
    {
        Log::info('Updating Billers');

        // update billers
        $billersID = [109, 913, 901, 908, 348, 3070, 923, 205, 104, 459, 112];

        // update billers
        $data = self::getBillers();
        if ( $data['success'] ) {
            $billers = $data['data']['billers'];
            foreach ( $billers as $biller) {
                if(in_array($biller['billerid'], $billersID) ) {
                    unset($biller['smallImageId']);
                    unset($biller['largeImageId']);

                    \App\Model\Biller::firstOrCreate(['billerid' => $biller['billerid']], $biller);
                    Log::info('Biller Saved!');
                }
            }
        }

        // update billers payment items
        Log::info('Updating paymentitems');
        foreach ($billersID as $billerid ) {
            $data = ISW::getBiller($billerid);
            foreach( $data['data']['paymentitems'] as $item ) {
                $biller = \App\Model\Biller::where('billerid', $billerid)->first();
                $biller->items()->firstOrCreate(['paymentCode' => $item['paymentCode'], 'billerid' => $billerid], $item);

                Log::info('Paymentitem saved/updated');
            }
        }

        Log::info('Billers and paymentitems updated successfully');
    }


    /**
     * Add a new biller
     *
     * @param $catId
     * @param $index
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public static function addBiller($catId, $index, $type)
    {
        $data = self::getBillersByCategory($catId);
        $biller = $data['data']['billers'][$index];

        $biller['biller_type'] = $type;
        unset($biller['customMessageUrl']);
        unset($biller['customSectionUrl']);
        unset($biller['url']);

        return Biller::updateOrCreate(['billerid' => $biller['billerid']], $biller);
    }


    public static function updateBillerItems($billerId)
    {
        $biller = Biller::find($billerId);
        $data = self::getBiller($biller->billerid);

        foreach( $data['data']['paymentitems'] as $item ) {

            $biller->items()->firstOrCreate(['paymentCode' => $item['paymentCode'], 'billerid' => $biller->billerid], $item);

        }

        return 'done';
    }

}
