@extends('layouts.app')


@section('css')
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">

<style>


</style>
@endsection

@section('js')
<script src="{{ asset('js/customer.js') }}" defer></script>
@endsection


@section('content')

    <a href="{{ url('customer') }}" class="btn btn-light"><i class="fas fa-arrow-left" style="margin-right:7px;"></i>Retour à la liste des clients</a>

    @editor
    @include('customer.datasourceSelect', ['databases' => $databases, 'tables' => $tables])
    @endeditor

    <div id="customer-displayer" class="tab-content">   


        <div class="container emp-profile">
            <form method="post">
                <div class="row">
                    <div class="col-md-4 rmv-resp">
                        <div class="profile-img">
                            @if($customer->picture)
                            <img src="{{ asset('storage/' . $customer->picture) }}" alt="Logo"/>
                            @else
                            <img src="{{ asset('img/default.jpg') }}" alt="Logo"/>
                            @endif
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
                                        {{ $customer->name }}
                                    </h3>
                                    <h6 class="text-muted">
                                        Dernière mise à jour le {{ $customer->getLastUpdate() }}
                                    </h6>
                                    <h6>
                                        <a href="#" id="update-button" onclick="updateCustomer(event);">
                                            Mettre à jour
                                        </a>
                                        <img src="{{ asset('/img/loading.gif') }} " id="loading-gif-update" style="width:80px; height:80px; margin:-30px -30px -30px -20px; visibility:hidden;"></img>
                                    </h6>
                                    <form id="update-customer-form"  method="POST" style="display: none;">
                                         @csrf
                                    </form>
                                    <p class="proile-rating">Information supp</p>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Informations</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Autres</a>
                                </li>
                                @editor
                                <li class="nav-item">
                                    <a class="nav-link" id="editor-tab" data-toggle="tab" href="#editor" role="tab" aria-controls="editor" aria-selected="false">Edition</a>
                                </li>
                                @endeditor
                            </ul>
                        </div>
                    </div>
                    @editor
                    <div class="col-md-2">
                        <input type="submit" class="profile-edit-btn" name="btnAddMore" value="Edit Profile"/>
                    </div>
                    @endeditor
                </div>
                <div class="row">
                    <div class="col-md-4 rmv-resp">
                        <div class="profile-work">
                            <p>Général</p>
                            <a>Info</a><br/>
                            
                            <p>Contact</p>
                            @if( $customer->phone)
                            <a>{{ $customer->phone }}</a><br/>
                            @endif
                            @if( $customer->email)
                            <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a><br/>
                            @endif
                            <p>Liens utiles</p>
                            @if( $customer->web_url)
                            <a href="{{ $customer->web_url }}">Site web</a><br/>
                            @endif
                             @if( $customer->sharepoint_client)
                            <a href="{{ $customer->sharepoint_client }}">Dossier client</a><br/>
                            @endif
                             @if( $customer->sharepoint_extranet)
                            <a href="{{ $customer->sharepoint_extranet }}">Dossier extranet</a><br/>
                            @endif
                           <br/>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content profile-tab" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Info 1</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>Info 1</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Info 2</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>Info 2</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">


                                <!-- Start Information card
                                    <div class=" mb-5">
                                        <!-- Collapse Panel 1
                                        <a data-toggle="collapse" href="#collapseDiv1" role="button" aria-expanded="true" aria-controls="collapseDiv1" class="btn btn-primary btn-block py-2 shadow-sm with-chevron">
                                            <p class="d-flex align-items-center justify-content-between mb-0 px-3 py-2"><strong class="text-uppercase">Collapse Panel</strong><i class="fa fa-angle-down"></i></p>
                                        </a>
                                        <div id="collapseDiv1" class="collapse shadow-sm show">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p class="font-italic mb-0 text-muted">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->

                                    <div class=" mb-5">
                                        <!-- Collapse Panel 2-->
                                        <button data-toggle="collapse" data-target="#collapseDiv2" role="button" aria-expanded="true" aria-controls="collapseDiv2" class="btn btn-success btn-block py-2 shadow-sm with-chevron">
                                            <p class="d-flex align-items-center justify-content-between mb-0 px-3 py-2"><strong class="text-uppercase">Fichiers et dossiers associés</strong><i class="fa fa-angle-down"></i></p>
                                        </button>
                                        <div id="collapseDiv2" class="collapse shadow-sm show">
                                            <div class="card">
                                                <div class="card-body">
                                                    <!-- Files -->
                                                    @include('customer.files') 
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class=" mb-5">
                                        <!-- Collapse Panel 3-->
                                        <a data-toggle="collapse" href="#collapseDiv3" role="button" aria-expanded="true" aria-controls="collapseDiv3" class="btn btn-warning btn-block p2-3 shadow-sm with-chevron">
                                            <p class="d-flex align-items-center justify-content-between mb-0 px-3 py-2"><strong class="text-uppercase">Tickets</strong><i class="fa fa-angle-down"></i></p>
                                        </a>
                                        <div id="collapseDiv3" class="collapse shadow-sm show">
                                            <div class="card">
                                                <div class="card-body">
                                                    <!-- Tickets -->
                                                    <div class="container">
                                                        <div class="row">
                                                            @include('customer.tickets') 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class=" mb-5">
                                        <!-- Collapse Panel 4-->
                                        <a data-toggle="collapse" href="#collapseDiv4" role="button" aria-expanded="true" aria-controls="collapseDiv4" class="btn btn-danger btn-block p2-3 shadow-sm with-chevron">
                                            <p class="d-flex align-items-center justify-content-between mb-0 px-3 py-2"><strong class="text-uppercase">Statistiques</strong><i class="fa fa-angle-down"></i></p>
                                        </a>
                                        <div id="collapseDiv4" class="collapse shadow-sm show">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p class="font-italic mb-0 text-muted">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <!-- End Information card -->
                                
                            </div>
                            @editor
                            <div class="tab-pane fade" id="editor" role="tabpanel" aria-labelledby="editor-tab">
                                @include('customer.editor') 
                            </div>
                            @endeditor
                        </div>
                    </div>
                </div>
            </form>           
        </div>


@endsection


