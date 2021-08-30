<?php


namespace App\Classes;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Paystack
{
    /**
     * @var mixed|\Yabacon\Paystack
     */
    protected $paystack;

    public function __construct()
    {
        $this->paystack = app(
            \Yabacon\Paystack::class,
            ['secret_key' => config('paystack.secret_key')]
        );
    }

    public function bvn($bvn)
    {
        return $this->paystack->bank->resolveBvn(compact('bvn'));
    }


    public static function resolveAccount( $account, $bankCode )
    {
        try {
            $payStackSK = env('APP_ENV') == 'production' ? env('PAYSTACK_SECRET') : env('PAYSTACK_SECRET_TEST');
            $endpoint = "https://api.paystack.co/bank/resolve?account_number=$account&bank_code=$bankCode";

            $headers = [
                "Authorization" => "Bearer $payStackSK",
                'Content-Type' => 'application/json'
            ];

            $client = new Client();
            $res = $client->get($endpoint, [
                'headers'   => $headers
            ]);

            if ( $res->getStatusCode() == 200 || $res->getStatusCode() == 201 ) {
                $data = json_decode($res->getBody()->getContents(), true);
                $data['success'] = true;

                Log::info("=====PAYSTACK VERIFY RESPONSE=====");
                Log::info(json_encode($data));
                return $data;
            }
            else {
                return ['success' => false, 'message' => 'Error validating account'];
            }
        }
        catch ( \Exception $exception ) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }


    public static function verifyTransaction( $reference )
    {
        try {
            $payStackSK = env('APP_ENV') == 'production' ? env('PAYSTACK_SECRET') : env('PAYSTACK_SECRET_TEST');
            $paystack = new \Yabacon\Paystack($payStackSK);

            $response = $paystack->transaction->verify(["reference" => $reference]);

            if ($response->status && $response->data->status == 'success') {
                return [
                    'success' => true,
                    'data' => $response->data
                ];
            }

            return ['success' => false, 'message' => $response->message ?? 'Error validating transaction'];
        }
        catch ( \Exception $exception ) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }


    public static function createTransferRecipient($account_number, $account_name, $bank_code)
    {
        try {
            $payStackSK = env('APP_ENV') == 'production' ? env('PAYSTACK_SECRET') : env('PAYSTACK_SECRET_TEST');
            $endpoint = "https://api.paystack.co/transferrecipient";
            $data = [
                'type' => 'nuban',
                'name' => $account_name,
                'description' => 'Savings account',
                'account_number' => $account_number,
                'bank_code' => $bank_code,
                'currency' => 'NGN',
            ];

            $headers = [
                "Authorization" => "Bearer $payStackSK",
                'Content-Type' => 'application/json',
                'Content-Length' => strlen(json_encode($data))
            ];

            $client = new Client();
            $res = $client->post($endpoint, [
                'headers'   => $headers,
                'json'      => $data
            ]);

            if ( $res->getStatusCode() == 200 || $res->getStatusCode() == 201 ) {
                Log::info("=====PAYSTACK RECIPIENT RESPONSE=====");
                Log::info(json_encode(['success' => true, 'data' => json_decode($res->getBody()->getContents(), true)]));
                return ['success' => true, 'data' => json_decode($res->getBody()->getContents(), true)];
            }
            else {
                return ['success' => false, 'message' => 'Error creating transfer recipient'];
            }
        }
        catch ( \Exception $exception ) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }


    public static function transferFunds($amount, $recipient_code, $narration)
    {
        try {
            $payStackSK = env('APP_ENV') == 'production' ? env('PAYSTACK_SECRET') : env('PAYSTACK_SECRET_TEST');
            $endpoint = "https://api.paystack.co/transfer";
            $data = [
                'source' => 'balance',
                'reason' => $narration,
                'amount' => $amount,
                'recipient' => $recipient_code,
            ];

            $headers = [
                "Authorization" => "Bearer $payStackSK",
                'Content-Type' => 'application/json',
                'Content-Length' => strlen(json_encode($data))
            ];

            $client = new Client();
            $res = $client->post($endpoint, [
                'headers'   => $headers,
                'json'      => $data
            ]);

            if ( $res->getStatusCode() == 200 || $res->getStatusCode() == 201 ) {
                Log::info("=====PAYSTACK TRANSFER RESPONSE=====");
                Log::info(json_encode(['success' => true, 'data' => json_decode($res->getBody()->getContents(), true)]));
                return ['success' => true, 'data' => json_decode($res->getBody()->getContents(), true)];
            }
            else {
                return ['success' => false, 'message' => 'Error transferring to recipient'];
            }
        }
        catch ( \Exception $exception ) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }


    public static function chargeAuthorization( $data )
    {
        try {
            $payStackSK = env('APP_ENV') == 'production' ? env('PAYSTACK_SECRET') : env('PAYSTACK_SECRET_TEST');
            $endpoint = "https://api.paystack.co/transaction/charge_authorization";

            $headers = [
                "Authorization" => "Bearer $payStackSK",
                'Content-Type' => 'application/json',
                'Content-Length' => strlen(json_encode($data))
            ];

            $client = new Client();
            $res = $client->post($endpoint, [
                'headers'   => $headers,
                'json'      => $data
            ]);

            if ( $res->getStatusCode() == 200 || $res->getStatusCode() == 201 ) {
                $data = json_decode($res->getBody()->getContents());

                if ($data->status && $data->data->status == 'success') {
                    return [
                        'success' => true,
                        'data' => $data->data
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data->data->gateway_response ?? 'Charge failed'
                ];
            }
            else {
                return ['success' => false, 'message' => 'Error with topup'];
            }
        }
        catch ( \Exception $exception ) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }


    public static function extractTransaction( $responseData, $paid_at = null)
    {
        return [
            'amount' => $responseData->amount / 100,
            'fees' => $responseData->fees / 100,
            'status' => $responseData->status === 'success' ? 'SUCCESSFUL' : strtoupper($responseData->status),
            'paid_at' => $paid_at ?? date('Y-m-d h:i:s'),
            'reference' => $responseData->reference,
        ];
    }
}
