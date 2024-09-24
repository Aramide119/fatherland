@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.record.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.records.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.record.fields.id') }}
                        </th>
                        <td>
                            {{ $record->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.record.fields.name') }}
                        </th>
                        <td>
                            {{ $record->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.record.fields.lineage') }}
                        </th>
                        <td>
                            {!! $record->lineage !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.record.fields.location') }}
                        </th>
                        <td>
                            {{ $record->location }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.record.fields.notable_individual') }}
                        </th>
                        <td>
                            {!! $record->notable_individual !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.record.fields.about') }}
                        </th>
                        <td>
                            {!! $record->about !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.record.fields.profile_picture') }}
                        </th>
                        <td>
                            @if($record->profile_picture)
                                <a href="{{ $record->profile_picture->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.record.fields.cover_picture') }}
                        </th>
                        <td>
                            @if($record->cover_picture)
                                <a href="{{ $record->cover_picture->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.record.fields.reference_link') }}
                        </th>
                        <td>
                            {{ $record->reference_link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.record.fields.reference') }}
                        </th>
                        <td>
                            @foreach($record->reference as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.records.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection