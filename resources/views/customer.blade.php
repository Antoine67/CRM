@extends('layouts.app')

@section('css')
    <!-- <link rel="stylesheet" href="{{ asset('css/browseButton.css') }}">
    <link rel="stylesheet" href="{{ asset('css/internshipList.css') }}"> -->

<style>
    .with-chevron[aria-expanded='true'] i {
      display: block;
      transform: rotate(180deg) !important;
    }


</style>
@endsection

@section('js')
@endsection


@section('content')

    <div>
        <h1 class="d-inline-block">Client {{ $id }}</h1>
        <img class="d-inline-block pull-right" src="~/img/test.png" style="object-fit: cover; height:75px; width:75px;" alt="logo" />
    </div>
   
    <div class="card">
        <div class="card-body">            
            <div class="row justify-content-center">
                <div class="w-100">
                    <div class="card">
                        <div class="card-header"><h2>Informations :</h2></div>

                        <div class="card-body">
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
                </div>
            </div>

                <hr />
                <div class=" mb-5">
                    <!-- Collapse Panel 1-->
                    <a data-toggle="collapse" href="#collapseExample1" role="button" aria-expanded="true" aria-controls="collapseExample1" class="btn btn-primary btn-block py-2 shadow-sm with-chevron">
                        <p class="d-flex align-items-center justify-content-between mb-0 px-3 py-2"><strong class="text-uppercase">Collapse Panel</strong><i class="fa fa-angle-down"></i></p>
                    </a>
                    <div id="collapseExample1" class="collapse shadow-sm show">
                        <div class="card">
                            <div class="card-body">
                                <p class="font-italic mb-0 text-muted">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" mb-5">
                    <!-- Collapse Panel 2-->
                    <button data-toggle="collapse" data-target="#collapseExample2" role="button" aria-expanded="true" aria-controls="collapseExample2" class="btn btn-success btn-block py-2 shadow-sm with-chevron">
                        <p class="d-flex align-items-center justify-content-between mb-0 px-3 py-2"><strong class="text-uppercase">Fichiers associés</strong><i class="fa fa-angle-down"></i></p>
                    </button>
                    <div id="collapseExample2" class="collapse shadow-sm show">
                        <div class="card">
                            <div class="card-body">
                                <p class="font-italic mb-0 text-muted">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" mb-5">
                    <!-- Collapse Panel 3-->
                    <a data-toggle="collapse" href="#collapseExample3" role="button" aria-expanded="true" aria-controls="collapseExample3" class="btn btn-warning btn-block p2-3 shadow-sm with-chevron">
                        <p class="d-flex align-items-center justify-content-between mb-0 px-3 py-2"><strong class="text-uppercase">Tickets</strong><i class="fa fa-angle-down"></i></p>
                    </a>
                    <div id="collapseExample3" class="collapse shadow-sm show">
                        <div class="card">
                            <div class="card-body">
                                <p class="font-italic mb-0 text-muted">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" mb-5">
                    <!-- Collapse Panel 4-->
                    <a data-toggle="collapse" href="#collapseExample4" role="button" aria-expanded="true" aria-controls="collapseExample4" class="btn btn-danger btn-block p2-3 shadow-sm with-chevron">
                        <p class="d-flex align-items-center justify-content-between mb-0 px-3 py-2"><strong class="text-uppercase">Statistiques</strong><i class="fa fa-angle-down"></i></p>
                    </a>
                    <div id="collapseExample4" class="collapse shadow-sm show">
                        <div class="card">
                            <div class="card-body">
                                <p class="font-italic mb-0 text-muted">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection


