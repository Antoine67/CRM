@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/creation.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/creation.js') }}" defer></script>
@endsection




@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ url('creation') }}" enctype="multipart/form-data">
                        @csrf

                        <h2>Création</h2>
                        <hr/>
                        <small>Informations générales</small>
                        <div class="form-group row justify-content-center">
                          <div id="profile-container">
                            <image id="profileImage" src="{{ asset('img/default.jpg')}}" />
                          </div>
                          <input id="imageUpload" type="file"  name="pictureProfile" placeholder="Photo" required="" capture>
                        </div>


                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nom') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control " name="name" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" required >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Tél.') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone" >
                            </div>
                        </div>

                        <hr/>

                        <small>Informations complémentaires</small>

                        <div class="form-group row">
                            <label for="website" class="col-md-4 col-form-label text-md-right">{{ __('Site web') }}</label>
                            <div class="col-md-6">
                                <input id="website" type="text" class="form-control" name="web_url">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="sharepoint" class="col-md-4 col-form-label text-md-right">{{ __('Lien SharePoint') }}</label>

                            <div class="col-md-6">
                                <input id="sharepoint" type="text" class="form-control" name="sharepoint_client">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="sharepoint-extra" class="col-md-4 col-form-label text-md-right">{{ __('Lien SharePoint Extranet') }}</label>

                            <div class="col-md-6">
                                <input id="sharepoint-extra" type="text" class="form-control" name="sharepoint_extranet">
                            </div>
                        </div>


                        <hr/>
                        <div style="text-align: center">
                            <button type="submit">
                                {{ __('Créer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
