<?php

namespace App\Http\Controllers\SagePay;

use App\Classes\SagePayWallet;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Model\Business;
use App\Classes\Wallet;
use App\Model\Commission;
use App\Model\commissionTransaction;
use App\Model\Fee;
use App\Model\Product;
use App\Model\SagePayTransaction;
use App\Model\Transaction;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class Admin extends Controller
{
    /**
     * @var AuthController
     */
    private $model;
    /**
     * @var Business
     */
    private $business;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @var Product
     */
    private $product;
    /**
     * @var Commission
     */
    private $commission;
    /**
     * @var commissionTransaction
     */
    private $commissionTransaction;
    /**
     * @var SagePayTransaction
     */
    private $sage_pay_transaction;


    /**
     * User constructor.
     * @param User $user
     * @param Business $business
     * @param SagePayTransaction $sage_pay_transaction
     * @param Product $product
     */
    public function __construct( User $user, Business $business, SagePayTransaction $sage_pay_transaction,
                                 Product $product)
    {
        $this->model = $user;
        $this->business = $business;
        $this->sage_pay_transaction = $sage_pay_transaction;
        $this->product = $product;
    }

    public function viewTransactions () {
        // Get all transactions
        $now = Carbon::now();
        if (\request()->has('week')) {
            $startOfWeek = Carbon::now()->startOfWeek();
            $transactions = $this->sage_pay_transaction
                ->orderBy('id', 'desc')
                ->select([
                    'created_at',
                    'reference',
                    'business_id',
                    'external_reference',
                    'customer_email',
                    'amount',
                    'charge',
                    'net_amount',
                    'info',
                    'auth_status',
                    'status',
                    'browser',
                    'ip_address',
                    'browser_details'
                ])
                ->with(['business' => function ($query) {
                    $query->select(['id', 'name']);
                }])
                ->whereDate('created_at','>=', $startOfWeek)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'week';
        }
        elseif (\request()->has('month')) {
            $startOfMonth = Carbon::now()->startOfMonth();
            $transactions = $this->sage_pay_transaction
                ->orderBy('id', 'desc')
                ->select([
                    'created_at',
                    'reference',
                    'external_reference',
                    'business_id',
                    'customer_email',
                    'amount',
                    'charge',
                    'net_amount',
                    'info',
                    'auth_status',
                    'status',
                    'browser',
                    'ip_address',
                    'browser_details'
                ])
                ->with(['business' => function ($query) {
                    $query->select(['id', 'name']);
                }])
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'month';
        }
        elseif (\request()->has('year')) {
            $startOfYear = Carbon::now()->startOfYear();
            $transactions = $this->sage_pay_transaction
                ->orderBy('id', 'desc')
                ->select([
                    'created_at',
                    'reference',
                    'external_reference',
                    'customer_email',
                    'amount',
                    'business_id',
                    'charge',
                    'net_amount',
                    'info',
                    'auth_status',
                    'status',
                    'browser',
                    'ip_address',
                    'browser_details'
                ])
                ->with(['business' => function ($query) {
                    $query->select(['id', 'name']);
                }])
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'year';
        }
        else {
            $startOfDay = Carbon::now()->startOfDay();
            $transactions = $this->sage_pay_transaction
                ->orderBy('id', 'desc')
                ->select([
                    'created_at',
                    'reference',
                    'external_reference',
                    'customer_email',
                    'amount',
                    'business_id',
                    'charge',
                    'net_amount',
                    'info',
                    'auth_status',
                    'status',
                    'browser',
                    'ip_address',
                    'browser_details'
                ])
                ->with(['business' => function ($query) {
                    $query->select(['id', 'name']);
                }])
                ->whereDate('created_at','>=', $startOfDay)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'today';
        }

        foreach ($transactions as $transaction) {
            if ($transaction->status == 'SUCCESSFUL')
                $transaction->color = 'text-success';
            elseif ($transaction->status == 'FAILED')
                $transaction->color = 'text-danger';
            else
                $transaction->color = 'text-warning';
        }
//        dd($transactions);

        // Return view
        return view('admin.payment_gateway.viewTransactions', compact('transactions','option'));
    }


    public function filterTransactions (Request $request) {
        $data = $request->all();

        $to_date = new Carbon($data['to_date']);
        $from_date = new Carbon($data['from_date']);
        $transactions = (object)[];

        if (is_null($data['reference'])) {
            if ($data['status'] === 'all' ) {
                $transactions = $this->sage_pay_transaction
                    ->orderBy('id', 'desc')
                    ->select([
                        'created_at',
                        'reference',
                        'external_reference',
                        'customer_email',
                        'amount',
                        'business_id',
                        'charge',
                        'net_amount',
                        'info',
                        'auth_status',
                        'status',
                        'browser',
                        'ip_address',
                        'browser_details'
                    ])
                    ->with(['business' => function ($query) {
                        $query->select(['id', 'name']);
                    }])
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get();
            }
            elseif ($data['status'] !== 'all' ) {
                $transactions = $this->sage_pay_transaction
                    ->orderBy('id', 'desc')
                    ->select([
                        'created_at',
                        'reference',
                        'external_reference',
                        'customer_email',
                        'amount',
                        'business_id',
                        'charge',
                        'net_amount',
                        'info',
                        'auth_status',
                        'status',
                        'browser',
                        'ip_address',
                        'browser_details'
                    ])
                    ->with(['business' => function ($query) {
                        $query->select(['id', 'name']);
                    }])
                    ->where('status', $data['status'])
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get();
            }
        }
        else {
            if ($data['status'] === 'all' ) {
                $transactions = $this->sage_pay_transaction
                    ->orderBy('id', 'desc')
                    ->select([
                        'created_at',
                        'reference',
                        'external_reference',
                        'customer_email',
                        'amount',
                        'business_id',
                        'charge',
                        'net_amount',
                        'info',
                        'auth_status',
                        'status',
                        'browser',
                        'ip_address',
                        'browser_details'
                    ])
                    ->with(['business' => function ($query) {
                        $query->select(['id', 'name']);
                    }])
                    ->where('reference', $data['reference'])
                    ->get();
            }
            elseif ($data['status'] !== 'all' ) {
                $transactions = $this->sage_pay_transaction
                    ->orderBy('id', 'desc')
                    ->select([
                        'created_at',
                        'reference',
                        'external_reference',
                        'customer_email',
                        'amount',
                        'business_id',
                        'charge',
                        'net_amount',
                        'info',
                        'auth_status',
                        'status',
                        'browser',
                        'ip_address',
                        'browser_details'
                    ])
                    ->with(['business' => function ($query) {
                        $query->select(['id', 'name']);
                    }])
                    ->where('status', $data['status'])
                    ->where('reference', $data['reference'])
                    ->get();
            }
        }
        $option = 'filter';

        $dates = [
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date']
        ];

        $filter = [
            'reference' => $data['reference'],
            'status' => $data['status']
        ];

        foreach ($transactions as $transaction) {
            if ($transaction->status == 'SUCCESSFUL')
                $transaction->color = 'text-success';
            elseif ($transaction->status == 'FAILED')
                $transaction->color = 'text-danger';
            else
                $transaction->color = 'text-warning';
        }

        return view('admin.payment_gateway.viewTransactions', compact('transactions', 'option', 'dates', 'filter'));

    }

    public function updateTransactionStatus ( Request $request ) {
        $data = $request->all();
        $transaction =  $this->sage_pay_transaction->find($data['transaction_id']);
//        $product = $transaction->business->getProduct($transaction->type);
        $transaction->update([
            'status' => $data['status']
        ]);


        if ($transaction && $transaction->status === 'FAILED') {
            // Reverse transaction
            $info = 'Reversal of '. $transaction->amount . ' for '. $transaction->type .' transaction from your Wallet';
            \App\Classes\Wallet::credit($transaction->business, $transaction->amount, $info);
        }


        if ($transaction && $transaction->status === 'SUCCESSFUL' && $data['old_status'] === 'FAILED') {
            // Debit business wallet
            $info = 'Debit of '. $transaction->amount . ' for '. $transaction->type .' transaction from your Wallet';
            \App\Classes\Wallet::debit($transaction->business, $transaction->amount, $info);
        }

        $user = auth()->user();

        // Log activity
        auth()->user()->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} updated sage_pay transaction of ref, {$transaction->reference}
             for the business, {$transaction->business->name} to {$transaction->status}"
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status Updated Successfully'
        ]);
    }
}
