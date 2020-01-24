@extends('layouts.app')

@section('css')
    <!-- <link rel="stylesheet" href="{{ asset('css/browseButton.css') }}">
    <link rel="stylesheet" href="{{ asset('css/internshipList.css') }}"> -->

<style>
    .with-chevron[aria-expanded='true'] i {
      display: block;
      transform: rotate(180deg) !important;
    }

    .form-group input[type="checkbox"] {
        display: none;
    }

    .form-group input[type="checkbox"] + .btn-group > label span {
        width: 20px;
    }

    .form-group input[type="checkbox"] + .btn-group > label span:first-child {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:last-child {
        display: inline-block;   
    }

    .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
        display: inline-block;
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
        display: none;   
    }


</style>
@endsection

@section('js')
<script src="{{ asset('js/customer.js') }}" defer></script>
@endsection


@section('content')

    <a href="{{ url('customer') }}" class="btn btn-light"><i class="fas fa-arrow-left" style="margin-right:7px;"></i>Retour à la liste des clients</a>
    <div class="text-center">
        <h1 class="d-inline-block"><b>{{ $customer->getName() }}</b></h1>
        <div class="text-muted">Dernière mise à jour le {{ $customer->getLastUpdatedProfile() }}</div>
        <div class="text-muted">
            <a href="#" onclick="updateCustomer(event);">
                Mettre à jour
            </a>

            <form id="update-customer-form"  method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    <div id="customer-displayer" class="tab-content">   
            
        <!-- Tab navbar -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="" data-target="#informations" data-toggle="tab" class="nav-link active">Informations</a>
            </li>
            <li class="nav-item">
                <a href="" data-target="#others" data-toggle="tab" class="nav-link">Autres</a>
            </li>
        </ul>

        <!-- Start Information card -->
        <div class="card tab-pane active" id="informations">
            <div class="row justify-content-center">
                <div class="w-100">
                    <form>
                        <div class="row">
                            <label for="id" class="col-md-4 col-form-label text-md-right">Information</label>
                                <div class="col-md-6">
                                    <p id="id" class="form-control" type="text">Info 1</p>
                                </div>
                        </div>
                        <div class="row">
                            <label for="id" class="col-md-4 col-form-label text-md-right">Information</label>
                            <div class="col-md-6">
                                    <p id="id" class="form-control" type="text">Info 2</p>
                                </div>
                        </div>
                        <div class="row">
                            <label for="id" class="col-md-4 col-form-label text-md-right">Information</label>
                            <div class="col-md-6">
                                <p id="id" class="form-control" type="text">Info 3</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
                       

            <hr />
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
                            <div class="align-middle d-inline-block" onClick="openUrl('https://google.fr')">
                                    
                                <div class="form-group">
                                    <input type="checkbox" name="fancy-checkbox-success" id="fancy-checkbox-success" autocomplete="off" checked="true" disabled />
                                    <div class="btn-group">
                                        <label for="fancy-checkbox-success" class="btn btn-success">
                                            <span class="fas fa-check"></span>
                                            <span> </span>
                                        </label>
                                        <label for="fancy-checkbox-success" class="btn btn-default active">
                                            Success Checkbox
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
                                    {{ $folder->getName() }}
                                </a></li>
                            @endforeach
                            </ul>
                            <h6 class="text-muted"><a href="#" onClick="openUrl('{{ $customer->getProperties()['extranetFolderWebUrl'] }}');">Dossier extranet</a></h6>
                            <ul class="fa-ul">
                            @foreach($customer->getExtranetFolders() as $folder)
                                <li><a href="#" onClick="openUrl('{{ $folder->getWebUrl() }}')">
                                    <span class="fa-li"><i class="fas fa-folder"></i></span>
                                    {{ $folder->getName() }}
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
                            <p class="font-italic mb-0 text-muted">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.</p>
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
        </div>
        <!-- End Information card -->

        <!-- Start Others card -->
        <div class="card tab-pane" id="others">
        </div>
    </div>
@endsection


