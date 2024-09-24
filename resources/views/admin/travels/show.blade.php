@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.travel.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.travels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.travel.fields.id') }}
                        </th>
                        <td>
                            {{ $travel->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.travel.fields.name') }}
                        </th>
                        <td>
                            {{ $travel->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.travel.fields.price') }}
                        </th>
                        <td>
                            {{ $travel->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.travel.fields.image') }}
                        </th>
                        <td>
                            @if($travel->image)
                                <a href="{{ $travel->image->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.travel.fields.location') }}
                        </th>
                        <td>
                            {{ $travel->location }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.travel.fields.description') }}
                        </th>
                        <td>
                            {!! $travel->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.travel.fields.no_of_people') }}
                        </th>
                        <td>
                            {{ $travel->no_of_people }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.travel.fields.start_date') }}
                        </th>
                        <td>
                            {{ $travel->start_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.travel.fields.end_date') }}
                        </th>
                        <td>
                            {{ $travel->end_date }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.travels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection