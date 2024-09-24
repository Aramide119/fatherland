@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.event.title_singular') }} Attendees
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form id="eventDetailsForm" action="{{ route('admin.event.details') }}" method="GET">
                    <div class="row">
                        <div class="form-group col-10">
                            <label for="eventSelect">Events</label>
                            <select class="form-control" id="eventSelect" name="eventId">
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-2">
                            <div class='mb-4'></div>
                            <button class="btn btn-success mt-1" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
          </div>
          @if(isset($data))
          <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover" id="eventDetailsTable">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.event.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.image') }}
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.location') }}
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.tags') }}
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.start_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.end_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.ticket_price') }}
                        </th>
                        <th>
                            Attendees
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <td>
                        {{ $data->name ?? '' }}
                    </td>
                    <td>
                        @if($data->image)
                            <a href="{{ $event->image->getUrl() }}" target="_blank">
                                {{ trans('global.view_file') }}
                            </a>
                        @endif
                    </td>
                    <td>
                        {{ $data->location ?? '' }}
                    </td>
                    <td>
                        {{ $data->tags ?? '' }}
                    </td>
                    <td>
                        {{ $data->start_date ?? '' }}
                    </td>
                    <td>
                        {{ $data->end_date ?? '' }}
                    </td>
                    <td>
                        {{ App\Models\Event::STATUS_SELECT[$data->status] ?? '' }}
                    </td>
                    <td>
                        <table>
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($event->ticketTypes as $ticketType)
                                    <tr>
                                        <td>{{ $ticketType->name }}</td>
                                        <td>{{ $ticketType->price }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                    <td>
                        {{ $attendeesCount }}
                    </td>
                </tbody>
            </table>
            <h6>Attendees</h6>
            <table class="table table-bordered table-striped table-hover" id="eventAttendeesTable">
                <thead>
                    <tr>
                        <th>{{ trans('cruds.event.fields.name') }}</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                    </tr>
                </thead>
                @if($attendees->count() > 0)
                @foreach ($attendees as $attendee)
                <tbody>
                    <td>{{ $attendee->name }}</td>
                    <td>{{ $attendee->email }}</td>
                    <td>{{ $attendee->phone_number }}</td>
                </tbody>
                @endforeach
                @else
                <tbody>
                   <tr><td colspan="3">No records found</td></tr>
                </tbody>
                @endif

            </table>
        </div>
        {{ $attendees->links() }}
        @endif
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>

</script>
@endsection
