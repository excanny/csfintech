<?php

namespace App\Http\Controllers\Merchant;

use App\Classes\Blusalt;
use App\Classes\ETranzact;
use App\Classes\General;
use App\Helpers\CapicollectHelper;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Model\Business;
use App\Http\Controllers\Controller;
use App\Model\DisputeMessage;
use App\Model\Product;
use App\Model\User;
use App\Model\WalletTopUpRequest;
use App\Model\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yabacon\Paystack;
use App\Classes\Wallet as WalletHelper;

class Wallet extends Controller
{

    private $model;
    /**
     * @var Business
     */
    private $business;
    /**
     * @var WalletTopUpRequest
     */
    private $walletTopUpRequest;


    /**
     * User constructor.
     * @param User $user
     * @param Business $business
     * @param WalletTopUpRequest $walletTopUpRequest
     */
    public function __construct( User $user, Business $business, WalletTopUpRequest $walletTopUpRequest)
    {
        $this->model = $user;
        $this->business = $business;
        $this->walletTopUpRequest = $walletTopUpRequest;
    }

    public function index () {
        // Get authenticated user
        $user = auth()->user();

        // Get user wallet
        $wallet = $user->business->wallet;

//        $transactions = !is_null($wallet->transactions) ? $wallet->transactions->sortDesc() : (object)[];
        $now = Carbon::now();
        if (\request()->has('week')) {
            $startOfWeek = Carbon::now()->startOfWeek();
            $transactions = $wallet->transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfWeek)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'week';
        }
        elseif (\request()->has('month')) {
            $startOfMonth = Carbon::now()->startOfMonth();
            $transactions = $wallet->transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'month';
        }
        elseif (\request()->has('year')) {
            $startOfYear = Carbon::now()->startOfYear();
            $transactions = $wallet->transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'year';
        }
        else {
            $startOfDay = Carbon::now()->startOfDay();
            $transactions = $wallet->transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfDay)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'today';
        }


        // Return view
        return view('merchant.wallet.wallet', compact('wallet', 'transactions', 'option'));
    }


    public function viewRequestTopUp () {
        $user = auth()->user();

        $bank_details = [];
        $bank_details['bank_name'] = env('BANK_NAME');
        $bank_details['account_name'] = env('ACCOUNT_NAME');
        $bank_details['account_number'] = env('ACCOUNT_NUMBER');

        return view('merchant.wallet.walletTopUp', compact('user', 'bank_details'));
    }

    public function requestTopUp ( Request $request) {
        // Fetch request data
        $data = $request->all();
        // Get authenticated user and user's business
        $user = auth()->user();
        $business = $user->business;

        if ($data['amount'] <= 0) {
            return back()->with('error', 'Invalid Amount Entered!!');
        }

        // Append status to create data
        $data['status'] = WalletTopUpRequest::$PENDING;

        if ( is_null($data['info']) ) {
            $data['info'] = 'Nil';
        }

        if ( $request->hasFile('image') && $request->file('image')->isValid()) {

            // Validate image
            $validated = $request->validate([
                'image' => 'mimes:jpeg,png|max:1024'
            ]);

            $validated['name'] = md5(\Str::random(16). time());
            $extension = $request->image->extension();

            $image_url = $request->image->storeAs('/top-up-request-images', $validated['name'].".".$extension, ['disk' => 'public']);
            $data['image_url'] = $image_url;
            unset($data['image']);
        }

        // Create top up request
        $topUpRequest = $business->topUpRequests()->create($data);

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} made a top up request of {$topUpRequest->amount} for
            {$business->name}"
        ]);

        $details = [
            'subject' => 'Top Request Notification 🔊',
            'greeting' => 'Hello 👋🏾 Support Admin!' ,
            'body' => "{$user->firstname} {$user->lastname} made a top up request of {$topUpRequest->amount} for
            {$business->name}",
            'moreBody' => "Date & Time: {$topUpRequest->created_at}",
            'thanks' => 'Sagecloud Automated Notification',
            'actionText' => 'See Now!',
            'actionURL' => url('/login')
        ];
        NotificationHelper::notifyAdmin($details);

        // Redirect
        return redirect()->route('wallet.view')->with('success', 'Request sent successfully');
    }

    public function viewRequests () {
        // Get authenticated
        $user = auth()->user();

        // Get user's top up requests
        $requests = $user->business->topUpRequests()
                    ->orderBy('id', 'desc')->get();

        // Set text color for each status
        foreach ( $requests as $request ) {
            if ($request->status == WalletTopUpRequest::$APPROVED)
                $request->color = 'success';
            elseif ($request->status == WalletTopUpRequest::$PENDING)
                $request->color = 'warning';
            else
                $request->color = 'danger';
        }

        // return view
        return view('merchant.wallet.viewRequests', compact('requests'));
    }


    public function openTopUp () {
        return view('merchant.wallet.topUpWithCard');
    }

    public function submitTopupToCapicollect( Request $request )
    {
        $user = auth()->user();
        $business = $user->business;
        $name = auth()->user()->firstname. " " .auth()->user()->lastname;
        $phone = auth()->user()->phone;
        $email = auth()->user()->email;
        $amount = $request->amount;
        if($amount <= 0 ){
            return back()->with('error', 'Please input a valid amount');
        }

        $desc = "Sagecloud Wallet Topup of {$amount}";


        // Append status to create data
        $data['status'] = WalletTopUpRequest::$PENDING;
        $data['name'] = $name;
        $data['info'] = "Wallet Topup of $amount using the online payment";
        $data['amount'] = $amount;

        // Create top up request
        $topUpRequest = $business->topUpRequests()->create($data);

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} made a top up  of {$topUpRequest->amount} for
            {$business->name} using the online payment"
        ]);

        $reference = auth()->user()->id."-".$topUpRequest->id;
        $capicollect  = CapicollectHelper::initTransaction($name,$phone,$email,$amount,$desc,$reference);

        if ($capicollect->success == true){
            $url = $capicollect->url;
            return redirect($url);
        }
        else{
            return back()->with('error', $capicollect->message);
        }
    }

    public function capicollectVerifyReference( Request $request )
    {
        $paymentReference = $request->paymentReference;
        $verify = CapicollectHelper::verifyTransaction($paymentReference);
        if ($verify->success == true){
            $complete_amount = $verify->data->amount;
            $charge = $verify->charge;
            $amount = $complete_amount - $charge;
            $ref = $verify->data->reference;
            $data = explode('-',$verify->reference);
            $user_id = $data[0];
            $t_id = $data[1];
            $user = User::find($user_id);

            $tp = $user->business->topUpRequests()->where('id',$t_id)->first();
            $tp->status = WalletTopUpRequest::$APPROVED;
            $tp->save();

            // Credit wallet
            $info = $tp->info;
            $credit = WalletHelper::credit($user->business, $amount, $info, false, $ref);
            if ($credit['success']) {
                return redirect(route('wallet.view'))->with('success','Wallet Topup successful');
            }
            return redirect(route('wallet.view'))->with('success','Wallet Topup successful, but issue with wallet, contact support.');

        }
        else{
            return redirect(route('wallet.top-up.view'))->with('error', $verify->message);
        }
    }

    public function getTopUpDetails()
    {
        $user = auth()->user();
        $key = Helper::getPaymentKey();

        //return response
        return response()->json([
            'email' => $user->email,
            'name' => $user->firstname.' '. $user->lastname,
            'phone' => $user->phone,
            'key' => $key['public']
        ]);
    }


    public function getTopUp( $ref )
    {
        if (substr($ref, 0, 9) !== 'sagecloud') {
            return response()->json(['success' => false, 'message' => 'Failed! This is not a SageCloud Transaction!!']);
        }
        $user = auth()->user();

        // Get PayStack Keys
        $key = Helper::getPaymentKey();

        // initiate the Library's Paystack Object
        $payStack = new Paystack($key['secret']);
        try
        {
            // verify using the library
            // unique to transactions
            $transaction = $payStack->transaction->verify([
                'reference' => $ref,
            ]);

        } catch(\Yabacon\Paystack\Exception\ApiException $e){
            Log::info("==============" . $e->getResponseObject() . "==============");
            return response()->json(['message' => $e->getMessage() , 'success' => false ]);
        }
        // check if this amount already has been created.
        if ( WalletTransaction::where('reference', $ref)->exists() ) {
            return response()->json(['success' => false, 'message' => "This transaction has occurred on this wallet."]);
        }

        if ('success' === $transaction->data->status && $user->email === $transaction->data->customer->email) {
            // transaction was successful...
            // Attach plan
            $amount = $transaction->data->amount / 100;

            // Credit wallet
            $info = "Wallet top Up of $amount using online payment";
            $credit = WalletHelper::credit($user->business, $amount, $info, false, $ref);

            if ($credit['success']) {
                return response()->json(['data' => $transaction->data , 'success' => true ]);
            }

            // Log the failed transaction
            WalletHelper::logFailedTransaction($user->business, $amount, $info . " FAILED", 'CREDIT');

            return response()->json(['message' => "Unable to credit your wallet" , 'success' => false ]);
        }

        // return error response
        return response()->json(['message' => "Sorry! An error occurred while verifying your payment." , 'success' => false ]);

    }


    public function filterWalletTransactions (Request $request) {
        $data = $request->all();

        $to_date = new Carbon($data['to_date']);
        $from_date = new Carbon($data['from_date']);
        $business = auth()->user()->business;
        $wallet = $business->wallet;

        $transactions = $business->wallet->transactions()
            ->orderBy('id', 'desc')
            ->whereDate('created_at','>=', $from_date)
            ->whereDate('created_at','<=', $to_date)
            ->get();

        $option = 'filter';

        $dates = [
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date']
        ];

        return view('merchant.wallet.wallet', compact('wallet','business','transactions', 'option', 'dates'));

    }

    public function commissionTransfer (Request $request) {
        try {
            $data = $request->except('_token');
            // Get business
            $user = auth()->user();
            $business = $user->business;

            // Verify request
            $required = ['target', 'amount'];
            foreach ($required as $req) {
                if (!isset($data[$req]))
                    return back()->with("error", "Sorry, request is missing: $req");
            }

            $charge = 0;
            $amount = (double) $data['amount'];
            $debited = false;

            // Get TRANSFER product
            $product = $business->getProduct('TRANSFER');

            if ($data['target'] == 'bank')
                $charge = \App\Classes\Wallet::transferCharge($amount, $product);

            $net_amount = $amount + $charge;

            // Get commission balance of the business
            $commission_balance = $business->wallet->commission;

            // Check for negative amount
            if ( $amount <= 0 ) {
                return back()->with("error", "Invalid Amount entered!");
            }

            // Check if the balance isn't enough
            if ( $net_amount > $commission_balance )
                return back()->with("error", "Insufficient commission balance");

            // Check the destinatiion for transfer
            if ( $data['target'] == 'wallet' ) {
                $info = "Transfer of N$amount from your commission wallet to wallet";

                // Debit business commission wallet
                $business->product_name = 'TRANSFER';
                $debit = \App\Classes\Wallet::debit($business, $net_amount, $info, true);
                if ( $debit['success'] ) {
                    $credit = \App\Classes\Wallet::credit($business, $net_amount, $info);

                    // Log activity
                    $user->activities()->create([
                        "info" => "{$user->firstname} {$user->lastname} made a transfer of {$amount} from commission
                     wallet to wallet {$business->name}"
                    ]);

                    return back()->with("success", "Wallet transfer of $amount successful");

                }
                return back()->with("error", "Error debiting your commission wallet");
            }

            if ( $data['target'] == 'bank' ) {

                // Collate business bank details
                $transfer_details = [
                    'bank_name' => $business->bank_name,
                    'bank_account_number' => $business->bank_account_number,
                    'bank_account_name' => $business->bank_account_name,
                    'bank_code' => $business->bank_code,
                ];

                // Check max. amount
                if ( $amount > 500000 ) {
                    return back()->with('error', 'Invalid amount! Maximum transfer amount is N500,000');
                }

                // Filter data for unfilled bank details
                foreach ($transfer_details as $key => $detail) {
                    if ($detail == '' || is_null($detail)){
                        $key = strtoupper(str_replace('_', ' ', $key));
                        return back()->with("error", "You haven't filled out your $key. Please do so in Settings");
                    }
                }

                // Prepare transcation info
                $info = "Transfer of N$amount with charge of N$charge from your commission wallet to {$transfer_details['bank_name']} @
                    {$transfer_details['bank_account_name']}, {$transfer_details['bank_account_number']}";

                // Prepare transcation reference
                $reference = General::generateReference();

                // Create transaction
                $transaction = $business->transactions()->create([
                    'amount' => $amount,
                    'charge' => $charge,
                    'net_amount' => $net_amount,
                    'status' => 'PENDING',
                    'info' => $info . ' | ' . request('narration'), 'type' => 'TRANSFER',
                    'channel' => request()->header('channel') ?? 'OTHERS',
                    'wallet_debited' => $debited,
                    'reference' => $reference
                ]);

                // Prepare narration for bank transfer
                $narration = 'Commission to bank transfer';

                // Debit commission wallet
                $business->product_name = 'TRANSFER';
                $debit = \App\Classes\Wallet::debit($user->business, $net_amount, $info, true);

                if ( $debit['success'] ) {
                    $debited = true;
                    $transaction->wallet_debited = true;
                    $transaction->save();

                    // Log activity
                    $user->activities()->create([
                        "info" => "{$user->firstname} {$user->lastname} initiated a transfer of {$amount} from commission
                     wallet to wallet {$business->name}"
                    ]);

                    // Initiate bank transfer
                    $transfer = ETranzact::transfer($amount, $transfer_details['bank_account_number'], $transfer_details['bank_code'], $transfer_details['bank_account_name'], $reference, $narration);

                    if ( $transfer['success']) {
                        $data = $transfer['data'];

                        // save transaction
                        $transaction->status = 'SUCCESSFUL';
                        $transaction->wallet_debited = $debited;
                        $transaction->save();

                        // Commission Settlement
                        $info = "Commission of N $charge for transfer of N$net_amount from the commission wallet of {$business->name} to "
                            . $transfer_details['bank_account_name'] . '('. $transfer_details['bank_account_number']. ') with transfer charge of ' . $charge;

                        // Record VAS commissions
                        $business->commissions()->create([
                            'amount' => $charge,
                            'product' => $product['name'],
                            'info' => $info
                        ]);
                        // End Commission Settlement

                        return back()->with('success', 'Fund transfer successful');
                    }

                    if ( !$transfer['success']) {
                        if ( $debited ) {
                            $business->product_name = 'TRANSFER';
                            $credit =  \App\Classes\Wallet::credit($business, $net_amount, $transaction->type . ' reversal', true );
                        }

                        // save transaction
                        $transaction->status = 'FAILED';
                        $transaction->wallet_debited = false;
                        $transaction->save();
                        return back()->with('error', 'Fund transfer failed');
                    }

                    return back()->with('message', 'Fund transfer is processing');
                }

                return back()->with('error', 'Error debiting your commission wallet');
            }
        }
        catch (\Exception $exception) {
//            return back()->with('error', $exception->getMessage().' line:'.$exception->getLine());
            return back()->with('error', 'Error exception with commission transfer');
        }
    }



}
