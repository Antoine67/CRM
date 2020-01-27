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
                    @if(Session::has('user') && Session::has('access_token'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('customer') }}">{{ __('Clients') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.office.com/">{{ __('Office') }}</a>
                        </li>
                    @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @if(!Session::has('user') || !Session::has('access_token'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('login') }}">{{ __('Connexion') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="{{url('profile') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Session::get('user')->getDisplayName() }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('profile') }}">
                                        {{ __('Profil') }}
                                    </a>
                                @if(Session::has('permission_level') && Session::get('permission_level') >= env('EDITOR_LEVEL', 2))
                                    <a class="dropdown-item" href="{{ url('sharepoint') }}">
                                        {{ __('Config Sharepoint') }}
                                    </a>
                                @endif
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Déconnexion') }}
                                    </a>

                                    <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        {{-- Display success/error messages via passed GET vars or directly via $msgError or $successMEssage vars --}}

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
        @elseif (Request::get('msgError') || isset($msgError))
          <div class="alert alert-danger alert-dismissible" style="margin:7px 2px -7px 2px">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            @if (Request::get('msgError') !== null)
                {{ Request::get('msgError') }}
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


