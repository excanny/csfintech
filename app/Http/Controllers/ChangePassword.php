<?php

namespace App\Http\Controllers;

use App\Rules\MatchPreviousPassword;
use App\Model\User;
use Illuminate\Http\Request;

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
        // Return view
        return view('admin.settings.changePassword');
    }

    public function store( Request $request )
    {
        // Validate password
        $request->validate([
            'current_password' => ['required', new MatchPreviousPassword],
            'new_password' => ['required'],
            'confirm_new_password' => ['same:new_password'],
        ]);

        // Get authenticated user
        $user = auth()->user();

        // Update new password
        $this->model->find($user->id)
            ->update([
                'password'=> bcrypt($request->new_password)
            ]);

        // Redirect
        return redirect()->route('admin.settings.profile')->with('success', 'Password changed successfully');
    }
}
