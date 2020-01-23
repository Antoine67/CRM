<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Storage;
use App\Customer;

class CustomerController extends Controller
{
    public function getById($id, Request $request)  {

        if(Storage::exists('customers/customer-'.$id.'.json')) {
            $customer = new Customer(json_decode(
                                                 Storage::get('customers/customer-'.$id.'.json'), true
                                     ));

           
            return view('customer')->with('customer',$customer);
        }
        $tokenCache = new \App\TokenStore\TokenCache;

        $graph = new Graph();
        $graph->setAccessToken($tokenCache->getAccessToken());


        //Fetch data in 'Client' folder
        $urlClientFolderId = "https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!zRfTFj8KRkW6OuScTfSQPLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/items/$id";
        $mainFolderInformations = $graph->createRequest('GET', $urlClientFolderId)->execute()->getBody();
        $mainFolderId = $mainFolderInformations['id'];
        $mainFolderName = $mainFolderInformations['name'];
        $mainFolderWebUrl = $mainFolderInformations['webUrl'];

        $urlClientFolder = "https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!zRfTFj8KRkW6OuScTfSQPLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/items/$mainFolderId/children";
        $dataClientFolder = $graph->createRequest('GET', $urlClientFolder)->execute()->getBody();


        //Fetch data in 'Extranet' folder
        $urlExtranetFolderId = "https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/root/children/$mainFolderName";
        $extranetFolderInformations = $graph->createRequest('GET', $urlExtranetFolderId)->execute()->getBody();
        $extranetFolderId = $extranetFolderInformations['id'];
        $extranetFolderWebUrl = $extranetFolderInformations['webUrl'];

        $urlExtranetFolder = "https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/items/$extranetFolderId/children";
        $dataExtranetFolder = $graph->createRequest('GET', $urlExtranetFolder)->execute()->getBody();
        



        //Create new customer and fill props
        $customer = new Customer([
                                    'id' => $id, 'name' => $mainFolderName ,
                                    'extranetId' => $extranetFolderId,
                                    'mainFolderWebUrl' => $mainFolderWebUrl,
                                    'extranetFolderWebUrl' => $extranetFolderWebUrl,
                                 ]);

        $this->fillFolders($customer, $dataClientFolder['value'], false);
        $this->fillFolders($customer, $dataExtranetFolder['value'], true);

        //dd($customer);
        Storage::put('customers/customer-'.$customer->getId().'.json', json_encode($customer));

        return view('customer')->with('customer',$customer);
    }

    private function fillFolders($customer, $dataFolder, $isExtranetFolder) {
        foreach($dataFolder as $folder) {

            if(!$isExtranetFolder)$customer->addFolder(new \App\Folder($folder));
            else $customer->addExtranetFolder(new \App\Folder($folder));
            
            if($folder['folder']['childCount'] > 0) {
                //If folder contains others folders
                //TODO Manage if many folders
            }
        }
        
    }

    /***
    * Display all customers from saved file
    ***/
    public function getAll(Request $request)  {
    
      if(Storage::exists('sharepoint-clients.json')) {
        $sharepoint = json_decode(Storage::get('sharepoint-clients.json'), true);

        return view("customers")
            ->with('sharepoint',$sharepoint);
      }else {
        return view("customers")
           ->with ('msgError','Issue with sharepoint customer file : missing or not found. Try refreshing data, or check with your administrator');
      }
      
    }
}
