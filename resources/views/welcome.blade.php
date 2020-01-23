@extends('layouts.app')

@section('css')
    <!-- <link rel="stylesheet" href="{{ asset('css/browseButton.css') }}">
    <link rel="stylesheet" href="{{ asset('css/internshipList.css') }}"> -->
@endsection

@section('js')
@endsection


@section('content')

    <div>
        <h3 class="d-inline-block"> - </h3>
    </div>
   
    <div class="card">
        <div class="card-body">            
            <div class="row justify-content-center">
                <div class="w-100">
                    <a href="{{ url('customer') }}">Clients</a>
                </div>
            </div>

        </div>
    </div>
@endsection


