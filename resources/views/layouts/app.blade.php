<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', ' ') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        function openUrl(url) { window.open(url, '_blank'); }
        function openUrlSameTab(url) { window.location.href = url;}
    </script>
    @yield('js')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('css')
    <style>
        main {
            padding: 10px;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', ' ') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth
                        @if (Auth::user()->permission_level >= env('USER_LEVEL', 1))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('customer') }}">{{ __('Clients') }}</a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link" href="https://www.office.com/">{{ __('Office') }}</a>
                        </li>

                        @endauth
                    </ul>

                    

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Connexion') }}</a>
                        </li>
                    @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Inscription') }}</a>
                            </li>
                    @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('profile') }}">
                                    {{ __('Profil') }}
                                </a>
                                <div class="dropdown-divider"></div>

                                @if(Auth::user()->permission_level >= env('EDITOR_LEVEL', 2))
                                <a class="dropdown-item" href="{{ url('editor-mode') }}" onclick="event.preventDefault(); document.getElementById('editor-form').submit();">
                                    @if(Auth::user()->editor_mode == true)
                                    {{ __('Désactiver mode éditeur') }}
                                    @else
                                    {{ __('Activer mode éditeur') }}
                                    @endif
                                </a>
                                @endif

                                @admin
                                <a class="dropdown-item" href="{{ url('datasources') }}">{{ __('Sources de données') }}</a>
                                @endadmin
                                

                                <a class="dropdown-item" href="{{ url('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    {{ __('Déconnexion') }}
                                </a>

                                <form id="editor-form" action="{{ url('editor-mode') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- Display success/error messages via passed GET vars or directly via $msgError or $successMEssage vars --}}
        @editor
        <div class="alert alert-info" style="margin:7px 2px -7px 2px">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            Vous êtes actuellement en mode éditeur, cliquez <a href="{{ url('editor-mode') }}" onclick="event.preventDefault(); document.getElementById('editor-form').submit();">ici</a> pour revenir à la vue classique
        </div>
        @endeditor


        @if(Session::has('successMessage') || isset($successMessage)) 
          <div class="alert alert-success alert-dismissible" style="margin:7px 2px -7px 2px">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            @if (Session::has('successMessage'))
                {{ Session::get('successMessage') }}
            @else
                @isset ($successMessage)
                    {{ $successMessage }}
                @endisset
            @endif
          </div>
          <?php \Session::forget('successMessage'); ?>
        @elseif (Session::has('msgError') || isset($msgError))
          <div class="alert alert-danger alert-dismissible" style="margin:7px 2px -7px 2px">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            @if (Session::has('msgError') !== null)
                {{ Session::get('msgError') }}
            @else
                @isset ($msgError)
                    {{ $msgError }}
                @endisset
            @endif
          </div>
          <?php \Session::forget('msgError'); ?>
        @endif

        

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>


