<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getById($id, Request $request)  {
        return view('customer')->with('id',$id);
    }

    public function getAll(Request $request)  {
        return view('customers');
    }
}
