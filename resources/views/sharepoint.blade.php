@extends('layouts.app')

@section('css')
    <!-- <link rel="stylesheet" href="{{ asset('css/browseButton.css') }}">
    <link rel="stylesheet" href="{{ asset('css/internshipList.css') }}"> -->

@endsection

@section('js')
@endsection


@section('content')

<a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('refresh-customer-form').submit();"> {{ __('Mise à jour des clients manuelle') }} </a>

<form id="refresh-customer-form" action="{{ url('sharepoint') }}" method="POST" style="display: none;">
    @csrf
</form>



https://portal.azure.com/#blade/Microsoft_AAD_RegisteredApps/ApplicationMenuBlade/Overview/appId/ea55abff-9153-4fa7-b167-e58a3edbe76e/objectId/06ca46dc-6c5c-4236-9c98-f6e1ab81126e/isMSAApp//defaultBlade/Overview/servicePrincipalCreated/true



@endsection
