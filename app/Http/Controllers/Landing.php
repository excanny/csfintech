<?php

namespace App\Http\Controllers;

use App\Mail\SupportEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class Landing extends Controller
{
    public function sendMail ( Request $request ) {
        $data = $request->except('_token');

        $mail = Mail::to(env('SUPPORT_MAIL'))->send(new SupportEmail($data));

        return back()->with('success', 'Your mail has been received, we will get back to you shortly');
    }
}
