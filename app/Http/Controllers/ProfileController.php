<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Session;

class ProfileController extends Controller
{
    public function get() {
        
        $tokenCache = new \App\TokenStore\TokenCache;

        $graph = new Graph();
        $graph->setAccessToken($tokenCache->getAccessToken());

        $user = $graph->createRequest('GET', '/me')
                    ->setReturnType(Model\User::class)
                    ->execute();


        $trending = $graph->createRequest('GET', '/me/insights/trending')
                    ->execute();

        /*$picture = $graph->createRequest('GET', 'https://graph.microsoft.com/beta/me/photo/$value')
                    ->execute();*/

        $sharepointRoot = $graph->createRequest('GET', 'https://graph.microsoft.com/v1.0/sites/root')
                        ->execute();

        
        //dd($picture);
        return view('profile')
        ->with('user',$user)
        //->with('picture',$picture)
        ->with('trending', $trending->getBody()['value'])
        ->with('sharepointRoot',$sharepointRoot->getBody());
    }

    public function logout(Request $request) {
        $tokenCache = new \App\TokenStore\TokenCache;
        $tokenCache->clearTokens();
        Session::forget('user');
        Session::forget('permission_level');
        return redirect('/?successMessage=Déconnecté');
    }
}
