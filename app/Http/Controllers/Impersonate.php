<?php

namespace App\Http\Controllers;

use App\Model\Business;
use Illuminate\Http\Request;

class Impersonate extends Controller
{
    /**
     * @var Business
     */
    private $business;

    public function __construct(Business $business)
    {
        $this->business = $business;
    }

    public function index ($id) {
        $business = $this->business->find($id);
        foreach ($business->users as $user) {
            if ($user->hasRole('MERCHANT')) {
                $owner = $user;
            }
        }
        auth()->user()->impersonate($owner);
        return redirect()->route('merchant.index');
    }

    public function leave () {
        auth()->user()->leaveImpersonation();
        return redirect()->route('merchants.view');
    }
}
