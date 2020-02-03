@editor
@include('customer.buttonDatasourceSelect', ['table_name' => 'tickets']) 
@endeditor

<h5>Derniers tickets : </h5>

<div class="container">
    <div class="row">
    @isset ($tickets)
                                                            
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
        @foreach ($tickets as $ticket)
            <div class="panel panel-default ">
            <a data-toggle="collapse" class="collapsed panel-title" data-parent="#accordion" href="#collapse-ticket-{{ $ticket->id }}" aria-expanded="false" aria-controls="collapse-ticket-{{ $ticket->id }}">
                <div class="panel-heading" role="tab" id="heading{{ $ticket->id }}">
                    Ticket n° <b>{{ $ticket->number }}</b>
                    @if($ticket->date !== null)
                        (modifié le {{ $ticket->date }})
                    @endif
                </div>
                    </a>
                <div id="collapse-ticket-{{ $ticket->id }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <hr/>
                    <div class="panel-body" style="padding: 5px;">
                        <!-- Remove HTML tags -->
                        {{ strip_tags(htmlspecialchars_decode($ticket->description)) }}
                    </div>
                    <hr/>
                </div>
            </div>
            @endforeach
                                                                
        </div>
                                                        
    @else
    <p class="font-italic mb-0 text-muted">Aucun ticket trouvé</p>
    @endisset
    </div>
</div>

