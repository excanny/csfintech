<?php

namespace App\Http\Controllers\SagePay;

use App\Classes\SagePayWallet;
use App\Classes\UBA;
use App\Model\SagePayTransaction;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class Payment extends Controller
{
    /**
     * @var SagePayTransaction
     */
    private $sagePayTransaction;

    public function __construct(SagePayTransaction $sagePayTransaction)
    {
        $this->sagePayTransaction = $sagePayTransaction;
    }

    public function index( $access_code = null ) {
        // Check for empty access code
        if (is_null($access_code)) {
            $error = "Sorry, page not found";
            return view('sage_pay.error', compact('error'));
        }

        try {
            // Try to decrypt access code and let catch handle exception if it isn't encrypted for further checks
            // If it is not an encrypted data, then the access code shouldn't be checked here
            $decrypted_data = decrypt($access_code);

            // Explode decrypted data to get access code
            $splits = explode('.', $decrypted_data);

            // Find transaction
            $transaction = $this->sagePayTransaction->where('access_code', $splits[1])->first();
            // Toggle status
            $transaction->status = SagePayTransaction::$FAILED;
            $transaction->save();

            // Prepare error message
            $error = "Sorry, payment session expired";

            // Return error page
            return view('sage_pay.error', compact('error'));
        } catch (Exception $exception) {

            // Check for invalid access code
            $transaction = $this->sagePayTransaction->where('access_code', $access_code)->first();
            if (is_null($transaction)) {
                $error = "Sorry, this transaction could not be started";
                return view('sage_pay.error', compact('error'));
            }

            // Check if authentication and payment was successful but merchant is not credited
            if ($transaction->auth_status == SagePayTransaction::$PAY_SUCCESSFUL && $transaction->wallet_credited != 1) {
                try {
                    $credit = SagePayWallet::credit($transaction->business, (float) $transaction->net_amount, $transaction->info);
                    if (!$credit['success']) {
                        Log::info("==========PAYMENT GATEWAY========");
                        Log::info("Credit failed with error: ". $credit['message']);
                    }else {
                        $transaction->wallet_credited = 1;
                        $transaction->save();
                    }
                }
                catch (Exception $exception) {
                    Log::info("==========PAYMENT GATEWAY========");
                    Log::info("Credit failed with error: ". $exception->getMessage());
                }

                $transaction->display_success = true;
                $transaction->save();
            }

            // Check, in case the customer has not seen the success page
            if ($transaction->display_success) {
                // Return success page
                return redirect("{$transaction->access_code}/success");
            }

            // Check if transaction is closed
            if ($transaction->status != SagePayTransaction::$PENDING) {
                $error = "Sorry, this transaction has been closed";
                return view('sage_pay.error', compact('error'));
            }
        }

        $business = $transaction->business;

        if (env('APP_ENV') == 'local') {
            $url = env('UBA_GATEWAY_BASE_URL_TEST');
            $merchantId = env('UBA_GATEWAY_MERCHANT_ID_TEST');
        }
        else {
            $url = env('UBA_GATEWAY_BASE_URL_LIVE');
            $merchantId = env('UBA_GATEWAY_MERCHANT_ID_LIVE');
        }

        // Return view to start new payment
        return view('sage_pay.index', compact('transaction', 'merchantId','url'));
    }

    public function initAuthPayer () {
        try {
            $data = request()->all();

            // Find the transaction
            $transaction = $this->sagePayTransaction->where('session_id', $data['id'])->first();

            // Check if transaction is found
            if (is_null($transaction)) {
                return response()->json([
                    "success" => false,
                    "message" => "Transaction not found",
                    "abort" => true
                ], 404);
            }

            // Check if transaction is closed
            if ($transaction->status == SagePayTransaction::$FAILED ||
                $transaction->status == SagePayTransaction::$SUCCESSFUL) {

                return response()->json([
                    "success" => false,
                    "message" => "Transaction is already closed, Please start payment again",
                    "abort" => true
                ], 403);
            }

            // Organize browser details
            $browserDetails = [
                "javaEnabled" => false,
                "language" => "English",
                "screenHeight" => $data["device"]["screenHeight"],
                "screenWidth" => $data["device"]["screenWidth"],
                "timeZone" => $data["device"]["timeZone"],
                "colorDepth" => $data["device"]["colorDepth"],
                "3DSecureChallengeWindowSize" => "FULL_SCREEN"
            ];

            // update transaction details
            $transaction->ip_address = $data['device']['ipAddress'];
            $transaction->browser = $data['device']['browser'];
            $transaction->browser_details = $browserDetails;
            $transaction->save();

            // Check if initiateAuthentication operation has been successful earlier
            if (is_null($transaction->auth_status)) {
                // Make call to initiate authentication of the payer
                $response = UBA::initiateAuthentication($transaction->session_id, $transaction->external_reference, $transaction->reference, $transaction->id);

                if (!$response['success']) {
                    return response()->json([
                        "success" => false,
                        "message" => $response['message'],
                        "error" => $response['error'] ?? null,
                    ], 500);
                }
            }

            // Toggle authentication status
            $transaction->auth_status = SagePayTransaction::$INITIATED;
            $transaction->save();

            // Make call to Start authentication of payer
            $authenticate = UBA::authenticatePayer($transaction, $browserDetails);

            if (!$authenticate['success']) {
                return response()->json([
                    "success" => false,
                    "message" => $authenticate['message'],
                    "error" => $authenticate['error'] ?? null,
                ], 500);
            }

            // Toggle authentication status and save redirect HTML
            $transaction->auth_status = SagePayTransaction::$IN_PROGRESS;
            $transaction->auth_html = $authenticate['data']['redirectHtml'];
            $transaction->save();

            // Return success response
            return response()->json(["success" => true, "message" => "Success initializing authentication, Authentication in progress"], 200);
        }
        catch (Exception $exception) {
            return response()->json([
                "success" => false,
                "message" => "Error initializing authentication",
                "error" => $exception->getMessage()
            ], 500);
        }


    }

    /**
     * Function that handles requests from callback_url sent to Mastercard to conclude payment
     * @param $access_code
     * @param Request $request
     * @return Factory|JsonResponse|RedirectResponse|Redirector|View
     */
    public function closePayment ($access_code, Request $request) {

        $data = $request->all();

        // Verify the transaction was initiated here
        $transaction = $this->sagePayTransaction->where([
            "access_code" => $access_code,
            "external_reference" => $data["order_id"],
            "reference" => $data["transaction_id"]
        ])->first();

        // Check if transaction was found
        if (is_null($transaction)) {
            $error = "Sorry, this transaction could not be found";
            return view('sage_pay.error', compact('error'));
        }

        // Verify the parameters before pay operation
        if ($data['response_gatewayRecommendation'] == 'PROCEED' && $data['result'] == 'SUCCESS') {

            // Toggle status to auth_successful
            $transaction->auth_status = SagePayTransaction::$AUTH_SUCCESSFUL;

            // Generate reference for pay operation
            $transaction->pay_reference = SagePayTransaction::generatePayReference();
            $transaction->save();

            // Make payment
            $payment = UBA::pay($transaction);

            if  (!$payment['success'])  {
                $transaction->status = SagePayTransaction::$FAILED;
                $transaction->save();

                // Prepare error message and return error page
                $error = "{$payment["message"]}. Please try again";
                return view('sage_pay.error', compact('error'));
            }

            // Toggle auth status
            $transaction->auth_status = SagePayTransaction::$PAY_SUCCESSFUL;
            $transaction->save();

            // Settlement
            $business = $transaction->business;

            // Credit wallet of the business
            try {
                $credit = SagePayWallet::credit($business, (float) $transaction->net_amount, $transaction->info);
                if (!$credit['success']) {
                    Log::info("==========PAYMENT GATEWAY========");
                    Log::info("Credit failed with error: ". $credit['message']);
                }else {
                    $transaction->wallet_credited = 1;
                    $transaction->save();
                }
            }
            catch (Exception $exception) {
                Log::info("==========PAYMENT GATEWAY========");
                Log::info("Credit failed with error: ". $exception->getMessage());
            }

            // Update fields
            $transaction->display_success = true;
            $transaction->status = SagePayTransaction::$SUCCESSFUL;
            $transaction->save();

            // Redirect to success page
            return redirect()->to("{$transaction->access_code}/success")->send();
        }

        // Toggle status to failed
        $transaction->auth_status = SagePayTransaction::$AUTH_FAILED;
        $transaction->status = SagePayTransaction::$FAILED;

        // Return error page
        $error = "Authentication Failed!";
        return view('sage_pay.error', compact('error'));
    }


    public function showSuccess ($access_code = null) {
        // Check for empty access code
        if (is_null($access_code)) {
            $error = "Sorry, transaction not found";
            return view('sage_pay.error', compact('error'));
        }

        // Get transaction
        $transaction = $this->sagePayTransaction->where('access_code', $access_code)->first();

        // Check if transaction is found
        if (is_null($transaction)) {
            $error = "Sorry, this transaction could not be found";
            return view('sage_pay.error', compact('error'));
        }

        // Check if transaction is closed
        if (!$transaction->display_success) {
            $error = "Sorry, this transaction has been closed";
            return view('sage_pay.error', compact('error'));
        }

        // Check if transaction is successful abi dem dey whine us
        if ($transaction->status != SagePayTransaction::$SUCCESSFUL &&
            $transaction->auth_status != SagePayTransaction::$AUTH_SUCCESSFUL) {
            $error = "Sorry, this transaction wasn't successful";
            return view('sage_pay.error', compact('error'));
        }

        $business = $transaction->business;

        if (is_null($transaction->callback_url)) {
            if (!is_null($business->sage_pay_settings) && !is_null($business->sage_pay_settings->callback_url)) {
                $transaction->callback_url = $business->sage_pay_settings->callback_url .'?ref='. $transaction->external_reference;
                $transaction->save();
            }
        }


        // Update fields
        $transaction->callback_url = $transaction->callback_url .'?ref='. $transaction->external_reference;
        $transaction->display_success = false;
        $transaction->save();

        // Return success page
        // Congrats you have reached the end of this transaction!!
        return view('sage_pay.success', compact('transaction'));
    }
}
