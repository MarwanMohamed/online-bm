<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
//        $this->load->library('session');
		$title = 'Home';
		$heading = "Online Motor Insurance";
		$footerchk = 1;

    	return view('site.home_page', compact('title', 'heading', 'footerchk'));
    }
}
