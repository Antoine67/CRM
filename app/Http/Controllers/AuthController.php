<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Request;
use Session;
use Debugbar;
use PermUser;
use Storage;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;


class AuthController extends Controller
{
  public function signin(Request $request)
    {
      // Initialize the OAuth client
      $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => env('OAUTH_APP_ID'),
        'clientSecret'            => env('OAUTH_APP_PASSWORD'),
        'redirectUri'             => env('OAUTH_REDIRECT_URI'),
        'urlAuthorize'            => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
        'urlAccessToken'          => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
        'urlResourceOwnerDetails' => '',
        'scopes'                  => env('OAUTH_SCOPES'),
      ]);

      // Generate the auth URL
      $authorizationUrl = $oauthClient->getAuthorizationUrl();

      // Save client state so we can validate in response
      Session::put('oauth_state', $oauthClient->getState());

      // Redirect to authorization endpoint
      header('Location: '.$authorizationUrl);
      exit();
    }

    public function gettoken()
    {

      // Authorization code should be in the "code" query param
      if (isset($_GET['code'])) {
        // Check that state matches
        if (empty($_GET['state']) || ($_GET['state'] !== Session::get('oauth_state'))) {
          Debugbar::addMessage($_GET['state']);
          Debugbar::addMessage(Session::get('oauth_state'));   
          echo('State provided in redirect does not match expected value.');
        }

        // Clear saved state
        session()->forget('oauth_state');

        // Initialize the OAuth client
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
          'clientId'                => env('OAUTH_APP_ID'),
          'clientSecret'            => env('OAUTH_APP_PASSWORD'),
          'redirectUri'             => env('OAUTH_REDIRECT_URI'),
          'urlAuthorize'            => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
          'urlAccessToken'          => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
          'urlResourceOwnerDetails' => '',
          'scopes'                  => env('OAUTH_SCOPES')
        ]);

        try {
          // Make the token request
          $accessToken = $oauthClient->getAccessToken('authorization_code', [
            'code' => $_GET['code']
          ]);

          // Save the access token and refresh tokens in session
          // This is for demo purposes only. A better method would
          // be to store the refresh token in a secured database
          $tokenCache = new \App\TokenStore\TokenCache;
          $tokenCache->storeTokens($accessToken->getToken(), $accessToken->getRefreshToken(),
            $accessToken->getExpires());


           $graph = new Graph();
           $graph->setAccessToken($tokenCache->getAccessToken());
           $user = $graph->createRequest('GET', '/me')
                    ->setReturnType(Model\User::class)
                    ->execute();
           Session::put('user',$user);

           //Give permissions if user already exists, else add user to permission file with default perms
           $defaultPerm = 1;
           $permissionLevel = $defaultPerm;
           $userCreated = false;
           if(Storage::exists('users.json')) {
                $usersArray = json_decode( Storage::get('users.json'), true );
                if($usersArray != null) {
                    foreach($usersArray as $currentUser) {
                    
                        if( !array_key_exists('email',$currentUser) ) continue;
                        if( strcmp ($currentUser['email'], $user->getMail() ) == 0){
                            if( !array_key_exists('permission_level',$currentUser) ) continue;
                            $permissionLevel = $currentUser['permission_level'];
                            $userCreated = true;
                            break;
                        }
                    }
                }
          }else { // If file doesnt exist yet or not foun
                $usersArray = array();
          }
          if(!$userCreated) {
            array_push( $usersArray, [ 'email' => $user->getMail(), 'permission_level' => $permissionLevel ] );;
          }

          //Store it in session and storage
          Session::put('permission_level', $permissionLevel);
          Storage::put('users.json',json_encode($usersArray));

          // Redirect back to default page
          return back(); //redirect('/');
        }
        catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
          exit('ERROR getting tokens: '.$e->getMessage());
        }
        exit();
      }
      elseif (isset($_GET['error'])) {
        exit('ERROR: '.$_GET['error'].' - '.$_GET['error_description']);
      }
    }
}
