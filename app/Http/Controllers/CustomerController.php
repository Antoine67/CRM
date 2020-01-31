<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Session;
use Carbon\Carbon;
use App\Customer;
use DB;
use App\Utils;

class CustomerController extends Controller
{
    public function getById($id, Request $request)  {

        $customer = Customer::find($id);
        
        return view('customer')->with('customer',$customer);
    }

    /***
    * Display all customers from saved file
    ***/
    public function getAll(Request $request)  {


      return view("customers")
            ->with('customers', \App\Customer::all()); 
      
    }


    public function updateById($id, Request $request) {
        $customer = $this->updateOrCreateCustomer($id);
        if($customer) {
            Session::put('successMessage', "Mise à jour réussie");
        }else {
            Session::put('msgError', "Mise à jour échouée");
        }
        
        return response(200);
    }

    public function updateOrCreateCustomer($id) {
    
        
        return "aa";
    }

}

