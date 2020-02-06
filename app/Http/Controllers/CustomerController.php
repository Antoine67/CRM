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
use Artisan;

class CustomerController extends Controller
{
    public function getById($id, Request $request)  {


    $customer = Customer::find($id);
    if ( is_null($customer) ) {
        abort(404);
    }


    $databases = null;  $tables = null; $datasources = null;

    //If user is logged as editor, and is in edit mode
    if(Auth::check() &&
       Auth::user()->permission_level >= env('EDITOR_LEVEL', 2) &&
       Auth::user()->editor_mode)
    {
        $databases = Database::all();
        if($databases->isEmpty()) $databases = null;

        $datasources = Datasource::where('id_customer', '=', $id)->get();
         if($datasources->isEmpty()) $datasources = null;

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
    if($tickets->isEmpty()) $tickets = null;
    

   
    return view('customer')
        ->with('customer',$customer)
        ->with('databases',$databases)
        ->with('datasources',$datasources)
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

        //Update a datasource (database, query)
        if( $request->input('datasource') !== null ) {

            $alreadyExist = Datasource::where('table_associated', '=', $request->input('datasource')['tableAssociated'] )->where('id_customer', '=', $id)->first();

            if(!is_null($alreadyExist)) { // Need to update datasource
                Datasource::where('table_associated', '=', $request->input('datasource')['tableAssociated'] )->where('id_customer', '=', $id)->first()->update([
                    'query' => $request->input('datasource')['query'],
                    'id_database' => $request->input('datasource')['databaseId'],
                ]);

                return response()->json([
                    'msg' => 'Mise à jour réussie',
                ], 200);
            }else { // Need to create datasource
                Datasource::create([
                'table_associated' => $request->input('datasource')['tableAssociated'],
                'query' => $request->input('datasource')['query'],
                'id_database' => $request->input('datasource')['databaseId'],
                'id_customer' => $id,
            ]);
                return response()->json([
                    'msg' => 'Création réussie',
                ], 200);
            }
            
        }

        //Update completely the current customer
        else {
             $error_code = $this->updateOrCreateCustomer($id);
             $http;
            if($error_code === 0) {
                Session::put('successMessage', "Mise à jour réussie");
                $http = 200;

            }else {
                Session::put('msgError', "Mise à jour échouée. Code d'erreur : $error_code");
                $http = 500;
            }

            return response()->json([
                    'msg' => $error_code,
                ], $http);
        }  
    }

    public function updateOrCreateCustomer($id) {
    
        $exitCode = Artisan::call('create:customer', [
           '--id' => $id
        ]);
        return $exitCode;
    }

}

