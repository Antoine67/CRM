<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class EditorController extends Controller
{
    public function post() {
        if(Auth::check() && Auth::user()->permission_level >= env('EDITOR_LEVEL', 2) ) {
            Auth::user()->editor_mode = !Auth::user()->editor_mode;
             Auth::user()->save();
            return redirect()->back();
        }
    }
}
