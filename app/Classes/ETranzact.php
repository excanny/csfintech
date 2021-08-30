<?php
/**
 * Created by Canaan Etai.
 * Date: 1/29/20
 * Time: 7:16 AM
 */

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
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ETranzact
{

    /**
     * Get api endpoint
     *
     * @return mixed
     */
    private static function endpoint()
    {
        if ( env('APP_ENV') == 'production' ) {
            return env('ET_LIVE_ENDPOINT');
        }
        return env('ET_TEST_ENDPOINT');
    }

    /**
     * Get TID
     *
     * @return mixed
     */
    private static function getTID()
    {
        if ( env('APP_ENV') == 'production' ) {
            return env('ET_TID_LIVE');
        }
        return env('ET_TID_TEST');
    }

    /**
     * Get TID
     *
     * @return mixed
     */
    private static function getPIN()
    {
        if ( env('APP_ENV') == 'production' ) {
            return env('ET_PIN_LIVE');
        }
        return env('ET_PIN_TEST');
    }

    /**
     * Get headers
     *
     * @return array
     */
    public static function getOptions($xml)
    {
        $options = [
            CURLOPT_URL => self::endpoint(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $xml,
            CURLOPT_HTTPHEADER => array(
                "Accept: application/soap+xml",
                "Content-Type: text/xml"
            ),
        ];

        return $options;
    }


    /**
     * Update ETranzact bank list
     *
     * @return array
     */
    public static function updateBankList()
    {
        try {
            $curl = curl_init();
            $terminalId = self::getTID();
            $pin = self::getPIN();
            $reference = General::generateReference();

            Str::random();

            $xml = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ws=\"http://ws.fundgate.etranzact.com/\">
                        <soapenv:Header/>
                        <soapenv:Body>
                            <ws:process>
                                <request>
                                    <direction>request</direction>
                                    <action>BL</action>
                                    <terminalId>$terminalId</terminalId>
                                    <transaction>
                                        <pin>$pin</pin>
                                        <bankCode></bankCode>
                                        <description>Account Query</description>
                                        <destination></destination>
                                        <reference>$reference</reference>
                                        <endPoint>A</endPoint>
                                    </transaction>
                                </request>
                            </ws:process>
                        </soapenv:Body>
                    </soapenv:Envelope>";

            curl_setopt_array($curl, self::getOptions($xml));

            $response = curl_exec($curl);

            curl_close($curl);

            $string = strstr($response, '<response>');
            $string = strstr($string, '</response>', true);
            $string .= '</response>';

            $response = json_decode(json_encode(simplexml_load_string($string)), true);

            if ( $response['error'] == '0' ) {
                $list = json_decode(json_encode(simplexml_load_string($response['message'])), true);

                $bankList = collect([]);
                foreach ($list['bank'] as $bank ) {
                    $bankList->add(['cbn_code' => $bank['bankCode'], 'bank_name' => $bank['bankName']]);
                }

                if ( file_exists(storage_path('bank_list.json')) ) {
                    unlink(storage_path('bank_list.json'));
                }
                file_put_contents(storage_path('bank_list.json'), $bankList->toJson());

                return [
                    'success' => false,
                    'message' => 'bank list updated successfully'
                ];
            }

            return [
                'success' => false,
                'message' => $response['message'] ?? 'Account validation failed.'
            ];

        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception with account validation',
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Validate Customer's account
     *
     * @param $accountNumber
     * @param $bankCode
     * @param $reference
     * @return array
     */
    public static function validateAccount($accountNumber, $bankCode)
    {
        try {
            $curl = curl_init();
            $terminalId = self::getTID();
            $pin = self::getPIN();
            $reference = General::generateReference();

            Str::random();

            $xml = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ws=\"http://ws.fundgate.etranzact.com/\">
                        <soapenv:Header/>
                        <soapenv:Body>
                            <ws:process>
                                <request>
                                    <direction>request</direction>
                                    <action>AQ</action>
                                    <terminalId>$terminalId</terminalId>
                                    <transaction>
                                        <pin>$pin</pin>
                                        <bankCode>$bankCode</bankCode>
                                        <description>Account Query</description>
                                        <destination>$accountNumber</destination>
                                        <reference>$reference</reference>
                                        <endPoint>A</endPoint>
                                    </transaction>
                                </request>
                            </ws:process>
                        </soapenv:Body>
                    </soapenv:Envelope>";

            curl_setopt_array($curl, self::getOptions($xml));

            $response = curl_exec($curl);

            curl_close($curl);

            $string = strstr($response, '<response>');
            $string = strstr($string, '</response>', true);
            $string .= '</response>';

            $response = json_decode(json_encode(simplexml_load_string($string)), true);

            if ( $response['error'] == '0' ) {
                return [
                    'success' => true,
                    'data' => $response
                ];
            }

            return [
                'success' => false,
                'message' => $response['message'] ?? 'Account validation failed.'
            ];

        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception with account validation',
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Fund Transfer
     *
     * @param $amount
     * @param $accountNumber
     * @param $bankCode
     * @param $accountName
     * @param $reference
     * @param $narration
     * @return array
     */
    public static function transfer($amount, $accountNumber, $bankCode, $accountName, $reference, $narration)
    {
        try {
            $curl = curl_init();
            $terminalId = self::getTID();
            $user = auth()->user();
            $pin = self::getPIN();

            Str::random();

            $xml = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ws=\"http://ws.fundgate.etranzact.com/\">
                        <soapenv:Header/>
                        <soapenv:Body>
                            <ws:process>
                                <request>
                                    <direction>request</direction>
                                    <action>FT</action>
                                    <terminalId>$terminalId</terminalId>
                                    <transaction>
                                        <pin>$pin</pin>
                                        <bankCode>$bankCode</bankCode>
                                        <amount>$amount</amount>
                                        <description>$narration</description>
                                        <destination>$accountNumber</destination>
                                        <reference>$reference</reference>
                                        <senderName>{$user->name};{$user->phone};$accountName</senderName>
                                        <endPoint>A</endPoint>
                                    </transaction>
                                </request>
                            </ws:process>
                        </soapenv:Body>
                    </soapenv:Envelope>";


//            Log::info($xml);

            curl_setopt_array($curl, self::getOptions($xml));

            $response = curl_exec($curl);

            curl_close($curl);

            $string = strstr($response, '<response>');
            $string = strstr($string, '</response>', true);
            $string .= '</response>';

            $response = json_decode(json_encode(simplexml_load_string($string)), true);
            Log::info('+======== etranzact resp ========+');
            Log::info(json_encode($response));

            if ( $response['error'] == '0' || $response['error'] == '-1' || $response['error'] == '31' ) {
                return [
                    'success' => true,
                    'status' => 'success',
                    'data' => $response
                ];
            }

            return [
                'success' => false,
                'status' => 'failed',
                'message' => $response['message'] ?? 'Account validation failed.'
            ];

        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception with account validation',
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Get transaction
     *
     * @param $reference
     * @return array
     */
    public static function getTransaction($reference)
    {
        try {
            $curl = curl_init();
            $terminalId = self::getTID();
            $pin = self::getPIN();

            Str::random();

            $xml = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ws=\"http://ws.fundgate.etranzact.com/\">
                        <soapenv:Header/>
                        <soapenv:Body>
                            <ws:process>
                                <request>
                                    <direction>request</direction>
                                    <action>TS</action>
                                    <terminalId>$terminalId</terminalId>
                                    <transaction>
                                        <pin>$pin</pin>
                                        <description>Status Check</description>
                                        <reference>$reference</reference>
                                        <lineType>OTHERS</lineType>
                                    </transaction>
                                </request>
                            </ws:process>
                        </soapenv:Body>
                    </soapenv:Envelope>";

            curl_setopt_array($curl, self::getOptions($xml));

            $response = curl_exec($curl);

            curl_close($curl);

            $string = strstr($response, '<response>');
            $string = strstr($string, '</response>', true);
            $string .= '</response>';

            $response = json_decode(json_encode(simplexml_load_string($string)), true);

            if ( $response['error'] == '0' ) {
                return [
                    'success' => true,
                    'status' => 'success',
                    'data' => $response
                ];
            }

            if ( $response['error'] == '31' || $response['error'] == '-1' ) {
                return [
                    'success' => true,
                    'status' => 'pending',
                    'data' => $response
                ];
            }

            return [
                'success' => false,
                'message' => $response['message'] ?? 'Get transaction status failed.'
            ];

        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception getting transaction',
                'error'   => $exception->getMessage()
            ];
        }
    }


    /**
     * Get transaction
     *
     * @return array
     */
    public static function balance()
    {
        try {
            $curl = \curl_init();
            $terminalId = self::getTID();
            $pin = self::getPIN();
            $reference = General::generateReference();

            Str::random();

            $xml = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ws=\"http://ws.fundgate.etranzact.com/\">
                        <soapenv:Header/>
                        <soapenv:Body>
                            <ws:process>
                                <request>
                                    <direction>request</direction>
                                    <action>BE</action>
                                    <terminalId>$terminalId</terminalId>
                                    <transaction>
                                        <pin>$pin</pin>
                                        <description>Balance Enquiry</description>
                                        <reference>$reference</reference>
                                    </transaction>
                                </request>
                            </ws:process>
                        </soapenv:Body>
                    </soapenv:Envelope>";

            curl_setopt_array($curl, self::getOptions($xml));

            $response = curl_exec($curl);

            curl_close($curl);

            $string = strstr($response, '<response>');
            $string = strstr($string, '</response>', true);
            $string .= '</response>';

            $response = json_decode(json_encode(simplexml_load_string($string)), true);


            if ( $response['error'] == '0' ) {
                return [
                    'success' => true,
                    'data' => $response
                ];
            }

            return [
                'success' => false,
                'message' => $response['message'] ?? 'Account validation failed.'
            ];

        }
        catch (\Exception $exception ) {
            return [
                'success'   => false,
                'message'   => 'Error exception with account validation',
                'error'   => $exception->getMessage()
            ];
        }
    }
}
