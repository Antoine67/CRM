@extends('layouts.app')

@section('js')
<script src="{{ asset('js/datadefault.js') }}" defer></script>
@endsection

@section('content')

<div id="message-displayer" style="position:fixed; top:0; right:0; left:0; width:100%; z-index:100;" >
    <div style="padding: 5px;">
        <div id="inner-message" class="alert alert-danger">
            <span id="message"></span>
        </div>
    </div>
</div>


<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-header"><h4>Données par défaut<h4></div>

            <div class="card-body ">
                <p class="text-muted">
                Ici vous pouvez créer des querys par défaut avec des <a href="#vars">variables</a>. Ces variables pourront ensuite être renseignés sur les profils clients.
                Pour utiliser une variable dans une query, entourez la de <b>crochets</b>.<br/>Exemple : <i><b> SELECT * FROM WHERE 'ID' = {id_in_database}</b></i>
                </p>
                <hr/>
                @isset($datadefaults)
                @foreach($datadefaults as $d)
                <form id="datadefault-{{ $d['id'] }}">
                    <h5>{{ $d['name'] }}:</h5>

                        <div class="form-group">
                        <label for="databaseSelected">Sources de données</label>
                        <select class="form-control" id="databaseSelected-{{ $d['id'] }}" autocomplete="off">
                        @foreach($databases as $db)
                            <option id="database-{{ $db->id }}" value="{{ $db->id }}"
                            @if ( $d['id_database'] === $db->id )
                            selected="selected"
                            @endif
                            >
                                {{ $db->name }}
                             </option>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="queryTextArea-{{ $d['id'] }}">Query</label>
                        <textarea class="form-control" id="queryTextArea-{{ $d['id'] }}" rows="3" placeholder="SQL Query">{{ $d['default_query'] }}</textarea>
                    </div>

                    
                    <button type="button" class="btn btn-success" onClick="saveDataDefault('{{ $d['id']}}');">Sauvegarder les modifications</button>
                    <hr/>
                </form>
                @endforeach
                @endisset


                <h5>Variables</h5>
                <div>
                    <button type="button" class="btn btn-light" onClick="newVar()">Nouvelle variable</button>
                </div>
                <br/>
                <div id="vars">
                @isset($vars)
                    
                @foreach( $vars as $var )
                    <div class="row" style="margin-bottom: 5px;">
                        <span hidden>{{ $var->id}}</span>
                        <div class="col col-sm-4">
                            <input type="text" class="form-control var-name" placeholder="Nom" value="{{ $var->name }}">
                        </div>
                        <div class="col col-sm-7">
                            <input type="text" class="form-control var-description" placeholder="Description" value="{{ $var->description }}">
                        </div>
                        <div class="col col-sm-1">
                            <button type="button" class="btn btn-danger" onClick="deleteVar({{ $var->id }}, this)">X</button>
                        </div>
                    </div>
                @endforeach
                @endisset
                </div>
                 <button type="button" class="btn btn-success" onClick="saveVars();">Sauvegarder les variables</button>

            </div>
        </div>
    </div>
</div>
@endsection
