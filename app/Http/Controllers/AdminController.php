<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Database;
use Config;
use DB;
use App\Utils;
use App\DatasourceVariablesDefinition;
use App\DatasourceDefault;

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
            "host"          =>      Utils::ifNotNull( $db['host'],''),
            "port"          =>      Utils::ifNotNull( $db['port'],''),
            "database"      =>      Utils::ifNotNull( $db['name'],''),
            "username"      =>      Utils::ifNotNull( $db['username'],''),
            "password"      =>      Utils::ifNotNull( $db['password'],''),
            "driver"        =>      Utils::ifNotNull( $db['driver'],''),
        ]);
        
        try {
            $con = DB::connection('testConnection')->getPdo();
        } catch (\Exception $e) {
            return ("Could not connect to the database");
        }
        return null;
    }

    public function datadefault() {

        $databases = Database::all();
        if($databases->isEmpty()) $databases = null;

        //Define editable areas
        $editableArea = [
            [ 'name' => 'Fichiers', 'table' => 'files' ] ,
            [ 'name' => 'Tickets', 'table' => 'tickets' ],
        ];

        //Get query and vars of these editable areas
        $datadefaults = array();
        foreach($editableArea as $area) {

            $default = DatasourceDefault::where('table_associated', '=', $area['table'])->first();
           
            array_push ($datadefaults,
            [
                'name' => $area['name'],
                'default_query' => is_null($default) ?  "" : $default->query,
                'id' => $default->id,
                'id_database' => $default->id_database,
            ]);
        }

        $vars =  DatasourceVariablesDefinition::all();

        return view('admin/datadefault')
            ->with('databases', $databases)
            ->with('datadefaults', $datadefaults)
            ->with('vars', $vars);
    }

    public function postDatadefault(Request $request) {
        $datasources_variables_definition = $request->input('datasources_variables_definition');
        $datasources_default = $request->input('datasources_default');
        $var_id_to_delete = $request->input('var_id_to_delete');
        
		$needRefresh = false;
        //Edit data default
        if (isset($datasources_default))
        {
            try {
                //Update data default definition
                $datasourcedef = DatasourceDefault::find($datasources_default['id']);
                $datasourcedef->update($datasources_default);

            }catch (\Exception $e) {
                return response()->json(['msg' => 'Error happened : ' . $e ,'needRefresh' => $needRefresh], 500);
            }

         
        }else if (isset($datasources_variables_definition)) {
            
            try {
                //Update or create associated vars
                foreach($datasources_variables_definition as $var) {
                    if( array_key_exists('id', $var) && $var['id'] != null ) {
                        $datasourcevardef = DatasourceVariablesDefinition::find($var['id']);
                        $datasourcevardef->update($var);
                    }else {
                        DatasourceVariablesDefinition::create($var);
						$needRefresh = true;
                    }
                }
            }catch (\Exception $e) {
                return response()->json(['msg' => 'Error happened : ' . $e ,'needRefresh' => $needRefresh], 500);
            }
                
        }else if(isset($var_id_to_delete)) {
            DatasourceVariablesDefinition::find($var_id_to_delete)->delete();
        }

        else {
            return response()->json(['msg' => 'Error : missing or incorrect fields','needRefresh' => $needRefresh], 500);
        }
        return response()->json(['msg' => 'Succès','needRefresh' => $needRefresh], 200);
        
    }

}
