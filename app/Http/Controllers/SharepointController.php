<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Session;
use Storage;
use Carbon\Carbon;
use Artisan;


/*
 
https://blog.atwork.at/post/Access-files-in-OneDrive-or-SharePoint-with-Microsoft-Graph-and-the-Excel-API

Example of microsoft graph API links

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

        //Get last update date
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
                //$msg = $this->refreshAllCustomersFromSharepoint();
                //TODO Run schedule task
                Artisan::call('schedule:runa');
                $msg = "Mise à jour en cours, cela peut prendre jusqu'à quelques dizaines de minutes";
                return redirect()->back()->with('successMsg', $msg);
        }
        
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
        Storage::put('customers.conf', json_encode($conf));
    }

 
}
