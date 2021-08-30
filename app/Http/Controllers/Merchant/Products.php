<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Products extends Controller
{
    public function index () {
        $user = auth()->user();

        $products = $user->business->fee->products;

        $userProducts = [];

        foreach ($products as $product) {
            if ($product['status'])
                $userProducts[] = $product;
        }

        // re-organise products array to suite the view
        foreach ($userProducts as &$product) {

            if (is_array($product['merchant_commission'])) {
                foreach ($product['merchant_commission'] as $key => $item) {
                    $product['billers'][$key]['commission'] = $item;
                }
                unset($product['merchant_commission']);
            }

            $product['transactions_volume'] = $user->business->transactions()
                ->where('status', 'SUCCESSFUL')
                ->where('type', str_replace(' ', '-', $product['name']))
                ->sum('net_amount');

            $product['transactions_count'] = $user->business->transactions()
                ->where('status', 'SUCCESSFUL')
                ->where('type', str_replace(' ', '-', $product['name']))
                ->count();

            $product['commissions_volume'] = $user->business->commissionTransactions()
                ->where('product', str_replace(' ', '-', $product['name']))
                ->sum('amount');

            $product['commissions_count'] = $user->business->commissionTransactions()
                ->where('product', str_replace(' ', '-', $product['name']))
                ->count();
        }
        sort($userProducts);

//        dd($userProducts);
        return view('merchant.products.viewProducts', compact('userProducts'));
    }

    public function viewProductTransactions ($product = null) {
        $data = \request()->all();
        $business = auth()->user()->business;

        if ( is_null($product)  ) {
            if ( request()->has('week') ) {
                $startOfWeek = Carbon::now()->startOfWeek();

                $product = $this->getProductTransactions($business, $data['product'], $startOfWeek, []);

                $product['option'] = 'week';
            }
            elseif ( request()->has('month') ) {
                $startOfMonth = Carbon::now()->startOfMonth();

                $product = $this->getProductTransactions($business, $data['product'], $startOfMonth, []);

                $product['option'] = 'month';
            }
            elseif ( request()->has('year') ) {
                $startOfYear = Carbon::now()->startOfYear();

                $product = $this->getProductTransactions($business, $data['product'], $startOfYear, []);

                $product['option'] = 'year';
            } else {
                $startOfDay = Carbon::now()->startOfDay();
                $product = $this->getProductTransactions($business, $data['product'], $startOfDay, []);
                $product['option'] = 'today';
            }
        }

        foreach ($product['transactions'] as &$transaction) {
            if ($transaction->status == 'SUCCESSFUL')
                $transaction->color = 'text-success';
            elseif ($transaction->status == 'FAILED')
                $transaction->color = 'text-danger';
            else
                $transaction->color = 'text-warning';
        }

        return view('merchant.products.viewProductTranx', compact('product', 'business'));
    }

    public function getProductTransactions ($business, $product, $period, $current_product) {
        $now = Carbon::now();
        $current_product['transactions'] = $business->transactions()
            ->orderBy('id', 'desc')
            ->where('type', str_replace(' ', '-', $product))
            ->whereDate('created_at','>=', $period)
            ->whereDate('created_at','<=', $now)
            ->get();

        $current_product['merchant_commissions'] = $business->commissionTransactions()
            ->orderBy('id', 'desc')
            ->where('product', str_replace(' ', '-', $product))
            ->whereDate('created_at','>=', $period)
            ->whereDate('created_at','<=', $now)
            ->get();

        return $current_product;
    }

    public function filterProductTransactions (Request $request) {
        $data = $request->all();
        $current_product = $data['product'];

        $business = auth()->user()->business;
        $to_date = new Carbon($data['to_date']);
        $from_date = new Carbon($data['from_date']);
        $product = [];

        if (is_null($data['reference'])) {
            if ($data['status'] === 'all' ) {
                $product['transactions'] = $business->transactions()
                    ->orderBy('id', 'desc')
                    ->where('type', str_replace(' ', '-', $current_product))
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get();
            }
            else {
                $product['transactions'] = $business->transactions()
                    ->orderBy('id', 'desc')
                    ->where('type', str_replace(' ', '-', $current_product))
                    ->where('status', $data['status'])
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get();
            }
        } else {
            if ($data['status'] === 'all' ) {
                $product['transactions'] = $business->transactions()
                    ->orderBy('id', 'desc')
                    ->where('type', str_replace(' ', '-', $current_product))
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->where('reference', $data['reference'])
                    ->get();
            }
            else {
                $product['transactions'] = $business->transactions()
                    ->orderBy('id', 'desc')
                    ->where('type', str_replace(' ', '-', $current_product))
                    ->where('status', $data['status'])
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->where('reference', $data['reference'])
                    ->get();
            }
        }

        $product['merchant_commissions'] = $business->commissionTransactions()
            ->orderBy('id', 'desc')
            ->where('product', str_replace(' ', '-', $current_product))
            ->whereDate('created_at','>=', $from_date)
            ->whereDate('created_at','<=', $to_date)
            ->get();

        $product['vas_commissions'] = $business->commissions()
            ->orderBy('id', 'desc')
            ->whereDate('created_at','>=', $from_date)
            ->whereDate('created_at','<=', $to_date)
            ->where('product', str_replace(' ', '-', $current_product))
            ->get();
        $product['option'] = 'filter';
        $product['from_date'] = $data['from_date'];
        $product['to_date'] = $data['to_date'];

        $filter = [
            'product' => $data['product'],
            'reference' => $data['reference'],
            'status' => $data['status']
        ];

        foreach ($product['transactions'] as &$transaction) {
            if ($transaction->status == 'SUCCESSFUL')
                $transaction->color = 'text-success';
            elseif ($transaction->status == 'FAILED')
                $transaction->color = 'text-danger';
            else
                $transaction->color = 'text-warning';
        }

        return view('merchant.products.viewProductTranx', compact('product', 'business', 'filter'));

    }
}
