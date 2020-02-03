<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Session;
use Carbon\Carbon;
use App\Customer;
use App\Database;
use App\Datasource;
use App\Ticket;
use App\File;
use DB;
use App\Utils;
use Auth;
use Schema;

class CustomerController extends Controller
{
    public function getById($id, Request $request)  {


    $customer = Customer::find($id);
    if ( is_null($customer) ) {
        abort(404);
    }


    $databases = null;  $tables = null;

    //If user is logged as editor, and is in edit mode
    if(Auth::check() &&
       Auth::user()->permission_level >= env('EDITOR_LEVEL', 2) &&
       Auth::user()->editor_mode)
    {
        $databases = Database::all();
        if($databases->isEmpty()) $databases = null;

        $tickets = Schema::getColumnListing('tickets');
        if(($key = array_search('id', $tickets)) !== false) unset($tickets[$key]);
        $files =  Schema::getColumnListing('files');
        if(($key = array_search('id', $files)) !== false) unset($files[$key]);
        $tables = [
            'tickets' => $tickets,
            'files' => $files,
        ];
    }

    $tickets = $customer->tickets()->get();
   
    return view('customer')
        ->with('customer',$customer)
        ->with('databases',$databases)
        //->with('files', $customer->files()->get())
        ->with('tickets', $tickets)
        ->with('tables',$tables);
    }

    /***
    * Display all customers from saved file
    ***/
    public function getAll(Request $request)  {


      return view("customers")
            ->with('customers', \App\Customer::all()); 
      
    }


    public function updateById($id, Request $request) {
        if( $request->input('datasource') !== null ) {
            Datasource::create([
                'table_associated' => $request->input('datasource')['tableAssociated'],
                'query' => $request->input('datasource')['query'],
                'id_database' => $request->input('datasource')['databaseId'],
                'id_customer' => $id,
            ]);
            return response('');
        }else {
             $customer = $this->updateOrCreateCustomer($id);
            if($customer) {
                Session::put('successMessage', "Mise à jour réussie");
            }else {
                Session::put('msgError', "Mise à jour échouée");
            }
        
            return response('');
        }


       
    }

    public function updateOrCreateCustomer($id) {
    
        
        return "aa";
    }

}

