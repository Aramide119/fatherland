@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} Post Details
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.reportedPosts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            Post Id
                        </th>
                        <td>
                            {{$showPost->id ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Text
                        </th>
                        <td>
                            {{ $showPost->post->text ?? '' }}
                        </td>
                    </tr>
                    <th>
                       Slug
                    </th>
                    <td>
                        {{ $showPost->post->slug ?? '' }}
                    </td>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.reportedPosts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection