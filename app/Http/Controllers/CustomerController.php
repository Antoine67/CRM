<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Storage;
use Session;
use Carbon\Carbon;
use App\Customer;

class CustomerController extends Controller
{
    public function getById($id, Request $request)  {

        //If local file already exists, simply read data from it
        if(Storage::exists('customers/customer-'.$id.'.json')) {
            $customer = new Customer(json_decode( Storage::get('customers/customer-'.$id.'.json'), true ));
            return view('customer')->with('customer',$customer);
        }

        $customer = $this->updateOrCreateCustomer($id);

        return view('customer')->with('customer',$customer);
    }

    private function fillFolders($customer, $dataFolder, $isExtranetFolder, $graph) {
        foreach($dataFolder as $folder) {

            if(!$isExtranetFolder)$customer->addFolder(new \App\Folder($folder));
            else $customer->addExtranetFolder(new \App\Folder($folder));

            //Check for interesting files (IP Plan, Visio, RoyalTS & Keepass)
            if($isExtranetFolder) {
                //Check in "Extranet" folder for IP Plan, Visio & RoytaTS files
                if(strcmp(strtolower($folder['name']), env('FOLDER_EXTRANET',"01_extranet")) == 0) {

                    $extranetFolders = $this->getChildren($graph, $folder['id'], true); //get all folders and files in folder "01_Extranet"
                    
                    foreach($extranetFolders['value'] as $extraFolder) {
                        if( strcmp(strtolower($extraFolder['name']), env("FOLDER_SITE","acesi_dossier de site")) == 0) {
                            if ($extraFolder['folder']['childCount'] <= 0) {
                                return;
                            }else {
                                $dataExtranetFolder = $this->getChildren($graph, $extraFolder['id'], true);
                               

                                foreach($dataExtranetFolder['value'] as $file) {
                                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                    if( strcmp($ext, 'vsd') == 0) {
                                        $customer->addAssociatedFiles($file['name'], 'Visio', $file['webUrl'], $file['@microsoft.graph.downloadUrl']);
                                    }else if ( strcmp($ext, 'rtsz') == 0) {
                                        $customer->addAssociatedFiles($file['name'], 'RoyalTS', $file['webUrl'], $file['@microsoft.graph.downloadUrl']);
                                    }else if ( strpos($file['name'] , 'IP') !== false) {
                                        $customer->addAssociatedFiles($file['name'], 'IP plan', $file['webUrl'], $file['@microsoft.graph.downloadUrl']);
                                    } 
                                }
                            }
                        }
                    }
                }
            }else {
                //Check in "Clients" folder for keepass file
                if(strcmp(strtolower($folder['name']), env('FOLDER_PWD',"09_mot de passe")) == 0) {
                    if ($folder['folder']['childCount'] <= 0) {
                        return;
                    }else {
                        $idFolder = $folder['id'];
                        $dataPasswordFolder = $this->getChildren($graph, $idFolder, false);
                        

                        foreach($dataPasswordFolder['value'] as $file) {
                            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                            if( strcmp($ext, 'kdbx') == 0) {
                                $customer->addAssociatedFiles($file['name'], 'Keepass', $file['webUrl'], $file['@microsoft.graph.downloadUrl']);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
    * Get chilren from either 'Extranet' or 'Clients' folder
    **/
    public function getChildren($graph, $idFolder, $isExtranet) {
        if($isExtranet) $idDrive = env('ID_DRIVE_EXTRANET',"b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_");
        else $idDrive = env('ID_DRIVE_CLIENTS',"b!zRfTFj8KRkW6OuScTfSQPLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_");
        $baseUrl = env('SHAREPOINT_GRAPH_URL','https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com');
        $url = "$baseUrl/drives/$idDrive/items/$idFolder/children";
        $arr = $graph->createRequest('GET', $url)->execute()->getBody();
        return $arr;
         
    }

    public function updateById($id, Request $request) {
        $customer = $this->updateOrCreateCustomer($id);
        if($customer) {
            Session::put('successMessage', "Mise à jour réussie");
        }else {
            Session::put('msgError', "Mise à jour échouée");
        }
        
        return response(200);
    }

    public function updateOrCreateCustomer($id) {
        $tokenCache = new \App\TokenStore\TokenCache;
        $graph = new Graph();
        $graph->setAccessToken($tokenCache->getAccessToken());

        $baseUrl = env('SHAREPOINT_GRAPH_URL','https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com');
        $idDriveExtranet = env('ID_DRIVE_EXTRANET',"b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_");
        $idDriveClients = env('ID_DRIVE_CLIENTS',"b!zRfTFj8KRkW6OuScTfSQPLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_");

        //Fetch data in 'Client' folder
        $urlClientFolderId = "$baseUrl/drives/$idDriveClients/items/$id";
        $mainFolderInformations = $graph->createRequest('GET', $urlClientFolderId)->execute()->getBody();
        $mainFolderId = $mainFolderInformations['id'];
        $mainFolderName = $mainFolderInformations['name'];
        $mainFolderWebUrl = $mainFolderInformations['webUrl'];

        $urlClientFolder = "$baseUrl/drives/$idDriveClients/items/$mainFolderId/children";
        $dataClientFolder = $graph->createRequest('GET', $urlClientFolder)->execute()->getBody();
        
        

        //Fetch data in 'Extranet' folder
        $urlExtranetFolderId = "$baseUrl/drives/$idDriveExtranet/root/children/$mainFolderName";
        $extranetFolderInformations = $graph->createRequest('GET', $urlExtranetFolderId)->execute()->getBody();
        $extranetFolderId = $extranetFolderInformations['id'];
        $extranetFolderWebUrl = $extranetFolderInformations['webUrl'];

        $urlExtranetFolder = "$baseUrl/drives/$idDriveExtranet/items/$extranetFolderId/children";
        $dataExtranetFolder = $graph->createRequest('GET', $urlExtranetFolder)->execute()->getBody();

        //Create new customer and fill props
        $customer = new Customer([
                                    'id' => $id, 'name' => $mainFolderName ,
                                    'extranetId' => $extranetFolderId,
                                    'mainFolderWebUrl' => $mainFolderWebUrl,
                                    'extranetFolderWebUrl' => $extranetFolderWebUrl,
                                    'lastUpdatedProfile' => Carbon::now('Europe/Paris')->toDateTimeString(),
                                 ]);

        $this->fillFolders($customer, $dataClientFolder['value'], false, $graph);
        $this->fillFolders($customer, $dataExtranetFolder['value'], true, $graph);
        Storage::put('customers/customer-'.$customer->getId().'.json', json_encode($customer));

        return $customer;
    }

    /***
    * Display all customers from saved file
    ***/
    public function getAll(Request $request)  {

      
      if(Storage::exists('sharepoint-clients.json') && Storage::exists('customers.conf')) {
        $customers = json_decode(Storage::get('sharepoint-clients.json'), true);
        $conf = json_decode(Storage::get('customers.conf'), true);

        $lastUpdate = null;
        if(isset($conf["lastCustomersUpdate"])) {
            $lastUpdate = Carbon::create($conf["lastCustomersUpdate"])->format('d/m/Y à H:i');
        }

        return view("customers")
            ->with('customers',$customers)
            ->with('lastUpdate', $lastUpdate);
      }else {
        return view("customers")
           ->with ('msgError','Issue with sharepoint customer file : missing or not found. Try refreshing data, or check with your administrator');
      }
      
    }

}
