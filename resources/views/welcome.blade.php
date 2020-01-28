@extends('layouts.app')

@section('css')
    <!-- <link rel="stylesheet" href="{{ asset('css/browseButton.css') }}">
    <link rel="stylesheet" href="{{ asset('css/internshipList.css') }}"> -->
@endsection

@section('js')

<script type="text/javascript" src="{{ asset('js/welcome.js') }}" defer></script>

@endsection


@section('content')


    <div class="card">
        <div class="card-body">
                <h3>Grafana :</h3>
                <h5 class="text-muted">Dernière mise à jour le {{ $lastUpdate }}</h5>
                <hr/>
            <div class="row justify-content-center">
                
                <div class="w-100" style="text-align:center; background-color:#161719;">
                @if(!Session::has('user') || !Session::has('access_token'))
                    <a href="{{ url('login') }}">Connectez vous pour avoir accès à la plateforme</a>
                @else
                    
                @endif
                    <div class="row row-cols-3">
                          @for($i=1; $i <= 6; $i++)
                      
                          <div class="col col-md-4"><img src="{{ asset("img/grafana/grafana-$i.png") }}" alt="logo"/></div>
                          @endfor
                        </div>
                     <div class="col col-md-12"><img src="{{ asset("img/grafana/grafana-7.png") }}" alt="logo"/></div>
                </div>
            </div>

        </div>
    </div>
@endsection


