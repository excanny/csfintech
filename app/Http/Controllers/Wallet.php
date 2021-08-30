<?php

namespace App\Http\Controllers;

use App\Model\Business;
use App\Model\WalletTopUpRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Wallet extends Controller
{
    private $walletTopUpRequest;
    private $business;

    /**
     * @param WalletTopUpRequest $walletTopUpRequest
     * @param Business $business
     */
    public function __construct(WalletTopUpRequest $walletTopUpRequest, Business $business)
    {
        $this->walletTopUpRequest = $walletTopUpRequest;
        $this->business = $business;
    }


    public function viewRequests () {
        // Fetch pending requests
        $requests = $this->walletTopUpRequest->orderBy('id', 'desc')
            ->get();

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
        return view('admin.wallet.viewRequests', compact('requests'));
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function approveRequest ($id) {
        // Get authenticated user
        $user = auth()->user();

        // Fetch request
        $top_up_request = $this->walletTopUpRequest->find($id);

        //Get business
        $business = $top_up_request->business;

        // Set previous and new balance
        $prev_bal = $business->wallet->balance;
        $new_bal = $prev_bal + $top_up_request->amount;

        // create transaction
        $business->wallet->transactions()->create([
            'business_id' => $business->id,
            'amount' => $top_up_request->amount,
            'prev_balance' => $prev_bal,
            'new_balance' => $new_bal,
            'type' => 'CREDIT',
            'info' => $top_up_request->info
        ]);

        // Update wallet
        $business->wallet()->update([
            'balance' => $new_bal
        ]);

        // Update request status
        $top_up_request->update([
            'status' => WalletTopUpRequest::$APPROVED
        ]);

        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} approved a top up request of {$top_up_request->amount}
             from, {$business->name}"
        ]);

        // Return view
        return back()->with('success', 'Request approved successfully');
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function rejectRequest ($id) {
        // Get authenticated user
        $user = auth()->user();

        // Fetch request
        $request = $this->walletTopUpRequest->find($id);

        //Get business
        $business = $request->business;

        // Update request status
        $request->update([
            'status' => WalletTopUpRequest::$REJECTED
        ]);

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} rejected a top up request of {$request->amount}
             from, {$business->name}"
        ]);

        // Return view
        return back()->with('success', 'Request rejected successfully');
    }

    public function viewBusinessWalletTranx ($id) {
        $business = $this->business->find($id);
        $wallet_transactions = $business->wallet->transactions;

        $now = Carbon::now();
        if (\request()->has('week')) {
            $startOfWeek = Carbon::now()->startOfWeek();
            $wallet_transactions = $business->wallet->transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfWeek)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'week';
        }
        elseif (\request()->has('month')) {
            $startOfMonth = Carbon::now()->startOfMonth();
            $wallet_transactions = $business->wallet->transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'month';
        }
        elseif (\request()->has('year')) {
            $startOfYear = Carbon::now()->startOfYear();
            $wallet_transactions = $business->wallet->transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'year';
        }
        else {
            $startOfDay = Carbon::now()->startOfDay();
            $wallet_transactions = $business->wallet->transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfDay)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'today';
        }

        return view('admin.merchants.viewWalletTransactions', compact('wallet_transactions', 'business', 'option'));
    }

    public function filterWalletTransactions (Request $request) {
        $data = $request->all();

        $to_date = new Carbon($data['to_date']);
        $from_date = new Carbon($data['from_date']);
        $transactions = (object)[];
        $business = $this->business->find($data['id']);

        $wallet_transactions = $business->wallet->transactions()
            ->orderBy('id', 'desc')
            ->whereDate('created_at','>=', $from_date)
            ->whereDate('created_at','<=', $to_date)
            ->get();

        $option = 'filter';

        $dates = [
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date']
        ];

        return view('admin.merchants.viewWalletTransactions', compact('business','wallet_transactions', 'option', 'dates'));

    }
}
