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

    

    <div class="card customers-container">

        <p id="loading">Chargement des clients en cours... </p>
        @isset($customers)

            <table id="table_id" class="display" style="opacity: 0;">
            <div style="text-align:center">
                <a href="{{ url('creation') }}" class="btn btn-success" style="width: max-content;">Nouveau client</a>
            </div>
            <hr/>
			    <thead>
				    <tr>
					    <td class="text-center">Nom</td>
					    <td class="text-center">Liens</td>
				    </tr>
			    </thead>
		    <tbody>
            @foreach($customers as $customer)

            <tr id="tr_{{ $customer->id }}">
            
			    <td class="customer-name all" onClick='openUrlSameTab(" {{ url('customer') . '/' . $customer->id }} ")';><span name="">{{ $customer->name }}</span></td>
            
            
			    <td class="customer-logos desktop">
                    @if(isset($customer->sharepoint_client) &&!empty($customer->sharepoint_client))
                    <a href="#" onClick="openUrl('{{ $customer->sharepoint_client }}');">
                        <span name=""><i class="fas fa-lg fa-folder-open"></i></span>
                    </a>
                    @endif
                    @if(isset($customer->sharepoint_extranet) && !empty($customer->sharepoint_extranet))
                    <a href="#" onClick="openUrl('{{ $customer->sharepoint_extranet }}');">
                        <span name=""><i class="fas fa-lg fa-file-alt"></i></span>
                    </a>
                    @endif
                     @if(isset($customer->web_url) && !empty($customer->web_url))
                    <a href="#" onClick="openUrl('{{ $customer->web_url }}');">
                        <span name=""><i class="fas fa-lg fa-globe"></i></span>
                    </a>
                    @endif
                </td>
            
        
		    </tr>

            @endforeach
            

         @endisset
    </div>


@endsection


