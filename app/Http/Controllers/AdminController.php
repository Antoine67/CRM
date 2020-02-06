<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Database;
use Config;
use DB;

class AdminController extends Controller
{
    public function get() {
        //TODO
    }

    public function postDatasources(Request $request) {
        $db = $request->input('db');
        $db_edit_create = $request->input('db_edit_create');

        //Check connection
        if( isset($db) ) { 
            $conStatus = $this->testConnection($db);
            if(!isset($conStatus)) {
                return response()->json([
                    'msg' => 'Successfully connected',
                ], 200);
            }else {
                return response()->json([
                    'msg' => $conStatus,
                ], 500);
            }
        }

        //Create or edit conn
        else if ( isset($db_edit_create) ) {
            
            switch($request->input('type')) {
                case 'new' :
                    Database::create($db_edit_create);
                    return response()->json([
                        'msg' => 'Successfully created',
                    ], 200);
                    break;
                case 'edit' :
                    Database::find($request->input('id'))->update($db_edit_create);
                    return response()->json([
                    'msg' => 'Successfully edited',
                ], 200);
                    break;
            }
        }
    }

    public function datasources() {
        $databases = Database::all();
        $drivers = ['mysql', 'sqlite','sqlsrv', 'pgsql'];
        return view('admin/datasources')
        ->with('databases',$databases)
        ->with('drivers',$drivers);
    }

    public function testConnection($db) {
        Config::set("database.connections.testConnection", [
            "host" => $this->ifNotNull( $db['host'],''),
            "port" => $this->ifNotNull( $db['port'],''),
            "database" =>  $this->ifNotNull( $db['name'],''),
            "username" =>  $this->ifNotNull( $db['username'],''),
            "password" =>  $this->ifNotNull( $db['password'],''),
            "driver" =>  $this->ifNotNull( $db['driver'],''),
        ]);
        
        try {
            $con = DB::connection('testConnection')->getPdo();
        } catch (\Exception $e) {
            return ("Could not connect to the database");
        }
        return null;
    }

    private function ifNotNull($val, $def) {
        if(!isset($val)) {
            return $def;
        }
        return $val;
    }

}
