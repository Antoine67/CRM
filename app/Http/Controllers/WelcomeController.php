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
        //Informix DB connection
        /*
        $informix = "DRIVER={DataDirect 7.1 Informix Wire Protocol};" .
                "CommLinks=tcpip(Host=172.31.50.5);" .
                "DatabaseName=db_cra;" .
                "uid=uccxhrc; pwd=5:T{i,5e!KqD*8;".
                "ServerName=cuccx_acesi_uccx; Host=172.31.50.5 ; Port=1504; Database=db_cra;";

         $conn = odbc_connect($informix, 'uccxhrc', '5:T{i,5e!KqD*8') or die ("pb de connexion à la base\n" );

         $sql = "select first 10 * from team ";
         $rs = odbc_exec($conn,$sql);
         odbc_fetch_row($rs);
         dd (odbc_result_all($rs));
         odbc_close($conn);*/



        //Graphs are automatically update every X seconds (see app/Console/Kernel.php & commands/RefreshGraphGrafana.php)
        $conf = json_decode(Storage::get('grafana.conf'), true);
        $lastUpd = Carbon::create($conf['lastAllGrafUpdate'])->format('d/m/Y à H:i');
        return view('welcome')
            ->with('data', $data)
            ->with('lastUpdate', $lastUpd);
    }


}
