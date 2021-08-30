<?php

namespace App\Http\Controllers\Merchant\SagePay;

use App\Classes\ETranzact;
use App\Model\Business;
use App\Model\Product;
use App\Model\SagePayTransaction;
use App\Model\Transaction;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Model\User as UserModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Dashboard extends Controller
{

    /**
     * @var UserModel
     */
    private $model;

    /**
     * @var Business
     */
    private $business;

    private $product;
    /**
     * @var SagePayTransaction
     */
    private $sage_pay_transaction;


    /**
     * Dashboard constructor.
     * @param UserModel $user
     * @param Business $business
     * @param SagePayTransaction $sage_pay_transaction
     * @param Product $product
     */
    public function __construct(UserModel $user, Business $business, SagePayTransaction $sage_pay_transaction, Product $product)
    {
        $this->model = $user;
        $this->business = $business;
        $this->sage_pay_transaction = $sage_pay_transaction;
        $this->product = $product;
    }


    public function viewTransactions () {
        $user = auth()->user();
        $transactions = (object)[];

        $now = Carbon::now();
        if (\request()->has('week')) {
            $startOfWeek = Carbon::now()->startOfWeek();
            $transactions = !is_null($user->business->sage_pay_transactions) ? $user->business->sage_pay_transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfWeek)
                ->whereDate('created_at','<=', $now)
                ->get() : (object)[];
            $option = 'week';
        }
        elseif (\request()->has('month')) {
            $startOfMonth = Carbon::now()->startOfMonth();
            $transactions = !is_null($user->business->sage_pay_transactions) ? $user->business->sage_pay_transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->get() : (object)[];
            $option = 'month';
        }
        elseif (\request()->has('year')) {
            $startOfYear = Carbon::now()->startOfYear();
            $transactions = !is_null($user->business->sage_pay_transactions) ? $user->business->sage_pay_transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->get() : (object)[];
            $option = 'year';
        }
        else {
            $startOfDay = Carbon::now()->startOfDay();
            $transactions = !is_null($user->business->sage_pay_transactions) ? $user->business->sage_pay_transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfDay)
                ->whereDate('created_at','<=', $now)
                ->get() : (object)[];
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

        return view('merchant.payment_gateway.viewTransactions', compact('transactions', 'option'));
    }


    public function filterTransactions (Request $request) {
        $data = $request->all();
        $user = auth()->user();

        $to_date = new Carbon($data['to_date']);
        $from_date = new Carbon($data['from_date']);

        $transactions = (object)[];

        if (is_null($data['reference'])) {
            if ($data['status'] === 'all' ) {
                $transactions = !is_null($user->business->sage_pay_transactions) ? $user->business->sage_pay_transactions()
                    ->orderBy('id', 'desc')
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get() : (object)[];
            }
            elseif ($data['status'] !== 'all' ) {
                $transactions = !is_null($user->business->sage_pay_transactions) ? $user->business->sage_pay_transactions()
                    ->orderBy('id', 'desc')
                    ->where('status', $data['status'])
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get() : (object)[];
            }
        }
        else {
            if ($data['status'] === 'all' ) {
                $transactions = !is_null($user->business->sage_pay_transactions) ? $user->business->sage_pay_transactions()
                    ->orderBy('id', 'desc')
                    ->where('external_reference', $data['reference'])
                    ->get() : (object)[];
            }
            elseif ($data['status'] !== 'all' ) {
                $transactions = !is_null($user->business->sage_pay_transactions) ? $user->business->sage_pay_transactions()
                    ->orderBy('id', 'desc')
                    ->where('status', $data['status'])
                    ->where('external_reference', $data['reference'])
                    ->get() : (object)[];
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

        return view('merchant.payment_gateway.viewTransactions', compact('transactions', 'option', 'dates', 'filter'));

    }

    public function showSettings () {
        $hasSettings = true;
        if (is_null(auth()->user()->business->sage_pay_settings)) {
            $hasSettings = false;
        }
        return view('merchant.payment_gateway.settings', compact('hasSettings'));
    }

    public function updateSettings (Request $request) {
        $data = $request->except("_token");

        $business = auth()->user()->business;


        if (is_null($business->sage_pay_settings)) {
            $business->sage_pay_settings()->create($data);
        }
        else {
            $business->sage_pay_settings()->update($data);
        }

        return back()->with("success", "Settings updated successfully");
    }
}
