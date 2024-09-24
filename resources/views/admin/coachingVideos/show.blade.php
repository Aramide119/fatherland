@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} Coaching Vidoes
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.coaching-videos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.id') }}
                        </th>
                        <td>
                            {{ $coachingVideo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Name
                        </th>
                        <td>
                            {{ $coachingVideo->coach->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Image
                        </th>
                        <td>
                            @if($coachingVideo->coach->image)
                                <a href="{{ $coachingVideo->coach->image->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            video
                        </th>
                        <td>
                            @if($coachingVideo->video)
                                    <a href="{{ $coachingVideo->video->getUrl() }}" target="_blank">
                                        {{ $coachingVideo->learning_category->name }} video: {{ $coachingVideo->video->file_name }}
                                    </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Biography
                        </th>
                        <td>
                            {!! $coachingVideo->coach->biography !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Category
                        </th>
                        <td>
                            {{ $coachingVideo->learning_category->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.coaching-videos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection