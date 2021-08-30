<?php

namespace App\Http\Controllers\Merchant;

use App\Classes\ETranzact;
use App\Model\Business;
use App\Model\Product;
use App\Model\Transaction;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Model\User as UserModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    /**
     * @var Transaction
     */
    private $transaction;

    private $product;


    /**
     * Dashboard constructor.
     * @param UserModel $user
     * @param Business $business
     * @param Transaction $transaction
     * @param Product $product
     */
    public function __construct(UserModel $user, Business $business, Transaction $transaction, Product $product)
    {
        $this->model = $user;
        $this->business = $business;
        $this->transaction = $transaction;
        $this->product = $product;
    }


    public function index () {
        // Get authenticated user
        $user = auth()->user();
        $business = $user->business;

        $products = $business->fee->products;

        // Get total transactions
        $business->transactions_count = $business->transactions()
            ->where('status', 'SUCCESSFUL')
            ->count();
//        $business->transactions_count = count($transactions);

        // Get recent transactions
        $recent_transactions = $business->transactions()
            ->orderBy('id', 'desc')
            ->take(20)->get();
//        rsort($recent_transactions);

        // Get total transactions for the past month and year
        $now = Carbon::now();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();
        $business->transactionsThisMonth = $business->transactions()
            ->where('status', 'SUCCESSFUL')
            ->whereDate('created_at','>=', $startOfMonth)
            ->whereDate('created_at','<=', $now)
            ->count();
        $business->transactionsThisYear = $business->transactions()
            ->where('status', 'SUCCESSFUL')
            ->whereDate('created_at','>=', $startOfYear)
            ->whereDate('created_at','<=', $now)
            ->count();

        // Get transactions sum for all products
        foreach ( $products as $key => &$product ) {
            if ($product['slug'] == 'payment_gateway') {
                $product['total_transactions'] = $business->sage_pay_transactions()
                    ->where('status', 'SUCCESSFUL')
                   ->count();
            }
            else {
                $product['total_transactions'] = $business->transactions()
                    ->where('status', 'SUCCESSFUL')
                    ->where('type', str_replace(' ', '-', $product['name']))->count();
            }

            if ($product['status'] == false) {
                unset($products[$key]);
            }
        }

//        dd($products);
        // Return view
        return view('merchant.index', compact('user', 'business','recent_transactions', 'products'));
    }


    public function filterIndex( Request $request ) {
        // Get authenticated user
        $user = auth()->user();

        $data = $request->all();
        $business = $user->business;

        $from_date = $data['from_date'];
        $to_date = $data['to_date'];

        $products = $business->fee->products;

        // Get total transactions
        $business->transactions_count = $business->transactions()
            ->where('status', 'SUCCESSFUL')
            ->whereDate('created_at','>=', $from_date)
            ->whereDate('created_at','<=', $to_date)
            ->count();
//        $business->transactions_count = count($transactions);

        // Get recent transactions
        $recent_transactions = $business->transactions()
            ->orderBy('id', 'desc')
            ->take(20)->get();
//        rsort($recent_transactions);

        // Get total transactions for the past month and year
        $now = Carbon::now();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();
        $business->transactionsThisMonth = $business->transactions()
            ->where('status', 'SUCCESSFUL')
            ->whereDate('created_at','>=', $startOfMonth)
            ->whereDate('created_at','<=', $now)
            ->count();
        $business->transactionsThisYear = $business->transactions()
            ->where('status', 'SUCCESSFUL')
            ->whereDate('created_at','>=', $startOfYear)
            ->whereDate('created_at','<=', $now)
            ->count();

        // Get transactions sum for all products
        foreach ( $products as $key => &$product ) {
            $product['total_transactions'] = $business->transactions()
                ->where('status', 'SUCCESSFUL')
                ->whereDate('created_at','>=', $from_date)
                ->whereDate('created_at','<=', $to_date)
                ->where('type', str_replace(' ', '-', $product['name']))->count();
            if ($product['status'] == false) {
                unset($products[$key]);
            }
        }
        $business->transaction_option = 'date_range';
        $dates = [
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date']
        ];

        // Return view
        return view('merchant.index', compact('user', 'business','recent_transactions', 'products','dates'));
    }


    public function viewProfile () {
        // Get authenticated user
        $user = auth()->user();

        // Return view
        return view('merchant.settings.editProfile', compact('user'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateUser (Request $request) {
        // Get input
        $data = $request->all();

        // Get Authenticated user
        $user = auth()->user();

        // Check if email exists
        $check = $this->model->where('email', $data['email'])->first();
        if(!is_null($check) && $check['email'] != $user->email)
            return back()->with('error', 'Account with same email address exists');

        // Update user
        $user->update($data);

        // Reload with success message
        return back()->with('success', 'Profile update success');
    }


    public function viewBusiness () {
        // Get authenticated user
        $user = auth()->user();

        // Get user's business
        $business = $user->business;

        // Return view
        return view('merchant.settings.editBusiness', compact('business'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateBusinessBasicInfo (Request $request ) {
        //Get Input
        $data = $request->all();

        // Get Authenticated user
        $user = auth()->user();

        //Update business
        $user->business()->update([
            "name" => $data['name'],
            "info" => $data['info'],
            "phone" => $data['phone'],
            "website" => $data['website'],
            "address" => $data['address'],
            "city" => $data['city'],
            "state" => $data['state'],
            "postal_code" => $data['postal_code'],
            "rc_number" => $data['rc_number']
        ]);

        //Reload
        return back()->with('success')->with('success', 'Successfully updated basic information');
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateBusinessEmails (Request $request ) {
        //Get Input
        $input = $request->all();

        // Get Authenticated user
        $user = auth()->user();

        // Check if email exists
        $check = $this->business->where('email', $input['email'])->first();
        if(!is_null($check) && $check['email'] != $user->business->email)
            return back()->with('error', 'Account with same email address already exist');

        // Check if charge back email exists
        $check2 = $this->business->where('email', $input['email'])->first();
        if(!is_null($check2) && $check2['email'] != $user->business->email)
            return back()->with('error', 'Account with same email address already exist');

        //Update business
        $user->business()->update([
            "email" => $input['email'],
            "charge_back_email" => $input['charge_back_email'],
        ]);

        if ((!is_null($input['bank_account_number']) || $input['bank_account_number'] != '') ||
            (!is_null($input['bank_code']) || $input['bank_code'] != '') ||
            (!is_null($input['bank_account_name']) || $input['bank_account_name'] != ''))
        {
            $account = ETranzact::validateAccount($input['bank_account_number'], $input['bank_code']);

            if ( !$account['success'] ) {
                return back()->with('error', 'Error verifying bank details, Please check and try again')->withInput();
            }

            if (strtoupper($input['bank_account_name']) !== trim($account['data']['message'])) {
                return back()->with('error', 'Your account name does not match other bank details');
            }
        }

        //Update business
        $user->business()->update([
            "bank_name" => $input['bank_name'],
            "bank_account_name" => $input['bank_account_name'],
            "bank_account_number" => $input['bank_account_number'],
            "bank_code" => $input['bank_code'],
        ]);

        //Reload
        return back()->with('success')->with('success', 'Successfully updated emails and bank details');
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateBusinessInternet (Request $request ) {
        //Get Input
        $input = $request->all();

        // Get Authenticated user
        $user = auth()->user();

        //Update business
        $user->business()->update([
            "facebook" => $input['facebook'],
            "twitter" => $input['twitter'],
            "instagram" => $input['instagram'],
            "linkedin" => $input['linkedin'],
            "youtube" => $input['youtube'],
        ]);

        //Reload
        return back()->with('success', 'Successfully updated internet information');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateNotification( Request $request )
    {
        //Get Input
        $input = $request->all();

        // Get Authenticated user
        $user = auth()->user();

        //Update business
        $user->business()->update([
            "alert_balance" => $input['alert_balance']
        ]);

        //Reload
        return back()->with('success', 'Successfully updated notification');
    }


    public function viewTeam () {
        // Get Authenticated user
        $user = auth()->user();
        $business = $user->business;

        // Fetch team members
        $teamMembers = $business->users;

        // Return view
        return view('merchant.team.viewTeam', compact('teamMembers'));
    }


    public function showAddMember () {
        // Return view
        return view('merchant.team.addTeamMember');
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function addMember (Request $request) {
        // Get request data
        $data = $request->all();

        // Hash password
        $data['password'] = bcrypt($data['password']);

        //Get authenticated user
        $user = auth()->user();
        $business = $user->business;

        // Check if email exists
        if ($this->model->where('email', $request->get('email'))->exists()) {
            return redirect()->back()->with('error', 'Account with same email address already exist.');
        }

        // Append business id to create data
        $data['business_id'] = $business->id;

        // Create team member
        $teamMember = $business->users()->create($data);

        $teamMember->assignRole('USER');

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} added {$teamMember->firstname} {$teamMember->lastname}
             as a new team member"
        ]);

        // Reload
        return redirect()->route('team.view')->with('success', 'Team member created successfully');
    }

    public function viewTransactions () {
        $user = auth()->user();
//        $transactions = !is_null($user->business->transactions) ? $user->business->transactions->sortDesc() : (object)[];
        $transactions = (object)[];

        $now = Carbon::now();
        if (\request()->has('week')) {
            $startOfWeek = Carbon::now()->startOfWeek();
            $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfWeek)
                ->whereDate('created_at','<=', $now)
                ->get() : (object)[];
            $option = 'week';
        }
        elseif (\request()->has('month')) {
            $startOfMonth = Carbon::now()->startOfMonth();
            $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->get() : (object)[];
            $option = 'month';
        }
        elseif (\request()->has('year')) {
            $startOfYear = Carbon::now()->startOfYear();
            $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
                ->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->get() : (object)[];
            $option = 'year';
        }
        else {
            $startOfDay = Carbon::now()->startOfDay();
            $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
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

        return view('merchant.transactions.viewTransactions', compact('transactions', 'option'));
    }

    public function disputeTransaction ( $ref ) {
        return view('merchant.transactions.dispute', compact('ref'));
    }

    public function filterTransactions (Request $request) {
        $data = $request->all();
        $user = auth()->user();

        $to_date = new Carbon($data['to_date']);
        $from_date = new Carbon($data['from_date']);

        $transactions = (object)[];

        if (is_null($data['reference'])) {
            if ($data['product'] === 'all' && $data['status'] === 'all' ) {
                $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
                    ->orderBy('id', 'desc')
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get() : (object)[];
            }
            elseif ($data['product'] !== 'all' && $data['status'] === 'all' ) {
                $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
                    ->orderBy('id', 'desc')
                    ->where('type', $data['product'])
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get() : (object)[];
            }
            elseif ($data['product'] === 'all' && $data['status'] !== 'all' ) {
                $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
                    ->orderBy('id', 'desc')
                    ->where('status', $data['status'])
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get() : (object)[];
            }
            elseif ($data['product'] !== 'all' && $data['status'] !== 'all' ) {
                $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
                    ->orderBy('id', 'desc')
                    ->where([
                        'status'=> $data['status'],
                        'type'=> $data['product']
                    ])
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get() : (object)[];
            }
        }
        else {
            if ($data['product'] === 'all' && $data['status'] === 'all' ) {
                $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
                    ->orderBy('id', 'desc')
                    ->where('external_reference', $data['reference'])
                    ->get() : (object)[];
            }
            elseif ($data['product'] !== 'all' && $data['status'] === 'all' ) {
                $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
                    ->orderBy('id', 'desc')
                    ->where('type', $data['product'])
                    ->where('external_reference', $data['reference'])
                    ->get() : (object)[];
            }
            elseif ($data['product'] === 'all' && $data['status'] !== 'all' ) {
                $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
                    ->orderBy('id', 'desc')
                    ->where('status', $data['status'])
                    ->where('external_reference', $data['reference'])
                    ->get() : (object)[];
            }
            elseif ($data['product'] !== 'all' && $data['status'] !== 'all' ) {
                $transactions = !is_null($user->business->transactions) ? $user->business->transactions()
                    ->orderBy('id', 'desc')
                    ->where([
                        'status'=> $data['status'],
                        'type'=> $data['product']
                    ])
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
            'product' => $data['product'],
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

        return view('merchant.transactions.viewTransactions', compact('transactions', 'option', 'dates', 'filter'));

    }

    public function changeUserRole ( $id, $role ) {

        // Decode url parameters
        $new_role = base64_decode($role);
        $user_id = base64_decode($id);

        // Fetch user
        $user = $this->model->find($user_id);

//        dd($user->roles);

        if ($new_role == 'USER') {
            // Fetch current user permission
            $userRole = $user->roles()->where('name', 'MERCHANT_ADMIN')->first();

            if (is_null($userRole)) {
                return back()->with('error', "$user->firstname is already a Viewer");
            }
            // Delete pivot linking the user to the role
            $userRole->pivot->delete();
        }
        elseif ($new_role == 'MERCHANT_ADMIN') {
            $user->assignRole('MERCHANT_ADMIN');
        }

        $merchant = auth()->user();

        // Log activity
        $merchant->activities()->create([
            "info" => "{$merchant->firstname} {$merchant->lastname} changed the role of
             {$user->firstname} {$user->lastname} to {$new_role}"
        ]);

        return back()->with('success', "Role changed successfully");
    }
}
