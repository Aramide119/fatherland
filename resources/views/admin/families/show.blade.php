@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} Group Details
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.families.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            Community Id
                        </th>
                        <td>
                            {{$showFamily->id ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <td>
                            {{ $showFamily->name ? $showFamily->name." Group" : '' }}
                        </td>
                    </tr>
                     <tr>
                        <th>
                            Created By
                        </th>
                        <td>
                            {{ $showFamily->createdBy->name ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Location
                        </th>
                        <td>
                            {{ $showFamily->location ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Current Location
                        </th>
                        <td>
                            {{ $showFamily->current_location ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                             Notable Individuals
                        </th>
                        <td>
                            {!! $showFamily->notable_individual ?? '' !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                             About
                        </th>
                        <td>
                            {!! $showFamily->about ?? '' !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Reference
                        </th>
                        <td>
                        <a href="{{ $showFamily->reference  ? $showFamily->reference  : '' }}" target="_blank">
                                {{ $showFamily->reference  ? 'view reference' : 'None' }}
                            </a>

                        </td>
                    </tr>
                    <tr>
                        <th>
                            Reference Link
                        </th>
                        <td>
                        <a href="{{ $showFamily->reference_link ? $showFamily->reference_link : '#' }}" target="_blank">
                            {{ $showFamily->reference_link ? 'View Reference Link' : 'None' }}
                        </a>

                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.families.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
