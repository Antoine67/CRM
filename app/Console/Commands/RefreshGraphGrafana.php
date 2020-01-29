<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Storage;
use Carbon\Carbon;

class RefreshGraphGrafana extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:grafana';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh grafana graphs';

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
        $this->refreshAllGraph();
    }

    public function refreshAllGraph() {
        //Read from conf file
        if(!Storage::exists('grafana.conf'))  Storage::put('grafana.conf', '');
        $conf = json_decode(Storage::get('grafana.conf'), true);
        
        if(isset($conf['lastAllGrafUpdate'])) {
            $lastUpd = Carbon::create($conf['lastAllGrafUpdate'],'Europe/Paris');

            //Security to be sure that it is not call too often
            if($lastUpd->diffInSeconds(Carbon::now('Europe/Paris')) < env('GRAFANA_REFRESH_COOLDOWN', 60)) {
                return;
            }
        }

        //Panel's ID to display (order is important, first in array first display in view)
        $panelsID = array(5,13, 18, 10, 14, 17, 12);
        $i = 1;
        $width=300; $height=300;
        foreach($panelsID as $id) {
            if($id == 12) {
                $width = 900;
                $height = 600;
                $this->updateGraph(1, $id, $width, $height, "grafana-$i", 'now-11d');
            }else {
                $this->updateGraph(1, $id, $width, $height, "grafana-$i");
            }
            
            $i++;
        }

       
        $conf['lastAllGrafUpdate'] = Carbon::now('Europe/Paris')->toDateTimeString();
        Storage::put('grafana.conf', json_encode($conf));
    }

    public function updateGraph($orgId, $panelId, $width, $height, $name, $from = 'now-24h' , $to = 'now') {

            $apiKey = "eyJrIjoiZ2lCaHFjQ2Y2cFJNNDBlcnJUMEZqWGRpMEJCdUM2SWMiLCJuIjoiYWRtaW4iLCJpZCI6MX0=";
            $url = "http://10.128.204.145:3000/render/dashboard-solo/db/acesi-indicateurs-de-pilotage-vita?orgId=$orgId&panelId=$panelId&from=$from&to=$to&width=$width&height=$height";
           
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => $url,
                // You can set any number of default request options.
                'timeout'  => 5.0,
                'headers' => ['Authorization' => "Bearer ". $apiKey],
                'sink' => public_path() . "/img/grafana/$name.png"
            ]);

           
            try {
                $response = $client->request('GET');
            } catch (\Guzzle\Http\Exception\BadResponseException $e) {
                $result = $e->getResponse()->getStatusCode();

                if ($result === 401) {
                    return "401 error !";
                }
            } catch(\Guzzle\Http\Exception\ConnectException $e) {
                return "Timeout";
            } catch(\Exception $e) {
                return "Erreur inconnue";
            }

            return null;

    }
}
