<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Storage;
use Carbon\Carbon;
use DB;
use PDO;


class WelcomeController extends Controller
{
    public function get() {

        $data = null;

        //https://github.com/flachglasschweiz/php_pdo_informix/blob/master/x64
        //Tried to connect to informix db, but driver are obsolete (PHP5), need to recompile source code into dll to make it work on PHP 7.X
        /*$con = new PDO("informix:host=172.31.50.5; service=1504; database=db_cra; server=cuccx_acesi_uccx; protocol=onsoctcp; EnableScrollableCursors=1", "uccxhruser", "5:T{i,5e!KqD*8");

            $sql    = "SELECT * FROM test";
            $prep   = $con->prepare($sql);
            $prep->execute();
            $data = $prep->fetchAll(PDO::FETCH_ASSOC);*/


        //Graphs are automatically update every X seconds (see app/Console/Kernel.php & commands/RefreshGraphGrafana.php)
        $conf = json_decode(Storage::get('grafana.conf'), true);
        $lastUpd = Carbon::create($conf['lastAllGrafUpdate'])->format('d/m/Y à H:i');
        return view('welcome')
            ->with('data', $data)
            ->with('lastUpdate', $lastUpd);
    }


}
