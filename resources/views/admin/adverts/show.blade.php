@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.advert.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.adverts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.advert.fields.id') }}
                        </th>
                        <td>
                            {{ $advert->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advert.fields.title') }}
                        </th>
                        <td>
                            {{ $advert->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advert.fields.content') }}
                        </th>
                        <td>
                            @if($advert->content)
                                <a href="{{ $advert->content->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advert.fields.description') }}
                        </th>
                        <td>
                            {!! $advert->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advert.fields.business_name') }}
                        </th>
                        <td>
                            {{ $advert->business_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advert.fields.location') }}
                        </th>
                        <td>
                            {{ $advert->location }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advert.fields.category') }}
                        </th>
                        <td>
                            {{ $advert->category }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advert.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Advert::STATUS_SELECT[$advert->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.adverts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection