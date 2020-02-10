<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Carbon\Carbon;
use App\DatasourcesDefaultUsage;

class CreationController extends Controller
{
    public function get() {
        return view('creation');
    }

    public function post(Request $request) {
        $inp = $request->all();
        //TODO add input verf
        $inp['created_at'] = Carbon::now('Europe/Paris');

        $newpath = null;
        if( $request->file('pictureProfile') != null ) {
            $path = $request->file('pictureProfile')->store('public/pictureProfile');
            $parts = explode('/', $path);
            array_shift($parts);
            $newpath = implode('/', $parts);
        }

        
        

        

        $inp['picture'] = $newpath;
        $customer = Customer::create($inp);
        $customer_id = $customer->id;


        $editable_areas = ['files', 'tickets'];
        foreach($editable_areas as $area) {
            DatasourcesDefaultUsage::create([
                'id_customer' => $customer_id,
                'table_associated' => $area,
                
            ]);
        }
        return redirect ('customer');
        
        
    }
}
