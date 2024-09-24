@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.news.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.inactivePosts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            ID
                        </th>
                        <td>
                            {{ $posts->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Text
                        </th>
                        <td>
                            {{ $posts->text }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            slug
                        </th>
                        <td>
                            {!! $posts->slug !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Status
                        </th>
                        <td>
                            {{ $posts->status ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            picture
                        </th>
                        <td>
                            
                        </td>
                    </tr>
                    <tr>
                        <th>
                            video
                        </th>
                        <td>
                            
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.inactivePosts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection