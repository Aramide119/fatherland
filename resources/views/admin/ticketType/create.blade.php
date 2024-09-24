@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} Ticket Type
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route("admin.ticketType.create") }}" enctype="multipart/form-data" id="eventForm">
            @csrf
            <div class="form-group">
                <label for="eventSelect">Events</label>
                <select class="form-control" id="eventSelect" name="eventId" onchange="submitForm()" required>
                    <option value="">Select Event</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ $event->id == $selectedEventId ? 'selected' : '' }}>{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>
           
        </form>
        <form method="POST" action="{{ route("admin.ticketType.store") }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="eventId" id="eventId" value="{{ $selectedEventId }}">
            <div class="row form-group">
                    @if ($ticketTypes->count() > 0)
                        <div class="col-6" id="ticketTypeSelect">
                            <label for="ticketTypeSelect">Ticket Types</label>
                            <select class="form-control" id="ticketType1" name="ticketTypeId">
                                <option value="">Select Ticket Type</option>
                                @foreach($ticketTypes as $ticketType)
                                    <option value="{{ $ticketType->id }}" data-price="{{ $ticketType->price }}">{{ $ticketType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                         <div class="col-6">
                            <label for="ticket_type" >Ticket Type</label>
                            <input class="form-control {{ $errors->has('ticket_type') ? 'is-invalid' : '' }}" type="text" name="ticket_type_manual" id="ticket_type" value="{{ old('ticket_type', '') }}" step="0.01" >
                            @if($errors->has('ticket_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ticket_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.event.fields.ticket_price_helper') }}</span>
                         </div>
                    @endif
                    <div class="col-6" id="addNewTicketType" style="display: none">
                        <label for="ticket_type_manual" >Ticket Type</label>
                        <input class="form-control {{ $errors->has('ticket_type_manual') ? 'is-invalid' : '' }}" type="text" name="ticket_type" id="ticket_type_manual" value="{{ old('ticket_type_manual', '') }}" step="0.01">
                        @if($errors->has('ticket_type_manual'))
                            <div class="invalid-feedback">
                                {{ $errors->first('ticket_type_manual') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.event.fields.ticket_price_helper') }}</span>
                     </div>
                <div class="col-6" id="addNewTicketPrice">
                    <label for="ticket_price">{{ trans('cruds.event.fields.ticket_price') }}</label>
                    <input class="form-control {{ $errors->has('ticket_price') ? 'is-invalid' : '' }}" type="number" name="ticket_price" id="ticket_price" value="{{ old('ticket_price', '') }}" step="0.01" required>
                    @if($errors->has('ticket_price'))
                        <div class="invalid-feedback">
                            {{ $errors->first('ticket_price') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.event.fields.ticket_price_helper') }}</span>
                </div>          
            </div>
            <button class="btn btn-danger" type="submit">
                Save
            </button>
            @if ($ticketTypes->count() > 0)
            <button class="btn btn-danger" type="button" onclick="newTicketType()">Add New</button>
            @endif
        </form>
            
    </div>
</div>

<script>
    document.getElementById('ticketType1').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var price = selectedOption.dataset.price;
        console.log(price);
        document.getElementById('ticket_price').value = price;
    });

    function submitForm() {
        document.getElementById("eventForm").submit();
    }
    function newTicketType(){
        var ticketTypeSelect = document.getElementById("ticketTypeSelect");
        var ticketTypeInput = document.getElementById("addNewTicketType");
        var ticketPriceInput = document.getElementById("ticket_price");

        ticketTypeSelect.style.display = "none";
        ticketTypeInput.style.display = "block";
        ticketPriceInput.value = ""; 
      
    }
    
</script>

@endsection