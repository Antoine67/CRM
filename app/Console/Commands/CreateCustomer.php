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
use App\DatasourcesDefaultUsage;
use App\DatasourceDefault;

class CreateCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:customer {--id=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update customer';

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
        $customer;
        if( $this->option('id') == null ) {
            //New user
        }else if ( filter_var($this->option('id'), FILTER_VALIDATE_INT) !== false ){
            //Update
            $customer = Customer::find($this->option('id'));
            if(is_null($customer)) {
                 $this->error('Customer not found with given ID');
            }

            $this->refreshFromDataSource(
                                            $customer ,
                                            DatasourcesDefaultUsage::where('id_customer', '=', $customer->id)->get()
                                        );
        }else {
            $this->error('Invalid ID provided');
        }

        $customer->updated_at=Carbon::now('Europe/Paris');
        $customer->update();
       
    }

    /**
     *
     * Update customer's informations (tickets, files,...) from stored datasources
     *
     */

    public function refreshFromDataSource($customer, $datasourcesDefaultUsage) {

        $dsefaultUsage = array();

        //Get preferences : either default query, or custom one
        foreach($datasourcesDefaultUsage as $dsdu) {
            $dsDefaultUsage[$dsdu->table_associated] = $dsdu->default_usage;
        }

		$datasources = $customer->datasources()->get();
         
		foreach($datasources as $ds) {
            
			try {
                $query; $db;

                //If default usage , or not specified -> execute default query on default db
                if(!array_key_exists($ds->table_associated, $dsDefaultUsage) || $dsDefaultUsage[$ds->table_associated] > 0) {
                    $defaultQuery = $this->refreshFromDefaultQuery($customer, $ds->table_associated);
                    $db = $defaultQuery['db'];
                    $query = $defaultQuery['query'];
                }else {
                    $db = $ds->database()->first();
                    $query = $ds->query;
                }

                Config::set("database.connections.refreshConnection-$db->name", [
				    "host" => Utils::ifNotNull( $db->host,''),
				    "port" => Utils::ifNotNull($db->port,''),
				    "database" =>  Utils::ifNotNull($db->name,''),
				    "username" =>  Utils::ifNotNull( $db->username,''),
				    "password" =>  Utils::ifNotNull( $db->password,''),
				    "driver" =>  Utils::ifNotNull($db->driver,''),
			    ]);


				Ticket::where('id_customer', '=', $customer->id )->delete();

				$result = DB::connection("refreshConnection-$db->name")->select( $query );
                
                foreach($result as $resultStd) {
                    try {
                        $re = json_decode(json_encode($resultStd), true);
				        $re['id_customer'] = $customer->id;
                        $re['date'] = new Carbon( $re['date'], 'Europe/Paris');
                        //dd($re);
				        switch($ds->table_associated) {
					        case 'files' :
                                $this->info('Trying to create a new associated file...');
						        File::create($re);
                                $this->line('Success');
						        break;
					        case 'tickets' :
                                $this->info('Trying to create a new associated ticket...');
						        Ticket::create($re);
                                $this->line('Success');
						        break;
                            default:
                                $this->line('Associated table not found');
                                break;
                        }
                    }catch (\Exception $e) {
				        $this->error("Error while updating/creating user : " . $e->getMessage());
			        }
                }
			} catch (\Exception $e) {
				$this->error("Error with database $db->name: " . $e->getMessage());
			}
			
		}

    }


    private function refreshFromDefaultQuery($customer, $table_associated) {
        $this->info("Customer n°$customer on $table_associated  -> default query");
        $dsdefault = DatasourceDefault::where('table_associated', '=', $table_associated)->first();
        return ['db' => Database::find($dsdefault->id_database), 'query' => $dsdefault->query ];
    }

}


