<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Session;
use Storage;
use Carbon\Carbon;


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
        if(!Storage::exists('customers.conf'))  Storage::put('customers.conf', '');
        $conf = json_decode(Storage::get('customers.conf'), true);
        $lastAllCustomersUpdate = null;
        if(isset ($conf['lastAllCustomersUpdate'])) $lastAllCustomersUpdate = Carbon::create($conf['lastAllCustomersUpdate'])->format('d/m/Y à H:i');


        return view("sharepoint")
        ->with('lastAllCustomersUpdate', $lastAllCustomersUpdate);
    }
    
    public function post(Request $request) {
        if(!Session::has('user') || !Session::has('access_token')) {
            //Not logged in
            return redirect('/');
        }
        if(!Session::has('permission_level') || Session::get('permission_level') < env('EDITOR_LEVEL', 2)) {
            //Trying to reach a forbidden access
            return redirect('/');
        }
        switch($request->input('type')) {
            case 'list' :
                $msg = $this->refreshCustomersFromSharepoint();
                return redirect()->back()->with('successMsg', $msg);
            case 'all' :
                $msg = $this->refreshAllCustomersFromSharepoint();
                return redirect()->back()->with('successMsg', $msg);
        }
        
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

        if(!Storage::exists('customers.conf'))  Storage::put('customers.conf', '');
        $conf = json_decode(Storage::get('customers.conf'), true);
        $conf['lastCustomersUpdate'] = Carbon::now('Europe/Paris')->toDateTimeString();

        Storage::put('sharepoint-clients.json', json_encode($clientsData));
        Storage::put('sharepoint-extranet.json', json_encode($extranetData));
        Storage::put('customers.conf', json_encode($conf));

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

    public function refreshAllCustomersFromSharepoint() {
        $customers = array();
        if(Storage::exists('sharepoint-clients.json') ) {
            $customers = json_decode(Storage::get('sharepoint-clients.json'), true);
        }

        //Limit for dev purpose, to remove when production
        $i=0;
        foreach($customers as $customerData) {
            $customer = new \App\Customer($customerData);
            if($i>10) break;
            app('App\Http\Controllers\CustomerController')->updateOrCreateCustomer($customer->getId());
            $i++;
        }

        if(!Storage::exists('customers.conf'))  Storage::put('customers.conf', '');
        $conf = json_decode(Storage::get('customers.conf'), true);
        $conf['lastAllCustomersUpdate'] = Carbon::now('Europe/Paris')->toDateTimeString();
    }

 
}
