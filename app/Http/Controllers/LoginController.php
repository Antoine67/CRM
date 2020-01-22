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

    public function fullLogout() {
        if(Session::has('user') && Session::has('access_token')) {
            return redirect('/');
        }
        return redirect('https://login.windows.net/common/oauth2/logout?post_logout_redirect_uri=https://plp.acesi');
    
    }

}
