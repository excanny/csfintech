<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

//    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm () {
        // Return view
        return view('landing.signIn');
    }

    public function login () {
        // Check user's credentials
        if ( auth()->attempt(['email' => request('email'), 'password' => request('password')], request('remember')) ) {
            // Redirect to dashboard
            return redirect()->route('dashboard');
        }

        // Reload with error message
        return back()->with('error', 'Invalid credentials')->withInput();
    }

    public function logout () {
        auth()->logout();
        return redirect('login');
    }
}
