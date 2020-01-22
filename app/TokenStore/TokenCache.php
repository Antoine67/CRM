<?php

namespace App\TokenStore;

use Session;

class TokenCache {
  public function storeTokens($access_token, $refresh_token, $expires) {
    Session::put('access_token', $access_token);
    Session::put('refresh_token' , $refresh_token);
    Session::put('token_expires' , $expires);
  }

  public function clearTokens() {
    Session::forget('access_token');
    Session::forget('refresh_token');
    Session::forget('token_expires');
  }

  public function getAccessToken() {
    // Check if tokens exist
    if (!Session::has('access_token') ||
        !Session::has('refresh_token') ||
        !Session::has('token_expires')) {
      return '';
    }

    // Check if token is expired
    //Get current time + 5 minutes (to allow for time differences)
    $now = time() + 300;
    if (Session::get('token_expires') <= $now) {
      // Token is expired (or very close to it)
      // so let's refresh

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
        $newToken = $oauthClient->getAccessToken('refresh_token', [
          'refresh_token' => session('refresh_token')
        ]);

        // Store the new values
        $this->   storeTokens($newToken->getToken(), $newToken->getRefreshToken(),
          $newToken->getExpires());

        return $newToken->getToken();
      }
      catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return '';
      }
    }
    else {
      // Token is still valid, just return it
      return Session::get('access_token');
    }
  }
}
