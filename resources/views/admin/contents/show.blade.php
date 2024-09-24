@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.content.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.contents.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.content.fields.id') }}
                        </th>
                        <td>
                            {{ $content->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.content.fields.title') }}
                        </th>
                        <td>
                            {{ $content->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Author
                        </th>
                        <td>
                            {{ $content->author }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.content.fields.blog_content') }}
                        </th>
                        <td>
                            {!! $content->blog_content !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.content.fields.blog_image') }}
                        </th>
                        <td>
                            @if($content->blog_image)
                                <a href="{{ $content->blog_image->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.content.fields.content_type') }}
                        </th>
                        <td>
                            {{ $content->content_type->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.content.fields.content_category') }}
                        </th>
                        <td>
                            {{ $content->content_category->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.contents.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection