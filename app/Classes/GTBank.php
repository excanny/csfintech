<?php


namespace App\Classes;


use App\AgentTransaction;
use App\Model\Transaction;
use DOMDocument;
use DOMElement;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use function GuzzleHttp\Psr7\str;

class GTBank
{


    /**
     * Get headers
     *
     * @param $xml
     * @param $url
     * @return array
     */
    public static function getOptions($xml, $url)
    {
        return [
            CURLOPT_URL => self::getEndPoint() . $url,
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
                "Content-Type: text/xml",
                "SOAPAction: http://tempuri.org/GAPS_Uploader/FileUploader/SingleTransfers",
            ),
        ];
    }



    /**
     * Get GTBank transfer endpoint
     * for further transactions.
     * @return mixed
     */
    public static function getEndPoint()
    {
        if (App::environment('local')) {
            // return test endpoint
            return env('GTB_TEST_API');
        }
        // return live endpoint
        return env('GTB_LIVE_API');
    }


    /**
     * Generate reference
     * @return string
     */
    public static function generateReference()
    {
        $reference = strtoupper(Str::random());

        if ( is_null(Transaction::where('reference', $reference)->first()) ) {
            return $reference;
        }

        return self::generateReference();
    }


    /**
     * Get authentication credentials
     * for Gtbank transactions
     * @return array
     */
    public static function getAuthDetails()
    {
        return [
            'accessCode'    => env('GTB_ACCESS_CODE'),
            'username'      => env('GTB_USERNAME'),
            'password'      => env('GTB_PASSWORD')
        ];
    }



    public static function mockTransactionDetails( $payload , $isBulk = false)
    {
        $now = now()->format('Y/m/d');
//        $vendorName = env('APP_NAME');
        $vendorName = env('GTB_VENDOR_NAME');
        $vendorAccNumber = env('GTB_VENDOR_ACC_NO');
        $vendorBankCode = env('GTB_VENDOR_BANK_CODE');
        $vendorCode = env('GTB_VENDOR_CODE');
        $reference = self::generateReference();

        if ($isBulk) {

            // Open xml string of transactions
            $xmlValues = '';
            $xmlValues .= "<transactions>";

            foreach ($payload['transactions'] as $transaction) {

                $xmlValues .= "<transaction>
                        <amount>{$transaction['amount']}</amount>
                        <paymentdate>$now</paymentdate>
                        <reference>$reference</reference>
                        <remarks>{$transaction['info']}</remarks>
                        <vendorcode>$vendorCode</vendorcode>
                        <vendorname>$vendorName</vendorname>
                        <vendoracctnumber>$vendorAccNumber</vendoracctnumber>
                        <vendorbankcode>$vendorBankCode</vendorbankcode>
                        <customeracctnumber>{$transaction['customer_acc_number']}</customeracctnumber>
                    </transaction> \n";
            }

            // Close xml string of transactions
            $xmlValues .= "</transactions>";
            // return xml string
            return $xmlValues;


        }

        // This is a single transaction, so return single xml string.
        return "<transactions>
                    <transaction>
                        <amount>{$payload['amount']}</amount>
                        <paymentdate>$now</paymentdate>
                        <reference>$reference</reference>
                        <remarks>{$payload['info']}</remarks>
                        <vendorcode>$vendorCode</vendorcode>
                        <vendorname>$vendorName</vendorname>
                        <vendoracctnumber>$vendorAccNumber</vendoracctnumber>
                        <vendorbankcode>$vendorBankCode</vendorbankcode>
                    </transaction>
                </transactions>";
    }

    public static function processSingleTransfer( $payload, $transactionXML )
    {
        try {

            $curl = curl_init();
            $url = '/Gaps_FileUploader/FileUploader.asmx';

            // Get authentication details
            $credentials = self::getAuthDetails();

            // Encode the transactions details
            $transDetailsEncoded = htmlentities($transactionXML);

            // Hash values; not encoded transaction xml and credentials
            $hashed = hash('sha512', $transactionXML .
                "<accesscode>{$credentials['accessCode']}</accesscode>" .
                "<username>{$credentials['username']}</username>" .
                "<password>{$credentials['password']}</password>");

//            dd($hashed);

            $xml = "<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://www.w3.org/2003/05/soap-envelope\">
                    <soap:Body>
                        <SingleTransfers xmlns=\"http://tempuri.org/GAPS_Uploader/FileUploader\">
                             <SingleTransferRequest>
                                <transdetails>$transDetailsEncoded</transdetails>
                                <accesscode>{$credentials['accessCode']}</accesscode>
                                <username>{$credentials['username']}</username>
                                <password>{$credentials['password']}</password>
                                <hash>$hashed</hash>
                             </SingleTransferRequest>
                        </SingleTransfers>
                    </soap:Body>
                </soap:Envelope>";

//            $xml = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:fil=\"http://tempuri.org/GAPS_Uploader/FileUploader\">
//                    <soapenv:Header/>
//                        <soapenv:Body>
//                            <fil:SingleTransfers>
//                                <!--Optional:-->
//                                <fil:xmlRequest>
//                                    <SingleTransfers><transdetails>&lt;transactions&gt;&lt;transaction&gt;&lt;amount&gt;1.00&lt;/amount&gt;&lt;paymentdate&gt;2018/07/25&lt;/paymentdate&gt;&lt;reference&gt;Test_20180725.1415.42021sjdj&lt;/reference&gt;&lt;remarks&gt;TEST!&lt;/remarks&gt;&lt;vendorcode&gt;25437&lt;/vendorcode&gt;&lt;vendorname&gt;TestName TestLastName&lt;/vendorname&gt;&lt;vendoracctnumber&gt;0004527849&lt;/vendoracctnumber&gt;&lt;vendorbankcode&gt;058152052&lt;/vendorbankcode&gt;&lt;/transaction&gt;&lt;/transactions&gt;</transdetails>
//                                        <accesscode>205140019</accesscode>
//                                        <username>adewotol</username>
//                                        <password>abcd1234*</password>
//                                        <hash>6d79119f989cdee5fcda4c709c060917acf323d46a78be044072d74204f1b9845771498e45f6c69ae883d22a68d256dab8d1ee5b76a427a465a739bf9aab2ab8</hash>
//                                    </SingleTransfers>
//                                </fil:xmlRequest>
//                            </fil:SingleTransfers>
//                        </soapenv:Body>
//                    </soapenv:Envelope>";


//            dd($xml, self::getOptions($xml, $url));


            curl_setopt_array($curl, self::getOptions($xml, $url));
//            dd($curl);

            $response = curl_exec($curl);

            curl_close($curl);
//            dd($response);


            // Parse response
            $string = strstr($response, '</SingleTransfersResult>', true);
            $string = strstr($string, '<SingleTransfersResult>');
            $string = html_entity_decode($string);
            $string = substr($string, strpos($string, '?') + 37);

            $result = json_decode(json_encode(simplexml_load_string($string)), true);
//            dd($result);

            if ($result['ResCode'] === '1000') {

                return [
                    'success' => true,
                    'status' => 'SUCCESSFUL',
                    'data' => $result['Message']
                ];
            }

            if ($result['ResCode'] === '1100') {

                return [
                    'success' => true,
                    'status' => 'PENDING',
                    'data' => $result['Message']
                ];
            }

            return [
                'success' => false,
                'status' => 'FAILED',
                'message' => $result['Message'] ?? 'Transfer failed.'
            ];

        } catch (\Exception $e) {
            return [
                'success'   => false,
                'message'   => 'Error exception with transfer',
                'error'   => $e->getMessage()
            ];
        }
    }


    public static function processBulkTransfer( $payload )
    {

        return [];
    }


    /**
     * Initiate transfer
     * @param array $payload
     * @param false $isBulk
     * @return array
     */
    public static function initTransfer( array $payload, $isBulk = false )
    {
        // Get the XML string for transaction details
        $transDetails = self::mockTransactionDetails($payload, $isBulk);

        if ( $isBulk ) {

            if (!isset($payload['transactions'])) {
                die("bulk transaction payload isn't set");
            }
            // Process bulk transfer
           return self::processBulkTransfer($payload);
        }
        // Process single transfer
        return self::processSingleTransfer($payload, $transDetails);
    }

}
