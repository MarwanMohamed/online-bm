<?php

namespace App\Http\Controllers;

class InsuranceController extends Controller
{
    public function index()
    {
        return view('site.insurance.new');
    }

    public function thirdparty()
    {
        return view('site.insurance.thirdparty');

    }
    public function comprehensive()
    {
        return view('site.insurance.comprehensive');

    }
}