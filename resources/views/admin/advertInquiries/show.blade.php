@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.advertInquiry.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.advert-inquiries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.id') }}
                        </th>
                        <td>
                            {{ $advertInquiry->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.first_name') }}
                        </th>
                        <td>
                            {{ $advertInquiry->first_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.last_name') }}
                        </th>
                        <td>
                            {{ $advertInquiry->last_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.location') }}
                        </th>
                        <td>
                            {{ $advertInquiry->location }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.email') }}
                        </th>
                        <td>
                            {{ $advertInquiry->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.company_name') }}
                        </th>
                        <td>
                            {{ $advertInquiry->company_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.advertInquiry.fields.comments') }}
                        </th>
                        <td>
                            {{ $advertInquiry->comments }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.advert-inquiries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection