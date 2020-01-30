<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Storage;
use Carbon\Carbon;
use DB;


class WelcomeController extends Controller
{
    public function get() {
        //Graphs are automatically update every X seconds (see app/Console/Kernel.php & commands/RefreshGraphGrafana.php)

        $conf = json_decode(Storage::get('grafana.conf'), true);
        $lastUpd = Carbon::create($conf['lastAllGrafUpdate'])->format('d/m/Y à H:i');
        return view('welcome')
            ->with('lastUpdate', $lastUpd);
    }


}
