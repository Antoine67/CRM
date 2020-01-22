<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Session;

class SharepointController extends Controller
{
    public function get() {

      $tokenCache = new \App\TokenStore\TokenCache;

      $graph = new Graph();
      $graph->setAccessToken($tokenCache->getAccessToken());


      $getMessagesUrl = 'https://graph.microsoft.com/v1.0/sites/root';//.http_build_query($messageQueryParams);
      $messages = $graph->createRequest('GET', $getMessagesUrl)
                        ->execute();
      dd($messages);

      foreach($messages as $msg) {
        echo 'Message: '.$msg->getSubject().'<br/>';
      }


    }
 
}
