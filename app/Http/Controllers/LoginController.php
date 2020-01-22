<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    public function get() {
        if(Session::has('user') && Session::has('access_token')) {
            return redirect('/');
        }
        return view('login');
    }
}
