<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Storage;
use Carbon\Carbon;


class WelcomeController extends Controller
{
    public function get() {
        $this->refreshAllGraph();

        $conf = json_decode(Storage::get('grafana.conf'), true);
        $lastUpd = Carbon::create($conf['lastAllGrafUpdate'])->format('d/m/Y à H:i');
        return view('welcome')
            ->with('lastUpdate', $lastUpd);
    }

    public function refreshAllGraph() {

        
        //Read from conf file
        if(!Storage::exists('grafana.conf'))  Storage::put('grafana.conf', '');
        $conf = json_decode(Storage::get('grafana.conf'), true);
        
        if(isset($conf['lastAllGrafUpdate'])) {
            $lastUpd = Carbon::create($conf['lastAllGrafUpdate']);
            if($lastUpd->diffInSeconds(Carbon::now()) < env('GRAFANA_REFRESH_COOLDOWN', 1800)) {
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

        
        
        $conf['lastAllGrafUpdate'] = Carbon::now('Europe/Paris');
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
