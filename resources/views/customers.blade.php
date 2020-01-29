@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/customers.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables/datatables.min.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('DataTables/Buttons-1.6.1/css/buttons.dataTables.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/customers.css') }}"/>
@endsection

@section('js')
    <script src="{{ asset('DataTables/datatables.min.js') }}" defer></script>
	<script src="{{ asset('DataTables/Buttons-1.6.1/js/dataTables.buttons.min.js') }}" defer></script>
    <script src="{{ asset('js/customers.js') }}" defer></script>


@endsection


@section('content')

    

    <div>
        <h1 class="d-inline-block">Clients </h1>
    </div>
    @if(Session::has('permission_level') && Session::get('permission_level') >= env('EDITOR_LEVEL', 2))
    <a href="#" onclick="event.preventDefault(); document.getElementById('refresh-customer-form').submit();"> {{ __('Mettre à jour la liste de clients') }} </a>
    <p class="text-muted">
        @isset($lastUpdate)
        Dernière mise à jour le {{ $lastUpdate }}
        @else
        Aucune mise à jour récente trouvée
        @endisset
    </p>
    <form id="refresh-customer-form" action="{{ url('sharepoint') }}" method="POST" style="display: none;">
        @csrf
        <input type="text" name="type" value="list"> </input>
    </form>
    @endif

    <div class="card customers-container">

        <p id="loading">Chargement des clients en cours... </p>
        @isset($customers)

            <table id="table_id" class="display" style="opacity: 0;">

			    <thead>
				    <tr>
					    <td class="text-center">Nom</td>
					    <td class="text-center">Liens</td>
				    </tr>
			    </thead>
		    <tbody>
            @foreach($customers as $customer)

            <tr id="tr_{{ $customer->getId() }}">
            
			    <td class="customer-name all" onClick='openUrlSameTab(" {{ url('customer') . '/' . $customer->getId() }} ")';><span name="">{{ $customer->getName() }}</span></td>
            
            
			    <td class="customer-logos desktop">
                    @if(isset($customer->getProperties()['webUrl']))
                    <a href="#" onClick="openUrl('{{ $customer->getProperties()['webUrl'] }}');">
                        <span name=""><i class="fas fa-folder-open"></i></span>
                    </a>
                    @endif
                </td>
            
        
		    </tr>

            @endforeach
            

         @endisset
    </div>


@endsection


