@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/datasources.css') }}">
@endsection

@section('js')
<script src="{{ asset('js/datasources.js') }}" defer></script>
<script>
    var databases = @json($databases);
</script>
@endsection


@section('content')
@admin

    <!-- Databases -->
    <div class="row">
        <div class="col-sm-6">Bases de données</div>
        <div class="col-sm-6">Actions</div>
    </div>
    <div class="row">
        <div class="col-sm-6" id="left-pane-db">
            @if(!$databases->isEmpty())
            <ul class="list-group">
            @foreach($databases as $db)
              <li class="list-group-item d-flex justify-content-between align-items-center db-item" id="db-item-{{ $db->id }}">
                {{ $db->name }} ({{ $db->host }}:{{ $db->port }} - {{ $db->driver }})
                <span id="{{ $db->id }}-state" class="badge badge-secondary badge-pill">Inconnu</span>
              </li>
             @endforeach
            </ul>
            @else
            <p>Aucune base de données ajoutée</p>
            @endif
        </div>
        <div class="col-sm-6" id="right-pane-db">
            <div class="card">
                <div class="card-body">
                    <a href="#" onClick="addDatabase()" >Ajouter une base de données</a>
                </div>
            </div>
            
        </div>
    </div>
    <!-- End Databases -->

    <!-- Modal Databases -->
    <div class="modal fade" id="dbModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="dbModalTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
                <div class="form-group">
                    <label for="dbName">Informations</label>
                    <input type="text" class="form-control" id="dbName" placeholder="Nom">
                </div>
                <div class="form-row">
                    <div class="col">
                      <input type="text" class="form-control" id="dbHost" placeholder="URL">
                    </div>
                    <div class="col">
                      <input type="text" class="form-control" id="dbPort" placeholder="Port">
                    </div>
                </div>
                <br/>
                <div class="form-row">
                    <div class="col">
                      <input type="text" class="form-control" id="dbUser" placeholder="Utilisateur">
                    </div>
                    <div class="col">
                      <input type="text" class="form-control" id="dbPassword" placeholder="Mot de passe">
                    </div>
                </div>
                <div class="form-group">
                    <label for="dbDriver">Driver</label>
                    <select class="form-control" id="dbDriver">
                    @foreach($drivers as $d)
                      <option id="driver-{{ $d }}" value="{{ $d }}">{{ $d }}</option>
                    @endforeach
                    </select>
                </div>
            </form>
            <div class="alert alert-warning fade show" id="msg-displayer" role="alert">
              <span id="msg-txt"></span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            <button type="button" class="btn btn-primary" onClick="testConnection()">Tester</button>
            <button type="button" class="btn btn-success" onClick="save()">Sauvegarder</button>
          </div>
        </div>
      </div>
</div>
    <!-- End Modal Databases -->

    

@endadmin
@endsection


