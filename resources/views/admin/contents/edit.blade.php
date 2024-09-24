@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.content.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.contents.update", [$content->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.content.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $content->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.content.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="author">Author</label>
                <input class="form-control {{ $errors->has('author') ? 'is-invalid' : '' }}" type="text" name="author" id="author" value="{{ old('author', $content->author) }}" required>
                @if($errors->has('author'))
                    <div class="invalid-feedback">
                        {{ $errors->first('author') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.content.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="blog_content">{{ trans('cruds.content.fields.blog_content') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('blog_content') ? 'is-invalid' : '' }}" name="blog_content" id="blog_content">{!! old('blog_content', $content->blog_content) !!}</textarea>
                @if($errors->has('blog_content'))
                    <div class="invalid-feedback">
                        {{ $errors->first('blog_content') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.content.fields.blog_content_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="blog_image">{{ trans('cruds.content.fields.blog_image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('blog_image') ? 'is-invalid' : '' }}" id="blog_image-dropzone">
                </div>
                @if($errors->has('blog_image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('blog_image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.content.fields.blog_image_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="content_type_id">{{ trans('cruds.content.fields.content_type') }}</label>
                <select class="form-control select2 {{ $errors->has('content_type') ? 'is-invalid' : '' }}" name="content_type_id" id="content_type_id" required>
                    @foreach($content_types as $id => $entry)
                        <option value="{{ $id }}" {{ (old('content_type_id') ? old('content_type_id') : $content->content_type->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('content_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('content_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.content.fields.content_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="content_category_id">{{ trans('cruds.content.fields.content_category') }}</label>
                <select class="form-control select2 {{ $errors->has('content_category') ? 'is-invalid' : '' }}" name="content_category_id" id="content_category_id" required>
                    @foreach($content_categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('content_category_id') ? old('content_category_id') : $content->content_category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('content_category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('content_category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.content.fields.content_category_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.contents.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $content->id ?? 0 }}');
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

<script>
    Dropzone.options.blogImageDropzone = {
    url: '{{ route('admin.contents.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="blog_image"]').remove()
      $('form').append('<input type="hidden" name="blog_image" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="blog_image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($content) && $content->blog_image)
      var file = {!! json_encode($content->blog_image) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="blog_image" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection