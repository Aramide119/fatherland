@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} Conversation Details
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.dynasties.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            Conversation Id
                        </th>
                        <td>
                            {{ $showDynasty->id ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <td>
                            {{ $showDynasty->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Location
                        </th>
                        <td>
                            {{ $showDynasty->location ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Created By
                        </th>
                        <td>
                            {{ $showDynasty->user->name ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                             Notable Individuals
                        </th>
                        <td>
                            {{ $showDynasty->notable_individual ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                             About
                        </th>
                        <td>
                            {!! $showDynasty->about ?? '' !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Reference
                        </th>
                        <td>
                            <a href="{{ $showDynasty->reference  ? $showDynasty->reference  : '' }}" target="_blank">
                                    {{ $showDynasty->reference  ? 'view reference' : 'None' }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>
                              Reference Link
                        </th>
                        <td>
                        <a href="{{ $showDynasty->reference_link }}" target="_blank">
                            {{ $showDynasty->reference_link ? 'View Reference Link' : 'None' }}
                        </a>

                        </td>
                    </tr>
                    <tr>
                        <th>
                             Status
                        </th>
                        <td>
                            {{ $showDynasty->status ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.dynasties.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
