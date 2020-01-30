<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Session;
use Auth;

class ProfileController extends Controller
{
    public function get() {
        
        $user = Auth::user();

        return view('profile')
        ->with('user',$user);
    }

}
