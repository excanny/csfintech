<?php

namespace App\Http\Controllers;

use App\Classes\SagePayWallet;
use App\Model\Business;
use App\Classes\Wallet;
use App\Model\Commission;
use App\Model\commissionTransaction;
use App\Model\Fee;
use App\Model\Product;
use App\Model\SagePayTransaction;
use App\Model\Transaction;
use App\Model\User;
use App\ReQuery;
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
     * @var Transaction
     */
    private $transaction;

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
    private $sage_pay_transaction;


    /**
     * User constructor.
     * @param User $user
     * @param Business $business
     * @param Permission $permission
     * @param Transaction $transaction
     * @param Product $product
     * @param Commission $commission
     * @param commissionTransaction $commissionTransaction
     * @param SagePayTransaction $sagePayTransaction
     */
    public function __construct( User $user, Business $business, Permission $permission, Transaction $transaction,
                                 Product $product, Commission $commission, commissionTransaction $commissionTransaction,
                                 SagePayTransaction $sagePayTransaction)
    {
        $this->model = $user;
        $this->business = $business;
        $this->permission = $permission;
        $this->transaction = $transaction;
        $this->product = $product;
        $this->sage_pay_transaction = $sagePayTransaction;
        $this->commission = $commission;
        $this->commissionTransaction = $commissionTransaction;
    }


    public function index () {
        // Get authenticated user
        $user = auth()->user();
        $data = \request()->all();

        //Fetch products
        $products = $this->product->orderBy('id', 'asc')
        ->get();

        $transactions = [];
        $transactions['option'] = '';

        if ( isset($data['option']) && $data['option'] == 'volume' ) {
            $transactions['total'] = $this->transaction->orderBy('id', 'desc')
                ->where('status', 'SUCCESSFUL')
                ->sum('net_amount');
            $now = Carbon::now();
            $startOfMonth = Carbon::now()->startOfMonth();
            $startOfYear = Carbon::now()->startOfYear();

            $transactions['thisMonth'] = $this->transaction
                ->where('status', 'SUCCESSFUL')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->sum('net_amount');

            $transactions['thisYear'] = $this->transaction
                ->where('status', 'SUCCESSFUL')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->sum('net_amount');

            // Get transactions sum for all products
            foreach ( $products as $product ) {
                if ($product['slug'] =='payment_gateway') {
                    $product->total_transactions = $this->sage_pay_transaction
                        ->where('status', 'SUCCESSFUL')
                        ->sum('net_amount');
                }else {
                    $product->total_transactions = $this->transaction
                        ->where('status', 'SUCCESSFUL')
                        ->where('type', str_replace(' ', '-', $product->name))
                        ->sum('net_amount');
                }
            }

            $transactions['option'] = 'volume';
        }
        elseif ( isset($data['option']) && $data['option'] == 'vas_commissions') {
            $transactions['total'] = $this->commission->orderBy('id', 'desc')
                ->sum('amount');
            $now = Carbon::now();
            $startOfMonth = Carbon::now()->startOfMonth();
            $startOfYear = Carbon::now()->startOfYear();

            $transactions['thisMonth'] = $this->commission->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->sum('amount');

            $transactions['thisYear'] = $this->commission->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->sum('amount');

            // Get transactions sum for all products
            foreach ( $products as $product ) {
                $product->total_transactions = $this->commission
                    ->where('product', str_replace(' ', '-', $product->name))
                    ->sum('amount');
            }

            $transactions['option'] = 'vas_commissions';
        }
        elseif ( isset($data['option']) && $data['option'] == 'merchant_commissions') {
            $transactions['total'] = $this->commissionTransaction->orderBy('id', 'desc')
                ->sum('amount');
            $now = Carbon::now();
            $startOfMonth = Carbon::now()->startOfMonth();
            $startOfYear = Carbon::now()->startOfYear();

            $transactions['thisMonth'] = $this->commissionTransaction->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->sum('amount');

            $transactions['thisYear'] = $this->commissionTransaction->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->sum('amount');

            // Get transactions sum for all products
            foreach ( $products as $product ) {
                $product->total_transactions = $this->commissionTransaction
                    ->where('product', str_replace(' ', '-', $product->name))
                    ->sum('amount');
            }

            $transactions['option'] = 'merchant_commissions';
        }
        else {
            // Get transactions count
            $transactions['total'] = $this->transaction->orderBy('id', 'desc')
                ->where('status', 'SUCCESSFUL')
                ->count();

            $now = Carbon::now();
            $startOfMonth = Carbon::now()->startOfMonth();
            $startOfYear = Carbon::now()->startOfYear();

            $transactions['thisMonth'] = $this->transaction
                ->where('status', 'SUCCESSFUL')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->count();

            $transactions['thisYear'] = $this->transaction
                ->where('status', 'SUCCESSFUL')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->count();

            // Get transactions count for all products
            foreach ( $products as $product ) {
                if ($product['slug'] =='payment_gateway') {
                    $product->total_transactions = $this->sage_pay_transaction
                        ->where('status', 'SUCCESSFUL')
                        ->count();
                }else {
                    $product->total_transactions = $this->transaction
                        ->where('status', 'SUCCESSFUL')
                        ->where('type', str_replace(' ', '-', $product->name))
                        ->count();
                }
            }
        }

        // Get recent transactions
        $recent_transactions = $this->transaction->orderBy('id', 'desc')
            ->with('business')
            ->take(20)->get();

        // Return view
        return view('admin.index', compact('user','recent_transactions','products', 'transactions'));
    }


    public function filterIndex ( Request $request ) {
        // Get authenticated user
        $user = auth()->user();

        $data = $request->all();
        $to_date = new Carbon($data['to_date']);
        $from_date = new Carbon($data['from_date']);

        //Fetch products
        $products = $this->product->orderBy('id', 'asc')
            ->get();

        $transactions = [];
        $transactions['option'] = '';

        if ( isset($data['option']) && $data['option'] == 'volume' ) {
            $transactions['total'] = $this->transaction->orderBy('id', 'desc')
                ->where('status', 'SUCCESSFUL')
                ->whereDate('created_at','>=', $from_date)
                ->whereDate('created_at','<=', $to_date)
                ->sum('net_amount');
            $now = Carbon::now();
            $startOfMonth = Carbon::now()->startOfMonth();
            $startOfYear = Carbon::now()->startOfYear();

            $transactions['thisMonth'] = $this->transaction
                ->where('status', 'SUCCESSFUL')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->sum('net_amount');

            $transactions['thisYear'] = $this->transaction
                ->where('status', 'SUCCESSFUL')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->sum('net_amount');

            // Get transactions sum for all products
            foreach ( $products as $product ) {
                $product->total_transactions = $this->transaction
                    ->where('status', 'SUCCESSFUL')
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->where('type', str_replace(' ', '-', $product->name))
                    ->sum('net_amount');
            }

            $transactions['option'] = 'volume';
        }
        elseif ( isset($data['option']) && $data['option'] == 'vas_commissions' ) {
            $transactions['total'] = $this->commission->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $from_date)
                ->whereDate('created_at','<=', $to_date)
                ->sum('amount');
            $now = Carbon::now();
            $startOfMonth = Carbon::now()->startOfMonth();
            $startOfYear = Carbon::now()->startOfYear();

            $transactions['thisMonth'] = $this->commission->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->sum('amount');

            $transactions['thisYear'] = $this->commission->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->sum('amount');

            // Get transactions sum for all products
            foreach ( $products as $product ) {
                $product->total_transactions = $this->commission
                    ->where('product', str_replace(' ', '-', $product->name))
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->sum('amount');
            }

            $transactions['option'] = 'vas_commissions';
        }
        elseif ( isset($data['option']) && $data['option'] == 'merchant_commissions' ) {
            $transactions['total'] = $this->commissionTransaction->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $from_date)
                ->whereDate('created_at','<=', $to_date)
                ->sum('amount');
            $now = Carbon::now();
            $startOfMonth = Carbon::now()->startOfMonth();
            $startOfYear = Carbon::now()->startOfYear();

            $transactions['thisMonth'] = $this->commissionTransaction->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->sum('amount');

            $transactions['thisYear'] = $this->commissionTransaction->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->sum('amount');

            // Get transactions sum for all products
            foreach ( $products as $product ) {
                $product->total_transactions = $this->commissionTransaction
                    ->where('product', str_replace(' ', '-', $product->name))
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->sum('amount');
            }

            $transactions['option'] = 'merchant_commissions';
        }
        else {
            // Get transactions count
            $transactions['total'] = $this->transaction->orderBy('id', 'desc')
                ->whereDate('created_at','>=', $from_date)
                ->whereDate('created_at','<=', $to_date)
                ->where('status', 'SUCCESSFUL')
                ->count();

            $now = Carbon::now();
            $startOfMonth = Carbon::now()->startOfMonth();
            $startOfYear = Carbon::now()->startOfYear();

            $transactions['thisMonth'] = $this->transaction
                ->where('status', 'SUCCESSFUL')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->count();

            $transactions['thisYear'] = $this->transaction
                ->where('status', 'SUCCESSFUL')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->count();

            // Get transactions count for all products
            foreach ( $products as $product ) {
                $product->total_transactions = $this->transaction
                    ->where('status', 'SUCCESSFUL')
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->where('type', str_replace(' ', '-', $product->name))->count();
            }
        }

        // Get recent transactions
        $recent_transactions = $this->transaction->orderBy('id', 'desc')
            ->with('business')
            ->take(20)->get();

        $dates = [
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
            'filter' => 'date_range'
        ];

//        Log::info("======= Debug =========");
//        Log::info(json_encode($dates));

        // Return view
        return view('admin.index', compact('user','recent_transactions','products', 'transactions','dates'));
    }



    public function viewMerchants () {
        // Get all merchants
        $merchants = $this->model::role('MERCHANT')
            ->orderBy('id', 'desc')
            ->get();

        // Initialize authorised merchants array
        $authorizedMerchants = [];

        // Push all active merchants
        foreach ( $merchants as $merchant ) {
            if (isset($merchant->business) && $merchant->business->status == Business::$ACTIVE)
                $authorizedMerchants[] = $merchant;
        }

        // Return view
        return view('admin.merchants.viewMerchants', compact('authorizedMerchants'));
    }


    public function viewAddMerchant () {
        // Return view
        return view('admin.merchants.addMerchant');
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     *
     */
    public function addMerchant ( Request $request ) {
        // Get request data
        $input = $request->all();

        // Hash password
        $input['password'] = bcrypt($input['password']);

        // Check if email exists in users table
        if ( $this->model->where('email', $request->get('email'))->exists() ) {
            // Reload with error message
            return redirect()->back()->with('error', 'Account with same email address exists');
        }

        // Check if email exists in business table
        if ( $this->business->where('email', $request->get('email'))->exists() ) {
            // Reload with error message
            return redirect()->back()->with('error', 'Account with same email address exists');
        }

        // Check if business name exists
        if ( $this->business->where('name', $request->get('business_name'))->exists() ) {
            // Reload with error message
            return redirect()->back()->with('error', 'Business with the same name exists');
        }

        // Create business
        $business = $this->business->create([
            'name' => $input['business_name'],
            'email' => $input['email']
        ]);

        // Prepare data for user creation
        unset($input['business_name']);
        $input['business_id'] = $business->id;

        // Create Merchant
        $user = $business->users()->create($input);
        //Assign merchant role
        $user->assignRole('MERCHANT');
        $admin = auth()->user();


        // Get products
        $products = $this->product->orderBy('id', 'desc')
            ->select(['id','slug', 'name', 'charge',
                    'charge_type','vas_commission',
                    'merchant_commission'])
            ->get();

        foreach ( $products as &$product ) {
            $product['status'] = false;

            // Save default commissions for products
            switch ( $product['slug'] ) {
                case Product::$data :
                    $product['vas_commission'] = Product::$DATA;
                    $product['merchant_commission'] = Product::$DATA;
                    $product['charge_type'] = Product::$PERCENTAGE;
                    break;
                case Product::$airtime :
                    $product['vas_commission'] = Product::$AIRTIME;
                    $product['merchant_commission'] = Product::$AIRTIME;
                    $product['charge_type'] = Product::$PERCENTAGE;
                    break;
                case Product::$cable_tv :
                    $product['vas_commission'] = Product::$CABLE_TV;
                    $product['merchant_commission'] = Product::$CABLE_TV;
                    $product['charge_type'] = Product::$PERCENTAGE;
                    break;
                case Product::$electricity :
                    $product['vas_commission'] = Product::$ELECTRICITY;
                    $product['merchant_commission'] = Product::$ELECTRICITY;
                    $product['charge_type'] = Product::$PERCENTAGE;
                    break;
                case Product::$transfer :
                    $product['is_flat'] = false;
                    $product['flat_charge'] = 0;
                    $product['flat_vas_commission'] = 0;
                    $product['vas_commission'] = Product::$TRANSFER;
                    $product['merchant_commission'] = Product::$TRANSFER;
                    break;
                case Product::$payment_gateway :
                    $product['cap'] = 0;
                    break;
            }
        }

        $feeData = [
            'products' => $products
        ];

        // Create fee for merchant
        $user->business->fee()->create( $feeData );

        // Create wallet for merchant's business
        $business->wallet()->create([
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => 0.0
        ]);

        // Log activity
        $admin->activities()->create([
            "info" => "{$admin->firstname} {$admin->lastname} added {$user->firstname} {$user->lastname} as
            a merchant with business, {$business->name}"
        ]);

        /*
        GENERATE SIGNING KEY FOR THE .ENV VARIABLES

        *===== php artisan tinker ======
        *===== echo base64_encode(openssl_random_pseudo_bytes(32)); =======
        *===== echo base64_encode(openssl_random_pseudo_bytes(64);  =======

        *SAVE BOTH KEYS IN .ENV FILE WITH THE VARIABLES AS FIRSTKEY AND SECONDKEY RESPECTIVELY

        */
//        $generateApiKey = new Api();
//
//        $keyGenerated = $generateApiKey->generateApiKey($createApiKey);

        /*SAVE API KEY $keyGenerated */


        // Redirect
        return redirect()->route('merchants.view')
            ->with('success', 'Merchant successfully created, The business is currently inactive');
    }


    public function viewAdministrators () {
        $administrators = $this->model::role('ADMIN')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.administrators.viewAdmin', compact('administrators'));
    }

    public function viewAddAdministrator () {
        return view('admin.administrators.addAdmin');
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function addAdministrator ( Request $request ) {
        // Get request data
        $input = $request->all();

        // Hash password
        $input['password'] = bcrypt($input['password']);
        $input['business_id'] = 0;

        // Check if email exists
        if ($this->model->where('email', $request->get('email'))->exists()) {
            return redirect()->back()->with('error', 'Account with same email address already exists.');
        }

        // Create Administrator
        $admin = $this->model->create($input);
        // Assign admin role
        $admin->assignRole('ADMIN');

        $user = auth()->user();

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} added {$admin->firstname} {$admin->lastname}
             as an admin"
        ]);

        // Return response
        return redirect()->route('administrators.view')->with('success', 'Administrator successfully created');
    }

    public function removeAdmin($id)
    {
        // Get authenticated
        $user = auth()->user();

        // Fetch admin
        $admin = $this->model->find($id);

        // Fetch current user permission
        $userPermissions = $admin->permissions;

        foreach ($userPermissions as $userPermission){
            $userPer = $this->permission->whereId($userPermission->id);
            // Delete pivot linking the user to the permission
            $userPermission->pivot->delete();
        }

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} stripped
             {$admin->firstname} {$admin->lastname} the permission to {$userPermission->name}"
        ]);

        $admin->delete();

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} deleted
             {$admin->firstname} {$admin->lastname}"
        ]);

        return back()->with("success", "Admin deleted successfully");
    }


    public function viewProfile () {
        // Get authenticated user
        $user = auth()->user();

        // Return view
        return view('admin.settings.editProfile', compact('user'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProfile ( Request $request ) {
        // Get request data
        $data = $request->all();

        // Get Authenticated user
        $user = auth()->user();

        //Check if email exists
        $check = $this->model->where('email', $data['email'])->first();
        if(!is_null($check) && $check['email'] != $user->email)
            return back()->with('error', 'Account with same email address exists');

        // Update user
        $user->update($data);

        // Reload
        return back()->with('success', 'Profile update success');
    }


    /**
     * @param $id
     * @return Factory|View
     */
    public function viewPermissions ( $id ) {
        // Fetch user
        $user = $this->model->find( $id );

        // Get user permissions
        $userPermissions = $user->permissions;

        // Get other permissions
        $ids = [];
        foreach ( $userPermissions as $item )
            $ids[] = $item->id;
        $permissions = $this->permission->whereNotIn('id', $ids)
        ->orderBy('id', 'desc')
        ->get();

        // Return view
        return view('admin.administrators.viewPermissions', compact('userPermissions','permissions', 'user'));
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function addPermission (Request $request, $id) {
        // Get permission name
        $permission = $request->get('name');

        // Get authenticated user
        $user = auth()->user();

        // Fetch admin
        $admin = $this->model->find($id);

        // Assign new permission to Admin
        $admin->givePermissionTo($permission);

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} granted
             {$admin->firstname} {$admin->lastname} the permission to $permission"
        ]);

        // Reload
        return back();
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function stripPermission ($id ) {
        // Get authenticated
        $user = auth()->user();

        // Fetch admin
        $admin = $this->model->find($id);

        // Fetch current user permission
        $userPermission = $admin->permissions()->find(request('permission_id'));

        // Delete pivot linking the user to the permission
        $userPermission->pivot->delete();

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} stripped
             {$admin->firstname} {$admin->lastname} the permission to {$userPermission->name}"
        ]);

        return back()->with("success", "Permission stripped off {$admin->firstname}");
    }


    public function viewVerifyPage () {
        // Fetch initiated businesses
        $businesses = $this->business
            ->where('status', Business::$INITIATED)
            ->get();

        // Fetch business owners
        foreach ( $businesses as $business ) {
            foreach ( $business->users as $user ) {
                if ($user->hasRole('MERCHANT'))
                    $business->owner = $user;
            }
        }

        // Return view
        return view('admin.merchants.verifyBusiness', compact('businesses'));
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function verifyBusiness ( $id ) {
        // Get authenticated user
        $user = auth()->user();

        // Fetch business
        $business = $this->business->find( $id );

        // Update
        $business->update([
           'status' => Business::$VERIFIED
        ]);

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} verified
             the business, {$business->name}"
        ]);

        // Reload
        return back()->with('success', 'Business is now verified, Authorisation is still needed to be active');
    }


    public function viewAuthorisePage () {
        // Fetch verified businesses
        $businesses = $this->business
            ->whereIn('status', [Business::$VERIFIED, Business::$INACTIVE])
            ->get();

        // Fetch business owners
        foreach ($businesses as $business) {
            foreach ($business->users as $user) {
                if ($user->hasRole('MERCHANT'))
                    $business->owner = $user;
            }
        }

        // Return view
        return view('admin.merchants.authoriseBusiness', compact('businesses'));
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function authoriseBusiness ($id) {
        // Get authenticated user
        $user = auth()->user();

        // Fetch Business
        $business = $this->business->find($id);

        // Update business
        $business->update([
            'status' => Business::$ACTIVE
        ]);

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} verified
             the business, {$business->name}"
        ]);

        // Reload
        return back()->with('success', 'Business is now authorised');
    }


    public function viewTransactions () {
        // Get all transactions
//        $transactions =  $this->transaction->orderBy('id', 'desc')
//            ->with('business')
//            ->get();
//        $data = \request()->all();
        $now = Carbon::now();
        if (\request()->has('week')) {
            $startOfWeek = Carbon::now()->startOfWeek();
            $transactions = $this->transaction
                ->orderBy('id', 'desc')
                ->with('business')
                ->whereDate('created_at','>=', $startOfWeek)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'week';
        }
        elseif (\request()->has('month')) {
            $startOfMonth = Carbon::now()->startOfMonth();
            $transactions = $this->transaction
                ->orderBy('id', 'desc')
                ->with('business')
                ->whereDate('created_at','>=', $startOfMonth)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'month';
        }
        elseif (\request()->has('year')) {
            $startOfYear = Carbon::now()->startOfYear();
            $transactions = $this->transaction
                ->orderBy('id', 'desc')
                ->with('business')
                ->whereDate('created_at','>=', $startOfYear)
                ->whereDate('created_at','<=', $now)
                ->get();
            $option = 'year';
        }
        else {
            $startOfDay = Carbon::now()->startOfDay();
            $transactions = $this->transaction
                ->orderBy('id', 'desc')
                ->with('business')
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

        // Return view
        return view('admin.transactions.viewTransactions', compact('transactions','option'));
    }


    /**
     * @param $id
     * @return Factory|View
     */
    public function viewBusiness ( $id ) {
        // Get the business
        $business = $this->business->with(['users',
            'wallet','commissionTransactions', 'commissions', 'transactions' => function ($query) {
            $query->where('status', 'SUCCESSFUL');
        }])->find($id);
        // Get business owner
        foreach ($business->users as $user) {
            if ($user->hasRole('MERCHANT'))
                $business->owner = $user;
        }

        // Get Owner's products
        $products = $business->fee->products;
        sort($products);
        $transactions_volume = $business->transactions()->where('status', 'SUCCESSFUL')->sum('amount');

        // re-organise products array to suite the view
        foreach ($products as &$product) {
            if (is_array($product['vas_commission'])) {
                foreach ($product['vas_commission'] as $key => $item) {
                    $product['billers'][$key]['vas_commission'] = $item;
                }
                unset($product['vas_commission']);
            }

            if (is_array($product['merchant_commission'])) {
                foreach ($product['merchant_commission'] as $key => $item) {
                    $product['billers'][$key]['merchant_commission'] = $item;
                }
                unset($product['merchant_commission']);
            }

            $product['transactions_volume'] = $business->transactions()
                ->where('status', 'SUCCESSFUL')
                ->where('type', str_replace(' ', '-', $product['name']))
                ->sum('net_amount');

            $product['merchant_commissions'] = $business->commissionTransactions()
                ->where('product', str_replace(' ', '-', $product['name']))
                ->sum('amount');

            $product['vas_commissions'] = $business->commissions()
                ->where('product', str_replace(' ', '-', $product['name']))
                ->sum('amount');
        }
//        dd($products);

        // Return view
        return view('admin.merchants.viewBusiness', compact('business', 'products', 'transactions_volume'));
    }


    public function viewProductTransactions ($product = null) {
        $data = \request()->all();
        $business = $this->business->find($data['id']);
//        dd($product);
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

        return view('admin.merchants.viewProductTransactions', compact('product', 'business'));
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

        $current_product['vas_commissions'] = $business->commissions()
            ->orderBy('id', 'desc')
            ->whereDate('created_at','>=', $period)
            ->whereDate('created_at','<=', $now)
            ->where('product', str_replace(' ', '-', $product))
            ->get();

        return $current_product;
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function updateFees (Request $request, $id ) {
        // Get authenticated user
        $user = auth()->user();

        // Get request data
        $data = $request->all();

        // Fetch user with fee
        $merchant = $this->model->with('business')->find($id);

        // Get user's products
        $products = $merchant->business->fee->products;

        // Organize update data
        $newProducts = [];
        foreach ( $products as $key => $product ) {
            // Append new data to newProducts array
            $newProducts[] = [
                'name' => $product['name'],
                'slug' => $product['slug'],
                'charge' => $product['charge'],
                'charge_type' => $data["charge_type_{$product['slug']}"],
                'status' => isset($data["status_{$product['slug']}"]) ? true : false
            ];

            // Check if product is transfer to add specific fields
            if ($product['name'] == 'TRANSFER') {
                $newProducts[$key]['is_flat'] = isset($data["is_flat_{$product['slug']}"])  ? true : false;
                $newProducts[$key]['flat_charge'] = $data["flat_charge_{$product['slug']}"];
                $newProducts[$key]['flat_vas_commission'] = $data["flat_vas_commission_{$product['slug']}"];
            }

            // Check if product is payment gateway to add specific fields
            if ($product['name'] == 'PAYMENT GATEWAY') {
                $newProducts[$key]['charge'] = $data["charge_{$product['slug']}"];
                $newProducts[$key]['cap'] = $data["cap_{$product['slug']}"];

                // Check if payment gateway is enabled and business has no sagepay wallet
                if ($newProducts[$key]['status'] && is_null($merchant->business->sage_pay_wallet)) {
                    // Create wallet for payment gateway collections
                    $merchant->business->sage_pay_wallet()->create([
                        'account_number' => SagePayWallet::generateAccountNumber(),
                        'balance' => 0.0
                    ]);
                }
            }

            // Check if vas_commission and merchant_commission contain arrays of commissions or just single data of commissions
            if (!is_array($product['vas_commission']) && !is_array($product['merchant_commission'])) {
                // Assign new values
                $newProducts[$key]['vas_commission'] = $data["vas_commission_{$product['slug']}"];
                $newProducts[$key]['merchant_commission'] = $data["merchant_commission_{$product['slug']}"];
            }
            else {
                // Loop through to assign each a new value
                foreach ($product['vas_commission'] as $item => $value) {

                    // Push the new value of vas_commission for each biller to the newProducts array
                    $newProducts[$key]['vas_commission'][$item]
                        = $data['vas_commission_'.$product['slug'].'_'.str_replace(' ', '_', $item)];
                }
                // Loop through to assign each a new value
                foreach ($product['merchant_commission'] as $item => $value) {

                    // Push the new value of merchant_commission for each biller  to the newProducts array
                    $newProducts[$key]['merchant_commission'][$item]
                        = $data['merchant_commission_'.$product['slug'].'_'.str_replace(' ', '_', $item)];
                }
            }
        }
//        dd($newProducts);

        // Save data
        $merchant->business->fee->update([
            'products' => $newProducts
        ]);

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} updated the fees
             for the business, {$merchant->business->name}"
        ]);

        // Reload
        return back()->with('message', 'Fees updated successfully');
    }

    public function deactivateMerchant ($id) {
        // Get authenticated user
        $user = auth()->user();

        // Fetch Business
        $business = $this->business->find($id);

        // Update business
        $business->update([
            'status' => Business::$INACTIVE
        ]);

        // Log activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} deactivated
             the business, {$business->name}"
        ]);

        // Reload
        return back()->with('success', 'Business is now deactivated');
    }


    public function viewDocuments () {
        $id = \request()->get('id');
        // Get user's business
        $business = $this->business->where('id', $id)
            ->select([
                'id',
                'certificate_of_incorporation',
                'articles_of_association',
                'cac_form',
                'other_document'
            ])
            ->with(['directors', 'beneficial_owners'])
            ->first();
//        dd($business);

        return view('admin.merchants.viewDocuments', compact('business'));
    }


    public function filterProductTransactions (Request $request) {
        $data = $request->all();
        $current_product = $data['product'];

        $business = $this->business->find($data['id']);
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

        return view('admin.merchants.viewProductTransactions', compact('product', 'business', 'filter'));

    }


    public function filterTransactions (Request $request) {
        $data = $request->all();

        $to_date = new Carbon($data['to_date']);
        $from_date = new Carbon($data['from_date']);
        $transactions = (object)[];

        if (is_null($data['reference'])) {
            if ($data['product'] === 'all' && $data['status'] === 'all' ) {
                $transactions = $this->transaction
                    ->orderBy('id', 'desc')
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get();
            }
            elseif ($data['product'] !== 'all' && $data['status'] === 'all' ) {
                $transactions = $this->transaction
                    ->orderBy('id', 'desc')
                    ->where('type', $data['product'])
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get();
            }
            elseif ($data['product'] === 'all' && $data['status'] !== 'all' ) {
                $transactions = $this->transaction
                    ->orderBy('id', 'desc')
                    ->where('status', $data['status'])
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get();
            }
            elseif ($data['product'] !== 'all' && $data['status'] !== 'all' ) {
                $transactions = $this->transaction
                    ->orderBy('id', 'desc')
                    ->where([
                        'status'=> $data['status'],
                        'type'=> $data['product']
                    ])
                    ->whereDate('created_at','>=', $from_date)
                    ->whereDate('created_at','<=', $to_date)
                    ->get();
            }
        }
        else {
            if ($data['product'] === 'all' && $data['status'] === 'all' ) {
                $transactions = $this->transaction
                    ->orderBy('id', 'desc')
                    ->where('reference', $data['reference'])
                    ->get();
            }
            elseif ($data['product'] !== 'all' && $data['status'] === 'all' ) {
                $transactions = $this->transaction
                    ->orderBy('id', 'desc')
                    ->where('type', $data['product'])
                    ->where('reference', $data['reference'])
                    ->get();
            }
            elseif ($data['product'] === 'all' && $data['status'] !== 'all' ) {
                $transactions = $this->transaction
                    ->orderBy('id', 'desc')
                    ->where('status', $data['status'])
                    ->where('reference', $data['reference'])
                    ->get();
            }
            elseif ($data['product'] !== 'all' && $data['status'] !== 'all' ) {
                $transactions = $this->transaction
                    ->orderBy('id', 'desc')
                    ->where([
                        'status'=> $data['status'],
                        'type'=> $data['product']
                    ])
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

        return view('admin.transactions.viewTransactions', compact('transactions', 'option', 'dates', 'filter'));

    }

    public function updateTransactionStatus ( Request $request ) {
        $data = $request->all();
        $transaction =  $this->transaction->find($data['transaction_id']);
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
            "info" => "{$user->firstname} {$user->lastname} updated transaction of ref, {$transaction->reference}
             for the business, {$transaction->business->name} to {$transaction->status}"
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status Updated Successfully'
        ]);
    }

    public function query()
    {
        $queries = ReQuery::where('status','pending')->get();
        return view('admin.settings.query', compact('queries'));
    }
}
