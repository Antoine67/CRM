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


    </div>
    <div id="customer-displayer" class="tab-content">   


        <div class="container emp-profile">
            <form method="post">
                <div class="row">
                    <div class="col-md-4 rmv-resp">
                        <div class="profile-img">
                            <img src="{{ asset('img/default.jpg') }}" alt="Logo"/>
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
                                        {{ $customer->getName() }}
                                    </h3>
                                    <h6 class="text-muted">
                                        Dernière mise à jour le {{ $customer->getLastUpdatedProfile() }}
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
                            </ul>
                        </div>
                    </div>
                    <!--
                    <div class="col-md-2">
                        <input type="submit" class="profile-edit-btn" name="btnAddMore" value="Edit Profile"/>
                    </div>
                    -->
                </div>
                <div class="row">
                    <div class="col-md-4 rmv-resp">
                        <div class="profile-work">
                            <p>Général</p>
                            <a>Info</a><br/>
                            
                            <p>Contact</p>
                            <a>+33 6 15 48 XX XX</a><br/>
                            <a href="mailto:test@email.fr">test@email.fr</a><br/>
                            <p>Contact VITA</p>
                            <a>
                                Tél: 
                                @if($customer->get('E_TEL_VITA') !== null)
                                    {{ $customer->get('E_TEL_VITA') }}
                                @else
                                    Non renseigné
                                @endif
                            </a><br/>
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


                                <!-- Start Information card -->
                                    <div class=" mb-5">
                                        <!-- Collapse Panel 1-->
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
                                    </div>

                                    <div class=" mb-5">
                                        <!-- Collapse Panel 2-->
                                        <button data-toggle="collapse" data-target="#collapseDiv2" role="button" aria-expanded="true" aria-controls="collapseDiv2" class="btn btn-success btn-block py-2 shadow-sm with-chevron">
                                            <p class="d-flex align-items-center justify-content-between mb-0 px-3 py-2"><strong class="text-uppercase">Fichiers et dossiers associés</strong><i class="fa fa-angle-down"></i></p>
                                        </button>
                                        <div id="collapseDiv2" class="collapse shadow-sm show">
                                            <div class="card">
                                                <div class="card-body">
                                                    <!-- Files -->
                                                    <h5>Fichiers :</h5>
                                                    @if($customer->getAssociatedFiles() !== null)
                                                    @foreach($customer->getAssociatedFiles() as $file)
                                                    <div class="align-middle d-inline-block" onClick="openUrl(' {{ $file['path'] }} ')">
                                    
                                                        <div class="form-group">
                                                            <input type="checkbox" name="fancy-checkbox-success" id="fancy-checkbox-success" autocomplete="off" checked="true" disabled />
                                                            <div class="btn-group">
                                                                <label for="fancy-checkbox-success" class="btn btn-success">
                                                                    <span class="fas fa-file"></span>
                                                                    <span> </span>
                                                                </label>
                                                                <label for="fancy-checkbox-success" class="btn btn-default active">
                                                                    {{ $file['givenName'] }}
                                                                </label>
                                                            </div>
                                                        </div>
                                    
                                                    </div>
                                                    @endforeach
                                                    @else
                                                    <p>Aucun fichier trouvé</p>
                                                    @endif


                                                    <!-- SharePoint folders -->
                                                    <h5>Dossiers SharePoint :</h5>
                                                    <h6 class="text-muted"><a href="#" onClick="openUrl('{{ $customer->getProperties()['mainFolderWebUrl'] }}')">Dossier client</a></h6>
                                                    <ul class="fa-ul">
                                                    @foreach($customer->getFolders() as $folder)
                                                        <li><a href="#" onClick="openUrl('{{ $folder->getWebUrl() }}')">
                                                            <span class="fa-li"><i class="fas fa-folder"></i></span>
                                                            {{ $folder->getName() }} - <i>{{ $folder->getChildCount() }} fichier(s)</i>
                                                        </a></li>
                                                    @endforeach
                                                    </ul>
                                                    <h6 class="text-muted"><a href="#" onClick="openUrl('{{ $customer->getProperties()['extranetFolderWebUrl'] }}');">Dossier extranet</a></h6>
                                                    <ul class="fa-ul">
                                                    @foreach($customer->getExtranetFolders() as $folder)
                                                        <li><a href="#" onClick="openUrl('{{ $folder->getWebUrl() }}')">
                                                            <span class="fa-li"><i class="fas fa-folder"></i></span>
                                                            {{ $folder->getName() }} - <i>{{ $folder->getChildCount() }} fichier(s)</i>
                                                        </a></li>
                                                    @endforeach
                                                    </ul>
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
                                                    <h5>20 derniers Tickets : </h5>
                                                    <div class="container">
                                                        <div class="row">
                                                        @if ($customer->getArray('tickets') !== null)
                                                            
                                                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
                                                            @foreach ($customer->getArray('tickets') as $ticket)
                                                                <div class="panel panel-default ">
                                                                <a data-toggle="collapse" class="collapsed panel-title" data-parent="#accordion" href="#collapse{{ $ticket->get('RFC_NUMBER') }}" aria-expanded="true" aria-controls="collaps{{ $ticket->get('RFC_NUMBER') }}">
                                                                    <div class="panel-heading" role="tab" id="heading{{ $ticket->get('RFC_NUMBER') }}">
                                                                        Ticket n° <b>{{ $ticket->get('RFC_NUMBER') }}</b>
                                                                        @if($ticket->getCreationDate() !== null)
                                                                            (modifié le {{ $ticket->getCreationDate() }})
                                                                        @endif
                                                                    </div>
                                                                     </a>
                                                                    <div id="collapse{{ $ticket->get('RFC_NUMBER') }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                                        <hr/>
                                                                        <div class="panel-body" style="padding: 5px;">
                                                                            <!-- Remove HTML tags -->
                                                                            {{ strip_tags(htmlspecialchars_decode($ticket->get('COMMENT'))) }}
                                                                        </div>
                                                                        <hr/>
                                                                    </div>
                                                                </div>
                                                             @endforeach
                                                                
                                                            </div>
                                                        
                                                        @else
                                                        <p class="font-italic mb-0 text-muted">Aucun ticket trouvé</p>
                                                        @endif
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
                        </div>
                    </div>
                </div>
            </form>           
        </div>


@endsection


