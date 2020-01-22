@extends('layouts.app')

@section('css')
    <!-- <link rel="stylesheet" href="{{ asset('css/browseButton.css') }}">
    <link rel="stylesheet" href="{{ asset('css/internshipList.css') }}"> -->

@endsection

@section('js')
@endsection


@section('content')


<?php


use Office365\PHP\Client\Runtime\Auth\AuthenticationContext;
use Office365\PHP\Client\Runtime\Auth\NetworkCredentialContext;
use Office365\PHP\Client\SharePoint\ClientContext;
use Office365\PHP\Client\GraphClient\GraphServiceClient;

$Settings = array(
    'TenantName' => "acesi.onmicrosoft.com",
	'Url' => "https://acesi.sharepoint.com/",
    'OneDriveUrl' => "https://acesi-my.sharepoint.com/",
    'Password' => "@ces!2020",
    'UserName' => "amohr@acesi.onmicrosoft.com",
    'ClientId' => "ea55abff-9153-4fa7-b167-e58a3edbe76e",
    'ClientSecret' => "aL-.gyVYoP7nC60MTn7MLj-SaUGId]iz",
    'RedirectUrl' => "https://acesi.sharepoint.com/"
);

//https://portal.azure.com/#blade/Microsoft_AAD_RegisteredApps/ApplicationMenuBlade/Overview/appId/ea55abff-9153-4fa7-b167-e58a3edbe76e/objectId/06ca46dc-6c5c-4236-9c98-f6e1ab81126e/isMSAApp//defaultBlade/Overview/servicePrincipalCreated/true


?>

@endsection
