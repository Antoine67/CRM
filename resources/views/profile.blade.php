@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}"> 
@endsection

@section('js')
@endsection




@section('content')

    

    </div>
    <div id="customer-displayer" class="tab-content">   


        <div class="container emp-profile">
            <form method="post">
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-img">
                            @isset($picture)
                            {{ $picture }}
                            @else
                            <img src="{{ asset('img/default.jpg') }}" style="width:40%" alt="Logo"/>
                            @endisset
                            <!--
                            <div class="file btn btn-lg btn-primary">
                                Change Photo
                                <input type="file" name="file"/>
                            </div>
                            -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-head">
                                    <h3>
                                        {{ $user->getDisplayName() }}
                                    </h3>
                                    
                                    <form id="update-customer-form"  method="POST" style="display: none;">
                                         @csrf
                                    </form>
                                    <p class="proile-rating">Information supp</p>
                        </div>
                    </div>
                    <!--
                    <div class="col-md-2">
                        <input type="submit" class="profile-edit-btn" name="btnAddMore" value="Edit Profile"/>
                    </div>
                    -->
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-work">
                            <hr/>
                            <p>Général</p>
                            <a>{{ $user->getJobTitle() }}</a><br/>
                            <a>
                            @switch(Session::get('permission_level'))
                            @case(0)
                                Utilisateur non ACESI
                                @break
                            @case(1)
                                Utilisateur ACESI
                                @break
                            @case(2)
                                Editeur
                                @break
                            @case(3)
                                Administrateur
                                @break
                            @endswitch

                            </a>
                            
                            <p>Contact</p>
                            @if($user->getMobilePhone() !== null)
                            <a>{{ $user->getMobilePhone() }}</a><br/>
                            @endif
                            @if($user->getMail() !== null)
                            <a>{{ $user->getMail() }}</a><br/>
                            @endif
                            @if($user->getOfficeLocation() !== null)
                            <a>{{ $user->getOfficeLocation() }}</a><br/>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4>Liens utiles</h4>
                        <a href="{{ $sharepointRoot['webUrl'] }}">Sharepoint</a><br/>
                        <a href="http://mail.netintra.local/EmployeeDirectory.html">Annuaire interne</a><br/>
                        <hr/>

                        <h4>Recommandations et dernières consultations</h4>
                        @foreach ($trending as $tr)
                        <a href="{{ $tr['resourceReference']['webUrl'] }}">
                            {{ $tr['resourceVisualization']['title'] }}
                        </a>
                        <br/>
                        @endforeach
                    </div>
                </div>
            </form>           
        </div>

         
                    
                    
@endsection
<!--
    <div>
        <h3 class="d-inline-block">Profil - {{ $user->getDisplayName() }}</h3>
    </div>
   
    <div class="card">
        <div class="card-body">            
            <div class="row justify-content-center">
                <div class="w-100">
                    {{ $user->getMail() }}
                    {{ $user->getJobTitle() }}
                    {{ $sharepointRoot['webUrl'] }}
                </div>
            </div>

        </div>
    </div>
    -->


