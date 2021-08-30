<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{


    public function index()
    {

        $user = auth()->user();
        //dd('kkk');
//        dd($user->hasAllRoles(Role::all()));
//      dd($user);
        // This user is a merchant
        if ( $user->hasRole('MERCHANT|USER') ) {
            return redirect()->route('merchant.index');
        }

        // This user is an admin
        if ( $user->hasRole('SUPER_ADMIN|ADMIN') ) {
            return redirect()->route('admin.index');
        }

        return abort(403);
    }
}
