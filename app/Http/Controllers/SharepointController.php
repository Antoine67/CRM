<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Session;
use Storage;


/*
 
https://blog.atwork.at/post/Access-files-in-OneDrive-or-SharePoint-with-Microsoft-Graph-and-the-Excel-API

https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com:/sites/Extranet 
https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com:/sites/Extranet:/drives?$select=name,id //Folder info, id , name
https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_ //Find url
https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/root //child count
https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/root/children //get all children
https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/root/children?$select=id,name // filtered
https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/items/01E3CW2CFFW5A7TESN7BDJBBENX4AAM46X //Info about specific file or folder
https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/items/01E3CW2CFFW5A7TESN7BDJBBENX4AAM46X/children/01_Extranet/children
*/

class SharepointController extends Controller
{
    public function get() {
      return view("sharepoint");
    }
    
    public function post() {
        if(!Session::has('user') || !Session::has('access_token')) {
            return redirect('/');
        }
        $msg = $this->refreshCustomersFromSharepoint();
        return redirect("/sharepoint?$msg");
    }

    /***
    * Get data from Microsoft Graph API and save it into a local file, to avoid making excessive requests
    ***/
    public function refreshCustomersFromSharepoint() {
        $tokenCache = new \App\TokenStore\TokenCache;

        $graph = new Graph();
        $graph->setAccessToken($tokenCache->getAccessToken());

        $maxNumberIterations = 10;

        $urlClients = 'https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!zRfTFj8KRkW6OuScTfSQPLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/root/children';
        $sharepointClientsData = $graph->createRequest('GET', $urlClients)->execute();

        $clientsData = $this->recursiveCallNextLink($sharepointClientsData->getBody(), $graph, $sharepointClientsData->getBody()['value'], $maxNumberIterations,0);

        $urlExtranet = 'https://graph.microsoft.com/v1.0/sites/acesi.sharepoint.com/drives/b!L3KH91MLuEG2wDMCyDRPnLIbRYh8ofNHlKCVtzt2FyRmUez0j23JTJnX9jLqS95_/root/children' ;
        $sharepointExtranetData = $graph->createRequest('GET', $urlExtranet)->execute();
        $extranetData = $this->recursiveCallNextLink($sharepointExtranetData->getBody(), $graph, $sharepointExtranetData->getBody()['value'], $maxNumberIterations,0);



        Storage::put('sharepoint-clients.json', json_encode($clientsData));
        Storage::put('sharepoint-extranet.json', json_encode($extranetData));

        return "successMessage=Successfully executed";
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

 
}
