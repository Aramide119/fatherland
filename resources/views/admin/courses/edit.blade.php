@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.course.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.courses.update", [$course->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="course_title">{{ trans('cruds.course.fields.course_title') }}</label>
                <input class="form-control {{ $errors->has('course_title') ? 'is-invalid' : '' }}" type="text" name="course_title" id="course_title" value="{{ old('course_title', $course->course_title) }}" required>
                @if($errors->has('course_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('course_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.course.fields.course_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="instructor_name">{{ trans('cruds.course.fields.instructor_name') }}</label>
                <input class="form-control {{ $errors->has('instructor_name') ? 'is-invalid' : '' }}" type="text" name="instructor_name" id="instructor_name" value="{{ old('instructor_name', $course->instructor_name) }}" required>
                @if($errors->has('instructor_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('instructor_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.course.fields.instructor_name_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection