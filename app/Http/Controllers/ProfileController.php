<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Session;

class ProfileController extends Controller
{
    public function get() {
      if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }

      $tokenCache = new \App\TokenStore\TokenCache;

      $graph = new Graph();
      $graph->setAccessToken($tokenCache->getAccessToken());

      $user = $graph->createRequest('GET', '/me')
                    ->setReturnType(Model\User::class)
                    ->execute();

      //echo 'User: '.$user->getDisplayName();
      return view('profile')->with('user',$user);
    }

    public function logout(Request $request) {
        $tokenCache = new \App\TokenStore\TokenCache;
        $tokenCache->clearTokens();
        Session::forget('user');
        return redirect('/')->with('successMsg',"Déconnecté");
    }
}
