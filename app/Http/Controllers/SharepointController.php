<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class SharepointController extends Controller
{
    public function get() {
 /*
        $username = 'username@tenant.onmicrosoft.com';
        $password = 'password';
        $url = "https://tenant.sharepoint.com/";
        $connectionStatus;

        try {
            $authCtx = new AuthenticationContext($Url);
            $authCtx->acquireTokenForUser($username,$password);
            $ctx = new ClientContext($Url,$authCtx);
            $connectionStatus = printTasks($ctx);
        }
        catch (Exception $e) {
            $connectionStatus = 'Authentication failed: '.  $e->getMessage() . "\n";
        }

        */
        $connectionStatus = "";
        return view('sharepoint')
        ->with('connectionStatus', $connectionStatus);
    }

    function printTasks(ClientContext $ctx){
	        $ret;
	        $listTitle = 'Tasks';
	        $web = $ctx->getWeb();
                $list = $web->getLists()->getByTitle($listTitle);
	        $items = $list->getItems();
                $ctx->load($items);
                $ctx->executeQuery();
	        foreach( $items->getData() as $item ) {
	            $ret .= "Task: '{$item->Title}'\r\n";
	        }
            return $ret;
    }
}
