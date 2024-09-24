@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} video
    </div>
 
    <div class="card-body">
      <form method="POST" action="{{ route('admin.coaching-videos.coach') }}" enctype="multipart/form-data" id="categoryForm">
        @csrf
        <div class="form-group">
            <label for="category">{{ trans('cruds.event.fields.category') }}</label>
            <select class="form-control {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category" id="category" required>
                <option value disabled {{ old('category', session('selected_category', null)) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category', session('selected_category')) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @if($errors->has('category'))
                <div class="invalid-feedback">
                    {{ $errors->first('category') }}
                </div>
            @endif
        </div>
    </form>
        <form method="POST" action="{{ route("admin.coaching-videos.store") }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="category" value="{{ session('selected_category') }}">
            <div class="form-group">
              <label for="coach">Coach</label>
              <select class="form-control {{ $errors->has('coach') ? 'is-invalid' : '' }}" name="coach" id="coach" required>
                  <option value disabled {{ old('coach', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                  @if (isset($coaches))
                    @foreach ($coaches as $coach)
                      <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                    @endforeach
                  @endif
              </select>
              @if($errors->has('event_category_id'))
                  <div class="invalid-feedback">
                      {{ $errors->first('learning_category_id') }}
                  </div>
              @endif
              <span class="help-block">{{ trans('cruds.event.fields.category_helper') }}</span>
          </div>
            <div class="form-group">
              <label class="required" for="image">{{ trans('cruds.event.fields.image') }}</label>
              <input type="file" class="form-control" name="video" id="video">
              @if($errors->has('image'))
                  <div class="invalid-feedback">
                      {{ $errors->first('image') }}
                  </div>
              @endif
              <span class="help-block">{{ trans('cruds.event.fields.image_helper') }}</span>
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

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
      var categorySelect = document.getElementById('category');
      categorySelect.addEventListener('change', function () {
          document.getElementById('categoryForm').submit();
      });
  });
</script>
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.coaching-videos.storeCKEditorVideos') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $event->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection
