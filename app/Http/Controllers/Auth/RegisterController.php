<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Wallet;
use App\Http\Controllers\Controller;
use App\Model\Business;
use App\Model\Product;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

//    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * @var \App\Model\User
     */
    private $model;
    private $product;
    private $business;

    /**
     * Create a new controller instance.
     *
     * @param \App\Model\User $user
     * @param Product $product
     * @param Business $business
     */
    public function __construct(\App\Model\User $user, Product $product, Business $business )
    {
        $this->middleware('guest');
        $this->model = $user;
        $this->product = $product;
        $this->business = $business;
    }

    public function showRegistrationForm () {
        // Return view
        return view('landing.signUp');
    }

    public function register ( Request $request ) {
        // Get request data
        $input = $request->all();

        $validated = Validator::make($input, [
            'firstname' => ['required', 'string', 'max:20'],
            'lastname' => ['required', 'string', 'max:20'],
            'phone' => ['required',  'min:11', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6']
        ]);

        if ($validated->fails()) {
            return back()
                ->withInput()
                ->withErrors($validated);
        }


        // Hash password
        $input['password'] = bcrypt($input['password']);

        // Check if email exists in users table
        if ( $this->model->where('email', $request->get('email'))->exists() ) {
            // Reload with error message
            return back()->with('error', 'Account with same email address exists')->withInput($request->all());
        }

        // Check if email exists in business table
        if ( $this->business->where('email', $request->get('email'))->exists() ) {
            // Reload with error message
            return back()->with('error', 'Business with same email address exists')->withInput();
        }

        // Check if business name exists
        if ( $this->business->where('name', $request->get('business_name'))->exists() ) {
            // Reload with error message
            return back()->with('error', 'Business with the same name exists')->withInput();
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


        // Get products
        $products = $this->product->orderBy('id', 'desc')
            ->select(['id','slug', 'name', 'charge',
                'charge_type','vas_commission',
                'merchant_commission'])
            ->get();

        foreach ( $products as $product ) {
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

        // Redirect
        return redirect()->route('login')
            ->with('success', 'Registration successful, You can sign in now');
    }
}
