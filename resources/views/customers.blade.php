@extends('layouts.app')

@section('css')
    <!-- <link rel="stylesheet" href="{{ asset('css/browseButton.css') }}">
    <link rel="stylesheet" href="{{ asset('css/internshipList.css') }}"> -->

@endsection

@section('js')
@endsection


@section('content')

    <div>
        <h1 class="d-inline-block">Clients </h1>
        <img class="d-inline-block pull-right" src="~/img/test.png" style="object-fit: cover; height:75px; width:75px;" alt="logo" />
    </div>
   
    <div class="card">
        <div class="card-body">            
            <div class="row justify-content-center">
                <div class="w-100">
                    <div class="card">
                        @isset($sharepoint)
                            @foreach($sharepoint as $customer)
                                <a href="{{ url('customer') . '/' . $customer['id'] }}">{{ $customer['name'] }}</a> - 
                                <a href="{{$customer['webUrl'] }}">lien</a><br/>
                            @endforeach
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


