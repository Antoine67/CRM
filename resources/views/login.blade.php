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
                    <a href="{{ url('signin') }}">Connexion par défaut</a>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Déconnexion de votre compte Microsoft') }}
                    </a>

                    <form id="logout-form" action="{{ url('login') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            

        </div>
    </div>
@endsection


