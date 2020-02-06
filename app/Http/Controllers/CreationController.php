<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Carbon\Carbon;

class CreationController extends Controller
{
    public function get() {
        return view('creation');
    }

    public function post(Request $request) {
        $inp = $request->all();
        //TODO add input verf
        $inp['created_at'] = Carbon::now('Europe/Paris');
        $path = $request->file('pictureProfile')->store('public/pictureProfile');

        $parts = explode('/', $path);
        array_shift($parts);
        $newpath = implode('/', $parts);

        $inp['picture'] = $newpath;
        $customer = Customer::create($inp);
        
        
    }
}
