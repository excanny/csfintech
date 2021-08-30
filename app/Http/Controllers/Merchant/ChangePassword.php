<?php

namespace App\Http\Controllers\Merchant;

use App\Model\User;
use App\Rules\MatchPreviousPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChangePassword extends Controller
{
    private $model;

    /**
     * User constructor.
     * @param User $user
     */
    public function __construct( User $user )
    {
        $this->model = $user;
    }


    public function index () {
        return view('merchant.settings.changePassword');
    }


    public function store(Request $request)
    {
        // Validate password
        $request->validate([
            'current_password' => ['required', new MatchPreviousPassword],
            'new_password' => ['required'],
            'confirm_new_password' => ['same:new_password'],
        ]);

        // Get authenticated user
        $user = auth()->user();

        // Update the new password
        $this->model->find($user->id)->update(['password'=> bcrypt($request->new_password)]);

        return redirect()->route('settings.profile')->with('success', 'Password changed successfully');
    }
}
