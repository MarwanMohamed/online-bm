<?php

namespace App\Http\Controllers;

class QuickPayController extends Controller
{
    public function quickPay()
    {
        return view('site.payment.quickpay')->with(['title' => 'Make Direct Payment']);
    }
}