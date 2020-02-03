<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customer;
use App\Datasource;
use App\Database;
use DB;
use Carbon\Carbon;
use Config;
use App\Utils;
use App\File;
use App\Ticket;

class CreateCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user {--user=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if( $this->option('user') == null ) {
            //New user
        }else if ( filter_var($this->option('user'), FILTER_VALIDATE_INT) !== false ){
            //Update
            $customer = Customer::find($this->option('user'));
            if(is_null($customer)) {
                 $this->error('Customer not found with given ID');
            }
            $this->refreshFromDataSource($customer);
        }else {
            $this->error('Invalid ID provided');
        }
       
    }

    public function refreshFromDataSource($customer) {
		$datasources = $customer->datasources()->get();
		foreach($datasources as $ds) {
			$db = $ds->database()->first();
			
			Config::set("database.connections.refreshConnection", [
				"host" => Utils::ifNotNull( $db->host,''),
				"port" => Utils::ifNotNull($db->port,''),
				"database" =>  Utils::ifNotNull($db->name,''),
				"username" =>  Utils::ifNotNull( $db->username,''),
				"password" =>  Utils::ifNotNull( $db->password,''),
				"driver" =>  Utils::ifNotNull($db->driver,''),
			]);
        
			try {

				Ticket::where('id_customer', '=', $customer->id )->delete();

				$result = DB::connection('refreshConnection')->select( DB::raw( $ds->query ));
				$result['id_customer'] = $customer->id;
				switch($ds->table_associated) {
					case 'files' :
						File::create($result);
						break;
					case 'tickets' :
						Ticket::create($result);
						break;
                }

			} catch (\Exception $e) {
				$this->error("Could not connect to the database $db->name:" . $e->getMessage());
			}
			
		}

    }


}


