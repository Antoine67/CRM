<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Session;
use Storage;
use Carbon\Carbon;
use Artisan;
use GuzzleHttp\Client;
use DB;
use App\Customer;
use App\Utils;

class RefreshListCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:customersList';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh customers lists';

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
        $this->refreshCustomersListFromDB();
        //$this->refreshCustomersFromSharepoint();

        if(!Storage::exists('customers.conf'))  Storage::put('customers.conf', '');
        $conf = json_decode(Storage::get('customers.conf'), true);
        $conf['lastCustomersUpdate'] = Carbon::now('Europe/Paris')->toDateTimeString();
        
        Storage::put('customers.conf', json_encode($conf));
    }


    //SHOULD WORK BUT COULDN'T TEST SINCE NO ADMIN PERMS

    /***
    * Get data from Microsoft Graph API and save it into a local file, to avoid making excessive requests
    ***/
    public function refreshCustomersFromSharepoint() {

        //Get token as application and not as user
        $urlToken = "https://login.microsoftonline.com/acesi.onmicrosoft.com/oauth2/v2.0/token";

        $body = ['client_id' => env('OAUTH_APP_ID'), 'scope' => 'ea55abff-9153-4fa7-b167-e58a3edbe76e/.default' , 'client_secret' => env('OAUTH_APP_PASSWORD') , 'grant_type' => 'client_credentials'];

        //HTTP Client
        $client = new Client(['verify' => false]);
        $res = $client->post( $urlToken, ['debug' => true, 'form_params' => $body]);
        $access_token = json_decode($res->getBody(), true)['access_token'];


        $graph = new Graph();
        $graph->setAccessToken($access_token);
        $graphClient  = new Client(['verify' => false /*, 'headers' => ['Authorization' => 'Bearer '.$access_token] */]);

        $maxNumberIterations = 10;

        $urlClients = 'https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!zRfTFj8KRkW6OuScTfSQPLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/root/children';
        $sharepointClientsData = $graph->createRequest('GET', $urlClients)->execute();

        $clientsData = $this->recursiveCallNextLink($sharepointClientsData->getBody(), $graph, $sharepointClientsData->getBody()['value'], $maxNumberIterations,0);

        $urlExtranet = 'https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/root/children' ;
        $sharepointExtranetData = $graph->createRequest('GET', $urlExtranet)->execute();
        $extranetData = $this->recursiveCallNextLink($sharepointExtranetData->getBody(), $graph, $sharepointExtranetData->getBody()['value'], $maxNumberIterations,0);

        Storage::put('sharepoint-clients.json', json_encode($clientsData));
        Storage::put('sharepoint-extranet.json', json_encode($extranetData));

        return "Successfully executed";
    }

    /***
    * If data returned by API is too important, it will return a 'next link', this function allows to recursively get data from these next links
    *
    */
    private function recursiveCallNextLink($previousSPData, $graph, $values, $maxNumberIterations, $currentIter=0) {
        if(!array_key_exists('@odata.nextLink',$previousSPData) || $currentIter >= $maxNumberIterations) {
            return $values;
        }
        

        $data = $graph->createRequest('GET', $previousSPData['@odata.nextLink'])->execute();
        $mergedData= array_merge($values, $data->getBody()['value']);

        $currentIter+=1;

        return $this->recursiveCallNextLink($data->getBody(), $graph, $mergedData, $maxNumberIterations, $currentIter);
    }

    /***
    * Get data from EasyVista DB and store it as an array of Customer objects
    ***/
    public function refreshCustomersListFromDB() {
         $customers = DB::connection('easyvista')->table('EVO_DATA50005.50005.AM_DEPARTMENT')->select(array('DEPARTMENT_ID','DEPARTMENT_FR','DEPARTMENT_CODE', 'E_TEL_VITA'))->take(1000)->get();
         $customersArray = array();

         $files = Storage::files('easyvista');
         Storage::delete($files);

         //Create news customers and fill an array with 
         foreach ($customers as $customer) {
         
            $cus = new Customer([
                'id' => $customer->DEPARTMENT_ID,
                'name' => $customer->DEPARTMENT_FR,
                'code' => $customer->DEPARTMENT_CODE,
                'E_TEL_VITA' => $customer->E_TEL_VITA,
            ]);

            //Write in local file to save data
            $displayName = (empty($cus->getCode()) ? $cus->getName() : $cus->getCode());
            $displayName = Utils::normalizeName($displayName);


            //Example : /easyvista/acesi
            $nomenclature = "/easyvista/$displayName";
            while(Storage::exists($nomenclature)) {
                $nomenclature .= '_';
            }
            Storage::put($nomenclature, json_encode($cus));
         }

        

    }
}
