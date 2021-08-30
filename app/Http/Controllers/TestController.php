<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
//        dd('ss');
        return view('sage_pay.index');
    }
}
