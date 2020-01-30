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
                            
                            <div class="file btn btn-lg btn-primary">
                                Modifier...
                                <input type="file" name="file"/>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-head">
                                    <h3>
                                        {{ $user->name }}
                                    </h3>
                                    
                                    <form id="update-customer-form"  method="POST" style="display: none;">
                                         @csrf
                                    </form>
                                    @if($user->email_verified_at !== null)
                                    <p class="proile-rating">Email vérifiée</p>
                                    @endif
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <input type="submit" class="profile-edit-btn" name="btnAddMore" value="Editer"/>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-work">
                            <hr/>
                            <p>Général</p>
                            <a>
                            @switch($user->permission_level)
                            @case(0)
                                Utilisateur
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
                            <a>{{ $user->email }}</a>

                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4>TODO</h4>
                        <p>TODO!</p>
                        <hr/>
                    </div>
                </div>
            </form>           
        </div>

         
                    
                    
@endsection



